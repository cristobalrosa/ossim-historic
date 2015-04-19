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
Session::logcheck("MenuIncidents", "IncidentsIncidents");
require_once 'ossim_db.inc';
require_once 'classes/Incident.inc';
require_once 'classes/Incident_alarm.inc';
require_once 'classes/Incident_event.inc';
require_once 'classes/Incident_metric.inc';
require_once 'classes/Incident_anomaly.inc';
require_once 'classes/Incident_vulnerability.inc';
$db = new ossim_db();
$conn = $db->connect();
$edit = GET('action') && GET('action') == 'edit' ? true : false;
$ref = !ossim_valid(GET('ref') , OSS_LETTER) ? die("Ref required") : GET('ref');
if ($edit) {
    if (!ossim_valid(GET('incident_id') , OSS_DIGIT)) {
        die("Wrong ID");
    }
    $incident_id = GET('incident_id');
    $list = Incident::get_list($conn, "WHERE incident.id=$incident_id");
    if (count($list) != 1) die("Wrong ID");
    $incident = $list[0];
    $title = $incident->get_title();
    $submitter = $incident->get_submitter();
    $priority = $incident->get_priority();
    $event_start = $incident->get_event_start();
    $event_end = $incident->get_event_end();
    $type = $incident->get_type();
    switch ($ref) {
        case 'Alarm':
            list($alarm) = Incident_alarm::get_list($conn, "WHERE incident_alarm.incident_id=$incident_id");
            $src_ips = $alarm->get_src_ips();
            $dst_ips = $alarm->get_dst_ips();
            $src_ports = $alarm->get_src_ports();
            $dst_ports = $alarm->get_dst_ports();
            break;

        case 'Event':
            list($event) = Incident_event::get_list($conn, "WHERE incident_event.incident_id=$incident_id");
            $src_ips = $event->get_src_ips();
            $dst_ips = $event->get_dst_ips();
            $src_ports = $event->get_src_ports();
            $dst_ports = $event->get_dst_ports();
            break;

        case 'Metric':
            list($metric) = Incident_metric::get_list($conn, "WHERE incident_metric.incident_id=$incident_id");
            $target = $metric->get_target();
            $metric_type = $metric->get_metric_type();
            $metric_value = $metric->get_metric_value();
            break;

        case 'Anomaly':
            list($anomaly) = Incident_anomaly::get_list($conn, "WHERE incident_anomaly.incident_id=$incident_id");
            $anom_type = $anomaly->get_anom_type();
            $anom_ip = $anomaly->get_ip();
            $anom_data_orig = $anomaly->get_data_orig();
            $anom_data_new = $anomaly->get_data_new();
            if ($anom_type == "mac") {
                list($a_sen, $a_date, $a_mac_o, $a_vend_o) = explode(",", $anom_data_orig);
                list($a_sen, $a_date, $a_mac, $a_vend) = explode(",", $anom_data_new);
            } elseif ($anom_type == "service") {
                list($a_sen, $a_date, $a_port, $a_prot_o, $a_ver_o) = explode(",", $anom_data_orig);
                list($a_sen, $a_date, $a_port, $a_prot, $a_ver) = explode(",", $anom_data_new);
            } elseif ($anom_type == "os") {
                list($a_sen, $a_date, $a_os_o) = explode(",", $anom_data_orig);
                list($a_sen, $a_date, $a_os) = explode(",", $anom_data_new);
            }
            break;

        case 'Vulnerability':
            list($vulnerability) = Incident_vulnerability::get_list($conn, "WHERE incident_vulns.incident_id=$incident_id");
            $ip = $vulnerability->get_ip();
            $port = $vulnerability->get_port();
            $nessus_id = $vulnerability->get_nessus_id();
            $risk = $vulnerability->get_risk();
            $description = $vulnerability->get_description();
            break;
    }
} else {
    $title = GET('title');
    $submitter = GET('submitter');
    $priority = GET('priority');
    $type = GET('type');
    $src_ips = GET('src_ips');
    $dst_ips = GET('dst_ips');
    $src_ports = GET('src_ports');
    $dst_ports = GET('dst_ports');
    $target = GET('target');
    $event_start = GET('event_start');
    $event_end = GET('event_end');
    $metric_type = GET('metric_type');
    $metric_value = GET('metric_value');
    $anom_type = GET('anom_type');
    $anom_ip = GET('anom_ip');
    $a_sen = GET('a_sen');
    $a_date = GET('a_date');
    $a_mac_o = GET('a_mac_o');
    $a_mac = GET('a_mac');
    $a_vend_o = GET('a_vend_o');
    $a_vend = GET('a_vend');
    $a_ver_o = GET('a_ver_o');
    $a_ver = GET('a_ver');
    $a_port = GET('a_port');
    $a_prot_o = GET('a_prot_o');
    $a_prot = GET('a_prot');
    $a_os_o = GET('a_os_o');
    $a_os = GET('a_os');
    $ip = GET('ip');
    $port = GET('port');
    $nessus_id = GET('nessus_id');
    $risk = GET('risk');
    $description = GET('description');
    // TODO: Check the validations below, narrow them down a bit
    ossim_valid($title, OSS_PUNC_EXT, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("title"));
    ossim_valid($submitter, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("submitter"));
    ossim_valid($priority, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("priority"));
    ossim_valid($type, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("type"));
    ossim_valid($src_ips, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("src_ips"));
    ossim_valid($dst_ips, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("dst_ips"));
    ossim_valid($src_ports, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("src_ports"));
    ossim_valid($dst_ports, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("dst_ports"));
    ossim_valid($target, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("target"));
    ossim_valid($event_start, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("event_start"));
    ossim_valid($event_end, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("event_end"));
    ossim_valid($metric_type, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("metric_type"));
    ossim_valid($metric_value, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("metric_value"));
    ossim_valid($anom_type, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anom_type"));
    ossim_valid($anom_ip, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anom_ip"));
    ossim_valid($a_sen, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anomaly sensor"));
    ossim_valid($a_date, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anomaly date"));
    ossim_valid($a_mac_o, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("original mac"));
    ossim_valid($a_vend_o, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("original vendor"));
    ossim_valid($a_ver_o, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("original version"));
    ossim_valid($a_ver, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anomaly version"));
    ossim_valid($a_port, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anomaly port"));
    ossim_valid($a_prot_o, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("original proto"));
    ossim_valid($a_prot, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anomaly proto"));
    ossim_valid($a_os_o, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("original os"));
    ossim_valid($a_os, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("anomaly os"));
    ossim_valid($ip, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("ip"));
    ossim_valid($port, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("port"));
    ossim_valid($nessus_id, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("nessus id"));
    ossim_valid($risk, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("risk"));
    ossim_valid($description, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("description"));
    if (ossim_error()) {
        die(ossim_error());
    }
    /* get default submitter info */
    if (!$submitter) {
        $session_info = Session::get_session_info();
        $submitter = $session_info['name'];
        if ($session_info['company']) $submitter.= '/' . $session_info['company'];
        if ($session_info['department']) $submitter.= '/' . $session_info['department'];
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <title> <?php
echo gettext("OSSIM Framework"); ?> </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>
<h1><?php echo " $ref " . _("Incident") ?></h1>

<form method="GET" action="manageincident.php">
<input type="hidden" name="action" value="<?php echo ($edit) ? 'editincident' : 'newincident' ?>" />
<input type="hidden" name="ref" value="<?php echo $ref ?>" />
<input type="hidden" name="incident_id" value="<?php echo $incident_id ?>" />
<table align="center">
  <tr>
    <th><?php echo _("Title") ?></th>
    <td class="left">
      <input type="text" name="title" size="40" value="<?php echo $title ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("Submitter") ?></th>
    <td class="left">
      <input type="text" name="submitter" size="40" value="<?php echo $submitter ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("Priority") ?></th>
    <td class="left">
      <select name="priority">
<?php
$options = "";
for ($i = 1; $i <= 10; $i++) {
    $options.= "<option value=\"$i\"";
    if ($priority == $i) {
        $options.= " selected ";
    }
    $options.= ">$i</option>";
}
print $options;
?>
      </select>
    </td>
  </tr>
  <tr>
    <th><?php echo _("Type") ?></th>
<?php
Incident::print_td_incident_type($conn, $type);
?>
  </tr>

<?php
if (($ref == "Alarm") or ($ref == "Event")) {
?>
  <tr>
    <th><?php echo _("Source Ips") ?></th>
    <td class="left">
      <input type="text" name="src_ips" value="<?php echo $src_ips ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("Dest Ips") ?></th>
    <td class="left">
      <input type="text" name="dst_ips" value="<?php echo $dst_ips ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("Source Ports") ?></th>
    <td class="left">
      <input type="text" name="src_ports" value="<?php echo $src_ports ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("Dest Ports") ?></th>
    <td class="left">
      <input type="text" name="dst_ports" value="<?php echo $dst_ports ?>" /></td>
  </tr>
  <tr>
    <th><?php echo _("Start of related events") ?></th>
    <td class="left">
      <input type="text" name="event_start" value="<?php echo $event_start ?>" /></td>
  </tr>
  <tr>
    <th><?php echo _("End of related events") ?></th>
    <td class="left">
      <input type="text" name="event_end" value="<?php echo $event_end ?>" /></td>
  </tr>

<?php
} elseif ($ref == "Metric") {
?>
  <tr>
    <th><?php echo _("Target (net, ip, etc)") ?></th>
    <td class="left">
      <input type="text" name="target" value="<?php echo $target ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("Metric type") ?></th>
    <td class="left">
      <select name="metric_type">
        <option value="Compromise"
        <?php
    if ($metric_type == "Compromise") echo " selected "; ?>
            >Compromise</option>
        <option value="Attack"
        <?php
    if ($metric_type == "Attack") echo " selected "; ?>
            >Attack</option>
        <option value="Level"
        <?php
    if ($metric_type == "Level") echo " selected "; ?>
            >Level</option>
      </select>
    </td>
  </tr>
  <tr>
    <th><?php echo _("Metric value") ?></th>
    <td class="left">
      <input type="text" name="metric_value" value="<?php echo $metric_value ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("Start of related events") ?></th>
    <td class="left">
      <input type="text" name="event_start" value="<?php echo $event_start ?>" /></td>
  </tr>
  <tr>
    <th><?php echo _("End of related events") ?></th>
    <td class="left">
      <input type="text" name="event_end" value="<?php echo $event_end ?>" /></td>
  </tr>
<?php
} elseif ($ref == "Anomaly") {
?>
  <tr>
    <th><?php echo _("Anomaly type") ?></th>
    <td class="left">
      <input type="text" name="anom_type" size="30" value="<?php echo $anom_type ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("Host") ?></th>
    <td class="left">
      <input type="text" name="anom_ip" size="30" value="<?php echo $anom_ip ?>" />
    </td>
  </tr>
 <tr>
    <th><?php echo _("Sensor") ?></th>
    <td class="left">
      <input type="text" name="a_sen" size="30" value="<?php echo $a_sen ?>" />
    </td>
  </tr>
<?php
    if ($anom_type == "os") {
?>
   <tr>
    <th><?php echo _("Old OS") ?></th>
    <td class="left">
      <input type="text" name="a_os_o" size="30" value="<?php echo $a_os_o ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("New OS") ?></th>
    <td class="left">
      <input type="text" name="a_os"  size="30" value="<?php echo $a_os ?>" />
    </td>
  </tr>
   <tr>
    <th><?php echo _("When") ?></th>
    <td class="left">
      <input type="text" name="a_date" size="30" value="<?php echo $a_date ?>" />
    </td>
  </tr>

     
<?php
    } elseif ($anom_type == "mac") {
?>
   <tr>
    <th><?php echo _("Old mac") ?></th>
    <td class="left">
      <input type="text" name="a_mac_o" size="30" value="<?php echo $a_mac_o ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("New mac") ?></th>
    <td class="left">
      <input type="text" name="a_mac" size="30" value="<?php echo $a_mac ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("Old vendor") ?></th>
    <td class="left">
      <input type="text" name="a_vend_o" size="30" value="<?php echo $a_vend_o ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("New vendor") ?></th>
    <td class="left">
      <input type="text" name="a_vend" size="30" value="<?php echo $a_vend ?>" />
    </td>
  </tr>
  <tr>
    <th><?php echo _("When") ?></th>
    <td class="left">
      <input type="text" name="a_date" size="30" value="<?php echo $a_date ?>" />
    </td>
  </tr>


<?php
    } elseif ($anom_type == "service") {
?>

  <tr>
    <th><?php echo _("Port") ?></th>
    <td class="left">
      <input type="text" name="a_port" value="<?php echo $a_port ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("Old Protocol") ?></th>
    <td class="left">
      <input type="text" name="a_prot_o" size="30" value="<?php echo $a_prot_o ?>" />
    </td>
  </tr>
     <tr>
    <th><?php echo _("Old Version") ?></th>
    <td class="left">
      <input type="text" name="a_ver_o" size="30" value="<?php echo $a_ver_o ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("New Protocol") ?></th>
    <td class="left">
      <input type="text" name="a_prot" size="30" value="<?php echo $a_prot ?>" />
    </td>
  </tr>
     <tr>
    <th><?php echo _("New Version") ?></th>
    <td class="left">
      <input type="text" name="a_ver" size="30" value="<?php echo $a_ver ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("When") ?></th>
    <td class="left">
      <input type="text" name="a_date" size="30" value="<?php echo $a_date ?>" />
    </td>
  </tr>

<?php
    }
?>


<?php
} elseif ($ref == "Vulnerability") {
?>

 <tr>
    <th><?php echo _("IP") ?></th>
    <td class="left">
      <input type="text" name="ip" value="<?php echo $ip ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("Port") ?></th>
    <td class="left">
      <input type="text" name="port" size="30" value="<?php echo $port ?>" />
    </td>
  </tr>
     <tr>
    <th><?php echo _("Nessus ID") ?></th>
    <td class="left">
      <input type="text" name="nessus_id" size="30" value="<?php echo $nessus_id ?>" />
    </td>
  </tr>
    <tr>
    <th><?php echo _("Risk") ?></th>
    <td class="left">
      <input type="text" name="risk" size="30" value="<?php echo $risk ?>" />
    </td>
  </tr>
     <tr>
    <th><?php echo _("Description") ?></th>
    <td style="border-width: 0px;">
        <textarea name="description" rows="10" cols="80" wrap="hard"><?php echo $description ?></textarea>
    </td>
  </tr>

<?php
}
?>

<tr>
    <td colspan="2">
      <input type="submit" value="OK" class="btn" />
    </td>
  </tr>
</table>
</form>

</body>
</html>
