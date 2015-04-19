<?php
/**
* Class and Function List:
* Function list:
* - PrintBASESubHeader()
* - PrintBASESubFooter()
* - PrintFramedBoxHeader()
* - PrintFramedBoxFooter()
* - PrintFreshPage()
* - chk_select()
* - chk_check()
* - dispYearOptions()
* - PrintBASEAdminMenuHeader()
* - PrintBASEAdminMenuFooter()
* - PrintBASEHelpLink()
* Classes list:
*/
/*******************************************************************************
** OSSIM Forensics Console
** Copyright (C) 2009 OSSIM/AlienVault
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
** Built upon work by the BASE Project Team <kjohnson@secureideas.net>
**/
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');
function PrintBASESubHeader($page_title, $page_name, $back_link, $refresh = 0, $page = "") {
    GLOBAL $debug_mode, $BASE_VERSION, $BASE_path, $BASE_urlpath, $html_no_cache, $max_script_runtime, $Use_Auth_System, $stat_page_refresh_time, $base_style, $refresh_stat_page, $ossim_servers, $sensors, $hosts, $DBlib_path, $DBtype, $db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password;
    if (ini_get("safe_mode") != true) set_time_limit($max_script_runtime);
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- ' . _TITLE . $BASE_VERSION . ' -->
<HTML>
  <HEAD><meta http-equiv="Content-Type" content="text/html; charset=' . _CHARSET . '">';
    if ($html_no_cache == 1) echo '<META HTTP-EQUIV="pragma" CONTENT="no-cache">';
    if ($refresh == 1) PrintFreshPage($refresh_stat_page, $stat_page_refresh_time);
    if (@$_COOKIE['archive'] == 0) echo '<TITLE>' . _TITLE . ': ' . $page_title . '</TITLE>';
    else echo '<TITLE>' . _TITLE . ': ' . $page_title . ' -- ARCHIVE</TITLE>';
    echo '<LINK rel="stylesheet" type="text/css" HREF="' . $BASE_urlpath . '/styles/' . $base_style . '">
        </HEAD>
        <BODY>';
    if (!array_key_exists("minimal_view", $_GET)) {
        include ("$BASE_path/base_hdr1.php");
        $db = NewBASEDBConnection($DBlib_path, $DBtype);
        $db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);
        include ("$BASE_path/base_hdr2.php");
    }
    //echo "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=RIGHT>".$back_link."</TD></TR></TABLE><BR>";
    if ($debug_mode > 0) PrintPageHeader();
}
function PrintBASESubFooter() {
    GLOBAL $BASE_VERSION, $BASE_path, $BASE_urlpath, $Use_Auth_System;
    echo "\n\n<!-- BASE Footer -->\n" . "<P>\n";
    //include("$BASE_path/base_footer.php");
    echo "\n\n";
}
function PrintFramedBoxHeader($title, $fore, $back) {
    echo '
<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 BORDER=0>
<TR><TD style="border:1px solid #ABB7C7">
  <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 BORDER=0>
  <TR><TD class="sectiontitle">&nbsp;' . $title . '&nbsp;</TD></TR>
    <TR><TD>';
}
function PrintFramedBoxFooter() {
    echo '
  </TD></TR></TABLE>
</TD></TR></TABLE>';
}
function PrintFreshPage($refresh_stat_page, $stat_page_refresh_time) {
    if ($refresh_stat_page)
    //echo '<META HTTP-EQUIV="REFRESH" CONTENT="'.$stat_page_refresh_time.'; URL='. htmlspecialchars(CleanVariable($_SERVER["REQUEST_URI"], VAR_FSLASH | VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER), ENT_QUOTES).'">'."\n";
    echo '<META HTTP-EQUIV="REFRESH" CONTENT="' . $stat_page_refresh_time . '">';
}
function chk_select($stored_value, $current_value) {
    if (strnatcmp($stored_value, $current_value) == 0) return " SELECTED";
    else return " ";
}
function chk_check($stored_value, $current_value) {
    if ($stored_value == $current_value) return " CHECKED";
    else return " ";
}
function dispYearOptions($stored_value) {
    // Creates the years for drop down boxes
    $thisyear = date("Y");
    $options = "";
    $options = "<OPTION VALUE=' ' " . chk_select($stored_value, " ") . ">" . _DISPYEAR . "\n";
    for ($i = 1999; $i <= $thisyear; $i++) {
        $options = $options . "<OPTION VALUE='" . $i . "' " . chk_select($stored_value, $i) . ">" . $i . "\n";
    }
    $options = $options . "</SELECT>";
    return ($options);
}
function PrintBASEAdminMenuHeader() {
    $menu = "<table width='100%' border=0><tr><td width='15%'>";
    $menu = $menu . "<div class='mainheadermenu'>";
    $menu = $menu . "<table border='0' class='mainheadermenu'>";
    $menu = $menu . "<tr><td class='menuitem'>" . _USERMAN . "<br>";
    $menu = $menu . "<hr><a href='base_useradmin.php?action=list' class='menuitem'>" . _LISTU . "</a><br>";
    $menu = $menu . "<a href='base_useradmin.php?action=create' class='menuitem'>" . _CREATEU . "</a><br>";
    $menu = $menu . "<br>" . _ROLEMAN . "<br><hr>";
    $menu = $menu . "<a href='base_roleadmin.php?action=list' class='menuitem'>" . _LISTR . "</a><br>";
    $menu = $menu . "<a href='base_roleadmin.php?action=create' class='menuitem'>" . _CREATER . "</a><br>";
    $menu = $menu . "</td></tr></table></div></td><td>";
    echo ($menu);
}
function PrintBASEAdminMenuFooter() {
    $footer = "</td></tr></table>";
    echo ($footer);
}
function PrintBASEHelpLink($target) {
    /*
    This function will accept a target variable which will point to
    an anchor in the base_help.php file.  It will output a help icon
    that will link to that target in a new window.
    */
}
?>
