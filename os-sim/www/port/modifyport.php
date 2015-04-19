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
Session::logcheck("MenuPolicy", "PolicyPorts");
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
echo gettext("Modify Port group"); ?> </h1>

<?php
require_once 'classes/Security.inc';
$name = POST('name');
$descr = POST('descr');
ossim_valid($name, OSS_ALPHA, OSS_SPACE, OSS_PUNC, 'illegal:' . _("name"));
ossim_valid($descr, OSS_ALPHA, OSS_SPACE, OSS_PUNC, OSS_AT, OSS_NULLABLE, 'illegal:' . _("Description"));
if (ossim_error()) {
    die(ossim_error());
}
if (POST('insert')) {
    require_once 'ossim_db.inc';
    require_once 'classes/Port_group.inc';
    $db = new ossim_db();
    $conn = $db->connect();
    $port_list = array();
    foreach($_POST as $key => $value) {
        if (preg_match("/^proto_/", $key)) {
            $proto_list = POST($key); // all protocols
            foreach($proto_list as $proto) {
                ossim_valid($proto, OSS_ALPHA, OSS_SCORE, OSS_SPACE, 'illegal' . _("Port"));
                if (ossim_error()) {
                    die(ossim_error());
                }
                if (!empty($proto)) $port_list[] = $proto;
            }
        }
    }
    Port_group::update($conn, $name, $port_list, $descr);
    $db->close($conn);
}
?>
    <p> <?php
echo gettext("Port succesfully updated"); ?> </p>
    <script>document.location.href="port.php"</script>

</body>
</html>

