<?php

class Rdp_newsletters {
    var $update_path = 'http://www.ufrgs.br/redd/plugin/rpd_newsletter/version.php';
    
    function dashboard()
        {
            
        }
    
    function main_menu()
        {
            add_menu_page(__('RDP Newsletter', 'textdomain'), 'Newsletters', 'manage_options', 'rdp_newsletter_admin', 'rdp_newsletter_admin_home', plugins_url('rdp-newsletter/img/icon_newletter.png'), 60);
            add_submenu_page('rdp_newsletter_admin', 'DMP Templates', 'Drashboard', 'manage_options', 'rdp_dashboard', 'rdp_dashboard', '1');
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

    /************************** FORM SUBSCRIPT **********/
    function subscript($pos = "L") {
        switch ($pos) {
            case 'L' :
                $sx = $this->vmc_view('view\form_subscript_landscape.php',null);
                break;
            default :
                $sx = readfile('view\form_subscript_portable.php');
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

    function knowledge_list() {
        return ("X");
    }
    
    function vmc_view($page='',$data=array())
        {
           $msg = array();
           $msg['$name'] = 'Nome';
           $msg['$email'] = 'e-mail';
           $msg['$submit'] = 'Inscriver-se';
            
           $f = plugin_dir_path(__FILE__).'../'.$page;
           $f = readfile($f);
           return($f); 
        }
}
?>