<?php
/*
 Plugin name: Newsletters - RDP Brasil
 Plugin url: https://github.com/ReneFGJr/rdp-newsletter
 Description: Newsletter da RDP Brasil
 Version: 1.18.02.26
 Author: Rene Faustino Gabriel Junior
 Author Uri: http://www.ufrgs.br/reed/rene
 License: GPLv2 or Later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: wporg
 Domain Path: /languages
 */

global $wpdb, $newsletter;

defined('ABSPATH') or die('No script kiddies please!');

/* definitions */
define("rdp_newsletter_VERSION", "v0.18.02.23");
define("rdp_newsletter_PLUGIN", "RDP-newsletters");
define("rdp_newsletter_DIR", "/wp-content/plugins/rdp-newsletter/");
define("rdp_newsletter_TABLE_TEMPLAT", "wp_rdp_newsletter");

/* Shortcode ***************************************************************/
add_shortcode( 'rdp_newsletter_form', 'rdp_newsletter_form' );

require("model/rdp_newsletter_model.php");
require("controller/rdp_newsletter_controller.php");

?>