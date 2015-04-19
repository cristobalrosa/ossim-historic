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
* - valid_value()
* - submit()
* Classes list:
*/
require_once 'classes/Session.inc';
Session::logcheck("MenuConfiguration", "ConfigurationMain");
require_once 'ossim_conf.inc';
require_once 'classes/Security.inc';
$ossim_conf = $GLOBALS["CONF"];
$CONFIG = array(
    "Language" => array(
        "title" => gettext("Language") ,
        "desc" => gettext("Configure Internationalization") ,
        "advanced" => 1,
        "conf" => array(
            "language" => array(
                "type" => array(
                    "de_DE" => gettext("German") ,
                    "en_GB" => gettext("English") ,
                    "es_ES" => gettext("Spanish") ,
                    "fr_FR" => gettext("French") ,
                    "ja_JP" => gettext("Japanese") ,
                    "pt_BR" => gettext("Brazilian Portuguese") ,
                    "zh_CN" => gettext("Simplified Chinese") ,
                    "zh_TW" => gettext("Traditional Chinese") ,
                    "ru_RU.UTF-8" => gettext("Russian")
                ) ,
                "help" => gettext("Obsolete, configure at Configuration -> Users") ,
                "desc" => gettext("Language") ,
                "advanced" => 1
            ) ,
            "locale_dir" => array(
                "type" => "text",
                "help" => gettext("Location of the ossim.mo localization files. You shouldn't need to change this.") ,
                "desc" => gettext("Locale File Directory") ,
                "advanced" => 1
            )
        )
    ) ,
    "Ossim Server" => array(
        "title" => gettext("Ossim Server") ,
        "desc" => gettext("Configure the server's listening address") ,
        "advanced" => 1,
        "conf" => array(
            "server_address" => array(
                "type" => "text",
                "help" => gettext("Server IP") ,
                "desc" => gettext("Server Address (it's usually 127.0.0.1)") ,
                "advanced" => 1
            ) ,
            "server_port" => array(
                "type" => "text",
                "help" => gettext("Port number") ,
                "desc" => gettext("Server Port (default:40001)") ,
                "advanced" => 1
            )
        )
    ) ,
    "Ossim Framework" => array(
        "title" => gettext("Ossim Framework") ,
        "desc" => gettext("PHP Configuration (graphs, acls, database api) and links to other applications") ,
        "advanced" => 1,
        "conf" => array(
            "ossim_link" => array(
                "type" => "text",
                "help" => gettext("Ossim web link. Usually located under /ossim/") ,
                "desc" => gettext("Ossim Link") ,
                "advanced" => 1
            ) ,
            "adodb_path" => array(
                "type" => "text",
                "help" => gettext("ADODB Library path. PHP database extraction library.") ,
                "desc" => gettext("ADODB Path") ,
                "advanced" => 1
            ) ,
            "jpgraph_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("JPGraph Path") ,
                "advanced" => 1
            ) ,
            "fpdf_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("FreePDF Path") ,
                "advanced" => 1
            ) ,
            "xajax_php_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("XAJAX PHP Path") ,
                "advanced" => 1
            ) ,
            "xajax_js_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("XAJAX JS Path") ,
                "advanced" => 1
            ) ,
            "report_graph_type" => array(
                "type" => array(
                    "images" => gettext("Images (php jpgraph)") ,
                    "applets" => gettext("Applets (jfreechart)")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Graph Type") ,
                "advanced" => 1
            ) ,
            "use_svg_graphics" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes (Need SVG plugin)")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Use SVG Graphics") ,
                "advanced" => 1
            ) ,
            "use_resolv" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Resolve IPs") ,
                "advanced" => 1
            ) ,
            "ntop_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Ntop Link") ,
                "advanced" => 1
            ) ,
            "nagios_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Nagios Link") ,
                "advanced" => 1
            ) ,
            "nagios_cfgs" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Nagios Configuration file Path") ,
                "advanced" => 1
            ) ,
            "nagios_reload_cmd" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Nagios reload command") ,
                "advanced" => 1
            ) ,
            "glpi_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("GLPI Link") ,
                "advanced" => 1
            ) ,
            "ocs_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OCS Link") ,
                "advanced" => 1
            ) ,
            "ovcp_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OVCP Link") ,
                "advanced" => 1
            ) ,
            "use_ntop_rewrite" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Apache-rewrite ntop") ,
                "advanced" => 1
            ) ,
            "use_munin" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable Munin") ,
                "advanced" => 1
            ) ,
            "munin_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Munin Link") ,
                "advanced" => 1
            ) ,
            "md5_salt" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("MD5 salt for passwords") ,
                "advanced" => 1
            )
        )
    ) ,
    "Ossim FrameworkD" => array(
        "title" => gettext("Ossim Framework Daemon") ,
        "desc" => gettext("Configure the frameworkd's listening address") ,
        "advanced" => 1,
        "conf" => array(
            "frameworkd_address" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OSSIM Frameworkd") ,
                "advanced" => 1
            ) ,
            "frameworkd_port" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Frameworkd Port") ,
                "advanced" => 1
            ) ,
            "frameworkd_dir" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Frameworkd Directory") ,
                "advanced" => 1
            ) ,
            "frameworkd_controlpanelrrd" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable ControlPanelRRD") ,
                "advanced" => 1
            ) ,
            "frameworkd_acidcache" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable AcidCache") ,
                "advanced" => 1
            ) ,
            "frameworkd_donagios" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable DoNagios") ,
                "advanced" => 1
            ) ,
            "frameworkd_optimizedb" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable DB Optimizations") ,
                "advanced" => 1
            ) ,
            "frameworkd_listener" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable Listener") ,
                "advanced" => 1
            ) ,
            "frameworkd_scheduler" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable Scheduler") ,
                "advanced" => 1
            ) ,
            "frameworkd_soc" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable SOC functionality") ,
                "advanced" => 1
            ) ,
            "frameworkd_businessprocesses" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable BusinesProcesses") ,
                "advanced" => 1
            ) ,
            "frameworkd_eventstats" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable EventStats") ,
                "advanced" => 1
            ) ,
            "frameworkd_backup" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable Backups") ,
                "advanced" => 1
            ) ,
            "frameworkd_alarmgroup" => array(
                "type" => array(
                    "0" => gettext("Disabled") ,
                    "1" => gettext("Enabled")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable Alarm Grouping") ,
                "advanced" => 1
            )
        )
    ) ,
    "Snort" => array(
        "title" => gettext("Snort") ,
        "desc" => gettext("Snort database and path configuration") ,
        "advanced" => 1,
        "conf" => array(
            "snort_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Snort location") ,
                "advanced" => 1
            ) ,
            "snort_rules_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Snort rule location") ,
                "advanced" => 1
            ) ,
            "snort_type" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Snort DB Type") ,
                "advanced" => 1
            ) ,
            "snort_base" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Snort DB Name") ,
                "advanced" => 1
            ) ,
            "snort_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Snort DB User") ,
                "advanced" => 1
            ) ,
            "snort_pass" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("Snort DB Password") ,
                "advanced" => 1
            ) ,
            "snort_host" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Snort DB Host") ,
                "advanced" => 1
            ) ,
            "snort_port" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Snort DB Port") ,
                "advanced" => 1
            )
        )
    ) ,
    "Osvdb" => array(
        "title" => gettext("OSVDB") ,
        "desc" => gettext("Open source vulnerability database configuration") ,
        "advanced" => 1,
        "conf" => array(
            "osvdb_type" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OSVDB DB Type") ,
                "advanced" => 1
            ) ,
            "osvdb_base" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OSVDB DB Name") ,
                "advanced" => 1
            ) ,
            "osvdb_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OSVDB DB User") ,
                "advanced" => 1
            ) ,
            "osvdb_pass" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("OSVDB DB Password") ,
                "advanced" => 1
            ) ,
            "osvdb_host" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OSVDB DB Host") ,
                "advanced" => 1
            )
        )
    ) ,
    "Metrics" => array(
        "title" => gettext("Metrics") ,
        "desc" => gettext("Configure metric settings") ,
        "advanced" => 0,
        "conf" => array(
            "recovery" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Recovery Ratio") ,
                "advanced" => 0
            ) ,
            "threshold" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Global Threshold") ,
                "advanced" => 0
            )
        )
    ) ,
    "Reporting Server and BI" => array(
        "title" => gettext("Reporting Server / Business Intelligence") ,
        "desc" => gettext("Configure BI (JapserServer) settings") ,
        "advanced" => 0,
        "conf" => array(
            "bi_type" => array(
                "type" => array(
                    "jasperserver" => gettext("JasperServer")
                ) ,
                "help" => gettext("Right now only Jasperserver is supported as reporting backend.") ,
                "desc" => gettext("Reporting Server Type") ,
                "advanced" => 0
            ) ,
            "bi_host" => array(
                "type" => "text",
                "help" => gettext("Reporting server ip address, defaults to 'localhost'.") ,
                "desc" => gettext("BI Host") ,
                "advanced" => 1
            ) ,
            "bi_port" => array(
                "type" => "text",
                "help" => gettext("Reporting server port, defaults to 8080.") ,
                "desc" => gettext("BI Port") ,
                "advanced" => 1
            ) ,
            "bi_link" => array(
                "type" => "text",
                "help" => gettext("Reporting server link, defaults to /jasperserver/.") ,
                "desc" => gettext("BI Link") ,
                "advanced" => 1
            ) ,
            "bi_user" => array(
                "type" => "text",
                "help" => gettext("Reporting server user, defaults to 'jasperadmin'.") ,
                "desc" => gettext("BI User") ,
                "advanced" => 1
            ) ,
            "bi_pass" => array(
                "type" => "text",
                "help" => gettext("Reporting server password, default to the one inside /etc/ossim/ossim_setup.conf") ,
                "desc" => gettext("BI Pass") ,
                "advanced" => 1
            )
        )
    ) ,
    "Executive Panel" => array(
        "title" => gettext("Executive Panel") ,
        "desc" => gettext("Configure panel settings") ,
        "advanced" => 1,
        "conf" => array(
            "panel_plugins_dir" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Executive Panel plugin Directory") ,
                "advanced" => 1
            ) ,
            "panel_configs_dir" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Executive Panel Config Directory") ,
                "advanced" => 1
            )
        )
    ) ,
    "ACLs" => array(
        "title" => gettext("phpGACL configuration") ,
        "desc" => gettext("Access control list database configuration") ,
        "advanced" => 1,
        "conf" => array(
            "phpgacl_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("PHPGacl Path") ,
                "advanced" => 1
            ) ,
            "phpgacl_type" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("PHPGacl DB Type") ,
                "advanced" => 1
            ) ,
            "phpgacl_host" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("PHPGacl DB Host") ,
                "advanced" => 1
            ) ,
            "phpgacl_base" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("PHPGacl DB Name") ,
                "advanced" => 1
            ) ,
            "phpgacl_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("PHPGacl DB User") ,
                "advanced" => 1
            ) ,
            "phpgacl_pass" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("PHPGacl DB Password") ,
                "advanced" => 1
            )
        )
    ) ,
    "RRD" => array(
        "title" => gettext("RRD") ,
        "desc" => gettext("RRD Configuration (graphing)") ,
        "advanced" => 1,
        "conf" => array(
            "graph_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("RRD Draw graph link") ,
                "advanced" => 1
            ) ,
            "rrdtool_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("RRDTool Path") ,
                "advanced" => 1
            ) ,
            "rrdtool_lib_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("RRDTool Lib Path") ,
                "advanced" => 1
            ) ,
            "mrtg_path" => array(
                "type" => "text",
                "help" => gettext("Unused.") ,
                "desc" => gettext("MRTG Path") ,
                "advanced" => 1
            ) ,
            "mrtg_rrd_files_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("MRTG RRD Files") ,
                "advanced" => 1
            ) ,
            "rrdpath_host" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Host Qualification RRD Path") ,
                "advanced" => 1
            ) ,
            "rrdpath_net" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Net Qualification RRD Path") ,
                "advanced" => 1
            ) ,
            "rrdpath_global" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Global Qualification RRD Path") ,
                "advanced" => 1
            ) ,
            "rrdpath_level" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Service level RRD Path") ,
                "advanced" => 1
            ) ,
            "rrdpath_incidents" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Incident trend RRD Path") ,
                "advanced" => 1
            ) ,
            "rrdpath_bps" => array(
                "type" => "text",
                "help" => gettext("business processes rrd directory") ,
                "desc" => gettext("BPs RRD Path") ,
                "advanced" => 1
            ) ,
            "rrdpath_ntop" => array(
                "type" => "text",
                "help" => gettext("Defaults to /var/lib/ntop/rrd/") ,
                "desc" => gettext("Ntop RRD Path") ,
                "advanced" => 1
            ) ,
            "rrdpath_stats" => array(
                "type" => "text",
                "help" => gettext("Event Stats RRD directory") ,
                "desc" => gettext("EventStats RRD Path") ,
                "advanced" => 1
            ) ,
            "font_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("TTF Location") ,
                "advanced" => 1
            )
        )
    ) ,
    "Backup" => array(
        "title" => gettext("Backup") ,
        "desc" => gettext("Backup configuration: backup database, directory, interval") ,
        "advanced" => 0,
        "conf" => array(
            "backup_type" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Backup DB Type") ,
                "advanced" => 1
            ) ,
            "backup_base" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Backup DB Name") ,
                "advanced" => 1
            ) ,
            "backup_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Backup DB User") ,
                "advanced" => 1
            ) ,
            "backup_pass" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("Backup DB Password") ,
                "advanced" => 1
            ) ,
            "backup_host" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Backup DB Host") ,
                "advanced" => 1
            ) ,
            "backup_port" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Backup DB Port") ,
                "advanced" => 1
            ) ,
            "backup_dir" => array(
                "type" => "text",
                "help" => gettext("Defaults to /var/lib/ossim/backup/") ,
                "desc" => gettext("Backup File Directory") ,
                "advanced" => 1
            ) ,
            "backup_day" => array(
                "type" => "text",
                "help" => gettext("How many days in the past do you want to keep Events in forensics?") ,
                "desc" => gettext("Forensics Active Event Window") ,
                "advanced" => 0
            )
        )
    ) ,
    "Vulnerability Scanner" => array(
        "title" => gettext("Vulnerability Scanner") ,
        "desc" => gettext("Vulnerability Scanner configuration") ,
        "advanced" => 0,
        "conf" => array(
            "scanner_type" => array(
                "type" => array(
                    "openvas2" => gettext("OpenVAS 2.x") ,
                    "nessus2" => gettext("Nessus 2.x") ,
                    "nessus3" => gettext("Nessus 3.x") ,
                    "nessus4" => gettext("Nessus 4.x")
                ) ,
                "help" => gettext("Vulnerability scanner used. OpenVAS is used by default.") ,
                "desc" => gettext("Vulnerability Scanner") ,
                "advanced" => 1
            ) ,
            "nessus_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Scanner Login") ,
                "advanced" => 1
            ) ,
            "nessus_pass" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("Scanner Password") ,
                "advanced" => 1
            ) ,
            "nessus_host" => array(
                "type" => "text",
                "help" => gettext("Only for non distributed scans") ,
                "desc" => gettext("Scanner host") ,
                "advanced" => 1
            ) ,
            "nessus_port" => array(
                "type" => "text",
                "help" => gettext("Defaults to port 1241 on Nessus, 9390 on OpenVAS") ,
                "desc" => gettext("Scanner port") ,
                "advanced" => 1
            ) ,
            "nessus_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Scanner Binary location") ,
                "advanced" => 1
            ) ,
            "nessus_rpt_path" => array(
                "type" => "text",
                "help" => gettext("Where will scanning results be located") ,
                "desc" => gettext("Scan output path") ,
                "advanced" => 1
            ) ,
            "nessusrc_path" => array(
                "type" => "text",
                "help" => gettext("Configuration (.rc) file") ,
                "desc" => gettext("Configuration file location") ,
                "advanced" => 0
            ) ,
            "nessus_distributed" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes")
                ) ,
                "help" => gettext("Obsolete, distributed is very recommended even if you only got one sensor.") ,
                "desc" => gettext("Distributed Scanning") ,
                "advanced" => 1
            ) ,
            "vulnerability_incident_threshold" => array(
                "type" => array(
                    "0" => "0",
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4",
                    "5" => "5",
                    "6" => "6",
                    "7" => "7",
                    "8" => "8",
                    "9" => "9",
                ) ,
                "help" => gettext("Any vulnerability with a higher risk level than this value will get inserted automatically into DB.") ,
                "desc" => gettext("Vulnerability Incident Threshold") ,
                "advanced" => 0
            )
        )
    ) ,
    "Acid/Base" => array(
        "title" => gettext("ACID/BASE") ,
        "desc" => gettext("Acid and/or Base configuration") ,
        "advanced" => 1,
        "conf" => array(
            "event_viewer" => array(
                "type" => array(
                    "acid" => gettext("Acid") ,
                    "base" => gettext("Base")
                ) ,
                "help" => gettext("Choose your event viewer") ,
                "desc" => gettext("Event Viewer") ,
                "advanced" => 1
            ) ,
            "acid_link" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Event viewer link") ,
                "advanced" => 1
            ) ,
            "acid_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Event viewer php path") ,
                "advanced" => 1
            ) ,
            "acid_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Frontend login for event viewer") ,
                "advanced" => 1
            ) ,
            "acid_pass" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("Frontend password for event viewer") ,
                "advanced" => 1
            ) ,
            "ossim_web_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("OSSIM Web user") ,
                "advanced" => 1
            ) ,
            "ossim_web_pass" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("OSSIM Web Password") ,
                "advanced" => 1
            )
        )
    ) ,
    "External Apps" => array(
        "title" => gettext("External applications") ,
        "desc" => gettext("Path to other applications") ,
        "advanced" => 1,
        "conf" => array(
            "nmap_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("NMap Binary Path") ,
                "advanced" => 1
            ) ,
            "p0f_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("P0f Binary Path") ,
                "advanced" => 1
            ) ,
            "arpwatch_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Arpwatch Binary Path") ,
                "advanced" => 1
            ) ,
            "mail_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Mail Binary Path") ,
                "advanced" => 1
            ) ,
            "touch_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("'touch' Binary Path") ,
                "advanced" => 1
            ) ,
            "wget_path" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Wget Binary Path") ,
                "advanced" => 1
            ) ,
            "have_scanmap3d" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Use Scanmap 3D") ,
                "advanced" => 0
            )
        )
    ) ,
    "User Log" => array(
        "title" => gettext("User action logging") ,
        "desc" => gettext("User action logging") ,
        "advanced" => 0,
        "conf" => array(
            "user_action_log" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Enable User Log") ,
                "advanced" => 0
            ) ,
            "log_syslog" => array(
                "type" => array(
                    "0" => gettext("No") ,
                    "1" => gettext("Yes")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Log to syslog") ,
                "advanced" => 0
            )
        )
    ) ,
    "Event Viewer" => array(
        "title" => gettext("Real time event viewer") ,
        "desc" => gettext("Real time event viewer") ,
        "advanced" => 1,
        "conf" => array(
            "max_event_tmp" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Event limit for real time event viewer") ,
                "advanced" => 1
            )
        )
    ) ,
    "Login" => array(
        "title" => gettext("Login methods/options") ,
        "desc" => gettext("Setup main login methods/options") ,
        "advanced" => 0,
        "conf" => array(
            "first_login" => array(
                "type" => array(
                    "yes" => _("Yes") ,
                    "no" => _("No")
                ) ,
                "help" => _("") ,
                "desc" => gettext("Show welcome message at next login") ,
                "advanced" => 0
            ) ,
            "login_enforce_existing_user" => array(
                "type" => array(
                    "yes" => _("Yes") ,
                    "no" => _("No")
                ) ,
                "help" => _("") ,
                "desc" => gettext("Require a valid ossim user for login") ,
                "advanced" => 0
            ) ,
            "login_enable_ldap" => array(
                "type" => array(
                    "yes" => _("Yes") ,
                    "no" => _("No")
                ) ,
                "help" => _("") ,
                "desc" => gettext("Enable LDAP for login") ,
                "advanced" => 0
            ) ,
            "login_ldap_server" => array(
                "type" => "text",
                "help" => _("") ,
                "desc" => gettext("Ldap server address") ,
                "advanced" => 0
            ) ,
            "login_ldap_cn" => array(
                "type" => "text",
                "help" => _("") ,
                "desc" => gettext("LDAP CN") ,
                "advanced" => 0
            ) ,
            "login_ldap_o" => array(
                "type" => "text",
                "help" => _("") ,
                "desc" => gettext("LDAP O") ,
                "advanced" => 0
            ) ,
            "login_ldap_ou" => array(
                "type" => "text",
                "help" => _("") ,
                "desc" => gettext("LDAP OU") ,
                "advanced" => 0
            )
        )
    ) ,
    "Updates" => array(
        "title" => gettext("Updates") ,
        "desc" => gettext("Configure updates") ,
        "advanced" => 0,
        "conf" => array(
            "update_checks_enable" => array(
                "type" => array(
                    "yes" => _("Yes") ,
                    "no" => _("No")
                ) ,
                "help" => gettext("The system will check once a day for updated packages, rules, directives, etc. No system information will be sent, it just gest a file with dates and update messages using wget.") ,
                "desc" => gettext("Enable auto update-checking") ,
                "advanced" => 0
            ) ,
            "update_checks_use_proxy" => array(
                "type" => array(
                    "yes" => _("Yes") ,
                    "no" => _("No")
                ) ,
                "help" => gettext("") ,
                "desc" => gettext("Use proxy for auto update-checking") ,
                "advanced" => 1
            ) ,
            "proxy_url" => array(
                "type" => "text",
                "help" => gettext("Enter the full path including a trailing slash, i.e., 'http://192.168.1.60:3128/'") ,
                "desc" => gettext("Proxy url") ,
                "advanced" => 1
            ) ,
            "proxy_user" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Proxy User") ,
                "advanced" => 1
            ) ,
            "proxy_password" => array(
                "type" => "password",
                "help" => gettext("") ,
                "desc" => gettext("Proxy Password") ,
                "advanced" => 1
            ) ,
            "last_update" => array(
                "type" => "text",
                "help" => gettext("") ,
                "desc" => gettext("Last update timestamp") ,
                "advanced" => 1
            ) ,
        )
    )
);
function valid_value($key, $value) {
    $numeric_values = array(
        "recovery",
        "threshold",
        "use_resolv",
        "have_scanmap3d",
        "max_event_tmp"
    );
    if (in_array($key, $numeric_values)) {
        if (!is_numeric($value)) {
            require_once ("ossim_error.inc");
            $error = new OssimError();
            $error->display("NOT_NUMERIC", array(
                $key
            ));
        }
    }
    return true;
}
function submit() {
?>
    <!-- submit -->
    
    <input type="submit" name="update" class="btn" style="font-size:12px" value=" <?php
    echo gettext("Update configuration"); ?> " />
    
	<br><br>
    <!-- end sumbit -->
<?php
}
if (POST('update')) {
    require_once 'classes/Config.inc';
    $config = new Config();
    for ($i = 0; $i < POST('nconfs'); $i++) {
        if (valid_value(POST("conf_$i") , POST("value_$i"))) {
            if (!$ossim_conf->is_in_file(POST("conf_$i"))) {
                $config->update(POST("conf_$i") , POST("value_$i"));
                //echo POST("conf_$i")."---->";
                //echo POST("value_$i")."<br><br>";
                
            }
        }
    }
    header("Location: " . $_SERVER['SCRIPT_NAME'] . "?adv=" . POST('adv') . "&word=" . POST('word'));
    exit;
}
if (REQUEST("reset")) {
    if (!(GET('confirm'))) {
?>
        <p align="center">
          <b><?php
        echo gettext("Are you sure ?") ?></b>
          <br/>
          <a href="?reset=1&confirm=1"><?php
        echo gettext("Yes") ?></a>&nbsp;|&nbsp;
          <a href="main.php"><?php
        echo gettext("No") ?></a>
        </p>
<?php
        exit;
    }
    require_once 'classes/Config.inc';
    $config = new Config();
    $config->reset();
    header("Location: " . $_SERVER['SCRIPT_NAME'] . "?adv=" . POST('adv') . "&word=" . POST('word'));
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <title> <?php
echo gettext("Advanced Configuration"); ?> </title>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
  <script src="../js/accordian.js" type="text/javascript" ></script>
  <style type="text/css">
	#basic-accordian{
		padding:0px;
		align:center;
		width:450px;
	}

	.accordion_headings {
		height:24px; line-height:22px;
		cursor:pointer;
		padding-left:5px; padding-right:5px; margin-bottom:2px;
		font-family:arial; font-size:12px; color:#0E3C70; font-weight:bold; text-decoration:none
	}

	.accordion_headings:hover {
	}

	.accordion_child {
		padding-left:5px;
		padding-right:5px;
		padding-bottom:5px
	}
	.header_highlight {
	}
	.semiopaque { opacity:0.9; MozOpacity:0.9; KhtmlOpacity:0.9; filter:alpha(opacity=90); background-color:#B5C3CF }
  </style>
  <script>
	var IE = document.all ? true : false
	if (!IE) document.captureEvents(Event.MOUSEMOVE)
	document.onmousemove = getMouseXY;
	var tempX = 0
	var tempY = 0

	var difX = 15
	var difY = 0 

	function getMouseXY(e) {
		if (IE) { // grab the x-y pos.s if browser is IE
				tempX = event.clientX + document.body.scrollLeft + difX
				tempY = event.clientY + document.body.scrollTop + difY 
		} else {  // grab the x-y pos.s if browser is MOZ
				tempX = e.pageX + difX
				tempY = e.pageY + difY
		}  
		if (tempX < 0){tempX = 0}
		if (tempY < 0){tempY = 0}
		var dh = document.body.clientHeight+ window.scrollY;
		if (document.getElementById("numeroDiv").offsetHeight+tempY > dh)
			tempY = tempY - (document.getElementById("numeroDiv").offsetHeight + tempY - dh)
		document.getElementById("numeroDiv").style.left = tempX
		document.getElementById("numeroDiv").style.top = tempY 
		return true
	}
	
	function ticketon(name,desc) { 
		if (document.getElementById) {
			var txt1 = '<table border=0 cellpadding=8 cellspacing=0 class="semiopaque"><tr><td class=nobborder style="line-height:18px;width:300px" nowrap><b>'+ name +'</b><br>'+ desc +'</td></tr></table>'
			document.getElementById("numeroDiv").innerHTML = txt1
			document.getElementById("numeroDiv").style.display = ''
			document.getElementById("numeroDiv").style.visibility = 'visible'
		}
	}

	function ticketoff() {
		if (document.getElementById) {
			document.getElementById("numeroDiv").style.visibility = 'hidden'
			document.getElementById("numeroDiv").style.display = 'none'
			document.getElementById("numeroDiv").innerHTML = ''
		}
	}
</script>

</head>
<body onload="new Accordian('basic-accordian',5,'header_highlight')">
  <div id="numeroDiv" style="position:absolute; z-index:999; left:0px; top:0px; height:80px; visibility:hidden; display:none"></div>
  <?php
$advanced = (POST('adv') == "1") ? true : ((GET('adv') == "1") ? true : false);
//$links = ($advanced) ? "<a href='main.php' style='color:#cccccc'>simple</a> | <b>advanced</b>" : "<b>simple</b> | <a href='main.php?adv=1' style='color:#cccccc'>advanced</a>";
//$title = ($advanced) ? "Advanced" : "Main";
include ("../hmenu.php");
?>
  
  <form method="POST" style="margin:0 auto" action="<?php
echo $_SERVER["SCRIPT_NAME"] ?>" />
  
  <table align=center>
  <tr>
  <td>
  
  <div id="basic-accordian" align="center">
<?php
$count = 0;
$div = 0;
$found = 0;
$arr = array();
foreach($CONFIG as $key => $val) if ($advanced || (!$advanced && $val["advanced"] == 0)) {
    $s = (POST('word') != "") ? POST('word') : ((GET('word') != "") ? GET('word') : "");
    if ($s != "") {
        foreach($val["conf"] as $conf => $type) if ($advanced || (!$advanced && $type["advanced"] == 0)) {
            if (preg_match("/$s/i", $conf)) {
                $found = 1;
                array_push($arr, $conf);
            }
        }
    }
?>
  <div id="test<?php
    if ($div > 0) echo $div ?>-header" class="accordion_headings <?php
    if ($found == 1) echo "header_highlight" ?>">

	<table width="100%" cellspacing="0" class=noborder>
		<th  <?php
    if ($found == 1) echo "style='background-color: #F28020; color: #FFFFFF'" ?>>
			<?php echo $val["title"] ?>
		</th>
	</table>
  </div>
  
  <div id="test<?php
    if ($div > 0) echo $div ?>-content">
	<div class="accordion_child">
		<table cellpadding=3 align="center">
<?php
    //print "<tr><th colspan=\"2\">" . $val["title"] . "</th></tr>";
    print "<tr><td colspan=\"3\">" . $val["desc"] . "</td></tr>";
    if ($advanced && $val["title"]=="RRD") {
?>
		<tr><td colspan="3" align="center">
		<input type="button" onclick="document.location.href='../rrd_conf/rrd_conf.php'" VALUE="<?php echo _("RRD Profiles definition") ?>" class="btn"> 
		</td></tr>
		<?php
    }
    foreach($val["conf"] as $conf => $type) if ($advanced || (!$advanced && $type["advanced"] == 0)) {
        //var_dump($type["type"]);
        $conf_value = $ossim_conf->get_conf($conf);
        $var = ($type["desc"] != "") ? $type["desc"] : $conf;
?>
    <tr <?php
        if (in_array($conf, $arr)) echo "bgcolor=#FE9B52" ?>>

      <input type="hidden" name="conf_<?php
        echo $count ?>"
             value="<?php
        echo $conf ?>" />

      <td><b><?php echo $var ?></b></td>
      <td class="left">
<?php
        $input = "";
        $disabled = ($type["disabled"] == 1 || $ossim_conf->is_in_file($conf)) ? "class=\"disabled\" disabled" : "";
        /* select */
        if (is_array($type["type"])) {
            $input.= "<select name=\"value_$count\" $disabled>";
            if ($conf_value == "") $input.= "<option value=''>";
            foreach($type["type"] as $option_value => $option_text) {
                $input.= "<option ";
                if ($conf_value == $option_value) $input.= " SELECTED ";
                $input.= "value=\"$option_value\">$option_text</option>";
            }
            $input.= "</select>";
        }
        /* input */
        else {
            $input.= "<input ";
            //if ($ossim_conf->is_in_file($conf)) {
            //   $input .= " class=\"disabled\" ";
            //    $input .= " DISABLED ";
            //}
            $input.= "type=\"" . $type["type"] . "\" size=\"30\" 
                    name=\"value_$count\" value=\"$conf_value\" $disabled/>";
        }
        echo $input;
?>
      </td><td align="left"><a href="javascript:;" onmouseover="ticketon('<?php echo str_replace("'", "\'", $var) ?>','<?php echo str_replace("'", "\'", $type["help"]) ?>')"  onmouseout="ticketoff()"><img src="../pixmaps/help.png" width="16" border=0></a></td>

    </tr>
<?php
        $count+= 1;
    }
?>
	</table>
	
	</div>
  </div>
<?php
    $div++;
    $found = 0;
}
?>
  </div>
  
  </td>
  <td valign=top>
<?php
submit();
?> 
	Find word :<input type="text" name="word" value="<?php echo $s
?>"><BR><BR>
	<input type=hidden name="adv" value="<?php
echo ($advanced) ? "1" : "" ?>">
	<input type="submit" VALUE="search" class="btn" style="font-size:12px"> 
	<input type="hidden" name="nconfs" value="<?php
echo $count ?>" />
	</form>

</td>
</tr>
</table>

</body>
</html>

