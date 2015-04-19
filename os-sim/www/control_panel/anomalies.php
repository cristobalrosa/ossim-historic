<?php
require_once ('classes/Session.inc');
Session::logcheck("MenuControlPanel", "ControlPanelAnomalies");
?>

<html>
<head>
  <title> <?php echo gettext("Control Panel"); ?> </title>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" href="../style/style.css"/>
</head>

<body>

  <h1 align="center"> <?php echo gettext("Anomalies"); ?> </h1>

<?php

require_once ('ossim_conf.inc');
require_once ('ossim_db.inc');
require_once ('classes/Host.inc');
require_once ('classes/Net.inc');
require_once ('classes/Host_os.inc');
require_once ('classes/Host_mac.inc');
require_once ('classes/Host_services.inc');
require_once ('classes/Sensor.inc');
require_once ('classes/Util.inc');
require_once ('classes/RRD_config.inc');
require_once ('classes/RRD_anomaly.inc');
require_once ('classes/RRD_anomaly_global.inc');
require_once ('classes/Security.inc');

$acked    = GET('acked');
$ex_os    = GET('ex_os');
$ex_oss   = GET('ex_oss');
$ex_mac   = GET('ex_mac');
$ex_macs  = GET('ex_macs');
$ex_serv  = GET('ex_serv');
$ex_servs = GET('ex_servs');
$ex_servp = GET('ex_servp');

ossim_valid($acked, OSS_DIGIT, OSS_NULLABLE, 'illegal:'._("acked"));
ossim_valid($ex_os, OSS_IP_ADDR, OSS_NULLABLE, 'illegal:'._("ex_os"));
ossim_valid($ex_oss, OSS_IP_ADDR, OSS_NULLABLE, 'illegal:'._("ex_oss"));
ossim_valid($ex_mac, OSS_IP_ADDR, OSS_NULLABLE, 'illegal:'._("ex_mac"));
ossim_valid($ex_macs, OSS_IP_ADDR, OSS_NULLABLE, 'illegal:'._("ex_macs"));
ossim_valid($ex_serv, OSS_IP_ADDR, OSS_NULLABLE, 'illegal:'._("ex_serv"));


ossim_valid($ex_servs, OSS_IP_ADDR, OSS_NULLABLE, 'illegal:'._("ex_servs"));
ossim_valid($ex_servp, OSS_DIGIT, OSS_NULLABLE, 'illegal:'._("ex_servp"));

if (ossim_error()) {
    die(ossim_error());
}
                    

function echo_values($val, $max, $ip, $image) {

    global $acid_link;
    global $acid_prefix;

    if ($val - $max > 0) {
        echo "<a href=\"". Util::get_acid_info($ip, $acid_link, $acid_prefix) . 
            "\"><font color=\"#991e1e\">$val</font></a>/" . 
            "<a href=\"$image\">" . intval($val * 100 / $max) ."</a>%";
    } else {
        echo "<a href=\"". Util::get_acid_info($ip, $acid_link, $acid_prefix) .
             "\">$val</a>/" . 
            "<a href=\"$image\">" . intval($val * 100 / $max) ."</a>%";
    } 
}

/* get conf */
$conf = $GLOBALS["CONF"];
$graph_link = $conf->get_conf("graph_link");
$acid_link = $conf->get_conf("acid_link");
$acid_prefix = $conf->get_conf("event_viewer");
$ntop_link = $conf->get_conf("ntop_link");
$nagios_link = $conf->get_conf("nagios_link");

/* connect to db */
$db = new ossim_db();
$conn = $db->connect();

?>

  <table align="center" width="100%">
    <table width="100%">
    <tr>
    <td colspan = 8>
    <table align="center" width="100%">
    <tr>
    <th colspan=8><?php echo gettext("RRD global anomalies"); ?>
     <a name="Anomalies" href="<?php echo $_SERVER["PHP_SELF"]?>?#Anomalies" title=" <?php echo gettext("Fix"); ?> "><img src="../pixmaps/Hammer2.png" width="24" border="0"></a>
     <a href="rrd_global.php" target="_blank">[<?php echo gettext("Get full list"); ?>]</a>
    </th>
    </tr>
    <tr>
    <th colspan=4>&nbsp;</th>
    <th align="center"><A HREF="<?php echo $_SERVER["PHP_SELF"] ?>?acked=1"> <?php echo gettext("Acknowledged"); ?> </A></th>
    <th align="center"><A HREF="<?php echo $_SERVER["PHP_SELF"] ?>?acked=0"> <?php echo gettext("Not Acknowledged"); ?> </A></th>
    <th align="center"><A HREF="<?php echo $_SERVER["PHP_SELF"] ?>?acked=-1"> <?php echo gettext("All"); ?> </A></th>
    </tr>
    <tr>
    <th> <?php echo gettext("Host"); ?> </th><th> <?php echo gettext("What"); ?> </th><th> <?php echo gettext("When"); ?> </th>
    <th> <?php echo gettext("Not acked count (hours)"); ?> </th><th> <?php echo gettext("Over threshold (absolute)"); ?> </th>
    <th align="center"> <?php echo gettext("Ack"); ?> </th>
    <th align="center"> <?php echo gettext("Delete"); ?> </th>
    </tr>

<form action="handle_anomaly.php" method="GET">
<?php

$where_clause = "where acked = 0";
switch ($acked){
    case -1:
    $where_clause = "";
    break;
    case 0:
    $where_clause = "where acked = 0";
    break;
    case 1:
    $where_clause = "where acked = 1";
    break;
}

$perl_interval = 3600 / 300;

if ($event_list_global = RRD_anomaly_global::get_list($conn, $where_clause,
"order by anomaly_time desc","0","10")) {
    foreach($event_list_global as $event) {
    $ip = "Global";
    if($rrd_list_temp = RRD_config::get_list($conn, "WHERE profile = \"global\"")) {
    $rrd_temp = $rrd_list_temp[0];
    }
?>
<tr>
<th> 

<A HREF="<?php echo
"$ntop_link/plugins/rrdPlugin?action=list&key=interfaces/eth0&title=interface%20eth0";?>" target="_blank"> 
<?php echo $ip;?></A> </th><td> <?php echo $rrd_names_global[$event->get_what()];?></td>
<td> <?php echo $event->get_anomaly_time();?></td>
<td> <?php echo round(($event->get_count())/$perl_interval);?>h. </td>
<td><font color="red"><?php echo ($event->get_over()/$rrd_temp->get_col($event->get_what(),"threshold"))*100;?>%</font>/<?php echo $event->get_over();?></td>
<td align="center"><input type="checkbox" name="ack,<?php echo $ip?>,<?php
echo $event->get_what();?>"></input></td>
<td align="center"><input type="checkbox" name="del,<?php echo $ip?>,<?php
echo $event->get_what();?>"></input></td>
</tr>
<?php }}

?>
<tr></tr>
<tr><th colspan="8"><?php echo gettext("RRD anomalies");?>
<a name="Anomalies" href="<?php echo $_SERVER["PHP_SELF"]?>?#Anomalies" title=" <?php echo gettext("Fix"); ?> "><img src="../pixmaps/Hammer2.png" width="24" border="0"></a>
<a href="rrd_anomaly.php" target="_blank">[<?php echo gettext("Get full list"); ?>]</a>
</th>
</tr>
<tr>

    <th colspan=4>&nbsp;</th>
<th align="center"><A HREF="<?php echo $_SERVER["PHP_SELF"] ?>?acked=1"> <?php echo gettext("Acknowledged"); ?> </A></th>
    <th align="center"><A HREF="<?php echo $_SERVER["PHP_SELF"] ?>?acked=0"> <?php echo gettext("Not Acknowledged"); ?> </A></th>
    <th align="center"><A HREF="<?php echo $_SERVER["PHP_SELF"] ?>?acked=-1"> <?php echo gettext("All"); ?> </A></th>
</tr>
<tr>
<th> <?php echo gettext("Host"); ?> </th><th> <?php echo gettext("What"); ?> </th><th> <?php echo gettext("When"); ?> </th>
<th> <?php echo gettext("Not acked count (hours)"); ?> </th><th> <?php echo gettext("Over threshold (absolute)"); ?> </th>
<th align="center"> <?php echo gettext("Ack"); ?> </th>
<th align="center"> <?php echo gettext("Delete"); ?> </th>
</tr>
<?php

$perl_interval = 4; // Host perl is being executed every 15 minutes
if ($event_list = RRD_anomaly::get_list($conn, $where_clause, "order by
anomaly_time desc","0","10")) {
    foreach($event_list as $event) {
    $ip = $event->get_ip();
?>
<tr>
<th>
<A HREF="<?php echo Sensor::get_sensor_link($conn, $ip) . 
    "/$ip.html";?>" target="_blank" title="<?php
echo $ip;?>">
<?php echo Host::ip2hostname($conn, $ip);?></A></th><td> <?php echo $event->get_what();?></td>
<td> <?php echo $event->get_anomaly_time();?></td>
<td> <?php echo round(($event->get_count())/$perl_interval);?>h. </td>
<td><font color="red"><?php echo 0;//echo ($event->get_over()/$rrd_temp->get_col($event->get_what(),"threshold"))*100;?>%</font>/<?php echo $event->get_over();?></td>
<td align="center"><input type="checkbox" name="ack,<?php echo $ip?>,<?php
echo $event->get_what();?>"></input></td>
<td align="center"><input type="checkbox" name="del,<?php echo $ip?>,<?php
echo $event->get_what();?>"></input></td>
</tr>
<?php }}?>
<tr>
<td align="center" colspan="7">
<input type="submit" value=" <?php echo gettext("OK"); ?> ">
<input type="reset" value=" <?php echo gettext("reset"); ?> ">
</td>
</tr>
</form>

<!-- OS detection -->
    <table width="100%">
    <tr>
    <td colspan="7">
    <table width="100%">
    <tr>
    	<th colspan="9"><u> <?php echo gettext("OS Changes"); ?> </u> <a name="OS" 
        href="<?php echo $_SERVER["PHP_SELF"]?>?#OS" title=" <?php echo gettext("Fix"); ?> "><img
        src="../pixmaps/Hammer2.png" width="24" border="0"></a>  
        &nbsp;&nbsp;[ <a href="os.php?show_anom=1" target="_blank"> <?php echo
gettext("Get anom list"); ?> </a> ] [<a href="os.php" target="_blank"> <?php
echo gettext("Get full list"); ?> </a> ]
    	</th>
    </tr>
    <tr>
   	<th>&nbsp;</th>
   	<th> <?php echo gettext("Host"); ?> </th>
	<th> <?php echo gettext("Sensor"); ?> </th>
	<th> <?php echo gettext("OS"); ?> </th>
	<th> <?php echo gettext("Previous OS"); ?> </th>
	<th> <?php echo gettext("When"); ?> </th>
      <?php if ($ex_os) { ?>
	<th> <?php echo gettext("Ack"); ?> </th>
	<th> <?php echo gettext("Ignore"); ?> </th>
        <th></th>
      <?php } ?>
    </tr>
<form action="handle_os.php" method="GET">
<?php
if ($anom_os_list = Host_os::get_anom_list($conn)) {
    foreach($anom_os_list as $anom_os) {
?>

<tr <?php if (($ex_os == $anom_os["ip"]) && ($ex_oss == $anom_os["sensor"]))  echo "bgcolor=\"#DFDFDF\"";?>>
<td colspan="1">
<?php 
if (($ex_os == $anom_os["ip"]) && ($ex_oss == $anom_os["sensor"])) { ?>
	<a href="<?php echo $_SERVER["PHP_SELF"] ?>"><img src="../pixmaps/arrow.gif" border=\"0\"></e>
<?php } else { ?>
	<a href="<?php echo $_SERVER["PHP_SELF"]."?ex_os=".$anom_os["ip"]."&ex_oss=".$anom_os["sensor"] ?>"><img src="../pixmaps/arrow2.gif" border=\"0\"></e>
<?php } ?>
</td><td>
<A HREF="<?php echo Sensor::get_sensor_link($conn, $anom_os["ip"]) . 
    "/".$anom_os["ip"].".html";?>" target="_blank" title="<?php
echo $ip;?>">
<?php echo Host::ip2hostname($conn, $anom_os["ip"]);?></A>
</td>
<td colspan="1"><?php echo Host::ip2hostname($conn, $anom_os["sensor"], true);?></td>
<td colspan="1"><font color="red"><?php echo $anom_os["os"];?></font></td>
<td colspan="1"><?php echo $anom_os["old_os"];?></td>
<td colspan="1"><?php echo $anom_os["date"];?></td>
<?php if ($ex_os) { ?>
  <td colspan="1">&nbsp;</td>
  <td colspan="1">&nbsp;</td>
  <td colspan="1">&nbsp;</td>
<?php } ?>

</tr>
<?php

if (($ex_os == $anom_os["ip"]) && ($ex_oss == $anom_os["sensor"])) {

	if ($anom_os_ip_list = Host_os::get_anom_ip_list($conn, $ex_os, $ex_oss)) {
	foreach ($anom_os_ip_list as $anom_os_ip) {
?>
	
	<tr bgcolor="#EFEFEF">
	<td>&nbsp;</td>
	<td>
	<A HREF="<?php echo Sensor::get_sensor_link($conn, $anom_os_ip["ip"]) . 
	    "/".$anom_os_ip["ip"].".html";?>" target="_blank" title="<?php
	echo $ip;?>">
	<?php echo Host::ip2hostname($conn, $anom_os_ip["ip"]);?></A>
	</td>
	<td><?php echo Host::ip2hostname($conn, $anom_os_ip["sensor"], true);?></td>
	<td><font color="red"><?php echo $anom_os_ip["os"];?></font></td>
	<td><?php echo $anom_os_ip["old_os"];?></td>
	<td colspan="1"><?php echo $anom_os_ip["date"];?></td>
	<td>
	<input type="checkbox" name="ip,<?php echo
$anom_os_ip["ip"].",".$anom_os_ip["sensor"].",".$anom_os_ip["date"];?>" value="<?php echo "ack".$anom_os_ip["ip"]; ?>"></input>
	</td>
	<td>
	<input type="checkbox" name="ip,<?php echo $anom_os_ip["ip"];?>,<?php echo $anom_os_ip["sensor"];?>,<?php echo $anom_os_ip["old_date"];?>" value="<?php echo "ignore".$anom_os_ip["ip"];?>"></input>
	</td>
<td>
 <a href="<?php echo
 "../incidents/newincident.php?ref=Anomaly&anom_type=os&anom_ip=".$anom_os_ip["ip"]."&a_sen=".$anom_os_ip["sensor"]."
 [".$anom_os_ip["interface"]."]&a_os_o=".$anom_os_ip["old_os"]."&a_os=".$anom_os_ip["os"]."&a_date=".$anom_os_ip["date"]."&title=Host
 ".$anom_os_ip["ip"]."
 changed its OS"; ?>">
 <img src="../pixmaps/incident.png" width="12" alt="i" border="0"/>
 </a>
</td>                                                                                                             
</tr>
	
<?php
}}}}}
?>
<tr>
<td align="center" colspan="9">
<input type="submit" value=" <?php echo gettext("OK"); ?> ">
<input type="reset" value=" <?php echo gettext("reset"); ?> ">
</td>
</tr>
</form>
    </table>
    </td>
    </tr>
    <!-- end OS detection -->

<!-- Mac detection -->
    <br/>
    <table width="100%">
    <tr>
    <td colspan="8">
    <table width="100%">
    <tr>
    	<th colspan="9"><u> <?php echo gettext("Mac Changes"); ?> </u> <a name="Mac" 
        href="<?php echo $_SERVER["PHP_SELF"]?>?#Mac" title=" <?php echo gettext("Fix"); ?> "><img
        src="../pixmaps/Hammer2.png" width="24" border="0"></a>  
        &nbsp;&nbsp;[ <a href="mac.php?show_anom=1" target="_blank"> <?php echo
gettext("Get anom list"); ?> </a> ] [<a href="mac.php" target="_blank"> <?php
echo gettext("Get full list"); ?> </a> ]
    	</th>
    </tr>
    <tr>
    	<th>&nbsp;</th>
    	<th> <?php echo gettext("Host"); ?> </th>
	<th> <?php echo gettext("Sensor"); ?> </th>
	<th> <?php echo gettext("Mac"); ?> </th>
	<th> <?php echo gettext("Previous Mac"); ?> </th>
	<th> <?php echo gettext("When"); ?> </th>
      <?php if ($ex_mac) { ?>
	<th> <?php echo gettext("Ack"); ?> </th>
	<th> <?php echo gettext("Ignore"); ?> </th>
        <th></th>
      <?php } ?>
    </tr>
<form action="handle_mac.php" method="GET">
<?php
if ($anom_mac_list = Host_mac::get_anom_list($conn)) {
    foreach($anom_mac_list as $anom_mac) {
?>

<tr <?php if (($ex_mac == $anom_mac["ip"]) && ($ex_macs == $anom_mac["sensor"])) echo "bgcolor=\"#DFDFDF\"";?>>
<td colspan="1">
<?php 
if (($ex_mac == $anom_mac["ip"]) && ($ex_macs == $anom_mac["sensor"])) { ?>
	<a href="<?php echo $_SERVER["PHP_SELF"] ?>"><img src="../pixmaps/arrow.gif" border=\"0\"></e>
<?php } else { ?>
	<a href="<?php echo $_SERVER["PHP_SELF"]."?ex_mac=".$anom_mac["ip"]."&ex_macs=".$anom_mac["sensor"] ?>"><img src="../pixmaps/arrow2.gif" border=\"0\"></e>
<?php } ?>
</td><td>
<A HREF="<?php echo Sensor::get_sensor_link($conn, $anom_mac["ip"]) . 
    "/".$anom_mac["ip"].".html";?>" target="_blank" title="<?php
echo $ip;?>">
<?php echo Host::ip2hostname($conn, $anom_mac["ip"]);?></A>
</td>
<td colspan="1"><?php echo Host::ip2hostname($conn, $anom_mac["sensor"], true);?></td>
<td colspan="1"><font color="red"><?php echo $anom_mac["mac"];?></font></td>
<td colspan="1"><?php echo $anom_mac["old_mac"];?></td>
<td colspan="1"><?php echo $anom_mac["date"];?></td>
<?php if ($ex_mac) { ?>
  <td colspan="1">&nbsp;</td>
  <td colspan="1">&nbsp;</td>
  <td colspan="1">&nbsp;</td>
<?php } ?>
</tr>

<?php
if (($ex_mac == $anom_mac["ip"]) && ($ex_macs == $anom_mac["sensor"])) {

	if ($anom_mac_ip_list = Host_mac::get_anom_ip_list($conn, $ex_mac, $ex_macs)) {
	foreach ($anom_mac_ip_list as $anom_mac_ip) {
?>
	
	<tr bgcolor="#EFEFEF">
	<td>&nbsp;</td>
	<td>
	<A HREF="<?php echo Sensor::get_sensor_link($conn, $anom_mac_ip["ip"]) . 
	    "/".$anom_mac_ip["ip"].".html";?>" target="_blank" title="<?php
	echo $ip;?>">
	<?php echo Host::ip2hostname($conn, $anom_mac_ip["ip"]);?></A>
	</td>
	<td><?php echo Host::ip2hostname($conn, $anom_mac_ip["sensor"], true);?></td>
	<td><font color="red"><?php echo $anom_mac_ip["mac"];?></font></td>
	<td><?php echo $anom_mac_ip["old_mac"];?></td>
	<td colspan="1"><?php echo $anom_mac_ip["date"];?></td>
	<td>
	<input type="checkbox" name="ip,<?php echo $anom_mac_ip["ip"].",".$anom_mac_ip["sensor"].",".$anom_mac_ip["date"];?>" value="<?php echo "ack".$anom_mac_ip["ip"]; ?>"></input>
	</td>
	<td>
	<input type="checkbox" name="ip,<?php echo $anom_mac_ip["ip"];?>,<?php echo $anom_mac_ip["sensor"];?>,<?php echo $anom_mac_ip["old_date"];?>" value="<?php echo "ignore".$anom_mac_ip["ip"];?>"></input>
	</td>
<td>
 <a href="<?php echo
 "../incidents/newincident.php?ref=Anomaly&anom_type=mac&anom_ip=".$anom_mac_ip["ip"]."&a_sen=".$anom_mac_ip["sensor"]."
 [".$anom_mac_ip["interface"]."]&a_ven=.".$anom_mac_ip["vendor"]."&a_ven_o=".$a_mac_ip["old_vendor"]."&a_mac_o=".$anom_mac_ip["old_mac"]."&a_mac=".$anom_mac_ip["mac"]."&a_date=".$anom_mac_ip["date"]."&title=Host
 ".$anom_mac_ip["ip"]."
 changed its MAC address"; ?>">
 <img src="../pixmaps/incident.png" width="12" alt="i" border="0"/>
 </a>
</td>                                                                                                             
</tr>
	
<?php
}}}}}
?>
<tr>
<td align="center" colspan="10">
<input type="submit" value=" <?php echo gettext("OK"); ?> ">
<input type="reset" value=" <?php echo gettext("reset"); ?> ">
</td>
</tr>
</form>
    </table>
    </td>
    </tr>
    <!-- end Mac detection -->
<br/>

<!--  Start Service detection -->
    <br/>
    <table width="100%">
    <tr>
    <td colspan="7">
    <table width="100%">
    <tr>
    	<th colspan="10"><u> <?php echo gettext("Service Changes"); ?> </u> <a
        name="Service" 
        href="<?php echo $_SERVER["PHP_SELF"]?>?#Service" title=" <?php echo gettext("Fix"); ?> "><img
        src="../pixmaps/Hammer2.png" width="24" border="0"></a>  
        &nbsp;&nbsp;[ <a href="services.php?show_anom=1" target="_blank"> <?php echo
gettext("Get anom list"); ?> </a> ] [<a href="services.php" target="_blank"> <?php
echo gettext("Get full list"); ?> </a> ]
    	</th>
    </tr>
    <tr>
       	<th>&nbsp;</th>
   	    <th> <?php echo gettext("Host"); ?> </th>
	    <th> <?php echo gettext("Sensor"); ?> </th>
	    <th> <?php echo gettext("Port"); ?> </th>
	    <th> <?php echo gettext("Service [Version]"); ?> </th>
	    <th> <?php echo gettext("Previous Service [Version]"); ?> </th>
	    <th> <?php echo gettext("When"); ?> </th>
      <?php if ($ex_servs) { ?>
	<th> <?php echo gettext("Ack"); ?> </th>
	<th> <?php echo gettext("Ignore"); ?> </th>
        <th></th>
      <?php } ?>
    </tr>
<form action="handle_services.php" method="GET">
<?php
if ($anom_services_list = Host_services::get_anom_list($conn)) {
    foreach($anom_services_list as $anom_services) {
?>

<tr <?php if (($ex_serv == $anom_services["ip"]) && ($ex_servs == $anom_services["sensor"]) 
                && ($ex_servp == $anom_services["port"])) echo "bgcolor=\"#DFDFDF\"";?>>
<td colspan="1">
<?php 
if (($ex_serv == $anom_services["ip"]) && ($ex_servs == $anom_services["sensor"]) && ($ex_servp == $anom_services["port"]) ) { ?>
	<a href="<?php echo $_SERVER["PHP_SELF"] ?>"><img src="../pixmaps/arrow.gif" border=\"0\"></e>
<?php } else { ?>
	<a href="<?php echo
    $_SERVER["PHP_SELF"]."?ex_serv=".$anom_services["ip"]."&ex_servs=".$anom_services["sensor"]."&ex_servp=".$anom_services["port"] ?>"><img src="../pixmaps/arrow2.gif" border=\"0\"></e>
<?php } ?>
</td><td>
<A HREF="<?php echo Sensor::get_sensor_link($conn, $anom_services["ip"]) . 
    "/".$anom_services["ip"].".html";?>" target="_blank" title="<?php
echo $ip;?>">
<?php echo Host::ip2hostname($conn, $anom_services["ip"]);?></A>
</td>
<td colspan="1"><?php echo Host::ip2hostname($conn, $anom_services["sensor"], true);?></td>
<td colspan="1"><?php echo $anom_services["port"];?></td>
<td colspan="1"><font color="red"><?php echo
$anom_services["service"]."/".getprotobynumber($anom_services["protocol"])."[".$anom_services["version"]."]";?></font></td>
<td colspan="1"><?php echo $anom_services["old_service"]."/".getprotobynumber($anom_services["old_protocol"])." [".$anom_services["old_version"]."]";?></td>
<td colspan="1"><?php echo $anom_services["date"];?></td>
<?php if ($ex_os) { ?>
  <td colspan="1">&nbsp;</td>
  <td colspan="1">&nbsp;</td>
  <td colspan="1">&nbsp;</td>
<?php } ?>
</tr>

<?php
if (($ex_serv == $anom_services["ip"]) && ($ex_servs == $anom_services["sensor"]) && ($ex_servp == $anom_services["port"])) {
	
    if ($anom_services_ip_list = Host_services::get_anom_ip_list($conn, $ex_serv, $ex_servs, $ex_servp)) {
	foreach ($anom_services_ip_list as $anom_services_ip) {
?>
	
	<tr bgcolor="#EFEFEF">
	<td>&nbsp;</td>
	<td>
	<A HREF="<?php echo Sensor::get_sensor_link($conn, $anom_services_ip["ip"]) . 
	    "/".$anom_services_ip["ip"].".html";?>" target="_blank" title="<?php
	echo $ip;?>">
	<?php echo Host::ip2hostname($conn, $anom_services_ip["ip"]);?></A>
	</td>
    <td colspan="1"><?php echo Host::ip2hostname($conn, $anom_services_ip["sensor"], true);?></td>
    <td colspan="1"><?php echo $anom_services_ip["port"];?></td>
    <td colspan="1"><font color="red"><?php echo $anom_services_ip["service"]."/".getprotobynumber($anom_services_ip["protocol"])." [".$anom_services_ip["version"]."]";?></font></td>
    <td colspan="1"><?php echo $anom_services_ip["service"]."/".getprotobynumber($anom_services_ip["old_protocol"])." [".$anom_services_ip["old_version"]."]";?></td>
    <td colspan="1"><?php echo $anom_services_ip["date"];?></td>
    <td>
        <input type="checkbox" name="ip,<?php echo
        $anom_services_ip["ip"];?>,<?php echo $anom_services_ip["sensor"];?>,<?php
        echo $anom_services_ip["date"];?>,<?php echo
        $anom_services_ip["port"];?>" value="<?php echo "ack".$anom_services_ip["ip"];?>"></input>
    </td>
    <td>
    <input type="checkbox" name="ip,<?php echo $anom_services_ip["ip"];?>,<?php echo $anom_services_ip["sensor"];?>,<?php
    echo $anom_services_ip["old_date"];?>,<?php echo $anom_services_ip["port"];?>" value="<?php echo "ignore".$anom_services_ip["ip"];?>"></input>
    </td>
<td>
 <a href="<?php echo
 "../incidents/newincident.php?ref=Anomaly&anom_type=service&anom_ip=".$anom_services_ip["ip"]."&a_sen=".$anom_services_ip["sensor"]."
 [".$anom_services_ip["interface"]."]&a_port=".$anom_services_ip["port"]."&a_prot=".getprotobynumber($anom_services_ip["protocol"])."&a_ver_o=".$anom_services_ip["version"]."&a_prot_o=".getprotobynumber($anom_services_ip["old_protocol"])."&a_ver=".$anom_services_ip["old_version"]."&a_date=".$anom_services_ip["date"]."&title=Host ".$anom_services_ip["ip"]." changed protocol/version at port ".$anom_services_ip["port"]; ?>">
 <img src="../pixmaps/incident.png" width="12" alt="i" border="0"/>
 </a>
</td>                                                                                                             
</tr>
	
<?php
}}}}}
?>
<tr>
<td align="center" colspan="11">
<input type="submit" value=" <?php echo gettext("OK"); ?> ">
<input type="reset" value=" <?php echo gettext("reset"); ?> ">
</td>
</tr>
</form>
    </table>
    </td>
    </tr>
    <!-- end services detection -->
<br/>

</table>
<br/>
 </table>

<?php
$db->close($conn);
?>

</body>
</html>


