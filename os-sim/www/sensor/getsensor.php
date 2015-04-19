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
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/xml");
require_once ('classes/Session.inc');
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
Session::logcheck("MenuPolicy", "PolicySensors");
require_once 'ossim_conf.inc';
require_once 'ossim_db.inc';
require_once 'classes/Sensor.inc';
require_once 'classes/Plugin.inc';
require_once 'classes/Security.inc';
require_once 'get_sensor_plugins.php';
require_once 'get_sensors.php';
require_once 'classes/WebIndicator.inc';
$page = POST('page');
if (empty($page)) $page = 1;
$rp = POST('rp');
if (empty($rp)) $rp = 25;
$order = GET('sortname');
if (empty($order)) $order = POST('sortname');
if (!empty($order)) $order.= (POST('sortorder') == "asc") ? "" : " desc";
ossim_valid($order, OSS_ALPHA, OSS_SPACE, OSS_SCORE, OSS_NULLABLE, 'illegal:' . _("order"));
if (ossim_error()) {
    die(ossim_error());
}
if (empty($order)) $order = "name";
$start = (($page - 1) * $rp);
$limit = "LIMIT $start, $rp";
$db = new ossim_db();
$conn = $db->connect();
list($sensor_list, $err) = server_get_sensors($conn);
$sensor_stack = array();
$sensor_stack_off = array();
$sensor_configured_stack = array();
if ($sensor_list) {
    foreach($sensor_list as $sensor_status) {
        if (in_array($sensor_status["sensor"], $sensor_stack)) continue;
        if ($sensor_status["state"] = "on") array_push($sensor_stack, $sensor_status["sensor"]);
        else array_push($sensor_stack_off, $sensor_status["sensor"]);
    }
}
$active_sensors = 0;
$total_sensors = 0;
// Munin
$ossim_conf = $GLOBALS["CONF"];
$use_munin = $ossim_conf->get_conf("use_munin");
if ($use_munin == 1) $munin_link = $ossim_conf->get_conf("munin_link");
$xml = "";
$sensor_list = Sensor::get_list($conn, "ORDER BY $order $limit");
if ($sensor_list[0]) {
    $total = $sensor_list[0]->get_foundrows();
    if ($total == 0) $total = count($sensor_list);
} else $total = 0;
$xml.= "<rows>\n";
$xml.= "<page>$page</page>\n";
$xml.= "<total>$total</total>\n";
foreach($sensor_list as $sensor) {
    $name = $sensor->get_name();
    $xml.= "<row id='$name'>";
    $ip = $sensor->get_ip();
    $ip = "<a href=\"sensor_plugins.php?sensor=$ip\">$ip</a>";
    $xml.= "<cell><![CDATA[" . $ip . "]]></cell>";
    $total_sensors++;
    $xml.= "<cell><![CDATA[" . $name . "]]></cell>";
    $xml.= "<cell><![CDATA[" . $sensor->get_priority() . "]]></cell>";
    $xml.= "<cell><![CDATA[" . $sensor->get_port() . "]]></cell>";
    $xml.= "<cell><![CDATA[" . $sensor->get_version() . "]]></cell>";
    if (in_array($sensor->get_ip() , $sensor_stack)) {
        $xml.= "<cell><![CDATA[<img src='../pixmaps/tables/tick.png'>]]></cell>";
        $active_sensors++;
        array_push($sensor_configured_stack, $sensor->get_ip());
    } elseif (in_array($sensor->get_ip() , $sensor_stack_off)) {
        $xml.= "<cell><![CDATA[<img src='../pixmaps/tables/warning.png' title='the following sensor(s) are being reported as enabled by the server but are not configured' alt='the following sensor(s) are being reported as enabled by the server but are not configured'>]]></cell>";
    } else {
        $xml.= "<cell><![CDATA[<img src='../pixmaps/tables/cross.png'>]]></cell>";
    }
    /*if ($use_munin==1)
    $xml .= "<cell><![CDATA[<a href=\"$munin_link\" target=\"_blank\"><img src='../pixmaps/chart_bar.png' border=0></a>]]></cell>";
    else
    $xml .= "<cell><![CDATA[<img src='../pixmaps/chart_bar_off.png'>]]></cell>"; */
    $desc = $sensor->get_descr();
    if ($desc == "") $desc = "&nbsp;";
    $xml.= "<cell><![CDATA[" . utf8_encode($desc) . "]]></cell>";
    $xml.= "</row>\n";
}
$xml.= "</rows>\n";
echo $xml;
$db->close($conn);
?>


