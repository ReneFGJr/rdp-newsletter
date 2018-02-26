<?php
/*
 Plugin name: Newsletters - RDP Brasil
 Plugin url: https://github.com/ReneFGJr/rdp-newsletter
 Description: Newsletter da RDP Brasil
 Version: 1.18.05
 Author: Rene Faustino Gabriel Junior
 Author Uri: http://www.ufrgs.br/reed/rene
 License: GPLv2 or Later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: wporg
 Domain Path: /languages
 */

defined('ABSPATH') or die('No script kiddies please!');

/* definitions */
define("rdp_newsletter_VERSION", "v0.18.02.23");
define("rdp_newsletter_PLUGIN", "RDP-newsletters");
define("rdp_newsletter_DIR", "/wp-content/plugins/rdp-newsletter/");
define("rdp_newsletter_TABLE_TEMPLAT", "wp_rdp_newsletter");

require ("model/rdp_newsletter_model.php");

// CSS
//wp_enqueue_style(rdp_newsletter_PLUGIN, get_site_url() . rdp_newsletter_DIR . ('css/style.css'), array(), rdp_newsletter_VERSION, 'all');
//wp_enqueue_style("bootstrap", get_site_url() . rdp_newsletter_DIR . ('css/bootstrap.css'), array(), rdp_newsletter_BOOTSTRAP_VERSION, 'all');
//wp_enqueue_script("bootstrap", get_site_url() . rdp_newsletter_DIR . ('js/bootstrap.js'), array(), rdp_newsletter_BOOTSTRAP_VERSION, 'all');

/**********************************************************************************/
/* active *************************************************************************/
/**********************************************************************************/
register_activation_hook(__FILE__, 'rdp_newsletter_activate');
function rdp_newsletter_activate() {
    $rdp = new rdp_newsletter;
    $rdp -> install();
}

/**********************************************************************************/
/* desative ***********************************************************************/
/**********************************************************************************/
register_deactivation_hook(__FILE__, 'rdp_newsletter_desactivate');
function rdp_newsletter_desactivate() {

}

/**********************************************************************************/
/* Admin Home Page ****************************************************************/
function rdp_newsletter_rdp_plugin() {
    echo "********************** rdp_newsletter_rdp_plugin";
}

/**********************************************************************************/
/* Register a custom menu page ****************************************************/
/**********************************************************************************/
function rdp_register_my_custom_menu_page() {
    global $wpdb;
    add_menu_page(__('RDP Newsletter', 'textdomain'), 'Newsletters', 'manage_options', 'rdp_newsletter_admin', 'rdp_newsletter_admin_home', plugins_url('rdp-newsletter/img/icon_newletter.png'), 6);
    add_submenu_page('rdp_newsletter_admin', 'DMP Templates', 'Drashboard', 'manage_options', 'rdp_newsletter_admin_templat', 'rdp_newsletter_admin_templat', '1');
    add_submenu_page('rdp_newsletter_admin', 'DMP Templates', 'Mailer groups', 'manage_options', 'rdp_newsletter_admin_templat', 'rdp_newsletter_admin_templat', '1');
    add_submenu_page('rdp_newsletter_admin', 'Knowledge ', 'Users registered', 'manage_options', 'rdp_newsletter_admin_group_members', 'rdp_newsletter_admin_knowledge', '2');

    global $menu, $submenu;

    $page = 'index.php/gdp';
}

add_action('admin_menu', 'rdp_register_my_custom_menu_page');

/* custom */

function rdp_newsletter_admin_home() {
    $c = new rdp_newsletter;
    $sx = $c -> cab('home');
    echo $sx;

    echo $c -> subscript();
}

/*************** templat *****************************/
function rdp_newsletter_admin_templat() {
    global $data, $wpdb;
    $c = new rdp_newsletter;
    $page = $_GET['action'];
    $id = $_GET['id'];
    echo $c -> cab('Registred Groups');

    switch ($page) {
        case 'list' :
            $data = $c -> le_templat($id);
            $data = get_object_vars($data);

            view('view/templat_show.php');
            $link = '<a href="admin.php?page=rdp_newsletter_admin_templat" class="btn btn-default">';
            $novo = $link . msg('return') . '</a>';
            echo $novo;

            break;
        default :
            $sx .= $c -> mailer_groups();
            break;
    }
    echo $sx;
}

function rdp_newsletter_admin_knowledge() {
    $c = new rdp_newsletter;

    echo $c -> cab('templat');

    switch ($page) {
        case 'list' :
            $sx = "<h1>Hello</h1>'";
            break;
        default :
            $sx = $c -> knowledge_list();
            break;
    }
    echo $sx;
}

/*********************************************************************************************/
/*********************************************************************************************/
/*********************************************************************************************/
/***************** REGISTRAR SHORTCODE */
add_shortcode('rdp_newsletter_form', 'rdp_newsletter_form');

function rdp_newsletter_form() {
    $c = new rdp_newsletter;
    $sx .= $c -> subscript();
    return ($sx);
}

/*********************************************************************************************/
if (!function_exists('msg')) {
    function msg($t) {
        return ($t);
    }

}
if (!function_exists('view')) {
    function view($f) {
        //$f = rdp_newsletter_DIR . $f;
        $dir = $_SERVER['SCRIPT_NAME'];
        $dir = substr($dir, 0, strpos($dir, '/admin'));
        $dir .= '/../' . rdp_newsletter_DIR;
        $f = $dir . $f;
        if (file_exists($f)) {
            require ($f);
            return ("");
        } else {
            return ("ERRO VIEW");
        }
    }

}

if (!function_exists('cr')) {
    function cr() {
        return (chr(13) . chr(10));
    }

}
if (!function_exists('hex')) {
    function hex($v, $sz = 6) {
        $s = dechex($v);
        while (strlen($s) < $sz) { $s = '0' . $s;
        }
        return ($s);
    }

}
if (!function_exists('troca')) {
    function troca($qutf, $qc, $qt) {
        if (is_array($qutf)) {
            return ('erro');
        }
        return (str_replace(array($qc), array($qt), $qutf));
    }

}
?>
