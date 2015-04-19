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
* - message_ok()
* - email_form()
* - exec_form()
* - submit()
* Classes list:
*/
require_once ('classes/Session.inc');
Session::logcheck("MenuPolicy", "PolicyActions");
?>
<html>
<head>
  <title>OSSIM Framework</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>

<?php
if (GET('withoutmenu') != "1") include ("../hmenu.php"); ?>

<?php
require_once 'ossim_db.inc';
require_once 'classes/Action.inc';
require_once 'classes/Action_type.inc';
require_once 'classes/Security.inc';
$action_id = REQUEST('id');
$action_type = REQUEST('action_type');
$descr = REQUEST('descr');
$email_from = REQUEST('email_from');
$email_to = REQUEST('email_to');
$email_subject = REQUEST('email_subject');
$email_message = REQUEST('email_message');
$exec_command = REQUEST('exec_command');
ossim_valid($action_id, OSS_DIGIT, 'illegal:' . _("Action id"));
ossim_valid($action_type, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("Action type"));
ossim_valid($descr, OSS_ALPHA, OSS_PUNC, OSS_SCORE, OSS_AT, OSS_NULLABLE, 'illegal:' . _("Description"));
ossim_valid($email_from, OSS_MAIL_ADDR, OSS_NULLABLE, 'illegal:' . _("Email from"));
ossim_valid($email_to, OSS_MAIL_ADDR, OSS_NULLABLE, 'illegal:' . _("Email to"));
ossim_valid($email_subject, OSS_ALPHA, OSS_PUNC, OSS_SCORE, OSS_AT, "><", OSS_NULLABLE, 'illegal:' . _("Email subject"));
ossim_valid($email_message, OSS_ALPHA, OSS_PUNC, OSS_SCORE, OSS_AT, "><", OSS_NULLABLE, OSS_NL, 'illegal:' . _("Email message"));
ossim_valid($exec_command, OSS_ALPHA, OSS_PUNC, OSS_SCORE, OSS_AT, "><", OSS_NULLABLE, 'illegal:' . _("Exec command"));
if (ossim_error()) {
    die(ossim_error());
}
$db = new ossim_db();
$conn = $db->connect();
if (REQUEST('modify_action')) {
    if ($action_type == "email") {
        if ((REQUEST('descr')) and (REQUEST('email_from')) and (REQUEST('email_to')) and (REQUEST('email_subject')) and (REQUEST('email_message'))) {
            Action::updateEmail($conn, $action_id, $action_type, $descr, $email_from, $email_to, $email_subject, $email_message);
            message_ok();
            exit;
        } else {
            require_once ("ossim_error.inc");
            $error = new OssimError();
            $error->display("FORM_NOFILL");
        }
    } elseif ($action_type == "exec") {
        if ((REQUEST('descr')) and (REQUEST('exec_command'))) {
            Action::updateExec($conn, $action_id, $action_type, $descr, $exec_command);
            message_ok();
            exit();
        } else {
            require_once ("ossim_error.inc");
            $error = new OssimNotice();
            $error->display("FORM_NOFILL");
        }
    }
}
if (is_array($action_list = Action::get_list($conn, "WHERE id = '$action_id'"))) {
    $action = $action_list[0];
} else {
    /* never reached */
    require_once ("ossim_error.inc");
    $error = new OssimError();
    $error->display("ACTIONID_UNK", array(
        $action_id
    ));
}
function message_ok() {
    echo "<p>" . gettext("Action succesfully updated") . "</p>";
    echo "<script>document.location.href=\"action.php\"</script>\n";
    echo "<p><a href=\"action.php\">";
    echo gettext("Back") . "</a></p>";
}
function email_form($action) {
?>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
      <th> <?php
    echo gettext("From:"); ?></th> 
      <td><input
        value="<?php
    echo $action->get_from(); ?>"
        name="email_from" type="text" size="60"/></td>
    </tr>
    <tr>
      <th><?php
    echo gettext("To:"); ?></th>
      <td><input
        value="<?php
    echo $action->get_to(); ?>"
        name="email_to" type="text" size="60"/></td>
    </tr>
    <tr>
      <th> <?php
    echo gettext("Subject:"); ?></th> 
      <td><input 
        value="<?php
    echo $action->get_subject(); ?>"
        name="email_subject" type="text" size="60" /></td>
    </tr>
    <tr>
      <th> <?php
    echo gettext("Message:"); ?> </th>
      <td><textarea name="email_message" rows="10" cols="80" WRAP=HARD>
      <?php
    echo $action->get_message() ?></textarea></td>
    </tr>
<?php
}
function exec_form($action) {
?>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
      <th> <?php
    echo gettext("Command:"); ?></th>
      <td><input
        value="<?php
    echo $action->get_command() ?>"
        name="exec_command" type="text" /></td>
    </tr>
<?php
}
function submit() {
?>
    <tr><td colspan="2">
      <input type="submit" name="modify_action" value="OK" class="btn" style="font-size:12px"></td>
    </tr>
<?php
}
?>

<table align="center">
<tr>
<td colspan="2" style="text-align: left">
<?php
echo gettext("You can use the following keywords within any field which will be get substituted by it's matching value upon action execution
") . ":";
?>    
<table width="80%" align="center" style="border-width: 0px"><tr>
<td style="text-align: left" valign="top">
<ul> 
<li> DATE
<li> PLUGIN_ID
<li> PLUGIN_SID
<li> RISK
<li> PRIORITY
<li> RELIABILITY
<li> SRC_IP
<li> DST_IP
<li> SRC_PORT
<li> DST_PORT
<li> PROTOCOL
<li> SENSOR
<li> BACKLOG_ID
<li> EVENT_ID
</ul>
</td>
<td style="text-align: left" valign="top">
<ul> 
<li> PLUGIN_NAME
<li> SID_NAME
<li> USERNAME
<li> PASSWORD
<li> FILENAME
<li> USERDATA1
<li> USERDATA2
<li> USERDATA3
<li> USERDATA4
<li> USERDATA5
<li> USERDATA6
<li> USERDATA7
<li> USERDATA8
<li> USERDATA9
</ul>
</td></tr></table>
</td></tr>
<form method="POST">
  <input type="hidden" name="id" value="<?php
echo $action->get_id() ?>" />
  <tr>
  
<?php
$action_type = $action->get_action_type();
if (REQUEST('descr')) $description = $descr;
else $description = $action->get_descr();
?>

    <th> <?php
echo gettext("Description"); ?> </th>
    <td>
      <textarea name="descr"><?php
echo $description ?></textarea>
    </td>
  </tr>
  <tr>
    <th>Type</th>
    <td>
      <input type="hidden" name="action_type" 
             value="<?php
echo $action_type ?>" />
      <b><?php
echo $action_type ?></b>
    </td>
  </tr>

<?php
/* type of action */
if ($action_type == "email") {
    email_form($action->get_action($conn));
    submit();
} elseif ($action_type == "exec") {
    exec_form($action->get_action($conn));
    submit();
}
?>

  </form>
</table>

<?php
$db->close($conn);
?>

</body>
</html>

