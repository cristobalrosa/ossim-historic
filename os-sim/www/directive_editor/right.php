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
$directive = $_GET['directive'];
$level = $_GET['level'];
$action = $_GET['action'];
$id = $_GET['id'];
$onlydir = ($_GET['onlydir'] == "1") ? true : false;
if ($directive == '' && $id == '') {
    $frames = '<iframe id="top" width="100%" marginwidth=0 marginheight=0 frameborder=0 STYLE="z-index:1;" src="viewer/index.php" name="top" ></iframe>';
} elseif ($action == 'add_rule') {
    $frames = '<iframe id="top" height="200px" width="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="viewer/index.php?directive=' . $directive . '&amp;level=' . $level . '" name="top" ></iframe>';
    $frames.= '<iframe id="bottom" width="100%" frameborder=0 marginwidth=0 marginheight=0 height="395px" STYLE="z-index:1;" src="editor/rule/index.php?directive=' . $directive . '&amp;level=' . $level . '&amp;id=' . $id . '&nlevel=' . $_GET['nlevel'] . '" name="bottom" ></iframe>';
} elseif ($action == 'edit_rule') {
    $frames = '<iframe id="top" height="200px" width="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="viewer/index.php?directive=' . $directive . '&amp;level=' . $level . '" name="top" ></iframe>';
    $frames.= '<iframe id="bottom" width="100%" frameborder=0 marginwidth=0 marginheight=0 height="395px" STYLE="z-index:1; vertical-align:middle" src="editor/rule/index.php?directive=' . $directive . '&amp;level=' . $level . '&amp;id=' . $id . '" name="bottom" ></iframe>';
} elseif ($action == 'edit_dir') {
    if (!$onlydir) {
        $frames = '<iframe id="top" height="200px" width="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="viewer/index.php?directive=' . $directive . '&amp;level=' . $level . '" name="top" ></iframe>';
        $frames.= '<iframe id="bottom" width="100%" frameborder=0 marginwidth=0 marginheight=0 height="165px" STYLE="z-index:1;" src="editor/directive/index.php?directive=' . $directive . '&amp;level=' . $level . '&amp;id=' . $id . '" name="bottom" ></iframe>';
    } else {
        $frames.= '<iframe id="bottom" width="100%" height="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="editor/directive/index.php?directive=' . $directive . '&amp;level=' . $level . '&amp;id=' . $id . '" name="bottom" ></iframe>';
    }
} elseif ($action == 'edit_file' || $action == 'add_file') {
    $frames = '<iframe id="top" width="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="viewer/index.php" name="top" ></iframe>';
    $frames.= '<iframe id="bottom" width="100%" frameborder=0 marginwidth=0 marginheight=0 height="140px" STYLE="z-index:1;" src="editor/category/index.php?id=' . $id . '" name="bottom" ></iframe>';
} elseif ($action == 'edit_group' || $action == 'add_group') {
    $frames = '<iframe id="top" width="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="viewer/index.php" name="top" ></iframe>';
    $frames.= '<iframe id="bottom" width="100%" frameborder=0 marginwidth=0 marginheight=0 height="140px" STYLE="z-index:1;" src="editor/group/index.php?name=' . $id . '" name="bottom" ></iframe>';
} else {
    $frames = '<iframe id="top" width="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="viewer/index.php?directive=' . $directive . '&amp;level=' . $level . '" name="top" ></iframe>';
}
?>
  <html><head>
  <script type="text/javascript" language="javascript">
    function init(nav)
    {
      var bottom = document.getElementById('bottom').height;
        if (document.body)
        {
          var haut = (document.body.clientHeight);
        }
        else
        {
          var haut = (window.innerHeight);
        }
        
        var marg = 16;
        if (nav.search(new RegExp("Firefox|firefox")) == -1)
          {
            marg = marg + 14;
          }
      
    	  var height = haut - bottom - marg;
        document.getElementById('top').height = height;
    	   
    }

   </script>
  </head>
  <body>
  <div id="fenetre" style="
  background-color:#FFFFFF;
  border:2px solid #17457c;
  padding:0px;
  position:absolute;
  display:none;
  text-align:center;
  z-index:100;"><iframe onload="init('<?php echo $_SERVER['HTTP_USER_AGENT'] ?>');" id="calque" width="99%" height="100%" frameborder=0 marginwidth=0 marginheight=0 STYLE="z-index:1;" src="" name="calque" ></iframe>
  </div>
  <div id="fond" style="
  background-color:#000000;
  filter:alpha(opacity=25);
  -moz-opacity:0.25;
  opacity: 0.25;
  display:none;
  position:absolute;
  border:0;
  width:100%;
  height:100%;
  top:0;
  left:0;
  z-index:90;"></div>
  <?php echo $frames; ?>
</body>
</html>