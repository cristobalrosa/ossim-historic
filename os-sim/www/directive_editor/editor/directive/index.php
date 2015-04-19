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
Session::logcheck("MenuCorrelation", "CorrelationDirectives");
require_once ('ossim_conf.inc');
require_once ('classes/Security.inc');
/* directories */
$conf = $GLOBALS["CONF"];
$base_dir = $conf->get_conf("base_dir");
$css_dir = '../../style';
$js_dir = '../javascript';
$js_dir_directive = 'javascript';
/* connection to the OSSIM database */
require_once ('../../include/directive.php');
dbConnect();
/* get the directive */
$directive = unserialize($_SESSION['directive']);
/* width */
$select_width = "180px";
$id_width = '250px';
$priority_width = '50px';
$group_width = '350px';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<link type="text/css" rel="stylesheet"
			href="<?php
echo $css_dir . '/directives.css'; ?>" />

		<style>
			input.editable {width: <?php
echo $right_text_width; ?>}
			select.editable {width: <?php
echo $right_select_width; ?>}
		</style>

		<script type="text/javascript" language="javascript"
			src="<?php
echo $js_dir . '/editor.js'; ?>"></script>

		<script type="text/javascript" language="javascript"
			src="<?php
echo $js_dir . '/editableSelectBox.js'; ?>"></script>

		<script type="text/javascript" language="javascript"
			src="<?php
echo $js_dir_directive . '/directive.js'; ?>"></script>
		
		<script type="text/javascript" language="javascript">
		function taille()
    {
        if (document.body)
        {
        var larg = (window.parent.document.body.clientWidth);
        var haut = (window.parent.document.body.clientHeight);
        }
        else
        {
        var larg = (window.parent.window.innerWidth);
        var haut = (window.parent.window.innerHeight);
        }
        /* default size */
    	   var width = 890;
    	   var height = 550;
    
    	   /* center the popup to the screen */
    	   if (width < larg)
    	   {
    	     var left = (larg - width) / 2;
    	   }
    	   else
    	   {
            width = larg - 20;
            left = 10;
         }
         
         if (height < haut)
    	   {
           var top = (haut - height) / 2;
    	   }
    	   else
    	   {
            height = haut - 20;
            top = 10;
         }
         
         window.parent.document.getElementById('fenetre').style.top = top;
         window.parent.document.getElementById('fenetre').style.left = left;
         window.parent.document.getElementById('fenetre').style.width = width;
         window.parent.document.getElementById('fenetre').style.height = height;
         
    }

		function open_frame(url){
    var iframe = window.parent.document.getElementById('fenetre');
    var fond = window.parent.document.getElementById('fond');
    iframe.childNodes[0].src = url;    
    taille();
    fond.style.display = 'block';
    iframe.style.display = 'block';
   }
		</script>
	</head>
	
	<body>
	<div style="
      background-color:#17457c;
      width:100%;
      position:fixed;
      height:2px;
      left:0px;"></div><br>
  <!-- #################### main container #################### -->
	<form method="POST" action="../../include/utils.php?query=save_directive">
	<table class="container" style="border-width: 0px" align="center">
	<tr>

	<!-- #################### left container #################### -->
	<td class="container" style="vertical-align: top">
	<table class="container">

	<tr><td class="container">
	<?php
include ("global.inc.php"); ?>
	</td></tr>

	<tr><td class="container">
		<input type="hidden" name="directive" value="<?php
echo $_GET["directive"]; ?>" />
		<input type="hidden" name="level" value="<?php
echo $_GET["level"]; ?>" />
		<input type="hidden" name="id" value="<?php
echo $_GET["id"]; ?>" />
		<input type="button" style="width: 100px"
			value="<?php
echo gettext('Cancel'); ?>"
			<?php
if (is_free($_GET["directive"]) == "false") print "onclick=\"onClickCancel(" . $_GET["directive"] . "," . $_GET["level"] . ")\"";
else print "onclick=\"onClickCancel2()\"";
?>
			
		/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" style="width: 100px"
			id="save"
			value="<?php
echo gettext('Save'); ?>"
			onclick="submit()"
		/>
	</td></tr>

	</table>
	</td>
	<!-- #################### END: left container #################### -->
	
	</tr>
	</table>
	</form>
	
	<!-- #################### END: main container #################### -->
	
	</body>
</html>

<?php
dbClose();
?>
