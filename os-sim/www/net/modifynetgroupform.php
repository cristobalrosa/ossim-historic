<?php
/*****************************************************************************
*
*    License:
*
*   Copyright (c) 2003-2006 ossim.net
*   Copyright (c) 2007-2009 AlienVault
*   All rights reserved.
*
*   This package is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; version 2 dated June, 1991.
*   You may not use, modify or distribute this program under any other version
*   of the GNU General Public License.
*
*   This package is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this package; if not, write to the Free Software
*   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,
*   MA  02110-1301  USA
*
*
* On Debian GNU/Linux systems, the complete text of the GNU General
* Public License can be found in `/usr/share/common-licenses/GPL-2'.
*
* Otherwise you can read it here: http://www.gnu.org/licenses/gpl-2.0.txt
****************************************************************************/
/**
* Class and Function List:
* Function list:
* Classes list:
*/
require_once ('classes/Session.inc');
Session::logcheck("MenuPolicy", "PolicyNetworks");
?>

<html>
<head>
  <title> <?php
echo gettext("OSSIM Framework"); ?> </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>
                                                                                
<?php
if (GET('withoutmenu') != "1") include ("../hmenu.php"); ?>

<?php
require_once 'classes/Net.inc';
require_once 'classes/Net_group.inc';
require_once 'classes/Net_group_scan.inc';
require_once 'ossim_db.inc';
require_once 'classes/Net_group_reference.inc';
require_once 'classes/RRD_config.inc';
require_once 'classes/Security.inc';
$name = GET('name');
ossim_valid($name, OSS_ALPHA, OSS_SPACE, OSS_PUNC, 'illegal:' . _("name"));
if (ossim_error()) {
    die(ossim_error());
}
$db = new ossim_db();
$conn = $db->connect();
if ($net_group_list = Net_group::get_list($conn, "WHERE name = '$name'")) {
    $net_group = $net_group_list[0];
}
?>

<form method="post" action="modifynetgroup.php">
<table align="center">
  <input type="hidden" name="insert" value="insert">
  <tr>
    <th> <?php echo gettext("Group Name"); ?> </th>
      <input type="hidden" name="name"
             value="<?php echo $net_group->get_name(); ?>">
      <td class="left">
        <b><?php echo $net_group->get_name(); ?></b>
      </td>
  </tr>

    <th> <?php echo gettext("Networks"); ?> <br/>
        <font size="-2">
          <a href="newnetform.php"> <?php echo gettext("Insert new network"); ?> ?</a>
        </font>
    </th> 
    <td class="left">
<?php
/* ===== Networks ==== */
$i = 1;
if ($network_list = Net::get_list($conn)) {
    foreach($network_list as $net) {
        $net_name = $net->get_name();
        $net_ips = $net->get_ips();
        if ($i == 1) {
?>
        <input type="hidden" name="<?php echo "nnets"; ?>" value="<?php echo count($network_list); ?>">
<?php
        }
        $name = "mboxs" . $i;
?>
        <input type="checkbox"
<?php
        if (Net_group_reference::in_net_group_reference($conn, $net_group->get_name() , $net_name)) {
            echo " CHECKED ";
        }
?>
            name="<?php echo $name; ?>"
            value="<?php echo $net_name; ?>">
            <?php echo $net_name . " (" . $net_ips . ")<br>"; ?>
        </input>
<?php
        $i++;
    }
}
?>
    </td>
  </tr>

  <tr>
    <th> <?php echo gettext("Threshold C"); ?> </th>
    <td class="left">
      <input type="text" name="threshold_c" size="4" value="<?php echo $net_group->get_threshold_c(); ?>"></td>
  </tr>
  <tr>
    <th> <?php echo gettext("Threshold A"); ?> </th>
    <td class="left">
      <input type="text" name="threshold_a" size="4" value="<?php echo $net_group->get_threshold_a(); ?>"></td>
  </tr>
  <tr>
    <th> <?php echo gettext("RRD Profile"); ?> <br/>
        <font size="-2">
          <a href="../rrd_conf/new_rrd_conf_form.php"> <?php echo gettext("Insert new profile"); ?> ?</a>
        </font>
    </th>
    <td class="left">
      <select name="rrd_profile">
<?php
foreach(RRD_Config::get_profile_list($conn) as $profile) {
    $net_profile = $net_group->get_rrd_profile();
    if (strcmp($profile, "global")) {
        $option = "<option value=\"$profile\"";
        if (0 == strcmp($net_profile, $profile)) $option.= " SELECTED ";
        $option.= ">$profile</option>\n";
        echo $option;
    }
}
?>
        <option value="" <?php if (!$net_profile) echo " SELECTED " ?>> <?php echo gettext("None"); ?> </option>
      </select>
    </td>
  </tr>
  <tr>

    <tr>
    <th> <?php echo gettext("Scan options"); ?> </th>
    <td class="left">
    <input type="checkbox" 
    <?php
$name = $net_group->get_name();
if (Net_group_scan::in_net_group_scan($conn, $name, 3001)) {
    echo " CHECKED ";
}
?>
    name="nessus" value="1"> Enable nessus scan </input>
</td>
</tr>

  <tr>
    <th> <?php echo gettext("Description"); ?> </th>
    <td class="left">
      <textarea name="descr" rows="2" cols="20"><?php echo $net_group->get_descr(); ?></textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="OK" class="btn" style="font-size:12px">
      <input type="reset" value="reset" class="btn" style="font-size:12px">
    </td>
  </tr>
</table>
</form>
<?php
$db->close($conn);
?>

</body>
</html>

