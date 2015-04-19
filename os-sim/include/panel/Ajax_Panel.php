<?php
require_once 'classes/Session.inc';
require_once 'ossim_conf.inc';
require_once 'panel/Panel.php';

/*

$data = array(
	'1x1' => array(
    			window_opts => array('title' => ..., 'id' => ...)
                plugin      => ....
                plugin_opts => array('foo' => ...)
    )
)

*/
class Window_Panel_Ajax
{
    var $plugin  = null;
    var $window_id = null;
    var $plugins = array();
    var $config_file = null;
    
    function Window_Panel_Ajax($plugin = null)
    {
        /*
         * showCategoriesHTML() lists categories as checkboxes.
         * When the user selects the category and clicks on subcategory, we
         * receive all the forms values, including one looking like:
         * "plugin_foo", for ex: [plugin_custom_sql_table] => on. Here we
         * extract the correct plugin we need to call.
         * 
         * Note: when showing the categories the first time, there is 
         * no plugin selected yet (the user will choose one)
         */
        $this->plugins = &$this->loadPlugins();
        
        //first time, no plugin selected
        if (!$plugin) {
            return;
        }
        // find and validate the plugin the selected plugin
        $found_plugin = '';
        if (!isset($this->plugins[$plugin])) {
            die("Plugin: '$plugin' not found");
        }
        $this->plugin = &$this->plugins[$plugin];
    }

    function &loadPlugins()
    {
        $conf = &$GLOBALS['conf'];
        $plugins_dir = array();
        $extra_plugins = $conf->get_conf('panel_plugins_dir');
        if ($extra_plugins) {
            $plugins_dir = preg_split('/\s*,\s*/', $extra_plugins);
        }
        $plugins_dir[] = dirname(__FILE__).'/plugins';
        $plugins = array();
        foreach ($plugins_dir as $dir) {
            if (!is_dir($dir) || !$d = dir($dir)) {
                echo "Warning: Plugins directory: '$dir' could not be opened";
                continue;
            }
            while (false !== ($file = $d->read())) {
                if (!preg_match('/\.php$/', $file)) {
                    continue;
                }
                require_once $dir. DIRECTORY_SEPARATOR . $file;
                list($class, ) = explode('.', $file);
                $class = "Plugin_" . $class;
                if (!class_exists($class)) {
                    echo "Warning: Wrong plugin class name '$class' in '$file'";
                    continue;
                }
                $obj = &new $class();
                // ensure correct object interface
                $required = array('getCategoryName', 'showSubCategoryHTML',
                                  'showSettingsHTML', 'showWindowContents');
                foreach ($required as $req) {
                    if (!method_exists($obj, $req)) {
                        echo "Warning: Wrong plugin interface, missing method: '$req'";
                        continue 2;
                    }
                }
                // register the plugin
                $class = strtolower($class);
                $plugins[$class] = array('cat'   => $obj->getCategoryName(),
                                         'class' => $class,
                                         'obj'   => $obj);
            }
            $d->close();
        }
        return $plugins;
    }

    function getConfigFile()
    {
        $conf = &$GLOBALS['conf'];
        $configs_dir = $conf->get_conf('panel_configs_dir');

        $uid = posix_getuid();
        $gid = posix_getgid();
        $user_info = posix_getpwuid($uid);
        $user = $user_info['name'];
        $group_info = posix_getgrgid($gid);
        $group = $group_info['name'];
        $fix_cmd = '. '._("To fix that, execute as root the following commands").':<br>'.
                   "# mkdir -p $configs_dir<br>".
                   "# chown $user:$group $configs_dir<br>".
                   "# chmod 0700 $configs_dir";
        $fix_extra = "<br><b>"._("You can configure the panel configs directory at 'Configuration -> Main -> Executive Panel -> panel_configs_dir''").'</b>';
        if (!is_dir($configs_dir)) {
            die(_("Directory for panel config files does not exists.").$fix_extra);
        }
        $fix_cmd .= $fix_extra;
        if (!$stat = stat($configs_dir)) {
            die(_("Could not stat configs dir").$fix_cmd);
        }
        // 2 -> file perms (must be 0700)
        // 4 -> uid (must be the apache uid)
        // 5 -> gid (must be the apache gid)
        if ($stat[2] != 16832 ||
            $stat[4] !== $uid ||
            $stat[5] !== $gid)
        {
            die(_("Invalid perms for configs dir").$fix_cmd);
        }
        $user = Session::get_session_user();
        if (!$user) {
            die("Not logged in, aborting");
        }
        $config_file = $configs_dir.'/'.$user;
        return $config_file;
    }

    function loadConfig($window_id = null)
    {
        $filename = $this->getConfigFile();
        if (!is_file($filename)) {
            $data = null;
        } else {
            $contents = file_get_contents($filename);
            if ($contents === false) {
                die(_("Could not read config file").": '$filename'");
            }
            $data = unserialize($contents);
            if ($data === false) {
                die(_("Bad data found in config file").": '$filename'");
            }
        }
        if (!$window_id) {
            return $data;
        }
        if (isset($data[$window_id]) && count($data[$window_id])) {
            return $data[$window_id];
        }
        return array();
    }
    
    function saveConfig($window_id, $options)
    {
        $data = $this->loadConfig();
        if ($window_id == 'panel') {
            $data['panel'] = $options;
        } else {
            if (!is_array($options) || !count($options)) {
                $data[$window_id] = array();
            } else {
                if (!$plugin = &$this->getPlugin($options)) {
                    return _("Please select a category first");
                }
                $data[$window_id] = $plugin->save();
                $data[$window_id]['window_opts']['id'] = $window_id;
            }
        }
        $filename = $this->getConfigFile();
        $save = serialize($data);
        if (!$fd = fopen($filename, 'w')) {
            die(_("Could not save config in file, invalid perms?").": '$filename'");
        }
        if (!fwrite($fd, $save)) {
            die(_("Could not write to file, disk full?").": '$filename'");
        }
        fclose($fd);
    }
        
    function parseTemplate($tpl_file, $vars)
    {
        $contents = file_get_contents($tpl_file);
        foreach ($vars as $key => $value) {
            $contents = str_replace("%$key%", $value, $contents);
        }
        return $contents;
    }
    
    function showCategoriesHTML($options)
    {
        $plugin = isset($options['plugin']) ? $options['plugin'] : false;
        $title  = isset($options['window_opts']['title']) ? $options['window_opts']['title'] : '';
        $help   = isset($options['window_opts']['help'])  ? $options['window_opts']['help']  : '';
        $html = '';
        // Window title
        $html .= _("Window Title");
        $html .= ': <input type="text" name="window_title"
                    value="'.$title.'"><br/><br/>';
        // Category/Plugin list
        foreach ($this->plugins as $plug) {
            // User case: user choose a category, then clicks on subcategory
            // and then clicks again on category, preserv the category
            if ($plugin && $plugin == $plug['class']) {
                $checked = 'checked';
            } else {
                $checked = '';
            }
            $html .= '<input type="radio" name="plugin" value="'.$plug['class']."\" $checked>".$plug['cat'];
            $html .= '<br/>';
        }
        $html .= '<br/>'._("HTML Window Help Message").':<br/>';
        $html .= "<textarea name='window_help' rows='10' cols='35' wrap='on'>$help</textarea>";
        return $html;
    }
    
    function &getPlugin($options)
    {
        if (!isset($options['plugin'])) {
            $r=false; return $r; // PHP only allows vars to be returned by reference
        }
        $plugin = $options['plugin'];
        $plugin = &$this->plugins[$plugin]['obj'];
        $plugin->setup($options);
        return $plugin;
    }
    
    function showSubCategoryHTML($options)
    {
        if (!$plugin = &$this->getPlugin($options)) {
            return _("Please select a category first");
        }
        return $plugin->showSubCategoryHTML();
    }
    
    function showSettingsHTML($options)
    {
        if (!$plugin = &$this->getPlugin($options)) {
            return _("Please select a category first");
        }
        return $plugin->showSettingsHTML();
    }
    
    function showWindowContents($options)
    {
        if (!$plugin = &$this->getPlugin($options)) {
            return _("Please select a category first");
        }
        return $plugin->showWindowContents();
    }
}

?>
