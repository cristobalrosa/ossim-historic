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
* - check_writable_relative()
* Classes list:
*/
require_once 'classes/Session.inc';
Session::logcheck("MenuControlPanel", "BusinessProcesses");

$can_edit = false;

if (Session::menu_perms("MenuControlPanel", "BusinessProcessesEdit")) {
$can_edit = true;
}


function check_writable_relative($dir){
$uid = posix_getuid();
$gid = posix_getgid();
$user_info = posix_getpwuid($uid);
$user = $user_info['name'];
$group_info = posix_getgrgid($gid);
$group = $group_info['name'];
$fix_cmd = '. '._("To fix that, execute following commands as root").':<br><br>'.
                   "cd " . getcwd() . "<br>".
                   "mkdir -p $dir<br>".
                   "chown $user:$group $dir<br>".
                   "chmod 0700 $dir";
if (!is_dir($dir)) {
     die(_("Required directory " . getcwd() . "$dir does not exist").$fix_cmd);
}
$fix_cmd .= $fix_extra;

if (!$stat = stat($dir)) {
        die(_("Could not stat configs dir").$fix_cmd);
}
        // 2 -> file perms (must be 0700)
        // 4 -> uid (must be the apache uid)
        // 5 -> gid (must be the apache gid)
if ($stat[2] != 16832 || $stat[4] !== $uid || $stat[5] !== $gid)
        {
            die(_("Invalid perms for configs dir").$fix_cmd);
        }
}
check_writable_relative("./maps");
check_writable_relative("./pixmaps/uploaded");

?>
<html>
<head>
<title><?= _("Alarms") ?> - <?= _("View")?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="./custom_style.css">
<style type="text/css">
	.itcanbemoved { position:absolute; }
</style>
</head>
<? 
require_once 'classes/Security.inc';
require_once 'ossim_db.inc';

$db = new ossim_db();
$conn = $db->connect();

$map = ($_GET["map"]!="") ? $_GET["map"] : 1;
$_SESSION["riskmap"] = $map;
//$hide_others = ($_GET["hide_others"]!="") ? $_GET["hide_others"] : 0;
$hide_others=1;

ossim_valid($map, OSS_DIGIT, 'illegal:'._("type"));

if (ossim_error()) {
die(ossim_error());
}



?>
<script>
	template_begin = '<table border=0 cellspacing=0 cellpadding=1><tr><td colspan=2 class=ne1 align=center><i>NAME</i></td></tr><tr><td><a href="URL"><img src="ICON" border=0></a></td><td>'
	template_end = '</td></tr></table>'
	txtbbb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtbbr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtbba = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtbbv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtbrb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtbrr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtbra = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtbrv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtbab = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtbar = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtbaa = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtbav = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtbvb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtbvr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtbva = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtbvv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'

	txtrbb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtrbr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtrba = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtrbv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtrrb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtrrr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtrra = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtrrv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtrab = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtrar = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtraa = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtrav = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtrvb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtrvr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtrva = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtrvv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'

	txtabb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtabr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtaba = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtabv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtarb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtarr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtara = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtarv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtaab = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtaar = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtaaa = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtaav = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtavb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtavr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtava = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtavv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'

	txtvbb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtvbr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtvba = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtvbv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtvrb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtvrr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtvra = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtvrv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtvab = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtvar = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtvaa = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtvav = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'
	txtvvb = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/b.gif" border=0></td></tr></table>'
	txtvvr = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/r.gif" border=0></td></tr></table>'
	txtvva = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/a.gif" border=0></td></tr></table>'
	txtvvv = '<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</<td><td class=ne11>V</<td><td class=ne11>A</<td></tr><tr><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td><td><img src="images/v.gif" border=0></td></tr></table>'

	function responderAjax(url) {
		var ajaxObject = document.createElement('script');
		ajaxObject.src = url;
		ajaxObject.type = "text/javascript";
		ajaxObject.charset = "utf-8";
		document.getElementsByTagName('head').item(0).appendChild(ajaxObject);
	}
	function urlencode(str) { return escape(str).replace('+','%2B').replace('%20','+').replace('*','%2A').replace('/','%2F').replace('@','%40'); }

	function changeDiv (id,name,url,icon,valor) {
	        var content = template_begin.replace('NAME',name).replace('URL',url).replace('ICON',icon) + valor + template_end
		document.getElementById('alarma'+id).innerHTML = content;
	}

	function initDiv () {
		var x = 0;
		var y = 0;
		var el = document.getElementById('map_img');
		var obj = el;
		do {
			x += obj.offsetLeft;
			y += obj.offsetTop;
			obj = obj.offsetParent;
		} while (obj);	
		var objs = document.getElementsByTagName("div");
		var txt = ''
		for (var i=0; i < objs.length; i++) {
			if (objs[i].className == "itcanbemoved") {
				xx = parseInt(objs[i].style.left.replace('px',''));
				objs[i].style.left = xx + x
				yy = parseInt(objs[i].style.top.replace('px',''));
				objs[i].style.top = yy + y;
				objs[i].style.visibility = "visible"
			}
		}
		refresh_indicators()
	}
	
	function refresh_indicators() {
		responderAjax("refresh.php?map=<? echo $map ?>")
	}
		refresh_indicators();
		setInterval(refresh_indicators,5000);
	
</script>
<body leftmargin=5 topmargin=5 class=ne1 onload="initDiv()">
<table border=0 cellpadding=0 cellspacing=0><tr>
<td valign=top id="map">
	<img id="map_img" src="maps/map<? echo $map ?>.jpg" border=0>
</td>
<td valign=top class=ne1 style="padding-left:5px">
<?php 
if(!$hide_others){
?>

 <h2><?= _("Maps") ?></h2>
 <?php
 if($can_edit){
   print "&nbsp;(<a href='riskmaps.php?hmenu=Risk+Maps&smenu=Edit+Risk+Maps' target='_parent'><b>" . _("Edit") . "</b></a>)";
   //print "&nbsp;(<a href='index.php?map=$map'><b>" . _("Edit") . "</b></a>)";
 }
 ?>
 <br>
 <?
	$maps = explode("\n",`ls -1 'maps' | grep -v CVS`);
	$i=0; $n=0; $txtmaps = ""; $linkmaps = "";
	foreach ($maps as $ico) if (trim($ico)!="") {
	        if(!getimagesize("maps/" . $ico)){ continue;}
		$n = str_replace("map","",str_replace(".jpg","",$ico));
		$txtmaps .= "<td><a href='$SCRIPT_NAME?map=$n'><img src='maps/$ico' border=".(($map==$n) ? "2" : "0")." width=100 height=100></a></td>";
		$i++; if ($i % 4 == 0) {
			$txtmaps .= "</tr><tr>";
		}
	}
 ?> 
 <table><tr><? echo $txtmaps ?></tr></table>	
 <br>
<?
} // if(!$hide_others)
	$query = "select * from risk_indicators where name <> 'rect' AND map= ?";
	$params = array($map);
        if (!$rs = &$conn->Execute($query, $params)) {
            print $conn->ErrorMsg();
        } else {
                while (!$rs->EOF){
		echo "<div id=\"alarma".$rs->fields["id"]."\" class=\"itcanbemoved\" style=\"left:".$rs->fields["x"]."px;top:".$rs->fields["y"]."px;height:".$rs->fields["h"]."px;width:".$rs->fields["w"]."px\">";
		if ($rs->fields["url"]=="") $rs->fields["url"]="javascript:;";
		echo "<table border=0 cellspacing=0 cellpadding=1><tr><td colspan=2 class=ne align=center><i>".$rs->fields["name"]."</i></td></tr><tr><td><a href=\"".$rs->fields["url"]."\"><img src=\"".$rs->fields["icon"]."\" border=0></a></td><td>";
		echo "<table border=0 cellspacing=0 cellpadding=1><tr><td class=ne11>R</td><td class=ne11>V</td><td class=ne11>A</td></tr><tr><td><img src='images/b.gif' border=0></td><td><img src='images/b.gif' border=0></td><td><img src='images/b.gif' border=0></td></tr></table>";
		echo "</td></tr></table></div>\n";
                $rs->MoveNext();
	}
	}

	$query = "select * from risk_indicators where name='rect' AND map= ?";
	$params = array($map);
        if (!$rs = &$conn->Execute($query, $params)) {
            print $conn->ErrorMsg();        
	} else {
           while (!$rs->EOF){
		echo "<div id=\"rect".$rs->fields["id"]."\" class=\"itcanbemoved\" style=\"left:".$rs->fields["x"]."px;top:".$rs->fields["y"]."px;height:".$rs->fields["h"]."px;width:".$rs->fields["w"]."px\">";
		if ($rs->fields["url"]=="") $rs->fields["url"]="javascript:;";
		echo "<a href=\"".$rs->fields["url"]."\" target=\"_blank\" style=\"text-decoration:none\"><table border=0 cellspacing=0 cellpadding=0 width=\"100%\" height=\"100%\"><tr><td style=\"border:1px dotted black\">&nbsp;</td></tr></table></a>";
		echo "</div>\n";
                $rs->MoveNext();
	    }
        }

//} // if(!$hide_others)
	
	$conn->close();
?>
</td>
</tr>
</table>
</body>
</html>
