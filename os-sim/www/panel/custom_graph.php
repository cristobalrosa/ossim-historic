<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require_once 'classes/Security.inc';
require_once 'panel/Ajax_Panel.php';
require_once 'ossim_conf.inc';
require_once 'ossim_db.inc';
Session::logcheck("MenuControlPanel", "ControlPanelExecutive");

$conf = $GLOBALS["CONF"];
$jpgraph = $conf->get_conf("jpgraph_path");

require_once "$jpgraph/jpgraph.php";

//
// This will show errors (both PHP Errors and those detected in the code)
// as graphics, so they can be read.
//
function mydie($errno, $errstr='', $errfile='', $errline='')
{
    $err = ($errstr) ? $errstr : $errno;
    if ($errfile) {
        switch ($errno) {
            case 1: $errprefix = 'Error'; break;
            case 2: $errprefix = 'Warning'; break;
            case 8: $errprefix = 'Notice'; break;
            default:
                return; // dont show E_STRICT errors
        }
        $err = "$errprefix: $err in '$errfile' line $errline";
    }
    $error = new JpGraphError();
    $error->Raise($err);
    exit;
}
set_error_handler('mydie');

$ajax = &new Window_Panel_Ajax();
$all_options = $ajax->loadConfig(GET('id'));
$options = $all_options['plugin_opts'];

$sql = $options['graph_sql'];
if (!preg_match('/^\s*\(?\s*SELECT\s/i', $sql) ||
             preg_match('/\sFOR\s+UPDATE/i', $sql) ||
             preg_match('/\sINTO\s+OUTFILE/i', $sql) ||
             preg_match('/\sLOCK\s+IN\s+SHARE\s+MODE/i', $sql))
{
    mydie(_("SQL Query invalid due security reasons"));
}
       
$dbname = $options['graph_db'];
$method = $dbname == 'snort' ? 'snort_connect' : 'connect';
$db = new ossim_db;
$conn = $db->$method();
$data = array();

if (!$rs = $conn->Execute($sql)) {
    mydie("Error was: ".$conn->ErrorMsg()."\n\nQuery was: ".$sql);
}
// Only one field. Use column name as legend.
if ($rs->RecordCount() == 1) {
    for ($i = 0; $i < $rs->FieldCount(); $i++) {
        $field = $rs->FetchField($i);
        $data['legend'][] = $field->name;
        $data['values'][] = $rs->fields[$i];
    }
} else {
    while (!$rs->EOF) {
        $data['legend'][] = $rs->fields[0];
        $data['values'][] = $rs->fields[1];
        $rs->MoveNext();
    }
}
$data['title'] = $options['graph_title'];
$width = 510;
if ($options['graph_type'] == 'pie') {
    
    require_once "$jpgraph/jpgraph_pie.php";
    require_once "$jpgraph/jpgraph_pie3d.php";
    // Setup graph
    $graph = new PieGraph($width,250,"auto");
    $graph->SetShadow();
    
    // Setup graph title
    $graph->title->Set($data['title']);
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
    
    // Create pie plot
    if ($options['graph_pie_3dangle'] == 0) {
        $plot = new PiePlot($data['values']);
    } else {
        $plot = new PiePlot3D($data['values']);
        $plot->setAngle($options['graph_pie_3dangle']);
    }
    //$plot->SetFont(FF_VERDANA,FS_BOLD);
    //$plot->SetFontColor("darkred");
    $plot->SetSize(0.3);
    $plot->SetCenter(0.20);
    $plot->SetLegends($data['legend']);
    $plot->setTheme($options['graph_pie_theme']);
    if ($options['graph_plotshadow']) {
        $plot->SetShadow();
    }
    //$plot->SetStartAngle(M_PI/8);
    //printr($options['graph_pie_explode_pos']);
    switch ($options['graph_pie_explode']) {
        case 'all':
            $plot->ExplodeAll(); break;
        case 'pos':
            $plot->ExplodeSlice((int)$options['graph_pie_explode_pos']); break;
    }
    
    $graph->Add($plot);

} elseif ($options['graph_type'] == 'bars') {
    
    require_once "$jpgraph/jpgraph_bar.php";
    
    $background = "white";
    $color = "navy";
    $color2 = "lightsteelblue";
    
    // Setup graph
    $graph = new Graph($width, 250, "auto");
    $graph->SetScale('textlin',
                     $options['graph_y_min'],
                     $options['graph_y_max'],
                     $options['graph_x_min'],
                     $options['graph_x_max']
                     );
    $graph->yaxis->scale->SetGrace($options['graph_y_top'], $options['graph_y_bot']);
    $graph->xaxis->scale->SetGrace($options['graph_x_top'], $options['graph_x_bot']);
    
    $graph->SetMarginColor($background);
    $graph->img->SetMargin(40,30,20,40);
    $graph->SetShadow();
    
    // Setup graph title
    $graph->title->Set($data['title']);
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
    
    $plot = new BarPlot($data['values']);
    
    $plot->SetWidth(0.6);   // set bars width in percentage
    $plot->SetAbsWidth(20); // set bars width in pixels

    $color = $options['graph_color'];
    // One plain color
    if ($options['graph_gradient'] == 0) {
        $plot->SetFillColor($color);
    // Gradient color
    } elseif ($options['graph_gradient'] != 0) {
        // Made the color $degree darker
        // Given color "#FF1E1E", we split the color into groups
        // (red, gree, blue): "FF", "1E" and "1E" and convert them to decimal.
        // Then sum to each group $degree and transform the result back to hex. 
        $degree = -75;
        $color2 = '';
        for ($x = 1; $x < 6; $x+=2) {
            $dec = hexdec($color{$x}.$color{$x+1});
            if (($dec + $degree) < 256) {
                if (($dec + $degree) > -1) {
                    $dec += $degree;
                } else {
                    $dec = 0;
                }
            } else {
                $dec = 255;
            }
            $color2 .= dechex($dec);
        }
        $color2 = "#$color2";
        $plot->SetFillGradient($color, $color2, $options['graph_gradient']);
    }

    if (!empty($options['graph_show_values'])) {
        if (!isset($plot->value) || !method_exists($plot->value, 'show')) {
            mydie("This JPGraph version does not support 'Show values'");
        }
        $plot->value->Show();
    }

    if ($options['graph_plotshadow']) {
        $plot->SetShadow();
    }

    $graph->Add($plot);
    
    $graph->xaxis->SetTickLabels($data['legend']);
    
    //$graph->yaxis->scale->SetGrace(5); //show 5% more values than max 
    
    //$graph->xaxis->SetLabelAngle(40); // only with TTF fonts
    
    //$graph->title->Set($title);
    //$graph->xaxis->title->Set($titley);
    //$graph->yaxis->title->Set($titlex);
    
} elseif ($options['graph_type'] == 'points') {
    
    require_once "$jpgraph/jpgraph_line.php";
    $incref = false;
    // Graph metadata
    $graph = new Graph($width, 300, "auto");
    $graph->SetScale("textlin");
    $graph->img->SetMargin(60,60,40,60);
    $graph->xgrid->Show(true);
    $graph->legend->SetLayout(LEGEND_HOR);
    $graph->legend->Pos(0.5,0.96,"center","bottom");
    $graph->SetShadow();
    // Build graph title
    $date = date("F j, Y, g:i a");
    $title = " - Graphed on ". $date ;
    $graph->title->Set($title);
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
    //$graph->xaxis->title->Set($scale);
    $graph->xaxis->SetTickLabels($data['legend']);
    $graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
    $graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
    $graph->yaxis->SetColor("blue");
    
    // Create the data plot
    $plot = new LinePlot($data['values']);
    $plot->SetColor("blue");
    $plot->SetWeight(2);
    $plot->mark->SetType(MARK_FILLEDCIRCLE);
    $plot->mark->SetColor('blue');
    
    if (!empty($options['graph_show_values'])) {
        $plot->value->HideZero();
        $plot->value->SetFormat('%u');
        $plot->value->SetFont(FF_FONT1,FS_BOLD);
        $plot->value->SetColor('blue');
        $plot->value->SetMargin(10);
        $plot->SetLegend("# of selected alerts");
        $plot->value->Show();
    }    
    // Add the data plot to the graph
    $graph->Add($plot);

} else {
    die("Graph type not valid");
}
$graph->Stroke();

?>
