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
* - html_service_level()
* - global_score()
* Classes list:
*/
require_once 'ossim_db.inc';
require_once 'classes/Incident.inc';
require_once 'classes/Incident_ticket.inc';
require_once 'classes/Alarm.inc';
function html_service_level($conn) {
    global $user;
    $range = "day";
    $level = 100;
    $class = "level4";
    //
    $sql = "SELECT c_sec_level, a_sec_level FROM control_panel WHERE id = ? AND time_range = ?";
    $params = array(
        "global_$user",
        $range
    );
    if (!$rs = & $conn->Execute($sql, $params)) {
        echo "error";
        die($conn->ErrorMsg());
    }
    if ($rs->EOF) {
        return array(
            $level,
            $fontcolor
        );
    }
    $level = number_format(($rs->fields["c_sec_level"] + $rs->fields["a_sec_level"]) / 2, 0);
    $class = "level" . round($level / 9, 0);
    return array(
        $level,
        $class
    );
}
function global_score($conn) {
    global $conf_threshold;
    //
    $sql = "SELECT host_ip, compromise, attack FROM host_qualification";
    if (!$rs = & $conn->Execute($sql)) {
        die($conn->ErrorMsg());
    }
    $score_a = 0;
    $score_c = 0;
    while (!$rs->EOF) {
        $score_a+= $rs->fields['attack'];
        $score_c = $rs->fields['compromise'];
        $rs->MoveNext();
    }
    $risk_a = round($score_a / $conf_threshold * 100);
    $risk_c = round($score_c / $conf_threshold * 100);
    $risk = ($risk_a > $risk_c) ? $risk_a : $risk_c;
    $img = 'green'; // 'off'
    $color = '';
    if ($risk > 500) {
        $img = 'red';
    } elseif ($risk > 300) {
        $img = 'yellow';
    } elseif ($risk > 100) {
        $img = 'green';
    }
    $alt = "$risk " . _("metric/threshold");
    return array(
        $img,
        $alt
    );
}
// Database Object
$db = new ossim_db();
$conn = $db->connect();
$user = Session::get_session_user();
$conf = $GLOBALS['CONF'];
$conf_threshold = $conf->get_conf('threshold');
// Get unresolved INCIDENTS
if (!$order_by) {
    $order_by = 'life_time';
    $order_mode = 'ASC';
}
$criteria['status'] = "Open";
$incident_list = Incident::search($conn, $criteria, $order_by, $order_mode);
$unresolved_incidents = count($incident_list);
$incident_list = Incident::get_list($conn, "ORDER BY date DESC");
$incident_date1 = ($incident_list[0]) ? $incident_list[0]->get_date() : 0;
$incident_ticket_list = Incident_ticket::get_list($conn, "ORDER BY date DESC");
$incident_date2 = ($incident_ticket_list[0]) ? $incident_ticket_list[0]->get_date() : 0;
if ($incident_list[0] || $incident_ticket_list[0]) {
    $incident_date = (strtotime($incident_date1) > strtotime($incident_date2)) ? $incident_date1 : $incident_date2;
    if ($incident_date == 0) $incident_date = "__/__/__ --:--:--";
}
$incident_list = Incident::get_list($conn, "ORDER BY priority DESC");
$incident_max_priority = ($incident_list[0]) ? $incident_list[0]->get_priority() : "0";
$incident_max_priority_id = ($incident_list[0]) ? $incident_list[0]->get_id() : "-";
// Get unresolved ALARMS
$unresolved_alarms = Alarm::get_count($conn);
list($alarm_date, $alarm_date_id) = Alarm::get_max_byfield($conn, "timestamp");
list($alarm_max_risk, $alarm_max_risk_id) = Alarm::get_max_byfield($conn, "risk");
// Get service LEVEL
//global $conn, $conf, $user, $range, $rrd_start;
list($level, $levelgr) = html_service_level($conn);
list($score, $alt) = global_score($conn);
$db->close($conn);
?>
document.getElementById('statusbar_unresolved_incidents').innerHTML = '<?php echo $unresolved_incidents
?>';
document.getElementById('statusbar_incident_date').innerHTML = '<?php echo $incident_date
?>';
document.getElementById('statusbar_incident_max_priority').innerHTML = '<?php echo $incident_max_priority
?>';
document.getElementById('statusbar_incident_max_priority').href = 'top.php?option=1&soption=0&url=<?php echo urlencode("incidents/incident.php?id=$incident_max_priority_id") ?>';
document.getElementById('statusbar_incident_max_priority_txt').href = 'top.php?option=1&soption=0&url=<?php echo urlencode("incidents/incident.php?id=$incident_max_priority_id") ?>';
document.getElementById('statusbar_unresolved_alarms').innerHTML = '<?php echo $unresolved_alarms ?>';
document.getElementById('statusbar_alarm_date').innerHTML = '<?php echo $alarm_date ?>';
document.getElementById('statusbar_alarm_max_risk').innerHTML = '<?php echo $alarm_max_risk ?>';
document.getElementById('statusbar_alarm_max_risk').href = 'top.php?option=0&soption=2&url=<?php echo urlencode("control_panel/events.php?backlog_id=$alarm_max_risk_id") ?>';
document.getElementById('statusbar_alarm_max_risk_txt').href = 'top.php?option=0&soption=2&url=<?php echo urlencode("control_panel/events.php?backlog_id=$alarm_max_risk_id") ?>';
document.getElementById('semaphore').src = "pixmaps/statusbar/sem_<?php echo $score ?>.gif";
document.getElementById('semaphore').alt = "<?php echo $alt ?>";
document.getElementById('semaphore').title = "<?php echo $alt ?>";
document.getElementById('service_level').innerHTML = "<?php echo $level ?> %";
document.getElementById('service_level_gr').className = "<?php echo $levelgr ?>";
