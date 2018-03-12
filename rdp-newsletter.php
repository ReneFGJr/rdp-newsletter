<?php
/*
 Plugin name: Newsletters - RDP Brasil
 Plugin url: https://github.com/ReneFGJr/rdp-newsletter
 Description: Newsletter da RDP Brasil
 Version:18.03.12
 Author: Rene Faustino Gabriel Junior
 Author Uri: http://www.ufrgs.br/reed/rene
 License: GNU General Public License v2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: wporg
 Domain Path: /languages
 */

global $wpdb, $newsletter;

defined('ABSPATH') or die('No script kiddies please!');

/* definitions */
define("rdp_newsletter_VERSION", "18.03.09");
define("rdp_newsletter_PLUGIN", "RDP-newsletters");
define("rdp_newsletter_DIR", "/wp-content/plugins/rdp-newsletter/");
define("rdp_newsletter_TABLE_TEMPLAT", "wp_rdp_newsletter");

/* Shortcode ***************************************************************/
add_shortcode( 'rdp_newsletter_form', 'rdp_newsletter_form' );

/* active *************************************************************************/
register_activation_hook(__FILE__, 'rdp_newsletters_activate');
function rdp_newsletters_activate() {
    $rdp = new Rdp_newsletters;
    $rdp -> install();
}

/* desative ***********************************************************************/
register_deactivation_hook(__FILE__, 'rdp_newsletters_desactivate');
function rdp_newsletters_desactivate() {

}

require('model/versions.php');
require("model/functions_model.php");
require("model/rdp_newsletter_model.php");
require("controller/rdp_newsletter_controller.php");
?>