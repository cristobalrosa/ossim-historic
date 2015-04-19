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
require_once 'classes/Incident_ticket.inc';
require_once 'ossim_db.inc';
Session::logcheck('MenuConfiguration', 'ConfigurationEmailTemplate');
$db = new ossim_db();
$conn = $db->connect();
foreach(array(
    'preview',
    'subject_tpl',
    'body_tpl',
    'save'
) as $var) {
    $$var = POST($var);
}
// User wants the default template
if (GET('reset')) {
    $save = true;
    $subject_tpl = $body_tpl = '';
}
// Save values in the "config" table
if ($save) {
    Incident_ticket::save_email_template($subject_tpl, $body_tpl);
    header("Location: " . $_SERVER['SCRIPT_NAME']);
    exit;
}
// First time, get the default templates. They are defined
// inside the function: Incident_ticket::get_email_template()
if (!$subject_tpl) $subject_tpl = Incident_ticket::get_email_template('subject');
if (!$body_tpl) $body_tpl = Incident_ticket::get_email_template('body');
$labels = array(
    'ID' => array(
        'help' => _("The Incident database ID") ,
        'sample' => '63'
    ) ,
    'INCIDENT_NO' => array(
        'help' => _("The incident human-oriented reference") ,
        'sample' => 'ALA63'
    ) ,
    'TITLE' => array(
        'help' => _("The incident resume") ,
        'sample' => _("Detected MAC change in DMZ")
    ) ,
    'EXTRA_INFO' => array(
        'help' => _("Related incident information") ,
        'sample' => "Source IPs: 10.10.10.10\n" . "Source Ports: 2267\n" . "Dest. IPs: 10.10.10.11\n" . "Dest. Ports: 22\n"
    ) ,
    'IN_CHARGE_NAME' => array(
        'help' => _("The person currently in charge of solving the incident") ,
        'sample' => 'John Smith'
    ) ,
    'IN_CHARGE_LOGIN' => array(
        'help' => _("The login of the person currently in charge of solving the incident") ,
        'sample' => 'jsmith'
    ) ,
    'IN_CHARGE_EMAIL' => array(
        'help' => _("The email of the person currently in charge of solving the incident") ,
        'sample' => 'jsmith@example.com'
    ) ,
    'IN_CHARGE_DPTO' => array(
        'help' => _("The department of the person currently in charge of solving the incident") ,
        'sample' => 'Tech Support'
    ) ,
    'IN_CHARGE_COMPANY' => array(
        'help' => _("The company of the person currently in charge of solving the incident") ,
        'sample' => 'Example Inc.'
    ) ,
    'PRIORITY_NUM' => array(
        'help' => _("The priority of the incident in numbers from 1 (low) to 10 (high)") ,
        'sample' => 8
    ) ,
    'PRIORITY_STR' => array(
        'help' => _("The priority in string format: Low, Medium or High") ,
        'sample' => 'High'
    ) ,
    'TAGS' => array(
        'help' => _("The extra labels of information attached to the incident") ,
        'sample' => "NEED_MORE_INFO, FALSE_POSITIVE"
    ) ,
    'CREATION_DATE' => array(
        'help' => _("When was the incident created") ,
        'sample' => '2005-10-18 19:30:53'
    ) ,
    'STATUS' => array(
        'help' => _("What's the current status: Open or Close") ,
        'sample' => 'Open'
    ) ,
    'CLASS' => array(
        'help' => _("The type of incident: Alarm, Event, Metric...") ,
        'sample' => 'Alarm'
    ) ,
    'TYPE' => array(
        'help' => _("The incident category or group") ,
        'sample' => 'Policy Violation'
    ) ,
    'LIFE_TIME' => array(
        'help' => _("The time passed since the creation of the incident") ,
        'sample' => '1 Day, 10:13'
    ) ,
    'TICKET_DESCRIPTION' => array(
        'help' => _("The description filled by the ticket author") ,
        'sample' => 'Detected a MAC change on dmz1.int host'
    ) ,
    'TICKET_ACTION' => array(
        'help' => _("The action filled by the ticket author") ,
        'sample' => 'Investigate the incident asap'
    ) ,
    'TICKET_AUTHOR_NAME' => array(
        'help' => _("The person who just created a new ticket") ,
        'sample' => 'Sam Max'
    ) ,
    'TICKET_AUTHOR_EMAIL' => array(
        'help' => _("The email of the ticket author") ,
        'sample' => 'smax@example.com'
    ) ,
    'TICKET_AUTHOR_DPTO' => array(
        'help' => _("The department of the ticket author") ,
        'sample' => 'Network Operations'
    ) ,
    'TICKET_AUTHOR_COMPANY' => array(
        'help' => _("The company of the ticket author") ,
        'sample' => 'Same Example Inc.'
    ) ,
    'TICKET_EMAIL_CC' => array(
        'help' => _("Who (Name and Email) received this email too") ,
        'sample' => "\"John Smith\" <jsmith@example.com>\n\"Sam Max\" <smax@example.com>"
    ) ,
    'TICKET_HISTORY' => array(
        'help' => _("The complete list of tickets related to this incident") ,
        'sample' => '-- Here goes the list of tickets --'
    ) ,
    'TICKET_INVERSE_HISTORY' => array(
        'help' => _("The complete list of tickets related to this incident") . " (" . _("reverse order") . ")",
        'sample' => '-- Here goes the reversed list of tickets --'
    )
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <title> <?php
echo gettext("OSSIM Framework"); ?> </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
<script language="JavaScript" type="text/javascript">

    function confirm_reset(text)
    {
        ret = confirm(text);
        if (ret) {
            document.location = '<?php echo $_SERVER['SCRIPT_NAME'] ?>?reset=1';
        }
        return ret
    }

    // Precious code from Dokuwiki! (dokuwiki/lib/scripts/script.js)
    function insertAtCarret(field,value)
    {
      //IE support
      if (document.selection) {
        field.focus();
        if (opener == null) {
          var sel = document.selection.createRange();
        } else {
          var sel = opener.document.selection.createRange();
        }
        sel.text = value;
      //MOZILLA/NETSCAPE support
      } else if (field.selectionStart || field.selectionStart == '0') {
        var startPos  = field.selectionStart;
        var endPos    = field.selectionEnd;
        var scrollTop = field.scrollTop;
        field.value = field.value.substring(0, startPos)
                      + value
                      + field.value.substring(endPos, field.value.length);
    
        field.focus();
        var cPos=startPos+(value.length);
        field.selectionStart=cPos;
        field.selectionEnd=cPos;
        field.scrollTop=scrollTop;
      } else {
        field.value += "\n"+value;
      }
      // reposition cursor if possible
      if (field.createTextRange) field.caretPos = document.selection.createRange().duplicate();
    }

    // Interface to insertAtCarret()
    function insertAtCursor(myField)
    {
      var tags = document.myform.tags;
      var index = tags.selectedIndex;
      var myValue = tags.options[index].text;
      insertAtCarret(myField, myValue);
    }
</script>
  

</head>
<body>

<?php
include ("../hmenu.php"); ?>
  
<div id="help" class="help" width="70%">
<i><?php echo _("Select a TAG to see its meaning") ?></i>
</div>
<br/>
<form name="myform" method="POST">
<table width="90%" border="0" align="center">

<tr valign="top">
    <td>
    <?php echo _("Template Labels") ?><br/>
    <select name="tags" size="21" onChange="javascript: show_help(this);">
        <?php
$i = 0;
foreach($labels as $label => $data) {
    $help_msgs[$i++] = addslashes($data['help']);
?>
        <option name="<?php echo $label
?>"><?php echo $label
?></option>
        <?php
} ?>
    </select>
    </td>
    <td>
        <table width="100%">
            <tr>
                <td>
                    <input type="button" value="->" onClick="javascript: insertAtCursor(document.myform.subject_tpl);" class="btn" style="font-size:12px">
                </td>
                <th width="10%"><?php echo _("Subject") ?></th>
                <td style="text-align: left;">
                    <input type="text" name="subject_tpl" value="<?php echo $subject_tpl ?>" size="80" style="font-family: mono-space, mono;"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="button" value="->" onClick="javascript: insertAtCursor(document.myform.body_tpl);" class="btn" style="font-size:12px">
                </td>
                <th valign="top" width="10%"><?php
echo gettext("Body"); ?></th>
                <td style="text-align: left;">
                    <textarea name="body_tpl" rows="25" cols="80" WRAP=HARD style="font-family: mono-space, mono;"><?php echo $body_tpl ?></textarea>
                </td>
            </tr>                
        </table>
</tr>
</table>
<script>
    function show_help(select_el)
    {
        var selected = select_el.selectedIndex;
        var help = new Array;
        <?php
foreach($help_msgs as $key => $text) { ?>
            help[<?php echo $key
?>] = '<?php echo $text
?>';
        <?php
} ?>
        document.getElementById('help').innerHTML = '<b>'+help[selected]+'</b>';
        return false;
    }

</script>
<p align="center">
    <input type="submit" name="preview" value="<?php echo _('Preview') ?>" class="btn" style="font-size:12px">&nbsp;
    <input type="button" name="reset" value="<?php echo _('Reset to Defaults') ?>" class="btn" style="font-size:12px"
           onClick="javscript: return confirm_reset('<?php echo addslashes(_("All changes will be lost. Continue anyway?")) ?>')" >
    &nbsp;<input type="submit" name="save" value="<?php echo _('Save Template') ?>"  class="btn" style="font-size:12px">
</p>
</form>
<?php
if ($preview) {
    foreach($labels as $k => $data) {
        $values[$k] = $data['sample'];
    }
    $subject = Incident_ticket::build_email_template($subject_tpl, $values);
    $body = Incident_ticket::build_email_template($body_tpl, $values);
?>
    <table align="center" width="80%">
    <tr>
        <th valign="top" width="10%" style="text-align: right;"><?php echo _("Subject") ?>:</td>
        <td valign="top" style="text-align: left;"><pre><?php echo $subject ?></pre></td>
    </tr>
    <tr>
        <th valign="top" width="10%" style="text-align: right;"><?php echo _("Body") ?>:</td>
        <td valign="top" style="text-align: left; border-width: 0px;"><pre><?php echo htmlentities($body, ENT_COMPAT, "UTF-8") ?></pre></td>
    </tr>
    </table>
<?php
} ?>
