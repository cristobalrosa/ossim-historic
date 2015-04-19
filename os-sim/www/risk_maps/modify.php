<?
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
* - check_writable_relative()
* Classes list:
*/
require_once 'classes/Session.inc';
Session::logcheck("MenuControlPanel", "BusinessProcesses");

if (!Session::menu_perms("MenuControlPanel", "BusinessProcessesEdit")) {
print _("You don't have permissions to edit risk indicators");
exit();

}

require_once 'ossim_db.inc';
require_once 'classes/Security.inc';
$db = new ossim_db();
$conn = $db->connect();

$map = $_GET["map"];
$data = $_GET["data"];
$name = $_GET["name"];
$url = $_GET["url"];
$id = $_GET["id"];

ossim_valid($map, OSS_DIGIT,'illegal:'._("map"));
ossim_valid($data, OSS_SCORE, OSS_NULLABLE, OSS_ALPHA, OSS_DIGIT, ";,.", 'illegal:'._("data"));

if (ossim_error()) {
die(ossim_error());
}


	$indicators = array();
	$delete_list = array();
	$elems = explode(";",$data);
	foreach ($elems as $elem) if (trim($elem)!="") {
		$param = explode(",",$elem);
		$id = str_replace("rect","",str_replace("alarma","",$param[0]));
		$indicators[$id]["x"] = str_replace("px","",$param[1]);
		$indicators[$id]["y"] = str_replace("px","",$param[2]);
		$indicators[$id]["w"] = str_replace("px","",$param[3]);
		$indicators[$id]["h"] = str_replace("px","",$param[4]);
	}


	$active = array_keys($indicators);
	$query = "select id from risk_indicators where map=?";
	$params = array($map);
        if (!$rs = &$conn->Execute($query, $params)) {
            $log = $conn->ErrorMsg();
        } else {
                while (!$rs->EOF){
		if (in_array($rs->fields["id"],$active)) {
			$pos = $indicators[$rs->fields["id"]];
			$query = "update risk_indicators set x= ?,y= ?, w= ?, h= ? where id= ?";
			$params = array($pos["x"],$pos["y"],$pos["w"],$pos["h"],$rs->fields["id"]);
			$conn->Execute($query, $params);
		} else {
			$delete_list[] = $rs->fields["id"];
		}
		$rs->MoveNext();
	}
	}
	foreach ($delete_list as $idb)
	{
	$query = "delete bp_asset_member.* from bp_asset_member, risk_indicators where risk_indicators.type_name = bp_asset_member.member and risk_indicators.type = bp_asset_member.member_type and risk_indicators.id = ?";
	$params = array($idb);
	$conn->Execute($query, $params);
	$query = "delete from risk_indicators where id= ?";
	$conn->Execute($query, $params);
	}
	$conn->close();
?>
