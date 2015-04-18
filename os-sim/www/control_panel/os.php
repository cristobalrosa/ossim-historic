<html>
<head>
  <title>OSSIM Framework</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>
                                                                                
  <h1>OSSIM Framework - OS list</h1>

<?php
require_once 'ossim_db.inc';
require_once 'classes/Host_os.inc';
require_once 'classes/Host.inc';
if (!$offset = intval($_GET["offset"])){ $offset = 0;}
if (!$count = intval($_GET["count"])){ $count = 50;}

$where_clause = " limit $count offset $offset ";
?>

<ul>
<li> <a href="<?php echo $_SERVER["PHP_SELF"] ?>?offset=<?php echo intval($offset); ?>&count=10"> Show 10 </a> 
<li> <a href="<?php echo $_SERVER["PHP_SELF"] ?>?offset=<?php echo intval($offset); ?>&count=50"> Show 50 </a> 
<li> <a href="<?php echo $_SERVER["PHP_SELF"] ?>?offset=<?php echo intval($offset); ?>&count=100"> Show 100 </a> 
</ul>
<?php

$db = new ossim_db();
$conn = $db->connect();
?>
<table width="100%">
<tr><th> Host </th><th> OS </th><th> Previous OS</th><th> When </th></tr>


<?php
if ($host_os_list = Host_os::get_list($conn, $where_clause, "")) {
    foreach($host_os_list as $host_os) {
?>
<tr>
<?php
        $ip = $host_os->get_ip();
        $os_time = $host_os->get_os_time();
        $os = $host_os->get_os();
        if(ereg("\|",$os)){
            $os = ereg_replace("\|", " or ", $os);
        }
        $previous = $host_os->get_previous();
        if(ereg("\|",$previous)){
            $previous = ereg_replace("\|", " or ", $previous);
        }
        $anom = $host_os->get_anom();


if($anom){
?>
<th><font color="red"><?php echo Host::ip2hostname($conn, $ip);?></font></th>
<?php
} else {
?>
<th><?php echo Host::ip2hostname($conn, $ip);?></th>
<?php
}
?>
<td><?php echo $os;?></td>
<td><?php echo $previous;?></td><td><?php echo $os_time?></td></tr>
<?php
    }
}
    $db->close($conn);
?>

</tr>
<tr>
<?php
if($offset == 0){
?>
<td colspan=4><a href="<?php echo $_SERVER["PHP_SELF"] ?>?offset=<?php echo intval($offset+$count); ?>&count=<?php echo $count;?>"> Next <?php echo $count ?> </a></td> 
<?php
} else {
?>
<td colspan=2><a href="<?php echo $_SERVER["PHP_SELF"] ?>?offset=<?php echo intval($offset-$count); ?>&count=<?php echo $count;?>"> Previous <?php echo $count ?> </a></td> 
<td colspan=2><a href="<?php echo $_SERVER["PHP_SELF"] ?>?offset=<?php echo intval($offset+$count); ?>&count=<?php echo $count;?>"> Next <?php echo $count ?> </a></td> 
<?php
}
?>
</tr>
</table>
</body>
</html>

