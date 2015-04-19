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
require_once ('classes/Session.inc');
Session::logcheck("MenuPolicy", "PolicyPolicy");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>OSSIM Framework</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css" />
  <link rel="stylesheet" type="text/css" href="../style/jquery-ui-1.7.custom.css" />
  <link rel="stylesheet" type="text/css" href="../style/tree.css" />
  <link rel="stylesheet" type="text/css" href="../style/greybox.css" />
  <script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
  <script type="text/javascript" src="../js/jquery-ui-1.7.custom.min.js"></script>
  <script type="text/javascript" src="../js/jquery.cookie.js"></script>
  <script type="text/javascript" src="../js/jquery.dynatree.js"></script>
  <script type="text/javascript" src="../js/urlencode.js"></script>
  <script type="text/javascript" src="../js/combos.js"></script>
  <script type="text/javascript" src="../js/greybox.js"></script>
  <script type="text/javascript">
	var tab_actual = 'tabs-1';
	//var loading = '<br><img src="../pixmaps/theme/ltWait.gif" border="0" align="absmiddle"> <?php echo _("Loading resource tree, please wait...") ?>';
	var reloading = '<img src="../pixmaps/theme/ltWait.gif" border="0" align="absmiddle"> <?php echo _("Re-loading data...") ?>';
	var layer = null;
	var nodetree = null;
	var suf = "c";
	var i=1;
	function load_tree(filter) {
		combo = (suf=="c") ? 'sources' : 'dests';
		if (nodetree!=null) {
			nodetree.removeChildren();
			$(layer).remove();
		}
		layer = '#srctree'+i;
		$('#container'+suf).append('<div id="srctree'+i+'" style="width:100%"></div>');
		$(layer).dynatree({
			initAjax: { url: "draw_tree.php", data: {filter: filter} },
			clickFolderMode: 2,
			onActivate: function(dtnode) {
				if (dtnode.data.url.match(/CCLASS/)) {
					// add childrens if is a C class
					var children = dtnode.tree.getAllNodes(dtnode.data.key.replace('.','\\.')+'\\.');
					for (c=0;c<children.length; c++)
						addto(combo,children[c].data.url,children[c].data.url)
				} else {
					addto(combo,dtnode.data.url,dtnode.data.url);
				}
				drawpolicy();
			},
			onDeactivate: function(dtnode) {}
		});
		nodetree = $(layer).dynatree("getRoot");
		i=i+1
	}
	var layerp = null;
	var nodetreep = null;
	function load_ports_tree() {
		if (nodetreep!=null) {
			nodetreep.removeChildren();
			$(layerp).remove();
		}
		layerp = '#srctree'+i;
		$('#containerp').append('<div id="srctree'+i+'" style="width:100%"></div>');
		$(layerp).dynatree({
			initAjax: { url: "draw_ports_tree.php" },
			clickFolderMode: 2,
			onActivate: function(dtnode) {
				if (dtnode.data.url!='noport') addto('ports',dtnode.data.url,dtnode.data.url);
				drawpolicy();
			},
			onDeactivate: function(dtnode) {}
		});
		nodetreep = $(layerp).dynatree("getRoot");
		i=i+1
	}
	var layerse = null;
	var nodetreese = null;
	function load_sensors_tree() {
		if (nodetreese!=null) {
			nodetreese.removeChildren();
			$(layerse).remove();
		}
		layerse = '#srctree'+i;
		$('#containerse').append('<div id="srctree'+i+'" style="width:100%"></div>');
		$(layerse).dynatree({
			initAjax: { url: "draw_sensors_tree.php" },
			clickFolderMode: 2,
			onActivate: function(dtnode) {
				addto('sensors',dtnode.data.url,dtnode.data.url);
				drawpolicy();
			},
			onDeactivate: function(dtnode) {}
		});
		nodetreese = $(layerse).dynatree("getRoot");
		i=i+1
	}
	function GB_onclose() {
		if (tab_actual=='tabs-1' || tab_actual=='tabs-2') {
			suf = (tab_actual=='tabs-1') ? 'c' : 'd';
			load_tree($('filter'+suf).val());
		} else if (tab_actual=='tabs-3') {
			load_ports_tree();
		} else if (tab_actual=='tabs-4') {
			$('#plugins').html(reloading);
			$.ajax({
				type: "GET",
				url: "getpolicydata.php?tab=plugins",
				data: "",
				success: function(msg) {
					$('#plugins').html(msg);
					$("a.greybox").click(function(){
						var t = this.title || $(this).text() || this.href;
						GB_show(t,this.href,490,"80%");
						return false;
					});
				}
			});
		} else if (tab_actual=='tabs-5') {
			load_sensors_tree();
		} else if (tab_actual=='tabs-6') {
			$('#targets').html(reloading);
			$.ajax({
				type: "GET",
				url: "getpolicydata.php?tab=targets",
				data: "",
				success: function(msg) {
					$('#targets').html(msg);
				}
			});
		} else if (tab_actual=='tabs-7') {
			$('#groups').html(reloading);
			$.ajax({
				type: "GET",
				url: "getpolicydata.php?tab=groups",
				data: "",
				success: function(msg) {
					$('#groups').html(msg);
				}
			});
		} else if (tab_actual=='tabs-8') {
			$('#responses').html(reloading);
			$.ajax({
				type: "GET",
				url: "getpolicydata.php?tab=responses",
				data: "",
				success: function(msg) {
					$('#responses').html(msg);
				}
			});
		}
	}
	$(document).ready(function(){
		// Tabs
		$('#tabs').tabs({
			select: function(event, ui) { 
				tab_actual = ui.panel.id
				// default loading tree for source /dest
				if (tab_actual=='tabs-1' || tab_actual=='tabs-2') {
					suf = (tab_actual=='tabs-1') ? 'c' : 'd';
					load_tree($('filter'+suf).val());
				}
				// default load tree for ports
				if (tab_actual=='tabs-3') load_ports_tree();
				// default load tree for sensors
				if (tab_actual=='tabs-5') load_sensors_tree();
				drawpolicy();
			}
		});
		// Tree
		load_tree("");
		// graybox
		$("a.greybox").click(function(){
		   var t = this.title || $(this).text() || this.href;
		   GB_show(t,this.href,490,"80%");
		   return false;
		});
		drawpolicy();
	});
	// show/hide some options
	function tsim(val) {
		valsim = val;
		$('#correlate').toggle();
		$('#cross_correlate').toggle();
		$('#store').toggle();
		$('#qualify').toggle();
		if (valsim==0) {
			$('input[name=correlate]')[1].checked = true;
			$('input[name=cross_correlate]')[1].checked = true;
			$('input[name=store]')[1].checked = true;
			$('input[name=qualify]')[1].checked = true;
		}
		if (valsim==0 && valsem==0) {
			$('#ralarms').hide();
			$('#revents').hide();
			$('input[name=resend_alarms]')[1].checked = true;
			$('input[name=resend_events]')[1].checked = true;
		} else {
			$('#ralarms').show();
			$('#revents').show();
		}
	}
	function tsem(val) {
		valsem = val
		$('#sign').toggle();
		if (valsem==0) {
			$('input[name=sign]')[1].checked = true;
		}
		if (valsim==0 && valsem==0) {
			$('#ralarms').hide();
			$('#revents').hide();
			$('input[name=resend_alarms]')[1].checked = true;
			$('input[name=resend_events]')[1].checked = true;
		} else {
			$('#ralarms').show();
			$('#revents').show();
		}
	}
	function submit_form(form) {
		selectall('sources');
		selectall('dests');
		selectall('ports');
		selectall('sensors');
		form.submit();
	}
	function putit(id,txt) {
		if (txt == '') {
			$(id).removeClass('bgred').removeClass('bggreen').addClass('bgred');
			$("#img"+id.substr(3)).attr("src","../pixmaps/tables/cross-small.png");
			$(id).html(txt);
		} else {
			$(id).removeClass('bgred').removeClass('bggreen').addClass('bggreen');
			$("#img"+id.substr(3)).attr("src","../pixmaps/tables/tick-small.png");
			$(id).html(txt);
		}
	}
	function iscomplete() {
		if ($("#imgsource").attr("src").match(/tick/) && $("#imgdest").attr("src").match(/tick/) &&
		$("#imgports").attr("src").match(/tick/) && $("#imgplugins").attr("src").match(/tick/) &&
		$("#imgtime").attr("src").match(/tick/) && $("#imgmore").attr("src").match(/tick/) &&
		$("#imgother").attr("src").match(/tick/))
			return true;
		return false;
	}
	function drawpolicy() {
		var elems = getcombotext('sources');
		for (var i=0,txt = ''; i<elems.length; i++) txt = txt + elems[i] + "<br>";
		putit("#tdsource",txt);
		//
		var elems = getcombotext('dests');
		for (var i=0,txt=''; i<elems.length; i++) txt = txt + elems[i] + "<br>";
		putit("#tddest",txt);
		//
		//var elems = getselectedcombotext('ports');
		var elems = getcombotext('ports');
		for (var i=0,txt=''; i<elems.length; i++) txt = txt + elems[i] + "<br>";
		putit("#tdports",txt);
		//
		txt = '';
		$(':checkbox:checked').each(function(i){ 
			if ($(this).attr('id').match(/^plugin/)) txt = txt + $(this).attr('id').substr(7) + "<br>";
		});
		putit("#tdplugins",txt);
		//var elems = getselectedcombotext('sensors');
		var elems = getcombotext('sensors');
		for (var i=0,txt=''; i<elems.length; i++) txt = txt + elems[i] + "<br>";
		putit("#tdsensors",txt);
		//
		txt = '';
		$(':checkbox:checked').each(function(i){ 
			if ($(this).attr('id').match(/^target/)) txt = txt + $(this).attr('id').substr(7) + "<br>";
		});
		putit("#tdtargets",txt);
		//
		txt = "Begin: <b>" + document.fop.begin_day.options[document.fop.begin_day.selectedIndex].text + " - " + document.fop.begin_hour.options[document.fop.begin_hour.selectedIndex].text + "</b><br>";
		txt = txt + "End: <b>" + document.fop.end_day.options[document.fop.end_day.selectedIndex].text + " - " + document.fop.end_hour.options[document.fop.end_hour.selectedIndex].text + "</b><br>";
		putit("#tdtime",txt);
		//
		txt = "Policy Group: <b> " + document.fop.group.options[document.fop.group.selectedIndex].text + "</b><br>"; 
		txt = txt + "Description: <i> " + document.fop.descr.value + "</i><br>";
		txt = txt + "Active: <b> " + ($("input[name='active']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Sign: <b> " + ($("input[name='sign']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Sem: <b> " + ($("input[name='sem']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Sim: <b> " + ($("input[name='sim']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		putit("#tdmore",txt);
		//
		txt = "Priority: <b>" + document.fop.priority.options[document.fop.priority.selectedIndex].text + "</b><br>";
		txt = txt + "Correlate: <b> " + ($("input[name='correlate']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Cross Correlate: <b> " + ($("input[name='cross_correlate']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Store: <b> " + ($("input[name='store']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Qualify: <b> " + ($("input[name='qualify']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Resend Alarms: <b> " + ($("input[name='resend_alarms']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		txt = txt + "Resend Events: <b> " + ($("input[name='resend_events']:checked").val()==1 ? "Yes" : "No") + "</b><br>";
		putit("#tdother",txt);
		//
		if (iscomplete()) {
			$('input[type="button"].sok').removeAttr("disabled");
			$('input[type="button"].sok').removeClass('btnoff').addClass('btn');
		} else {
			$('input[type="button"].sok').attr("disabled", "disabled");
			$('input[type="button"].sok').removeClass('btn').addClass('btnoff');
		}
	}
  </script>
</head>
<body>
                                                                                
<?php
include ("../hmenu.php");
require_once ('classes/Policy.inc');
require_once ('classes/Policy_group.inc');
require_once ('classes/Host.inc');
require_once ('classes/Host_group.inc');
require_once ('classes/Net.inc');
require_once ('classes/Net_group.inc');
require_once ('classes/Port_group.inc');
require_once ('classes/Plugingroup.inc');
require_once ('classes/Server.inc');
require_once ('classes/Action.inc');
require_once ('classes/Response.inc');
require_once ('ossim_db.inc');
$db = new ossim_db();
$conn = $db->connect();
$id = GET('id');
$group = GET('group');
$order = GET('order');
$insert = (GET('insertafter') != "") ? GET('insertafter') : GET('insertbefore');
ossim_valid($id, OSS_ALPHA, OSS_SPACE, OSS_PUNC, OSS_NULLABLE, 'illegal:' . _("id"));
ossim_valid($group, OSS_DIGIT, OSS_NULLABLE, 'illegal:' . _("group"));
ossim_valid($order, OSS_DIGIT, OSS_NULLABLE, 'illegal:' . _("order"));
if (ossim_error()) {
    die(ossim_error());
}
// product version check
require_once "ossim_conf.inc";
$conf = $GLOBALS["CONF"];
$version = $conf->get_conf("ossim_server_version", FALSE);
$opensource = (!preg_match("/.*pro.*/i",$version) && !preg_match("/.*demo.*/i",$version)) ? true : false;
//
$ossim_hosts = array();
$ossim_nets = array();
if ($host_list = Host::get_list($conn, "", "ORDER BY hostname")) foreach($host_list as $host) $ossim_hosts[$host->get_ip() ] = $host->get_hostname();
if ($net_list = Net::get_list($conn, "ORDER BY name")) {
    foreach($net_list as $net) {
        $net_name = $net->get_name();
        $net_ips = $net->get_ips();
        $hostin = array();
        foreach($ossim_hosts as $ip => $hname) if ($net->isIpInNet($ip, $net_ips)) $hostin[$ip] = $hname;
        $ossim_nets[$net_name] = $hostin;
    }
}
// default vars
$priority = 2;
$correlate = 1;
$cross_correlate = 1;
$store = 1;
$qualify = 1;
$active = 1;
$order = 0;
$resend_alarm = 1;
$resend_event = 1;
$sign = 0;
$sem = 0;
$sim = 1;
if ($group == "") $group = 1;
$desc = "";
$sources = $dests = $ports = $plugingroups = $sensors = $targets = $actions = array();
$timearr = array(
    1,
    0,
    7,
    23
);
if ($id != "") {
    settype($id, "int");
    if ($policies = Policy::get_list($conn, "WHERE policy.order=$id")) {
        $policy = $policies[0];
        $id = $policy->get_id();
        $priority = $policy->get_priority();
        $active = $policy->get_active();
        $group = $policy->get_group();
        $order = $policy->get_order();
        if ($source_host_list = $policy->get_hosts($conn, 'source')) foreach($source_host_list as $source_host) {
            //$host = Host::ip2hostname($conn, $source_host->get_host_ip());
            $sources[] = ($host == "any") ? "ANY" : "HOST:" . $source_host->get_host_ip();
        }
        if ($source_net_list = $policy->get_nets($conn, 'source')) foreach($source_net_list as $source_net) {
            $sources[] = "NETWORK:" . $source_net->get_net_name();
        }
        if ($source_host_list = $policy->get_host_groups($conn, 'source')) foreach($source_host_list as $source_host_group) {
            $sources[] = "HOST_GROUP:" . $source_host_group->get_host_group_name();
        }
        if ($source_net_list = $policy->get_net_groups($conn, 'source')) foreach($source_net_list as $source_net_group) {
            $sources[] = "NETWORK_GROUP:" . $source_net_group->get_net_group_name();
        }
        //
        if ($dest_host_list = $policy->get_hosts($conn, 'dest')) foreach($dest_host_list as $dest_host) {
            //$host = Host::ip2hostname($conn, $dest_host->get_host_ip());
            $dests[] = ($host == "any") ? "ANY" : "HOST:" . $dest_host->get_host_ip();
        }
        if ($dest_net_list = $policy->get_nets($conn, 'dest')) foreach($dest_net_list as $dest_net) {
            $dests[] = "NETWORK:" . $dest_net->get_net_name();
        }
        if ($dest_host_list = $policy->get_host_groups($conn, 'dest')) foreach($dest_host_list as $dest_host_group) {
            $dests[] = "HOST_GROUP:" . $dest_host_group->get_host_group_name();
        }
        if ($dest_net_list = $policy->get_net_groups($conn, 'dest')) foreach($dest_net_list as $dest_net_group) {
            $dests[] = "NETWORK_GROUP:" . $dest_net_group->get_net_group_name();
        }
        //
        if ($port_list = $policy->get_ports($conn)) foreach($port_list as $port_group) {
            $ports[] = $port_group->get_port_group_name();
        }
        foreach($policy->get_plugingroups($conn, $policy->get_id()) as $pgroup) {
            $plugingroups[] = $pgroup['id'];
        }
        if ($sensor_list = $policy->get_sensors($conn)) foreach($sensor_list as $sensor) {
            $sensors[] = $sensor->get_sensor_name();
        }
        $policy_time = $policy->get_time($conn);
        $timearr[0] = $policy_time->get_begin_day();
        $timearr[1] = $policy_time->get_begin_hour();
        $timearr[2] = $policy_time->get_end_day();
        $timearr[3] = $policy_time->get_end_hour();
        if ($target_list = $policy->get_targets($conn)) foreach($target_list as $target) {
            $targets[] = $target->get_target_name();
        }
        $desc = html_entity_decode($policy->get_descr());
        $role_list = $policy->get_role($conn);
        foreach($role_list as $role) {
            $correlate = ($role->get_correlate()) ? 1 : 0;
            $cross_correlate = ($role->get_cross_correlate()) ? 1 : 0;
            $store = ($role->get_store()) ? 1 : 0;
            $qualify = ($role->get_qualify()) ? 1 : 0;
            $resend_alarm = ($role->get_resend_alarm()) ? 1 : 0;
            $resend_event = ($role->get_resend_event()) ? 1 : 0;
            $sign = ($role->get_sign()) ? 1 : 0;
            $sem = ($role->get_sem()) ? 1 : 0;
            $sim = ($role->get_sim()) ? 1 : 0;
            break;
        }
        // responses
        if ($response_list = Response::get_list($conn, "WHERE descr='policy $id'")) {
            if ($action_list = $response_list[0]->get_actions($conn)) {
                foreach($action_list as $act) $actions[] = $act->get_action_id();
            }
        }
    }
} else {
    $ports[] = "ANY";
    $targets[] = "any";
    $sensors[] = "any";
}
if ($insert != "") {
    settype($insert, "int");
    if ($policies = Policy::get_list($conn, "WHERE policy.order=$insert")) {
        $order = $policies[0]->get_order();
        $group = $policies[0]->get_group();
        if (GET('insertafter') != "") $order++; // insert after
        
    }
}
?>

<form method="post" name="fop" action="<?php echo ($id != "") ? "modifypolicy.php?id=$id" : "newpolicy.php" ?>">
<input type="hidden" name="insert" value="insert">
<input type="hidden" name="order" value="<?php echo $order ?>">

<div id="elem_list" style="display:none"></div>
<div id="port_list" style="display:none"></div>
<div id="sensor_list" style="display:none"></div>

<div id="tabs">
<ul>
	<li><a href="#tabs-1"><?php echo _("Source") . required() ?></a></li>
	<li><a href="#tabs-2"><?php echo _("Dest") . required() ?></a></li>
	<li><a href="#tabs-3"><?php echo _("Ports") . required() ?></a></li>
	<li><a href="#tabs-4"><?php echo _("Plugin Groups") . required() ?></a></li>
	<li><a href="#tabs-5"><?php echo _("Sensors") . required() ?></a></li>
	<li><a href="#tabs-6"><?php echo _("Install in") . required() ?></a></li>
	<li><a href="#tabs-7"><?php echo _("Policy group") . required() ?></a></li>
	<li><a href="#tabs-8"><?php echo _("Actions") ?></a></li>
	<li><a href="#tabs-9"><?php echo _("Policy Behaviour") ?></a></li>
</ul>

<div id="tabs-1">
  <table align="center"><tr><td class="nobborder" valign="top">
	  <table align="center" class="noborder">
	  <tr>
	    <th><?php echo _("Source") . required() ?><br/>
	        <font size="-2">
	          <a href="../net/newnetform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new net?") ?></a>
	        </font><br/>
	        <font size="-2">
	          <a href="../net/newnetgroupform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new net group?") ?></a>
	        </font><br/>
	        <font size="-2">
	          <a href="../host/newhostform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new host?") ?></a>
	        </font><br/>
	        <font size="-2">
	          <a href="../host/newhostgroupform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new host group?") ?></a>
	        </font><br/>
	    </th>
	    <td class="left nobborder">
			<select id="sources" name="sources[]" size="21" multiple="multiple" style="width:250px">
			<?php
foreach($sources as $source) echo "<option value='$source'>$source"; ?>
			</select>
			<input type="button" value=" [X] " onclick="deletefrom('sources');drawpolicy()" class="btn">
	    </td>
	  </tr>
	  </table>
  </td>
  <td class="left nobborder" valign="top">
		Filter: <input type="text" id="filterc" name="filterc" size=20>&nbsp;<input type="button" value="Apply" onclick="load_tree(this.form.filterc.value)" class="btn">
		<div id="containerc" style="width:350px"></div>
  </td>
  </tr>
  </table>
  <center style="padding-top:10px">
    <input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',1);" class="btn" style="font-size:16px">
    <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  </center>
</div>

<div id="tabs-2">
  <table align="center"><tr><td class="nobborder" valign="top">
	  <table align="center" class="noborder">
	  <tr>
	    <th><?php echo _("Dest") . required() ?><br/>
			<font size="-2">
			  <a href="../net/newnetform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new net?") ?></a>
			</font><br/>
			<font size="-2">
			  <a href="../net/newnetgroupform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new net group?") ?></a>
			</font><br/>
			<font size="-2">
			  <a href="../host/newhostform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new host?") ?></a>
			</font><br/>
			<font size="-2">
			  <a href="../host/newhostgroupform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new host group?") ?></a>
			</font><br/>
	    </th>
	    <td class="left nobborder" valign="top">
			<select id="dests" name="dests[]" size="21" multiple="multiple" style="width:250px">
			<?php
foreach($dests as $dest) echo "<option value='$dest'>$dest"; ?>
			</select>
			<input type="button" value=" [X] " onclick="deletefrom('dests');drawpolicy()" class="btn">
	    </td>
	  </tr>
	  </table>
  </td>
  <td class="left nobborder" valign="top">
		Filter: <input type="text" id="filterd" name="filterd" size=20>&nbsp;<input type="button" value="Apply" onclick="load_tree(this.form.filterd.value)" class="btn">
		<div id="containerd" style="width:350px"></div>
  </td>
  </tr>
  </table>
  <center style="padding-top:10px">
    <input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',2);" class="btn" style="font-size:16px">
    <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  </center>
</div>

<div id="tabs-3">
<table align="center">
  <tr>
    <th><?php echo _("Ports") . required() ?><br/>
        <font size="-2">
          <a href="../port/newportform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new port group?") ?></a>
        </font><br/>
    </th>
    <td class="left nobborder" valign="top">
		<select id="ports" name="mboxp[]" size="20" multiple="multiple" class="multi" style="width:200px">
		<?php
foreach($ports as $pgrp) echo "<option value='$pgrp'>$pgrp"; ?>
		</select>
		<input type="button" value=" [X] " onclick="deletefrom('ports');drawpolicy()" class="btn">
    </td>
    <td class="left nobborder" valign="top">
		<div id="containerp" style="width:350px"></div>
    </td>
    </tr>
  </table>
  <center style="padding-top:10px">
    <input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',3);" class="btn" style="font-size:16px">
    <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  </center>
</div>

<div id="tabs-4">
<table align="center">
  <tr>
    <th> <?php echo _("Plugin Groups") . required() ?> <br/>
        <font size="-2">
          <a href="../policy/modifyplugingroupsform.php?action=new&withoutmenu=1" class="greybox"> <?php echo gettext("Insert new plugin group"); ?>? </a>
        </font><br/>
        <font size="-2">
          <a href="../policy/plugingroups.php?withoutmenu=1" class="greybox"> <?php echo gettext("View all plugin groups"); ?></a>
        </font><br/>
    </th>
    <td class="left nobborder" valign="top" id="plugins">
<?php
/* ===== plugin groups ==== */
foreach(Plugingroup::get_list($conn) as $g) {
?>
    <input type="checkbox" id="plugin_<?php echo $g->get_name() ?>" onclick="drawpolicy()" name="plugins[<?php echo $g->get_id() ?>]" <?php echo (in_array($g->get_id() , $plugingroups)) ? "checked='checked'" : "" ?>> <a href="../policy/modifyplugingroupsform.php?action=edit&id=<?php echo $g->get_id() ?>&withoutmenu=1" class="greybox" title="View plugin group"><?php echo $g->get_name() ?></a><br/>
<?php
} ?>

    </td>
  </tr>
  </table>
  <center style="padding-top:10px">
    <input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',4);" class="btn" style="font-size:16px">
    <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  </center>
</div>

<div id="tabs-5">
<table align="center">
  <tr>
    <th><?php echo _("Sensors") . required() ?><br/>
        <font size="-2">
          <a href="../sensor/newsensorform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new sensor?") ?></a>
        </font><br/>
    </th>
    <td class="left nobborder" valign="top">
		<select id="sensors" name="mboxs[]" size="20" multiple="multiple" class="multi" style="width:200px">
		<?php
foreach($sensors as $sensor) echo "<option value='$sensor'>$sensor"; ?>
		</select>
		<input type="button" value=" [X] " onclick="deletefrom('sensors');drawpolicy()" class="btn">
    </td>
    <td class="left nobborder" valign="top">
		<div id="containerse" style="width:350px"></div>
    </td>
    </tr>
  </table>
  <center style="padding-top:10px">
    <input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',5);" class="btn" style="font-size:16px">
    <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  </center>
</div>

<div id="tabs-6">
<table align="center">
  <tr>
    <th><?php echo _("Install in") . required() ?><br/>
        <font size="-2">
          <a href="../server/newserverform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new server?") ?></a>
        </font><br/>
    </th>
    <td class="left nobborder" valign="top" id="targets">
<?php
/* ===== target sensors ====
$i = 1;
if ($sensor_list = Sensor::get_list($conn, "ORDER BY name")) {
foreach($sensor_list as $sensor) {
$sensor_name = $sensor->get_name();
$sensor_ip =   $sensor->get_ip();
if ($i == 1) {
?>
<input type="hidden" name="<?= "targetsensor"; ?>"
value="<?= count($sensor_list); ?>">
<?php
}
$name = "targboxsensor" . $i;
?>
<input type="checkbox"  id="target_<?= $sensor_ip . " (" . $sensor_name . ")"?>" name="<?= $name;?>"
value="<?= $sensor_name; ?>" <?= (in_array($sensor_name,$sensors)) ? "checked='checked'" : "" ?>>
<?= $sensor_ip . " (" . $sensor_name . ")<br>";?>
</input>
<?php
$i++;
}
}*/
?>
<?php
/* ===== target servers ==== */
$i = 1;
if ($server_list = Server::get_list($conn, "ORDER BY name")) {
    foreach($server_list as $server) {
        $server_name = $server->get_name();
        $server_ip = $server->get_ip();
        if ($i == 1) {
?>
        <input type="hidden" name="<?php echo "targetserver"; ?>"
            value="<?php echo count($server_list); ?>">
<?php
        }
        $name = "targboxserver" . $i;
?>
        <input type="checkbox" id="target_<?php echo $server_ip . " (" . $server_name . ")" ?>" name="<?php echo $name; ?>"
            value="<?php echo $server_name; ?>" <?php echo (in_array($server_name, $targets)) ? "checked='checked'" : "" ?>>
            <?php echo $server_ip . " (" . $server_name . ")<br>"; ?>
        </input>
<?php
        $i++;
    }
}
/* == ANY target == */
?>
    <input type="checkbox" id="target_ANY" name="target_any" value="any" <?php echo (in_array("any", $targets)) ? "checked='checked'" : "" ?>>&nbsp;<b><?php echo _("ANY") ?></b><br></input>
  </td>
  </tr>
  </table>
  <center style="padding-top:10px">
    <input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',6);" class="btn" style="font-size:16px">
    <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  </center>
</div>

<div id="tabs-7">
<table align="center">
  <tr>
    <th><?php echo _("Policy group") . required() ?><br/>
        <font size="-2">
          <a href="newpolicygroupform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new policy group?") ?></a>
        </font><br/>
    </th>
    <td class="left nobborder" valign="top">
		<select name="group" size="20" class="multi" style="width:200px" id="groups">
		<?php
$policygroups = Policy_group::get_list($conn, "ORDER BY name");
foreach($policygroups as $policygrp) {
    $sel = ($policygrp->get_group_id() == $group) ? " selected" : "";
?>
		<option value="<?php echo $policygrp->get_group_id() ?>" <?php echo $sel ?>> <?php echo $policygrp->get_name() ?>
		<?php
} ?>
		</select>
	</td>
  </tr> 
  </table>
  <center style="padding-top:10px"><input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',7);" class="btn" style="font-size:16px"></center>
</div>

<div id="tabs-8">
<table align="center">
  <tr>
    <th><?php echo _("Actions") ?><br/>
        <font size="-2">
          <a href="../action/newactionform.php?withoutmenu=1" class="greybox"><?php echo _("Insert new action?") ?></a>
        </font><br/>
    </th>
    <td class="left nobborder" valign="top">
		<select name="actions[]" size="20" class="multi" style="width:200px" id="responses">
		<?php
if ($action_list = Action::get_list($conn)) {
    foreach($action_list as $act) { ?>
			<option value="<?php echo $act->get_id() ?>" <?php echo (in_array($act->get_id() , $actions)) ? "selected" : "" ?>> <?php echo $act->get_descr() ?>
		<?php
    }
}
?>
		</select>
	</td>
  </tr> 
  </table>
  <center style="padding-top:10px">
    <input type="button" value=" Next >> " onclick="$('#tabs').tabs('select',8);" class="btn" style="font-size:16px">
    <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  </center>
</div>

<div id="tabs-9">
<table align="center">
  <tr>
    <th><?php echo _("Priority") . required() ?></th>
    <td class="left">
      <select name="priority">
        <option <?php echo ($priority == - 1) ? "selected" : "" ?> value="-1"><?php echo _("Do not change"); ?></option>
        <option <?php echo ($priority == 0) ? "selected" : "" ?> value="0">0</option>
        <option <?php echo ($priority == 1) ? "selected" : "" ?> value="1">1</option>
        <option <?php echo ($priority == 2) ? "selected" : "" ?> value="2">2</option>
        <option <?php echo ($priority == 3) ? "selected" : "" ?> value="3">3</option>
        <option <?php echo ($priority == 4) ? "selected" : "" ?> value="4">4</option>
        <option <?php echo ($priority == 5) ? "selected" : "" ?> value="5">5</option>
      </select>
    </td>
  </tr>
  <tr>
    <th><?php echo _("Time Range") . required() ?></th>
    <td>
      <table>
        <tr>
          <td><?php echo _("Begin") ?></td><td></td><td><?php echo _("End") ?></td>
        </tr>
        <tr>
          <td>
            <select name="begin_day">
              <option <?php echo ($timearr[0] == 1) ? "selected" : "" ?> value="1"><?php echo _("Mon"); ?></option>
              <option <?php echo ($timearr[0] == 2) ? "selected" : "" ?> value="2"><?php echo _("Tue"); ?></option>
              <option <?php echo ($timearr[0] == 3) ? "selected" : "" ?> value="3"><?php echo _("Wed"); ?></option>
              <option <?php echo ($timearr[0] == 4) ? "selected" : "" ?> value="4"><?php echo _("Thu"); ?></option>
              <option <?php echo ($timearr[0] == 5) ? "selected" : "" ?> value="5"><?php echo _("Fri"); ?></option>
              <option <?php echo ($timearr[0] == 6) ? "selected" : "" ?> value="6"><?php echo _("Sat"); ?></option>
              <option <?php echo ($timearr[0] == 7) ? "selected" : "" ?> value="7"><?php echo _("Sun"); ?></option>
            </select>
            <select name="begin_hour">
              <option <?php echo ($timearr[1] == 0) ? "selected" : "" ?> value="0">0h</option>
              <option <?php echo ($timearr[1] == 1) ? "selected" : "" ?> value="1">1h</option>
              <option <?php echo ($timearr[1] == 2) ? "selected" : "" ?> value="2">2h</option>
              <option <?php echo ($timearr[1] == 3) ? "selected" : "" ?> value="3">3h</option>
              <option <?php echo ($timearr[1] == 4) ? "selected" : "" ?> value="4">4h</option>
              <option <?php echo ($timearr[1] == 5) ? "selected" : "" ?> value="5">5h</option>
              <option <?php echo ($timearr[1] == 6) ? "selected" : "" ?> value="6">6h</option>
              <option <?php echo ($timearr[1] == 7) ? "selected" : "" ?> value="7">7h</option>
              <option <?php echo ($timearr[1] == 8) ? "selected" : "" ?> value="8">8h</option>
              <option <?php echo ($timearr[1] == 9) ? "selected" : "" ?> value="9">9h</option>
              <option <?php echo ($timearr[1] == 10) ? "selected" : "" ?> value="10">10h</option>
              <option <?php echo ($timearr[1] == 11) ? "selected" : "" ?> value="11">11h</option>
              <option <?php echo ($timearr[1] == 12) ? "selected" : "" ?> value="12">12h</option>
              <option <?php echo ($timearr[1] == 13) ? "selected" : "" ?> value="13">13h</option>
              <option <?php echo ($timearr[1] == 14) ? "selected" : "" ?> value="14">14h</option>
              <option <?php echo ($timearr[1] == 15) ? "selected" : "" ?> value="15">15h</option>
              <option <?php echo ($timearr[1] == 16) ? "selected" : "" ?> value="16">16h</option>
              <option <?php echo ($timearr[1] == 17) ? "selected" : "" ?> value="17">17h</option>
              <option <?php echo ($timearr[1] == 18) ? "selected" : "" ?> value="18">18h</option>
              <option <?php echo ($timearr[1] == 19) ? "selected" : "" ?> value="19">19h</option>
              <option <?php echo ($timearr[1] == 20) ? "selected" : "" ?> value="20">20h</option>
              <option <?php echo ($timearr[1] == 21) ? "selected" : "" ?> value="21">21h</option>
              <option <?php echo ($timearr[1] == 22) ? "selected" : "" ?> value="22">22h</option>
              <option <?php echo ($timearr[1] == 23) ? "selected" : "" ?> value="23">23h</option>
            </select>
          </td>
          <td>-</td>
          <td>
            <select name="end_day">
              <option <?php echo ($timearr[2] == 1) ? "selected" : "" ?> value="1"><?php echo _("Mon"); ?></option>
              <option <?php echo ($timearr[2] == 2) ? "selected" : "" ?> value="2"><?php echo _("Tue"); ?></option>
              <option <?php echo ($timearr[2] == 3) ? "selected" : "" ?> value="3"><?php echo _("Wed"); ?></option>
              <option <?php echo ($timearr[2] == 4) ? "selected" : "" ?> value="4"><?php echo _("Thu"); ?></option>
              <option <?php echo ($timearr[2] == 5) ? "selected" : "" ?> value="5"><?php echo _("Fri"); ?></option>
              <option <?php echo ($timearr[2] == 6) ? "selected" : "" ?> value="6"><?php echo _("Sat"); ?></option>
              <option <?php echo ($timearr[2] == 7) ? "selected" : "" ?> value="7"><?php echo _("Sun"); ?></option>
            </select>
            <select name="end_hour">
              <option <?php echo ($timearr[3] == 0) ? "selected" : "" ?> value="0">0h</option>
              <option <?php echo ($timearr[3] == 1) ? "selected" : "" ?> value="1">1h</option>
              <option <?php echo ($timearr[3] == 2) ? "selected" : "" ?> value="2">2h</option>
              <option <?php echo ($timearr[3] == 3) ? "selected" : "" ?> value="3">3h</option>
              <option <?php echo ($timearr[3] == 4) ? "selected" : "" ?> value="4">4h</option>
              <option <?php echo ($timearr[3] == 5) ? "selected" : "" ?> value="5">5h</option>
              <option <?php echo ($timearr[3] == 6) ? "selected" : "" ?> value="6">6h</option>
              <option <?php echo ($timearr[3] == 7) ? "selected" : "" ?> value="7">7h</option>
              <option <?php echo ($timearr[3] == 8) ? "selected" : "" ?> value="8">8h</option>
              <option <?php echo ($timearr[3] == 9) ? "selected" : "" ?> value="9">9h</option>
              <option <?php echo ($timearr[3] == 10) ? "selected" : "" ?> value="10">10h</option>
              <option <?php echo ($timearr[3] == 11) ? "selected" : "" ?> value="11">11h</option>
              <option <?php echo ($timearr[3] == 12) ? "selected" : "" ?> value="12">12h</option>
              <option <?php echo ($timearr[3] == 13) ? "selected" : "" ?> value="13">13h</option>
              <option <?php echo ($timearr[3] == 14) ? "selected" : "" ?> value="14">14h</option>
              <option <?php echo ($timearr[3] == 15) ? "selected" : "" ?> value="15">15h</option>
              <option <?php echo ($timearr[3] == 16) ? "selected" : "" ?> value="16">16h</option>
              <option <?php echo ($timearr[3] == 17) ? "selected" : "" ?> value="17">17h</option>
              <option <?php echo ($timearr[3] == 18) ? "selected" : "" ?> value="18">18h</option>
              <option <?php echo ($timearr[3] == 19) ? "selected" : "" ?> value="19">19h</option>
              <option <?php echo ($timearr[3] == 20) ? "selected" : "" ?> value="20">20h</option>
              <option <?php echo ($timearr[3] == 21) ? "selected" : "" ?> value="21">21h</option>
              <option <?php echo ($timearr[3] == 22) ? "selected" : "" ?> value="22">22h</option>
              <option <?php echo ($timearr[3] == 23) ? "selected" : "" ?> value="23">23h</option>
            </select>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr id="correlate" <?php
if ($sim == 0) echo "style='display:none'" ?>>
    <th> <?php echo _("Correlate events") . required() ?> </th>
    <td class="left">
    <input type="radio" name="correlate" value="1" <?php echo ($correlate == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="correlate" value="0" <?php echo ($correlate == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?> <small>1)</small>
    </td>
  </tr>
  <tr id="cross_correlate" <?php
if ($sim == 0) echo "style='display:none'" ?>>
    <th> <?php echo _("Cross Correlate events") . required() ?> </th>
    <td class="left">
    <input type="radio" name="cross_correlate" value="1" <?php echo ($cross_correlate == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="cross_correlate" value="0" <?php echo ($cross_correlate == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?> <small>1)</small>
    </td>
  </tr>
  <tr id="store" <?php
if ($sim == 0) echo "style='display:none'" ?>>
    <th> <?php echo _("Store events") . required() ?> </th>
    <td class="left">
    <input type="radio" name="store" value="1" <?php echo ($store == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="store" value="0" <?php echo ($store == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?> <small>1)</small>
    </td>
  </tr>
  <tr id="qualify" <?php
if ($sim == 0) echo "style='display:none'" ?>>
    <th> <?php echo _("Qualify events") . required() ?> </th>
    <td class="left">
    <input type="radio" name="qualify" value="1" <?php echo ($qualify == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="qualify" value="0" <?php echo ($qualify == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?>
    </td>
  </tr>
  <tr id="ralarms" <?php echo ($sim == 0 && $sem == 0) ? "style='display:none'" : "style='display:;'" ?>>
    <th> <?php echo _("Resend alarms") . required() ?> </th>
    <td class="left">
    <input type="radio" name="resend_alarms" value="1" <?php echo ($resend_alarms == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="resend_alarms" value="0" <?php echo ($resend_alarms == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?>
    </td>
  </tr>
  <tr id="revents" <?php echo ($sim == 0 && $sem == 0) ? "style='display:none'" : "style='display:;'" ?>>
    <th> <?php echo _("Resend events") . required() ?> </th>
    <td class="left">
    <input type="radio" name="resend_events" value="1" <?php echo ($resend_events == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="resend_events" value="0" <?php echo ($resend_events == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?>
    </td>
  </tr>
  <tr id="sign" <?php 
if ($sem == 0) echo "style='display:none'" ?>>
    <th> <?php echo _("Sign") . required() ?> </th>
    <td class="left">
    <input type="radio" name="sign" value="1" <?php echo ($sign == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="sign" value="0" <?php echo ($sign == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?>
    </td>
  </tr>
  <tr>
    <th> <?php echo _("SEM") . required() ?> </th>
    <td class="left" <?= ($opensource) ? "style='color:gray'" : "" ?>>
    <input type="radio" name="sem" onclick="tsem(1)" value="1" <?php echo ($sem == 1) ? "checked='checked'" : "" ?> <?= ($opensource) ? "disabled='disabled'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="sem" onclick="tsem(0)" value="0" <?php echo ($sem == 0) ? "checked='checked'" : "" ?> <?= ($opensource) ? "disabled='disabled'" : "" ?>> <?php echo _("No"); ?>
    <?= ($opensource) ? "&nbsp;<a href='../sem' style='size:11px;color:gray'>\"Only available in Professional SIEM\"</a>" : "" ?> </td>
  </tr>
  <tr>
    <th> <?php echo _("SIM") . required() ?> </th>
    <td class="left">
    <input type="radio" name="sim" onclick="tsim(1)" value="1" <?php echo ($sim == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="sim" onclick="tsim(0)" value="0" <?php echo ($sim == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?>
    </td>
  </tr>
<tr>
<script>
	var valsim = <?php echo $sim ?>;
	var valsem = <?php echo $sem ?>;
</script>
<td colspan="2" class="left">
1) <?php echo _("Does not apply to targets without associated database.") ?> <?php echo _("Implicit value is always No for them."); ?>
</td>
</tr>
  <tr>
    <th><?php echo _("Description") . required() ?></th>
    <td class="left" class="nobborder">
        <textarea name="descr" rows="2" cols="40"><?php echo $desc ?></textarea>
    </td>
  </tr>
  <tr>
    <th> <?php echo _("Active") . required() ?> </th>
    <td class="left">
    <input type="radio" name="active" value="1" <?php echo ($active == 1) ? "checked='checked'" : "" ?>> <?php echo _("Yes"); ?>
    <input type="radio" name="active" value="0" <?php echo ($active == 0) ? "checked='checked'" : "" ?>> <?php echo _("No"); ?>
    </td>
  </tr>

<?php
$db->close($conn);
?>
</table>
<center style="padding-top:10px">
  <input type="button" value="OK" class="btn sok" onclick="submit_form(this.form)" style="font-size:16px">
  <input type="reset" value="<?php echo gettext('Reset'); ?>" onclick="drawpolicy()" class="btn" style="font-size:16px">
</center>
</div>
</form>

<table width="100%">
<th nowrap><?php echo _("Source") ?> <img src="../pixmaps/tables/cross-small.png" id="imgsource" align="absmiddle"></th>
<th nowrap><?php echo _("Dest") ?> <img src="../pixmaps/tables/cross-small.png" id="imgdest" align="absmiddle"></th>
<th nowrap><?php echo _("Ports") ?> <img src="../pixmaps/tables/cross-small.png" id="imgports" align="absmiddle"></th>
<th nowrap><?php echo _("Plugin Groups") ?> <img src="../pixmaps/tables/cross-small.png" id="imgplugins" align="absmiddle"></th>
<th nowrap><?php echo _("Sensors") ?> <img src="../pixmaps/tables/cross-small.png" id="imgsensors" align="absmiddle"></th>
<th nowrap><?php echo _("Targets") ?> <img src="../pixmaps/tables/cross-small.png" id="imgtargets" align="absmiddle"></th>
<th nowrap><?php echo _("Time Range") ?> <img src="../pixmaps/tables/cross-small.png" id="imgtime" align="absmiddle"></th>
<th nowrap><?php echo _("Description") ?> <img src="../pixmaps/tables/cross-small.png" id="imgmore" align="absmiddle"></th>
<th nowrap><?php echo _("Policy Actions") ?> <img src="../pixmaps/tables/cross-small.png" id="imgother" align="absmiddle"></th>
<tr>
<td id="tdsource" class="small"></td>
<td id="tddest" class="small"></td>
<td id="tdports" class="small"></td>
<td id="tdplugins" class="small"></td>
<td id="tdsensors" class="small"></td>
<td id="tdtargets" class="small"></td>
<td id="tdtime" class="small"></td>
<td id="tdmore" class="small"></td>
<td id="tdother" class="small" nowrap></td>
</tr>
</table>

</body>
</html>

