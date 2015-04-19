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
$group = $_GET['group'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title> Control Panel </title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" href="../style/style.css"/>
</head>

<body>
<table cellpadding=0 cellspacing=0 border=0 class="noborder">
	<tr><td class="nobborder">Warning, you're going to remove Alarms. That will affect to other parts
	like visualization. May be that your want to <a href="alarm_group_console.php?action=close_group&group=<?php echo $group
?>" target="_parent"><b>CLOSE ALARMS</b></a>, and not to remove
	it. Are you sure that you want to remove them?</td></tr>
	<tr><td class="nobborder" style="padding-top:30px;text-align:center">DELETE GROUPS: <i><?php echo $group
?></i> <b>?</b> <input type="button" value="YES" class="btn" onclick="parent.location.href='alarm_group_console.php?action=delete_group&group=<?php echo $group
?>'"></td></tr>
</table>
</body>
