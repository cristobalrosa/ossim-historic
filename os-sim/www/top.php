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
require_once 'classes/Security.inc';
require_once 'classes/Session.inc';
require_once 'classes/Upgrade.inc';
require_once 'classes/WebIndicator.inc';
Session::logcheck("MainMenu", "Index", "session/login.php");
$upgrade = new Upgrade();
require_once ('ossim_conf.inc');
$conf = $GLOBALS["CONF"];
$ntop_link = $conf->get_conf("ntop_link", FALSE);
ossim_set_lang();
$uc_languages = array(
    "de_DE.UTF-8",
    "de_DE.UTF8",
    "de_DE",
    "en_GB",
    "es_ES",
    "fr_FR",
    "pt_BR"
);
$sensor_ntop = parse_url($ntop_link);
$ocs_link = $conf->get_conf("ocs_link", FALSE);
$glpi_link = $conf->get_conf("glpi_link", FALSE);
$ovcp_link = $conf->get_conf("ovcp_link", FALSE);
$nagios_link = $conf->get_conf("nagios_link", FALSE);
$sensor_nagios = parse_url($nagios_link);
if (!isset($sensor_nagios['host'])) {
    $sensor_nagios['host'] = $_SERVER['SERVER_NAME'];
}
$menu = array();
$hmenu = array();
if (Session::am_i_admin() && $upgrade->needs_upgrade()) {
    $menu["Upgrade"][] = array(
        "name" => gettext("System Upgrade Needed") ,
        "id" => "Upgrade",
        "url" => "upgrade/index.php"
    );
    $hmenu["Upgrade"][] = array(
        "name" => gettext("Software Upgrade") ,
        "id" => "Upgrade",
        "url" => "upgrade/"
    );
    $hmenu["Upgrade"][] = array(
        "name" => gettext("Update Notification") ,
        "id" => "Updates",
        "url" => "updates/index.php"
    );
}
$placeholder = gettext("Dashboard");
$placeholder = gettext("Events");
$placeholder = gettext("Monitors");
$placeholder = gettext("Incidents");
$placeholder = gettext("Reports");
$placeholder = gettext("Policy");
$placeholder = gettext("Correlation");
$placeholder = gettext("Configuration");
$placeholder = gettext("Tools");
$placeholder = gettext("Logout");
// Passthrough Vars
$status = "Open";
if (GET('status') != null) $status = GET('status');
/* Menu options */
include ("menu_options.php");
/* Generate reporting server url */
switch ($conf->get_conf("bi_type", FALSE)) {
    case "jasperserver":
    default:
        if ($conf->get_conf("bi_host", FALSE) == "localhost") {
            $bi_host = $_SERVER["SERVER_ADDR"];
        } else {
            $bi_host = $conf->get_conf("bi_host", FALSE);
        }
        if (!strstr($bi_host, "http")) {
            $reporting_link = "http://";
        }
        $bi_link = $conf->get_conf("bi_link", FALSE);
        $bi_link = str_replace("USER", $conf->get_conf("bi_user", FALSE) , $bi_link);
        $bi_link = str_replace("PASSWORD", $conf->get_conf("bi_pass", FALSE) , $bi_link);
        $reporting_link.= $bi_host;
        $reporting_link.= ":";
        $reporting_link.= $conf->get_conf("bi_port", FALSE);
        $reporting_link.= $bi_link;
}
$option = GET('option');
$soption = GET('soption');
$url = GET('url');
if (empty($option)) $option = 0;
if (!isset($soption)) {
    if (isset($_SESSION["_TopMenu_" . $option])) $soption = $_SESSION["_TopMenu_" . $option];
    else $soption = 0;
} else {
    $_SESSION["_TopMenu_" . $option] = $soption;
}
$keys = array_keys($menu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Ossim Menu</title>
<link rel="stylesheet" type="text/css" href="style/top.css">
<style>
html, body {height:100%;overflow-y:auto;overflow-x:hidden}
</style>
<script src="js/accordian.js" type="text/javascript" ></script>
<script>
var newwindow;
function new_wind(url,name)
{
	newwindow=window.open(url,name,'height=768,width=1024,scrollbars=yes');
	if (window.focus) {newwindow.focus()}
}
function fullwin(){
	window.open("index.php","main_window","fullscreen,scrollbars")
}
function init() {
		new Accordian('basic-accordian',5,'header_highlight');
}
</script>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onload="init()" bgcolor="#D2D2D2">

<table width="100%" height="100%" border=0 cellpadding=0 cellspacing=0 style="border:1px solid #AAAAAA">
<tr><td valign="top">

<table width="100%" border=0 cellpadding=0 cellspacing=0>
<!--<tr><td style="background:#678297 url(pixmaps/top/blueshadow.gif) top left" align="center" height="40" class="white"> :: Operation :: </td></tr> -->
<tr><td>

	<div id="basic-accordian" ><!--Parent of the Accordion-->
	
	<?php
$i = 0;
$moption = "";
foreach($menu as $name => $opc) if ($name != "Logout") {
    if (!isset($language)) $language = "";
    $open = ($option == $i) ? "header_highlight" : "";
    $txtopc = (in_array($language, $uc_languages)) ? htmlentities(strtoupper(html_entity_decode(gettext($name)))) : gettext($name);
?>
	
	<!--Start of each accordion item-->
	  <div id="test<?php
    if ($i > 0) echo $i ?>-header" class="accordion_headings <?php
    echo $open ?>">
		&nbsp;<img src="pixmaps/menu/<?php
    echo strtolower($name) ?>.gif" border=0 align="absmiddle"> &nbsp; <?php
    echo $txtopc ?>
	  </div>
	  
	  <!--Prefix of heading (the DIV above this) and content (the DIV below this) to be same... eg. foo-header & foo-content-->
	  
	  <div id="test<?php
    if ($i > 0) echo $i ?>-content"><!--DIV which show/hide on click of header-->

		<!--This DIV is for inline styling like padding...-->
		<div class="accordion_child">
			<table cellpadding=0 cellspacing=0 border=0 width="100%">
			<?php
    if (is_array($menu[$keys[$i]])) {
        foreach($menu[$keys[$i]] as $j => $op) {
            if ($option == $i && $soption == $j && $url == "") {
                $url = $op["url"];
                $moption = $op["id"];
            }
            $txtsopc = (in_array($language, $uc_languages)) ? htmlentities(strtoupper(html_entity_decode($op["name"]))) : $op["name"];
            $lnk = ($option == $i && $soption == $j) ? "on" : "";
            if ($op["id"] != "Help") {
?>
				<tr><td>
					<div class="opc<?php
                echo $lnk
?>" onclick="document.location.href='<?php
                echo $SCRIPT_NAME
?>?option=<?php
                echo $i
?>&soption=<?php
                echo $j
?>'">
						<table cellpadding=0 cellspacing=0 border=0 width="100%">
						<tr>
							<td class="cell right"><img src="pixmaps/menu/icon0.gif"></td>
							<td class="cell" nowrap><span class="lnk<?php
                echo $lnk
?>"><?php
                echo $txtsopc
?></span></td>
						</tr>
						</table>
					</div>
				</td></tr>
			<?php
            } else {
?>
				<tr><td>
					<table cellpadding=0 cellspacing=0 border=0 width="100%">
					<tr>
						<td class="opc right"><img src="pixmaps/menu/help.gif"></td>
						<td class="opc" nowrap><a href="<?php
                echo $op["url"] ?>" class="help">Help</a></td>
					</tr>
					</table>
				</td></tr>
			<?php
            }
        }
    }
?>
			</table>
		</div>
		
	  </div>
	  
	<?php
    $i++;
}
?>

	</div>

</td></tr>
<tr><td height="26" class="outmenu">
		<img src="pixmaps/menu/logout.png" border=0 align="absmiddle"> &nbsp; <a href="session/login.php?action=logout"><font color="black">Logout</font></a> [<font color="gray"><?php
echo $_SESSION["_user"] ?></font>]
 </td></tr>
 <tr><td height="26" class="outmenu">
		<img src="pixmaps/menu/maximizep.png" border=0 align="absmiddle"> &nbsp; <a href="#" onClick="fullwin()"><font color="black">Maximize</font></a>
</td></tr>
</table>

</td><!-- <td style="background:url('pixmaps/menu/dg_gray.gif') repeat-y top left;width:6"><img src="pixmaps/menu/dg_gray.gif"></td> -->
</tr>
</table>
<?php
if ($url != "") { ?>
<script> window.open('<?php echo $url . (preg_match("/\?/", $url) ? "&" : "?") . "hmenu=" . urlencode($moption) . "&smenu=" . urlencode($moption) ?>', 'main') </script>
<?php
}
$OssimWebIndicator->update_display();
?>
</body>
</html>



