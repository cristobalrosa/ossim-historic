<?php
/**
* Class and Function List:
* Function list:
* Classes list:
*/
/*******************************************************************************
** OSSIM Forensics Console
** Copyright (C) 2009 OSSIM/AlienVault
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
** Built upon work by the BASE Project Team <kjohnson@secureideas.net>
*/
include ("base_conf.php");
include ("vars_session.php");
include ("$BASE_path/includes/base_constants.inc.php");
include ("$BASE_path/includes/base_include.inc.php");
include_once ("$BASE_path/base_db_common.php");
include_once ("$BASE_path/base_qry_common.php");
include_once ("$BASE_path/base_stat_common.php");
$et = new EventTiming($debug_time_mode);
$cs = new CriteriaState("base_stat_class.php");
$cs->ReadState();
$qs = new QueryState();
// Check role out and redirect if needed -- Kevin
$roleneeded = 10000;
$BUser = new BaseUser();
if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1)) base_header("Location: " . $BASE_urlpath . "/index.php");
$submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(
    _SELECTED,
    _ALLONSCREEN,
    _ENTIREQUERY
));
$qs->MoveView($submit); /* increment the view if necessary */
$page_title = _CHRTCLASS;
if ($qs->isCannedQuery()) PrintBASESubHeader($page_title . ": " . $qs->GetCurrentCannedQueryDesc() , $page_title . ": " . $qs->GetCurrentCannedQueryDesc() , $cs->GetBackLink() , 1);
else PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink() , 1);
/* Connect to the Alert database */
$db = NewBASEDBConnection($DBlib_path, $DBtype);
$db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);
if ($event_cache_auto_update == 1) UpdateAlertCache($db);
$criteria_clauses = ProcessCriteria();
if (!$printing_ag) {
    /* ***** Generate and print the criteria in human readable form */
    echo '<TABLE WIDTH="100%">
           <TR>
             <TD WIDTH="60%" VALIGN=TOP>';
    if (!array_key_exists("minimal_view", $_GET)) {
        PrintCriteria($caller);
    }
    echo '</TD></tr><tr>
           <TD VALIGN=TOP>';
    if (!array_key_exists("minimal_view", $_GET)) {
        PrintFramedBoxHeader(_QSCSUMM, "#669999", "#FFFFFF");
        PrintGeneralStats($db, 1, $show_summary_stats, "$join_sql ", "$where_sql $criteria_sql");
    }
    PrintFramedBoxFooter();
    echo ' </TD>
           </TR>
          </TABLE>
		  <!-- END HEADER TABLE -->
		  
		  </div>  </TD>
           </TR>
          </TABLE>';
}
$from = " FROM acid_event " . $criteria_clauses[0];
$where = " WHERE " . $criteria_clauses[1];
// use accumulate tables only with timestamp criteria
$use_ac = (preg_match("/AND/", preg_replace("/AND \( timestamp/", "", $criteria_clauses[1]))) ? false : true;
$qs->AddValidAction("ag_by_id");
$qs->AddValidAction("ag_by_name");
$qs->AddValidAction("add_new_ag");
$qs->AddValidAction("del_alert");
$qs->AddValidAction("email_alert");
$qs->AddValidAction("email_alert2");
$qs->AddValidAction("csv_alert");
$qs->AddValidAction("archive_alert");
$qs->AddValidAction("archive_alert2");
$qs->AddValidActionOp(_SELECTED);
$qs->AddValidActionOp(_ALLONSCREEN);
$qs->SetActionSQL($from . $where);
$et->Mark("Initialization");
$qs->RunAction($submit, PAGE_STAT_CLASS, $db);
$et->Mark("Alert Action");
/* Get total number of events */
if (!$use_ac) $event_cnt = EventCnt($db);
/* create SQL to get Unique Alerts */
$cnt_sql = "SELECT count(DISTINCT sig_class_id) " . $from . $where;
/* Run the query to determine the number of rows (No LIMIT)*/
if (!$use_ac) $qs->GetNumResultRows($cnt_sql, $db);
$et->Mark("Counting Result size");
/* Setup the Query Results Table */
$qro = new QueryResultsOutput("base_stat_class.php?caller=" . $caller);
$qro->AddTitle(" ");
$qro->AddTitle(_CHRTCLASS, "class_a", " ", " ORDER BY sig_class_id ASC", "class_d", " ", " ORDER BY sig_class_id DESC");
$qro->AddTitle(_TOTAL . "&nbsp;#", "occur_a", " ", " ORDER BY num_events ASC", "occur_d", " ", " ORDER BY num_events DESC");
$qro->AddTitle(_SENSOR . "&nbsp;#", "sensor_a", " ", " ORDER BY num_sensors ASC", "sensor_d", " ", " ORDER BY num_sensors DESC");
$qro->AddTitle(_("Sig") , "sig_a", " ", " ORDER BY num_sig ASC", "sig_d", " ", " ORDER BY num_sig DESC");
$qro->AddTitle(_("Scr.Addr") , "saddr_a", " ", " ORDER BY num_sip ASC", "saddr_d", " ", " ORDER BY num_sip DESC");
$qro->AddTitle(_("Dst.Addr") , "daddr_a", " ", " ORDER BY num_dip ASC", "daddr_d", " ", " ORDER BY num_dip DESC");
/*$qro->AddTitle(_FIRST,
"first_a", " ",
" ORDER BY first_timestamp ASC",
"first_d", " ",
" ORDER BY first_timestamp DESC");

$qro->AddTitle(_LAST,
"last_a", " ",
" ORDER BY last_timestamp ASC",
"last_d", " ",
" ORDER BY last_timestamp DESC");
*/
$qro->AddTitle(_("Graph"));
$sort_sql = $qro->GetSortSQL($qs->GetCurrentSort() , $qs->GetCurrentCannedQuerySort());
$sql = "SELECT DISTINCT sig_class_id, " . " COUNT(acid_event.cid) as num_events," . " COUNT( DISTINCT acid_event.sid) as num_sensors, " . " COUNT( DISTINCT signature ) as num_sig, " . " COUNT( DISTINCT ip_src ) as num_sip, " . " COUNT( DISTINCT ip_dst ) as num_dip, " . " min(timestamp) as first_timestamp, " . " max(timestamp) as last_timestamp " . $sort_sql[0] . $from . $where . " GROUP BY sig_class_id " . $sort_sql[1];
//echo $sql."<br>";
// time selection for graph x
$tr = ($_SESSION["time_range"] != "") ? $_SESSION["time_range"] : "all";
list($x, $y, $xticks, $xlabels) = range_graphic($tr);
switch ($tr) {
    case "today":
        $interval = "hour(timestamp) as intervalo, 'h' as suf";
        $grpby = " GROUP BY intervalo,suf";
        break;

    case "day":
        $interval = "hour(timestamp) as intervalo, day(timestamp) as suf";
        $grpby = " GROUP BY intervalo,suf";
        break;

    case "week":
    case "weeks":
        $interval = ($use_ac) ? "day(day) as intervalo, monthname(day) as suf" : "day(timestamp) as intervalo, monthname(timestamp) as suf";
        $grpby = "GROUP BY intervalo,suf ORDER BY suf,intervalo";
        break;

    case "month":
        $interval = ($use_ac) ? "day(day) as intervalo, monthname(day) as suf" : "day(timestamp) as intervalo, monthname(timestamp) as suf";
        $grpby = "GROUP BY intervalo,suf ORDER BY suf,intervalo";
        break;

    default:
        $interval = ($use_ac) ? "monthname(day) as intervalo, year(day) as suf" : "monthname(timestamp) as intervalo, year(timestamp) as suf";
        $grpby = "GROUP BY intervalo,suf ORDER BY suf,intervalo";
}
$sqlgraph = "SELECT COUNT(cid) as num_events, $interval FROM acid_event $where AND sig_class_id=SIGCLASSID $grpby";
// use accumulate tables only with timestamp criteria
if ($use_ac) {
    $where = $more = $sqla = $sqlb = $sqlc = $sqld = "";
    if (preg_match("/timestamp/", $criteria_clauses[1])) {
        $where = "WHERE " . str_replace("timestamp", "day", $criteria_clauses[1]);
        $sqla = " and ac_alertsclas_classid.day=ac_alertsclas_sid.day";
        $sqlb = " and ac_alertsclas_classid.day=ac_alertsclas_signature.day";
        $sqlc = " and ac_alertsclas_classid.day=ac_alertsclas_ipsrc.day";
        $sqld = " and ac_alertsclas_classid.day=ac_alertsclas_ipdst.day";
    }
    $orderby = str_replace("acid_event.", "", $sort_sql[1]);
    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT sig_class_id, sum(cid) as num_events,
      (select count(distinct(sid)) from ac_alertsclas_sid where ac_alertsclas_classid.sig_class_id=ac_alertsclas_sid.sig_class_id $sqla) as num_sensors,
      (select count(distinct(signature)) from ac_alertsclas_signature where ac_alertsclas_classid.sig_class_id=ac_alertsclas_signature.sig_class_id $sqlb) as num_sig,
      (select count(distinct(ip_src)) from ac_alertsclas_ipsrc where ac_alertsclas_classid.sig_class_id=ac_alertsclas_ipsrc.sig_class_id $sqlc) as num_sip,
      (select count(distinct(ip_dst)) from ac_alertsclas_ipdst where ac_alertsclas_classid.sig_class_id=ac_alertsclas_ipdst.sig_class_id $sqld) as num_dip,
      min(first_timestamp) as first_timestamp,  max(last_timestamp) as last_timestamp
      FROM ac_alertsclas_classid FORCE INDEX(primary) $where GROUP BY sig_class_id $orderby";
    $event_cnt = EventCnt($db, "", "", "SELECT sum(cid) FROM ac_alertsclas_classid $where");
    $where = "AND " . str_replace("timestamp", "day", $criteria_clauses[1]);
    if ($tr != "today" && $tr != "day") {
        // we dont have hour interval in ac_* tables
        $sqlgraph = "SELECT sum(cid) as num_events, $interval FROM ac_alertsclas_classid WHERE sig_class_id=SIGCLASSID $grpby";
    }
}
//echo $sql."<br>".$sqlgraph."<br>".$interval." ".$tr;
/* Run the Query again for the actual data (with the LIMIT) */
$result = $qs->ExecuteOutputQuery($sql, $db);
if ($use_ac) $qs->GetCalcFoundRows($cnt_sql, $db);
$et->Mark("Retrieve Query Data");
if ($debug_mode == 1) {
    $qs->PrintCannedQueryList();
    $qs->DumpState();
    echo "$sql<BR>";
}
/* Print the current view number and # of rows */
$qs->PrintResultCnt();
echo '
  <script src="js/jquery.js" language="javascript" type="text/javascript"></script>
  <script src="js/jquery.flot.pack.js" language="javascript" type="text/javascript"></script>
  ';
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) echo '<script src="js/excanvas.pack.js" language="javascript" type="text/javascript" ></script>';
echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_class.php">';
$qro->PrintHeader();
$i = 0;
while (($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt())) {
    $class_id = $myrow[0];
    if ($class_id == "") $class_id = 0;
    $total_occurances = $myrow[1];
    $sensor_num = $myrow[2];
    $sig_num = $myrow[3];
    $sip_num = $myrow[4];
    $dip_num = $myrow[5];
    $min_time = $myrow[6];
    $max_time = $myrow[7];
    /* Print out */
    qroPrintEntryHeader($i, 0, 'height="42"');
    $tmp_rowid = rawurlencode($class_id);
    echo '  <TD>&nbsp;&nbsp;
                 <INPUT TYPE="checkbox" NAME="action_chk_lst[' . $i . ']" VALUE="' . $tmp_rowid . '">
                 &nbsp;&nbsp;
             </TD>';
    echo '      <INPUT TYPE="hidden" NAME="action_lst[' . $i . ']" VALUE="' . $tmp_rowid . '">';
    qroPrintEntry(GetSigClassName($class_id, $db) , 'center', 'middle');
    qroPrintEntry('<FONT>' . '<A HREF="base_qry_main.php?new=1&amp;sig_class=' . $class_id . '&amp;submit=' . _QUERYDBP . '&amp;num_result_rows=-1">' . $total_occurances . '</A> 
                   (' . (round($total_occurances / $event_cnt * 100)) . '%)' . '</FONT>', '', 'middle');
    qroPrintEntry('<FONT><A HREF="base_stat_sensor.php?sig_class=' . $class_id . '">' . $sensor_num . '</A>', 'center', 'middle');
    qroPrintEntry('<FONT><A HREF="base_stat_alerts.php?sig_class=' . $class_id . '">' . $sig_num . '</FONT>', 'center', 'middle');
    qroPrintEntry('<FONT>' . BuildUniqueAddressLink(1, '&amp;sig_class=' . $class_id) . $sip_num . '</A></FONT>', 'center', 'middle');
    qroPrintEntry('<FONT>' . BuildUniqueAddressLink(2, '&amp;sig_class=' . $class_id) . $dip_num . '</A></FONT>', 'center', 'middle');
    //qroPrintEntry('<FONT>'.$min_time.'</FONT>');
    //qroPrintEntry('<FONT>'.$max_time.'</FONT>');
    // GRAPH
    $graph = '<div id="plotarea' . $i . '" class="plot"></div>';
    $sqlgr = str_replace("SIGCLASSID", $class_id, $sqlgraph);
    $rgraph = $qs->ExecuteOutputQuery($sqlgr, $db);
    $yy = $y;
    while ($rowgr = $rgraph->baseFetchRow()) {
        $label = trim($rowgr[1] . " " . $rowgr[2]);
        if (isset($yy[$label]) && $yy[$label] == 0) $yy[$label] = $rowgr[0];
    }
    /*$x= array(1,2,3,4,5,6,7);
    $y= array(1,0,1,0,1,0,1);
    $xticks= array(1,2,3,4,5,6,7);
    $xlabels= array("","","","","","","");*/
    $plot = plot_graphic("plotarea" . $i, 45, 400, $x, $yy, $xticks, $xlabels);
    qroPrintEntry($graph . $plot, 'center', 'middle');
    qroPrintEntryFooter();
    $i++;
    $prev_time = null;
}
$result->baseFreeRows();
$qro->PrintFooter();
$qs->PrintBrowseButtons();
$qs->PrintAlertActionButtons();
$qs->SaveState();
echo "\n</FORM>\n";
PrintBASESubFooter();
$et->Mark("Get Query Elements");
$et->PrintTiming();
echo "</body>\r\n</html>";
?>
