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
Session::logcheck("MenuPolicy", "PolicyPolicy");
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

  <h1> <?php
echo gettext("Delete policy"); ?> </h1>

<?php
require_once 'classes/Security.inc';
$id = GET('id');
ossim_valid($id, OSS_ALPHA . OSS_PUNC, 'illegal:' . _("Policy id"));
if (ossim_error()) {
    die(ossim_error());
}
if (!GET('confirm')) {
?>
    <p> <?php
    echo gettext("Are you sure"); ?> ?</p>
    <p><a href="<?php
    echo $_SERVER["PHP_SELF"] . "?id=$id&confirm=yes"; ?>">
    <?php
    echo gettext("Yes"); ?> </a>&nbsp;&nbsp;&nbsp;<a href="policy.php">
    <?php
    echo gettext("No"); ?> </a>
    </p>
<?php
    if (GET('activate') == "change") {
        require_once 'ossim_db.inc';
        require_once 'classes/Policy.inc';
        $db = new ossim_db();
        $conn = $db->connect();
        $ids = explode(",", $id);
        foreach($ids as $id) if ($id != "") Policy::activate($conn, $id);
        $db->close($conn);
    }
    exit();
}
require_once 'ossim_db.inc';
require_once 'classes/Policy.inc';
require_once 'classes/Response.inc';
$db = new ossim_db();
$conn = $db->connect();
$ids = explode(",", $id);
foreach($ids as $id) if ($id != "") {
    $list = Policy::get_list($conn, "WHERE policy.order=$id");
    if ($list[0]) {
        $pid = $list[0]->get_id();
        Policy::delete($conn, $pid);
        // Response/Actions
        $response_list = Response::get_list($conn, "WHERE descr='policy $pid'");
        if ($response_list[0]) Response::delete($conn, $response_list[0]->get_id());
    }
}
$db->close($conn);
?>

    <p> <?php
echo gettext("Policy deleted"); ?> </p>
    <script>document.location.href="policy.php"</script>
<?php
// update indicators on top frame
$OssimWebIndicator->update_display();
?>

</body>
</html>

