<html>
<head>
  <title>OSSIM Framework</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>
                                                                                
  <h1>OSSIM Framework</h1>
  <h2>Insert new policy</h2>

<?php
    
    require_once ('classes/Host.inc');
    require_once ('classes/Net.inc');
    require_once ('classes/Port_group.inc');
    require_once ('classes/Signature_group.inc');
    require_once ('classes/Sensor.inc');
    require_once ('ossim_db.inc');
    $db = new ossim_db();
    $conn = $db->connect();
?>

</p>

<form method="post" action="newpolicy.php">
<table align="center">
  <input type="hidden" name="insert" value="insert">
  <tr>
    <th>Source<br/>
        <font size="-2">
          <a href="../host/newhostform.php">Insert new host?</a>
        </font><br/>
        <font size="-2">
          <a href="../net/newnetform.php">Insert new net?</a>
        </font><br/>
    </th>
    <td class="left">
<?php

    /* ===== source nets =====*/
    $j = 1;
    if ($net_list = Net::get_list($conn, "ORDER BY name")) {
        foreach ($net_list as $net) {
            $net_name = $net->get_name();
            if ($j == 1) {
?>
        <input type="hidden" name="<?php echo "sourcengrps"; ?>"
            value="<?php echo count($net_list); ?>">
<?php
            } $name = "sourcemboxg" . $j;
?>
        <input type="checkbox" name="<?php echo $name;?>"
            value="<?php echo $net_name; ?>">
            <?php echo $net_name ?><br>
        </input>
<?php
            $j++;
        }
    }
?>


<?php

    /* ===== source hosts ===== */
    $i = 1;
    if ($host_list = Host::get_list($conn, "", "ORDER BY inet_aton(ip)")) {
        foreach ($host_list as $host) {
            $ip       = $host->get_ip();
            $hostname = $host->get_hostname();
            if ($i == 1) {
?>
        <input type="hidden" name="<?php echo "sourcenips"; ?>"
            value="<?php echo count($host_list) + 1; ?>">
<?php
            }
            $name = "sourcemboxi" . $i;
?>
        <input type="checkbox" name="<?php echo $name; ?>"
            value="<?php echo $ip ?>">
            <?php echo $ip . ' (' .$hostname.")<br>"; ?>
        </input>
<?php
            $i++;
        }
    }
    $name = "sourcemboxi".$i;
?>
    <input type="checkbox" name="<?php echo $name; ?>"
           value="any">&nbsp;<b>ANY</b><br></input>



    </td>
  </tr>
  <tr>
    <th>Dest<br/>
        <font size="-2">
          <a href="../host/newhostform.php">Insert new host?</a>
        </font><br/>
        <font size="-2">
          <a href="../net/newnetform.php">Insert new net?</a>
        </font><br/>
    </th>
    <td class="left">
<?php

    /* ===== dest nets =====*/
    $j = 1;
    if ($net_list = Net::get_list($conn, "ORDER BY name")) {
        foreach ($net_list as $net) {
            $net_name = $net->get_name();
            if ($j == 1) {
?>
        <input type="hidden" name="<?php echo "destngrps"; ?>"
            value="<?php echo count($net_list); ?>">
<?php
            } $name = "destmboxg" . $j;
?>
        <input type="checkbox" name="<?php echo $name;?>"
            value="<?php echo $net_name; ?>">
            <?php echo $net_name ?><br>
        </input>
<?php
            $j++;
        }
    }
?>


<?php

    /* ===== source hosts ===== */
    $i = 1;
    if ($host_list =  Host::get_list($conn, "", "ORDER BY inet_aton(ip)")) {
        foreach ($host_list as $host) {
            $ip       = $host->get_ip();
            $hostname = $host->get_hostname();
            if ($i == 1) {
?>
        <input type="hidden" name="<?php echo "destnips"; ?>"
            value="<?php echo count($host_list) + 1; ?>">
<?php
            }
            $name = "destmboxi" . $i;
?>
        <input type="checkbox" name="<?php echo $name; ?>"
            value="<?php echo $ip ?>">
            <?php echo $ip . ' (' .$hostname.")<br>"; ?>
        </input>
<?php
            $i++;
        }
    }
    $name = "destmboxi".$i;
?>
    <input type="checkbox" name="<?php echo $name; ?>"
           value="any">&nbsp;<b>ANY</b><br></input>


    </td>
  </tr>

  <tr>
    <th>Ports<br/>
        <font size="-2">
          <a href="../port/newportform.php">Insert new port group?</a>
        </font><br/>
    </th>
    <td class="left">
<?php

    /* ===== ports ==== */
    $i = 1;
    if ($port_group_list = Port_group::get_list($conn, "ORDER BY name")) {
        foreach($port_group_list as $port_group) {
            $port_group_name = $port_group->get_name();
            if ($i == 1) {
?>
        <input type="hidden" name="<?php echo "nprts"; ?>"
            value="<?php echo count($port_group_list); ?>">
<?php
            }
            $name = "mboxp" . $i;
?>
        <input type="checkbox" name="<?php echo $name;?>"
            value="<?php echo $port_group_name; ?>">
            <?php echo $port_group_name . "<br>";?>
        </input>
<?php
            $i++;
        }
    }
?>
    </td>
  </tr>

  <tr>
    <th>Priority</th>
    <td class="left">
      <select name="priority">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
      </select>
    </td>
  </tr>

  <tr>
    <th>Signatures<br/>
        <font size="-2">
          <a href="../signature/newsignatureform.php">Insert new signature
          group?</a>
        </font><br/>
    </th>
    <td class="left">
<?php

    /* ===== signatures ==== */
    $i = 1;
    if ($sig_group_list = Signature_group::get_list($conn, "ORDER BY name")) {
        foreach($sig_group_list as $sig_group) {
            $sig_group_name = $sig_group->get_name();
            if ($i == 1) {
?>
        <input type="hidden" name="<?php echo "nsigs"; ?>"
            value="<?php echo count($sig_group_list); ?>">
<?php
            }
            $name = "mboxsg" . $i;
?>
        <input type="checkbox" name="<?php echo $name;?>"
            value="<?php echo $sig_group_name; ?>">
            <?php echo $sig_group_name . "<br>";?>
        </input>
<?php
            $i++;
        }
    }
?>
    </td>
  </tr>

  <tr>
    <th>Sensors<br/>
        <font size="-2">
          <a href="../sensor/newsensorform.php">Insert new sensor?</a>
        </font><br/>
    </th>
    <td class="left">
<?php

    /* ===== sensors ==== */
    $i = 1;
    if ($sensor_list = Sensor::get_list($conn, "ORDER BY inet_aton(ip)")) {
        foreach($sensor_list as $sensor) {
            $sensor_name = $sensor->get_name();
            $sensor_ip =   $sensor->get_ip();
            if ($i == 1) {
?>
        <input type="hidden" name="<?php echo "nsens"; ?>"
            value="<?php echo count($sensor_list); ?>">
<?php
            }
            $name = "mboxs" . $i;
?>
        <input type="checkbox" name="<?php echo $name;?>"
            value="<?php echo $sensor_name; ?>">
            <?php echo $sensor_ip . " (" . $sensor_name . ")<br>";?>
        </input>
<?php
            $i++;
        }
    }
?>
    </td>
  </tr>

  <tr>
    <th>Time Range
    </th>
    <td>
      <table>
        <tr>
          <td>Begin</td><td></td><td>End</td>
        </tr>
        <tr>
          <td>
            <select name="begin_day">
              <option selected value="1">Mon</option>
              <option value="2">Tue</option>
              <option value="3">Wed</option>
              <option value="4">Thu</option>
              <option value="5">Fri</option>
              <option value="6">Sat</option>
              <option value="7">Sun</option>
            </select>
            <select name="begin_hour">
              <option selected value="0">0h</option>
              <option value="1">1h</option>
              <option value="2">2h</option>
              <option value="3">3h</option>
              <option value="4">4h</option>
              <option value="5">5h</option>
              <option value="6">6h</option>
              <option value="7">7h</option>
              <option value="8">8h</option>
              <option value="9">9h</option>
              <option value="10">10h</option>
              <option value="11">11h</option>
              <option value="12">12h</option>
              <option value="13">13h</option>
              <option value="14">14h</option>
              <option value="15">15h</option>
              <option value="16">16h</option>
              <option value="17">17h</option>
              <option value="18">18h</option>
              <option value="19">19h</option>
              <option value="20">20h</option>
              <option value="21">21h</option>
              <option value="22">22h</option>
              <option value="23">23h</option>
            </select>
          </td>
          <td>-</td>
          <td>
            <select name="end_day">
              <option value="1">Mon</option>
              <option value="2">Tue</option>
              <option value="3">Wed</option>
              <option value="4">Thu</option>
              <option value="5">Fri</option>
              <option value="6">Sat</option>
              <option selected value="7">Sun</option>
            </select>
            <select name="end_hour">
              <option value="0">0h</option>
              <option value="1">1h</option>
              <option value="2">2h</option>
              <option value="3">3h</option>
              <option value="4">4h</option>
              <option value="5">5h</option>
              <option value="6">6h</option>
              <option value="7">7h</option>
              <option value="8">8h</option>
              <option value="9">9h</option>
              <option value="10">10h</option>
              <option value="11">11h</option>
              <option value="12">12h</option>
              <option value="13">13h</option>
              <option value="14">14h</option>
              <option value="15">15h</option>
              <option value="16">16h</option>
              <option value="17">17h</option>
              <option value="18">18h</option>
              <option value="19">19h</option>
              <option value="20">20h</option>
              <option value="21">21h</option>
              <option value="22">22h</option>
              <option selected value="23">23h</option>
            </select>
          </td>
        </tr>
      </table>
    </td>
  </tr>


  <tr>
    <th>Description</th>
    <td class="left">
        <textarea name="descr" rows="2" cols="20"></textarea>
    </td>
  </tr>

<?php
    $db->close($conn);
?>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="OK">
      <input type="reset" value="reset">
    </td>
  </tr>
</table>
</form>

</body>
</html>

