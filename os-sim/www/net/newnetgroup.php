<?php
require_once ('classes/Session.inc');
Session::logcheck("MenuPolicy", "PolicyNetworks");
?>

<html>
<head>
  <title> <?php echo gettext("OSSIM Framework"); ?> </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>
                                                                                
  <h1> <?php echo gettext("New network group"); ?> </h1>

<?php
require_once 'classes/Security.inc';

$descr = POST('descr');
$net_group_name = POST('name');
$threshold_a = POST('threshold_a');
$threshold_c = POST('threshold_c');
$rrd_profile = POST('rrd_profile');
$nnets = POST('nnets');

ossim_valid($net_group_name, OSS_ALPHA, OSS_SPACE, OSS_PUNC, OSS_SPACE, 'illegal:'._("Net name"));
ossim_valid($threshold_a, OSS_DIGIT, 'illegal:'._("threshold_a"));
ossim_valid($threshold_c, OSS_DIGIT, 'illegal:'._("threshold_c"));
ossim_valid($nnets, OSS_DIGIT, OSS_NULLABLE, 'illegal:'._("nnets"));
ossim_valid($rrd_profile, OSS_ALPHA, OSS_NULLABLE, OSS_SPACE, OSS_PUNC, 'illegal:'._("Net name"));
ossim_valid($descr, OSS_ALPHA, OSS_NULLABLE, OSS_SPACE, OSS_PUNC, OSS_AT, 'illegal:'._("Description"));

if (ossim_error()) {
        die(ossim_error());
}

if (POST('insert')) {
   
    $nets = array();
    for ($i = 1; $i <= $nnets; $i++) {
        $name = "mboxs" . $i;
        
        ossim_valid(POST("$name"), OSS_ALPHA, OSS_NULLABLE, OSS_PUNC, OSS_SPACE, 'illegal:'._("$name"));

        if (ossim_error()) {
            die(ossim_error());
        }
        
        $name_aux = POST("$name");
        
        if (!empty($name_aux))
            $nets[] = POST("$name");
    }

    require_once 'ossim_db.inc';
    require_once 'classes/Net.inc';
    require_once 'classes/Net_group.inc';
    require_once 'classes/Net_group_scan.inc';
    $db = new ossim_db();
    $conn = $db->connect();
   
    Net_group::insert ($conn, $net_group_name, $threshold_c, $threshold_a, $rrd_profile, $nets, $descr);

    if(POST('nessus')){
        Net_group_scan::insert ($conn, $net_group_name, 3001, 0);
    }

    $db->close($conn);
}
?>
    <p> <?php echo gettext("Network group succesfully inserted"); ?> </p>
    <p><a href="netgroup.php">
    <?php echo gettext("Back"); ?> </a></p>

</body>
</html>

