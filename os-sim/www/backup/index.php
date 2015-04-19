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
require_once 'classes/Session.inc';
require_once 'classes/Security.inc';
Session::logcheck("MenuTools", "ToolsBackup");
require_once 'classes/Util.inc';
require_once 'ossim_db.inc';
require_once ('classes/Backup.inc');
$conf = $GLOBALS["CONF"];
$data_dir = $conf->get_conf("data_dir");
$backup_dir = $conf->get_conf("backup_dir");
//$backup_dir = "/root/pruebas_backup";
$isDisabled = Backup::running_restoredb();
$perform = POST("perform");
if (!$isDisabled) {
    if ($perform == "insert") {
        $insert = POST("insert");
        Backup::Insert($insert);
    } elseif ($perform == "delete") {
        $delete = POST("delete");
        Backup::Delete($delete);
    }
}
$db = new ossim_db();
$conn = $db->snort_connect();
$insert = Array();
$delete = Array();
if (!is_dir($backup_dir)) {
    die(ossim_error(_("Could not access backup dir") . ": <b>$backup_dir</b>"));
}
$dir = dir($backup_dir);
$query = OssimQuery("SELECT DISTINCT DATE_FORMAT(timestamp, '%Y%m%d') as day FROM acid_event ORDER BY timestamp DESC");
if (!$rs = $conn->Execute($query)) {
    print 'error: ' . $conn->ErrorMsg() . '<BR>';
    exit;
}
// Delete
while (!$rs->EOF) {
    $delete[] = $rs->fields["day"];
    $rs->MoveNext();
}
// Insert
while ($file = $dir->read()) {
    if (preg_match("/^insert\-(.+)\.sql\.gz/", $file, $found)) {
        if (!in_array($found[1], $delete)) $insert[] = $found[1];
    }
}
rsort($insert);
$dir->close();
$db->close($conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Backup</title>
 		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  		<meta http-equiv="Pragma" content="no-cache">
  		<link rel="stylesheet" type="text/css" href="../style/style.css"/>
  		<script language="javascript">
  			function boton (form, act) {
  				form.perform.value = act;
  				form.submit();
  			}
  		</script>
  	</head>
  	<body>
		<?php
include ("../hmenu.php"); ?>
  		<center>
  		<form name="backup" action="<?php
echo $_SERVER["PHP_SELF"] ?>" method="post">
  	  	<table>
  			<tr>
  				<th colspan="3"><?php
echo gettext("Backup Manager"); ?></th>
			</tr>
  			<tr>
  				<th><?php
echo gettext("Dates to Restore"); ?></th>
  				<th></th>
  				<th><?php
echo gettext("Dates in Database"); ?></th>
  			</tr>
  			<tr>
  				<td>
		<select name="insert[]" size="10" multiple>
<?php
if (is_array($insert)) {
    foreach($insert as $insert_item) {
?>
       <option value="<?php echo $insert_item
?>">&nbsp;&nbsp;<?php echo preg_replace("/(\d\d\d\d)(\d\d)(\d\d)/", "\\3-\\2-\\1", $insert_item) ?>&nbsp;&nbsp;</option>
<?php
    }
} else { ?>
	<option size="100" disabled>&nbsp;&nbsp;--&nbsp;<?php echo _("NONE") ?>&nbsp;--&nbsp;&nbsp;</option>
<?php
} ?>
	   </select>
  				</td>
				<td></td>
				<td>
		<select name="delete[]" size="10" multiple>
<?php
if (is_array($delete)) {
    foreach($delete as $delete_item) {
?>
		<option size="100" value="<?php echo $delete_item
?>">&nbsp;&nbsp;<?php echo preg_replace("/(\d\d\d\d)(\d\d)(\d\d)/", "\\3-\\2-\\1", $delete_item) ?>&nbsp;&nbsp;</option>
<?php
    }
} else { ?>
		<option size="100" disabled>&nbsp;&nbsp;--&nbsp;<?php echo _("NONE") ?>&nbsp;--&nbsp;&nbsp;</option>
<?php
} ?>
	   </select>
				</td>
  			</tr>
  			<tr>
  				<td>
  					<button name="insertB" value="insertDo" type="submit" onclick="boton(this.form, 'insert')" <?php echo ($isDisabled) ? "disabled" : "" ?> ><?php
echo gettext("Insert"); ?></button>
  				</td>
  				<td></td>
  				<td>
  					<button name="deleteB" value="deleteDo" type="submit" onclick="boton(this.form, 'delete')"  <?php echo ($isDisabled) ? "disabled" : "" ?> ><?php
echo gettext("Delete"); ?></button>
  				</td>
  			</tr>
  		</table>
  		<input type="hidden" name="perform" value="">
  		</form>
  		<br>
		<table aling="center">
			<tr>
				<th colspan="5"><?php
echo gettext("Backup Events"); ?></th>
			</tr>
			<tr>
				<th><?php
echo gettext("User"); ?></th>
				<th><?php
echo gettext("Date"); ?></th>
				<th><?php
echo gettext("Action"); ?></th>
				<th><?php
echo gettext("Status"); ?></th>
				<th><?php
echo gettext("Percent"); ?></th>
			</tr>
<?php
$db1 = new ossim_db();
$conn1 = $db1->connect();
$query = OssimQuery("SELECT * FROM restoredb_log ORDER BY id DESC LIMIT 10");
if (!$rs1 = $conn1->Execute($query)) {
    print 'error: ' . $conn1->ErrorMsg() . '<BR>';
    exit;
}
while (!$rs1->EOF) {
?>
			<tr>
				<td><?php echo $rs1->fields["users"] ?></td>
				<td><?php echo Util::timestamp2date($rs1->fields["date"]) ?></td>
				<td><?php echo $rs1->fields["data"] ?></td>
	<?php
    if ($rs1->fields["status"] == 1) { ?>
				<td><font color="orange"><b><?php
        echo gettext("Running"); ?></b></font></td>
	<?php
    } else { ?>
				<td><font color="green"><b><?php
        echo gettext("Done"); ?></b></font></td>
	<?php
    } ?>
				<td><?php echo $rs1->fields["percent"] ?></td>
			</tr>
<?php
    $rs1->MoveNext();
}
$db1->close($conn1);
?>
		</table>
		</center>
  	</body>
</html>
