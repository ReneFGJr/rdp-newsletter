<?php
/*
 Plugin name: Newsletters - RDP Brasil
 Plugin url: http://www.ufrgs.br/redd/newsletter/
 Description: Newsletter da RDP Brasil
 Version: 1.18.05
 Author: Rene Faustino Gabriel Junior
 Author Uri: http://www.ufrgs.br/reed/rene
 License: GPLv2 or Later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: wporg
 Domain Path: /languages
 */

/* definitions */
define("rdp_newsletter_VERSION", "v0.18.02.04");
define("rdp_newsletter_PLUGIN", "DMP-newsletters");
define("rdp_newsletter_DIR", "/wp-content/plugins/rdp-newsletter/");
define("rdp_newsletter_TABLE_TEMPLAT", "wp_rdp_newsletter");

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

class rdp_newsletter {

    function __construct() {
        add_action('admin_init', array($this, 'hook_admin_init'));

        if (is_admin()) {
            add_action('admin_head', array($this, 'hook_admin_head'));

            // Protection against strange schedule removal on some installations
            if (!wp_next_scheduled('newsletter') && (!defined('WP_INSTALLING') || !WP_INSTALLING)) {
                wp_schedule_event(time() + 30, 'newsletter', 'newsletter');
            }

            add_action('admin_menu', array($this, 'add_extensions_menu'), 90);
        }
    }

    function install() {
        global $wpdb;
        $sqlMembros = "CREATE TABLE IF NOT EXISTS " . rdp_newsletter_TABLE_TEMPLAT . " (
                id_n serial NOT NULL,
                  n_name char(100) NOT NULL,
                  n_email char(100),
                  n_status int(11),
                  n_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  n_area text
                )";
        $wpdb -> query($sqlMembros);

        $sqlMembros = "CREATE TABLE IF NOT EXISTS " . rdp_newsletter_TABLE_TEMPLAT . "_mailer (
                id_nm serial NOT NULL,
                  nm_name char(200) NOT NULL,
                  nm_text text,
                  nm_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  nm_group char(30)
                )";
        $wpdb -> query($sqlMembros);

        /* Plans */
        $sqlMembros = "CREATE TABLE IF NOT EXISTS " . rdp_newsletter_TABLE_TEMPLAT . "_groups (
                id_ng serial NOT NULL,
                  ng_name char(200) NOT NULL,
                  ng_status int(11),
                  ng_registered int(1)) NOT NULL DEFAULT 0,
                  ng_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
                )";
        $wpdb -> query($sqlMembros);

        return (1);
    }

    function cab($sub = '') {
        $img = rdp_newsletter_DIR . 'img/icone_rdp_newsletter_rnp.png';

        $img_logo = '<img src="' . get_site_url() . $img . '" class="img-thumbnail" style="height: 90px;" align="right">';
        $title = '<span class="newsletter_title">';
        $title .= 'RDP - Newsletter';
        if (strlen($sub) > 0) {
            $title .= ' - ' . $sub;
        }
        $title .= '</span>';

        $sx = '<div class="wrap">';
        $sx .= $img_logo;
        $sx .= '<h1 class="wp-heading-inline">' . $title . '</h1>';
        $sx .= '</div>';
        $sx .= '<hr>';

        return ($sx);
    }

    function cores($cor = '') {
        $sx = 'CORES<br>';
        $g = 0;
        $b = 0;
        $r = 0;
        echo '<table>';
        for ($g = 0; $g < 256; $g = $g + 17) {
            echo '<tr>';
            $r = 0;
            for ($b = 0; $b < 256; $b = $b + 17) {
                $cor = hex($r * 256 * 256 + $g * 256 + $b);
                $sx = '<td>';
                $sx .= '<a href="#" title="#' . $cor . '">';
                $sx .= '<div style="width: 15px; height: 15px; background-color: #';
                $sx .= $cor;
                $sx .= ';">';
                $sx .= '</div></a>' . cr();
                $sx .= '</td>';
                echo $sx;
            }
            $b = 255;
            for ($r = 0; $r < 256; $r = $r + 17) { 
                    $cor = hex($r * 256 * 256 + $g * 256 + $b);
                    $sx = '<td>';
                    $sx .= '<a href="#" title="#' . $cor . '">';
                    $sx .= '<div style="width: 15px; height: 15px; background-color: #';
                    $sx .= $cor;
                    $sx .= ';">';
                    $sx .= '</div></a>' . cr();
                    $sx .= '</td>';
                    $b = $b - 17;
                    echo $sx;                
            }
            echo '</tr>';
        }
        $rn = 17;
        $rg = 255;
        /********************* parte II ********************/
        for ($g = (255-17); $g >= 0; $g = $g - 17) {
            echo '<tr>';
            $r = 0;
            for ($b = 0; $b < 256; $b = $b + 17) {
                $cor = hex(($rn) * 256 * 256 + $g * 256 + $b);
                $sx = '<td>';
                $sx .= '<a href="#" title="#' . $cor . '">';
                $sx .= '<div style="width: 15px; height: 15px; background-color: #';
                $sx .= $cor;
                $sx .= ';">';
                $sx .= '</div></a>' . cr();
                $sx .= '</td>';
                echo $sx;
            }
            $b = 255;
            $f = 0;
            $rg = $g;
            for ($r = 0; $r < 256; $r = $r + 17) { {
                    $max = ($r+$rn);
                    if ($max > 255) { $max = 255; }
                    if ($rg > 255) { $rg = 255; }
                    $cor = hex($max * 256 * 256 + ($rg) * 256 + ($b+$f));
                    $sx = '<td>';
                    $sx .= '<a href="#" title="#' . $cor . '">';
                    $sx .= '<div style="width: 15px; height: 15px; background-color: #';
                    $sx .= $cor;
                    $sx .= ';">';
                    $sx .= '</div></a>' . cr();
                    $sx .= '</td>';
                    $b = $b - 17;
                    $rg = $rg + 17;
                    echo $sx;
                }
            }

            $rn = $rn + 17;
            $rg = $rg + 17;
            echo '</tr>';
        }
        echo '</table>';
        return ($sx);
    }

    /************************** FORM SUBSCRIPT **********/
    function subscript($pos = "L") {
        switch ($pos) {
            case 'L' :
                $sx = '
                <table class="row rdp_newsletter_body" border=0 width="100%" style="margin: 5px; border-radius: 10px;">
                    <tr>
                        <td colspan=3 style="padding: 10px;">
                            <h1>Newsletter</h1>
                            <p>Para receber atualizações</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">
                        <span style="font-size: 65%;">' . msg('name') . '</span>
                        <input type="text" name="news_name" class="form-control" style="width:100%;" placeholder="Nome*">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">
                        <span style="font-size: 65%;">' . msg('email') . '</span>
                        <input type="text" name="news_name" class="form-control" style="width:100%;" placeholder="Email*">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">
                        <input type="submit" name="news_name" class="btn btn-primary" value="Inscreva-se" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 20%;">&nbsp;</td>
                    </tr>                    
                </table>
                ';
                break;
            default :
                $sx = '
                <table class="row rdp_newsletter_body" border=0 width="90%" style="margin: 5px; border-radius: 10px;">
                    <tr>
                        <td colspan=3 style="padding: 10px;">
                            <h1>Newsletter</h1>
                            <p>Para receber atualizações</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">                        
                        <input type="text" name="news_name" class="form-control" style="width:100%;" placeholder="Nome*">
                        </td>

                        <td style="padding: 10px;">
                        <input type="text" name="news_name" class="form-control" style="width:100%;" placeholder="Email*">
                        </td>

                        <td style="padding: 10px;">
                        <input type="submit" name="news_name" class="btn btn-primary" value="Inscreva-se" style="width:100%;">
                        </td>
                    </tr>
                </table>
                ';
                break;
        }
        $sx .= '
        <style>
            .rdp_newsletter_body
                {
                    background-color: #404080;
                    color: #ffffff;
                }
            
        </style>
        ';
        return ($sx);
    }

    /************************** MAILER GROUPS ***********/
    function mailer_groups() {
        global $wpdb;
        $sql = "select * from " . rdp_newsletter_TABLE_TEMPLAT . "_groups ";
        $wpdb -> query($sql);
        $rlt = $wpdb -> last_result;
        $rlt = $wpdb -> get_results($wpdb -> prepare($sql, $some_parameter));
        echo '<table class="table" width="100%">';
        echo '
            <tr>
                <th>Status</th>
                <th>Created</th>
                <th align="left">Description</th>
            </tr>        
            ';
        foreach ($rlt as $rlt) {
            echo '<tr>';

            echo '<td width="5%" align="center">';
            echo $rlt -> ng_status;
            echo '</td>';

            echo '<td width="10%" align="center">';
            echo $rlt -> ng_created;
            echo '</td>';

            echo '<td width="80%">';
            echo $rlt -> ng_name;
            echo '</td>';

            echo '</tr>';
        }
        echo '</table>';
    }

}

/**********************************************************************************/
/* Admin Home Page ****************************************************************/
function rdp_newsletter_rdp_plugin() {
    echo "********************** rdp_newsletter_rdp_plugin";
}

/**********************************************************************************/
/* Register a custom menu page ****************************************************/
/**********************************************************************************/
function dmprdp_register_my_custom_menu_page() {
    global $wpdb;
    add_menu_page(__('RDP Newsletter', 'textdomain'), 'Newsletters', 'manage_options', 'rdp_newsletter_admin', 'rdp_newsletter_admin_home', plugins_url('DMP-Wordpress/img/icone_menu_r.png'), 6);
    //add_menu_page('RDP Newsletter', 'RDP Newsletter', (1 == 1) ? 'manage_categories' : 'manage_options', 'newsletter_main_index', '', plugins_url('rdp_newsletter') . '/images/menu-icon.png', '30.333');
    add_submenu_page('rdp_newsletter_admin', 'DMP Templates', 'Drashboard', 'manage_options', 'rdp_newsletter_admin_templat', 'rdp_newsletter_admin_templat', '1');
    add_submenu_page('rdp_newsletter_admin', 'DMP Templates', 'Mailer groups', 'manage_options', 'rdp_newsletter_admin_templat', 'rdp_newsletter_admin_templat', '1');
    add_submenu_page('rdp_newsletter_admin', 'Knowledge ', 'Users registered', 'manage_options', 'rdp_newsletter_admin_group_members', 'rdp_newsletter_admin_knowledge', '2');

    global $menu, $submenu;

    $page = 'index.php/gdp';
}

add_action('admin_menu', 'dmprdp_register_my_custom_menu_page');

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
    $c = new dmp;

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
    $sx .= $c -> cores();
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
