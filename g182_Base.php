<?php
include_once(ABSPATH . 'wp-config.php');
include_once(ABSPATH . 'wp-includes/wp-db.php');
include_once(ABSPATH . 'wp-includes/pluggable.php');

/*
Plugin Name: Plugin base template by 182code
Description: Empty plugin base class by 182code. Dependency system assumes structure of g182_[Pluginname]/g182_[Pluginname].php, and after loading the plugin is initialized as a global instance of the plugin class.
Author: Geert van Dijk
Version: 0.0.1
*/

class g182_Base {

    public function __construct() {

    }

    function activate() {
        $deps = array();
        $msgs = '';
        $errors = 0;
        $plugin_dir = plugin_dir_path( __FILE__ );
        $plugin_dir = str_replace(get_class($this) . '/', '', $plugin_dir);
        $plugins = array();

        foreach ($deps as $dep) {
            $dep = 'g182_' . $dep;
            global $$dep;
            if (!isset($$dep)) {       
                $plugins[] = $plugin_dir . $dep . '/' . $dep . '.php';    
                $msgs .= 'Dependency ' . $dep . ' missing or broken (is it enabled?)<br />';
                $errors++;
            }
        }

        if ($errors > 0) {
            $msgs .= '<a href="' . get_admin_url(null, 'plugins.php') . '">Back to plugins page</a>';
            
            deactivate_plugins(basename( __FILE__ ));
        
            wp_die($msgs);
        }
    }
}

add_action('init', 'g182_Base_Init', 1);
function g182_Base_Init() { global $g182_Base; $g182_Base = new g182_Base(); }

register_activation_hook(__FILE__, array( 'g182_Base', 'activate' ));

?>