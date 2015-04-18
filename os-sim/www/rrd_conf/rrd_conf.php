<html>
<head>
  <title>OSSIM Framework</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>
                                                                                
  <h1>OSSIM Framework</h1>

  <h2>RRD Config</h2>

  <table align="center">
    <tr>
      <th>Ip</th>
      <th>Action</th>
    </tr>

<?php
    require_once 'ossim_db.inc';
    require_once 'classes/RRD_conf.inc';
    require_once 'classes/RRD_conf_global.inc';
    require_once 'classes/Host.inc';

    $db = new ossim_db();
    $conn = $db->connect();
 if ($rrd_list_global = RRD_conf_global::get_list($conn)) {
        foreach($rrd_list_global as $rrd_global) {
?>
    <tr>
      <td><?php echo "Global"; ?></td>
    <td><a href="modify_rrd_conf_form.php?ip=<?php echo "global"  ?>">Modify</a>
    </tr>

<?php
        } /* rrd_list */
    } /* foreach */

    
    if ($rrd_list = RRD_conf::get_list($conn)) {
        foreach($rrd_list as $rrd) {
            $ip = $rrd->get_ip();
?>

    <tr>
      <td><?php echo Host::ip2hostname($conn, $ip);?></td> 


      <td><a href="modify_rrd_conf_form.php?ip=<?php echo $ip ?>">Modify</a>
          <a href="delete_rrd_conf.php?ip=<?php echo $ip ?>">Delete</a></td>
    </tr>

<?php
        } /* rrd_list */
    } /* foreach */

    $db->close($conn);
?>
    <tr>
      <td colspan="7"><a href="new_rrd_conf_form.php">Insert new rrd_conf</a></td>
    </tr>
  </table>
    
</body>
</html>

