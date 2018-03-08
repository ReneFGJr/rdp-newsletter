<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action('plugins_loaded', 'my_plugin_activation');


function my_plugin_activation() {
$version = get_option( 'my_plugin_version' );
 
if( version_compare($version, '2.0.0', '<')) {
    // Do some special things when we update to 2.0.0.
}
 
update_option( 'my_plugin_version', MY_PLUGIN_VERSION );
return MY_PLUGIN_VERSION;
}
?>