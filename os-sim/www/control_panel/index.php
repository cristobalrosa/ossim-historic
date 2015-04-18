<html>
<head>
  <title> Riskmeter </title>
  <meta http-equiv="refresh" content="60">
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" href="../style/style.css"/>
</head>

<body>

  <h1 align="center">OSSIM Framework</h1>
  <h2 align="center">Control Panel</h2>

<?php

require_once ('ossim_conf.inc');
require_once ('ossim_db.inc');
require_once ('classes/Control_panel_host.inc');
require_once ('classes/Control_panel_net.inc');
require_once ('classes/Host.inc');
require_once ('acid_funcs.inc');
require_once ('common.inc');

if (!$range = $_GET["range"])  $range = 'day';

/* get conf */
$conf = new ossim_conf();
$mrtg_link = $conf->get_conf("mrtg_link");
$graph_link = $conf->get_conf("graph_link");
$acid_link = $conf->get_conf("acid_link");
$ntop_link = $conf->get_conf("ntop_link");
$opennms_link = $conf->get_conf("opennms_link");
$stats_link = $conf->get_conf("stats_link");
$mailstats_link = $conf->get_conf("mailstats_link");

/* connect to db */
$db = new ossim_db();
$conn = $db->connect();

/* get host & net lists */
$hosts_order_by_c = Control_panel_host::get_list($conn, 
            "WHERE time_range = '$range' ORDER BY max_c DESC", 5);
$hosts_order_by_a = Control_panel_host::get_list($conn, 
            "WHERE time_range = '$range' ORDER BY max_a DESC", 5);
$nets_order_by_c = Control_panel_net::get_list($conn, 
            "WHERE time_range = '$range' ORDER BY max_c DESC", 5);
$nets_order_by_a = Control_panel_net::get_list($conn, 
            "WHERE time_range = '$range' ORDER BY max_a DESC", 5);

?>

  <table align="center">
    <tr><td colspan="8">
      [<a href="<?php echo $_SERVER["PHP_SELF"] ?>?range=day">Last Day</a>]
      [<a href="<?php echo $_SERVER["PHP_SELF"] ?>?range=month">Last Month</a>]
      [<a href="<?php echo $_SERVER["PHP_SELF"] ?>?range=year">Last Year</a>]
    </td></tr>
    <tr><td colspan="8">
<?php
        if ($range == 'day') {
            $image1 = "$graph_link?ip=global&what=attack&start=N-24h&end=N&type=global&zoom=0.85";
            $image2 = "$graph_link?ip=global&what=compromise&start=N-24h&end=N&type=global&zoom=0.85";
            $start = "N-1D";
        } elseif ($range == 'month') {
            $image1 = "$graph_link?ip=global&what=attack&start=N-1M&end=N&type=global&zoom=0.85";
            $image2 = "$graph_link?ip=global&what=compromise&start=N-1M&end=N&type=global&zoom=0.85";
            $start = "N-1M";
        } elseif ($range == 'year') {
            $image1 = "$graph_link?ip=global&what=attack&start=N-1Y&end=N&type=global&zoom=0.85";
            $image2 = "$graph_link?ip=global&what=compromise&start=N-1Y&end=N&type=global&zoom=0.85";
            $start = "N-1Y";
        }
?>
      <img src="<?php echo "$image1"; ?>">
      <img src="<?php echo "$image2"; ?>">
    </td></tr>
    <tr><th colspan="8">Compromise and Attack level - Top 5 Hosts</th></tr>
    <tr>
      <th>Host</th>
      <th>Max C</th>
      <th>Min C</th>
      <th>Avg C</th>
      <th>Host</th>
      <th>Max A</th>
      <th>Min A</th>
      <th>Avg A</th>
    </tr>
    <tr>
      <td>
        <table width="100%">
<?php 
    if ($hosts_order_by_c)
    foreach ($hosts_order_by_c as $host) { ?>
          <tr>
            <td><a href="<?php echo get_acid_info($host->get_host_ip(), 
                                                  $acid_link); ?>">
            <?php echo Host::ip2hostname($conn, $host->get_host_ip()); ?></a>
            </td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php
    if ($hosts_order_by_c)
    foreach ($hosts_order_by_c as $host) {
    $image = graph_image_link($host->get_host_ip(), "host", "compromise",
                              $start, "N", 1); 
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $host->get_max_c(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($hosts_order_by_c)
    foreach ($hosts_order_by_c as $host) { 
    $image = graph_image_link($host->get_host_ip(), "host", "compromise",
                              $start, "N", 1); 
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $host->get_min_c(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($hosts_order_by_c)
    foreach ($hosts_order_by_c as $host) { 
    $image = graph_image_link($host->get_host_ip(), "host", "compromise",
                              $start, "N", 1); 
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $host->get_avg_c(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($hosts_order_by_a)
    foreach ($hosts_order_by_a as $host) { ?>
          <tr>
            <td><a href="<?php echo get_acid_info($host->get_host_ip(), 
                                                  $acid_link); ?>">
            <?php echo Host::ip2hostname($conn, $host->get_host_ip()); ?></a>
            </td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($hosts_order_by_a)
    foreach ($hosts_order_by_a as $host) {
    $image = graph_image_link($host->get_host_ip(), "host", "attack",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $host->get_max_a(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($hosts_order_by_a)
    foreach ($hosts_order_by_a as $host) { 
    $image = graph_image_link($host->get_host_ip(), "host", "attack",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $host->get_min_a(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($hosts_order_by_a)
    foreach ($hosts_order_by_a as $host) { 
    $image = graph_image_link($host->get_host_ip(), "host", "attack",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $host->get_avg_a(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
    </tr>

    <tr><th colspan="8">Compromise and Attack level - Top 5 Networks</th></tr>
    <tr>
      <th>Network</th>
      <th>Max C</th>
      <th>Min C</th>
      <th>Avg C</th>
      <th>Network</th>
      <th>Max A</th>
      <th>Min A</th>
      <th>Avg A</th>
    </tr>
    <tr>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_c)
    foreach ($nets_order_by_c as $net) { ?>
          <tr>
            <td><?php echo $net->get_net_name(); ?>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_c)
    foreach ($nets_order_by_c as $net) { 
    $image = graph_image_link($net->get_net_name(), "net", "compromise",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $net->get_max_c(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_c)
    foreach ($nets_order_by_c as $net) { 
    $image = graph_image_link($net->get_net_name(), "net", "compromise",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $net->get_min_c(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_c)
    foreach ($nets_order_by_c as $net) { 
    $image = graph_image_link($net->get_net_name(), "net", "compromise",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $net->get_avg_c(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_a)
    foreach ($nets_order_by_a as $net) { ?>
          <tr>
            <td><?php echo $net->get_net_name(); ?>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_a)
    foreach ($nets_order_by_a as $net) { 
    $image = graph_image_link($net->get_net_name(), "net", "compromise",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $net->get_max_a(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_a)
    foreach ($nets_order_by_a as $net) { 
    $image = graph_image_link($net->get_net_name(), "net", "compromise",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $net->get_min_a(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
      <td>
        <table width="100%">
<?php 
    if ($nets_order_by_a)
    foreach ($nets_order_by_a as $net) { 
    $image = graph_image_link($net->get_net_name(), "net", "compromise",
                              $start, "N", 1);
?>
          <tr>
            <td><a href="<?php echo $image ?>"><?php echo $net->get_avg_a(); ?></a></td>
          </tr>
<?php } ?>
        </table>
      </td>
    </tr>
  </table>

<p>&nbsp;</p>

  <!-- static code -->
<center><h3> Static code. work in progress...</h3></center>
 <table align="center">
  <tr>
    <th colspan="2"></th>
    <th colspan="2">Transmitted</th>
    <th colspan="2">Throughput</th>
  </tr>
  <tr>
    <td align="center" colspan="2"></td>
    <td align="center">Total</td>
    <td align="center">%Avg</td>
    <td align="center">Total</td>
    <td align="center">%Avg</td>
  </tr>
  <tr>
    <td align="center" colspan="2">Internet</td>
    <td align="center">23</td>
    <td align="center">30%</td>
    <td align="center">1,2</td>
    <td align="center">15%</td>
  </tr>
  <tr>
    <td align="center" colspan="2">DMZ</td>
    <td align="center">
      <a href="<?php echo $ntop_link?>/IpL2R.html"><font color="red">46</font></a></td>
    <td align="center">
      <a href="<?php echo $ntop_link?>/IpL2R.html"><font color="red">400%</font></a></td>
    <td align="center">
      <a href="<?php echo $ntop_link?>/thptStats.html"><font color="red">9,3</font></a></td>
    <td align="center">
      <a href="<?php echo $ntop_link?>/thptStats.html"><font color="red">200%</font></a></td>
  </tr>
  <tr>
    <td align="center" colspan="2">Internal</td>
    <td align="center">459</td>
    <td align="center">-20%</td>
    <td align="center">60</td>
    <td align="center">-10%</td>
  </tr>
 


 
  <tr><th colspan="6" bgcolor="silver">Services</th></tr>
  <tr>
    <th colspan="2"></th>
    <th colspan="2">Latency (seg)</th>
    <th colspan="2">RTT (ms)</th>
  </tr>
  <tr>
    <td align="center">Host</td>
    <td align="center">Protocol</td>
    <td align="center">Max</td>
    <td align="center">%Avg</td>
    <td align="center">Max</td>
    <td align="center">%Avg</td>
  </tr>
  <tr>
    <td align="center">www.ipsoluciones.com</td>
    <td align="center">http</td>
    <td align="center">
      <a href="<?php echo $stats_link?>/stats/web/www.ipsoluciones.com.html">5,6</a></td>
    <td align="center">2,30%</td>
    <td align="center">
      <a href="<?php echo $stats_link?>/stats/ping/www.ipsoluciones.com.html">5</a></td>
    <td align="center">-20%</td>
  </tr>
  <tr>
    <td align="center">script.ipsoluciones.com</td>
    <td align="center">http</td>
    <td align="center">9,2</td>
    <td align="center">10%</td>
    <td align="center">4</td>
    <td align="center">-10%</td>
  </tr>
  <tr>
    <td align="center">mail.ipsoluciones.com</td>
    <td align="center">smtp</td>
    <td align="center"><a href="<?php echo $stats_link?>/stats/smtp/mail.ipsoluciones.com.html">22,3</a></td>
    <td align="center"><a href="<?php echo $stats_link?>/stats/smtp/mail.ipsoluciones.com.html">89,20%</a></td>
    <td align="center">3</td>
    <td align="center">8%</td>
  </tr>
  <tr>
    <td align="center">pop.ipsoluciones.com</td>
    <td align="center">pop</td>
    <td align="center">1,3</td>
    <td align="center">-6%</td>
    <td align="center">4</td>
    <td align="center">9%</td>
  </tr>
  <tr>
    <td align="center">ftp.ipsoluciones.com</td>
    <td align="center">ftp</td>
    <td align="center">
      <a href="<?php echo $stats_link?>/stats/ftp/ftp.ipsoluciones.com.html">2,3</a></td>
    <td align="center">9,20%</td>
    <td align="center">4</td>
    <td align="center">8%</td>
  </tr>

  <tr><th colspan="6" bgcolor="silver">Transactions</th></tr>
  <tr>
    <th colspan="2">Tipo</th>
    <th colspan="2">Total</th>
    <th colspan="2">%Average</th>
  </tr>
  <tr>
    <td align="center" colspan="2"><font color="blue">web</font></td>
    <td align="center" colspan="2"><font color="blue">1400</font></td>
    <td align="center" colspan="2"><font color="blue">30%</font></td>
  </tr>
  <tr>
    <td align="center" colspan="2"><font color="blue">mail</font></td>
    <td align="center" colspan="2"><font color="red"><A HREF="<?php echo $mailstats_link?>/mailscanner-mrtg/mail/mail.html"><font color="red" >1623</font></A></font></td>
    <td align="center" colspan="2"><font color="red">140%</font></td>
  </tr>
  <tr>
    <td align="center" colspan="2"><font color="blue">virus</font></td>
    <td align="center" colspan="2"><font color="red"><A HREF="<?php echo $mailstats_link?>/mailscanner-mrtg/virus/virus.html"><font color="red">40</font></A></font></td>
    <td align="center" colspan="2"><font color="red">102%</font></td>
  </tr>
<FORM name="temp" action="">
  <tr><th colspan="6" bgcolor="silver">Profile anomalies</th></tr>
  <tr>
    <td colspan="3" align="center">Show anomalies</td>
    <td align="center"><A HREF="">Acknowledged</A></td>
    <td align="center"><A HREF="">Not Acknowledged</A></td>
    <td align="center"><A HREF="">All</A></td>
  </tr>
  <tr>
    <th bgcolor="silver"> Date </th>
    <th colspan="2" bgcolor="silver"> System </th>
    <th colspan="2" bgcolor="silver"> Anomaly </th>
    <th bgcolor="silver"> Ack </th>
  </tr>
  <tr>
    <td align="center"> Jul-03 16:35 </td>
    <td colspan="2" align="center">
        <a href="<?php echo $rootaddr ?>/stats/frameoptions.php?ip=192.168.1.97">golgotha</a></td>
    <td colspan="2" align="left">
        <a href="<?php echo $ntop_link?>/sortDataThpt.html?col=3">
          <font color="red"><u>Over 600% traffic transmitted</u></font></a></td>
    <td align="center"><input type="checkbox"></input> 
  </tr>
  <tr>
    <td align="center"> Jul-03 04:10 </td>
    <td colspan="2" align="center">
        <a href="<?php echo $rootaddr ?>/stats/frameoptions.php?ip=192.168.1.203">vixen</a></td>
    <td colspan="2" align="left">
        <a href="<?php echo $ntop_link?>/192.168.1.203.html">
          <font color="green"><u>New port 442 used 100MB</u></font></a></td>
    <td align="center"><input type="checkbox"></input> 
  </tr>
  <tr>
    <td align="center"> Jul-02 18:22 </td>
    <td colspan="2" align="center">
        <a href="<?php echo $rootaddr ?>/stats/frameoptions.php?ip=192.168.1.7">kaneda</a></td>
    <td colspan="2" align="left">
        <a href="<?php echo $ntop_link?>/192.168.1.7.html">
          <font color="orange"><u>620% more connections established</u></font></a></td>
    <td align="center"><input type="checkbox"></input> 
  </tr>
  <tr>
    <td align="center"> Jul-02 09:50 </td>
    <td colspan="2" align="center">
        <a href="<?php echo $rootaddr ?>/stats/frameoptions.php?ip=192.168.1.97">golgotha</a></td>
    <td colspan="2" align="left">
        <a href="<?php echo $stats_link?>/stats/system/load.html">
          <font color="orange"><u>System load too high. 300% over average</u></font></a></td>
    <td align="center"><input type="checkbox"></input> 
  </tr>
  <tr>
    <td align="center"> Jul-01 19:50 </td>
    <td colspan="2" align="center">
        <a href="<?php echo $rootaddr
        ?>/stats/frameoptions.php?ip=192.168.1.40">Router_Mad</a></td>
    <td colspan="2" align="left">
        <a href="<?php echo $opennms_link?>/element/node.jsp?node=3">
          <font color="red"><u>Smtp availability under 97%</u></font></a></td>
    <td align="center"><input type="checkbox"></input> 
  </tr>
  <tr>
    <td align="center"> Jun-30 17:03 </td>
    <td colspan="2" align="center">
        <a href="<?php echo $rootaddr ?>/stats/frameoptions.php?ip=192.168.1.97">golgotha</a></td>
    <td colspan="2" align="left">
        <a href="<?php echo $ntop_link?>/dataHostTraffic.html">
          <font color="orange"><u>Traffic at strange hours</u></font></a></td>
    <td align="center"><input type="checkbox"></input> 
  </tr>
  <tr>
    <td align="center"> Jun-28 12:21 </td>
    <td colspan="2" align="center">
        <a href="<?php echo $rootaddr ?>/stats/frameoptions.php?ip=192.168.1.97">golgotha</a></td>
    <td colspan="2" align="left">
        <a href="<?php echo $ntop_link?>/localHostsInfo.html"><u>OS Change: Linux 2.4.1</u></a></td>
    <td align="center"><input type="checkbox"></input> 
  </tr>
  <tr>
  <td bgcolor="silver" align="center" colspan="6"><INPUT TYPE="submit" NAME="Aceptar"
  VALUE="Aceptar"></INPUT></td>
  </TR>
  </FORM>
  <!-- end static code -->





  </table>

<?php
$db->close($conn);
?>

</body>
</html>


