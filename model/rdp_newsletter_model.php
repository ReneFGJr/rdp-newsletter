<?php

class Rdp_newsletters {
    var $update_path = 'http://www.ufrgs.br/redd/plugin/rpd_newsletter/version.php';

    function dashboard() {
        $nw = new Rdp_newsletters();
        $nw -> cab();
        echo '<h1>Dashboard</h1>';
    }

    function manual() {
        $nw = new Rdp_newsletters();
        $nw -> cab();
        echo '<h1>Manual</h1>';
        echo $nw -> vmc_view("view/help.php");
    }

    function setting() {
        $nw = new Rdp_newsletters();
        $nw -> cab();
        echo '<h1>Setting</h1>';
    }

    function main_menu() {
        $active = '<b style="color:#f9845b">';
        $active_a = '</b>';

        add_menu_page('RDP Newsletter', 'RDP Newsletter', 'manage_options', 'rdp-dashboard', array(__CLASS__, 'dashboard'), plugins_url('rdp-newsletter/img/icon_newletter.png'), 5);

        /* DASHBOARD */
        add_submenu_page('rdp-dashboard', 'RDP simple' . ' Dashboard', ' Dashboard', 'manage_options', 'rdp-dashboard', array(__CLASS__, 'dashboard'));
        add_submenu_page('rdp-dashboard', 'RDP simple' . ' Settings', ' Settings', 'manage_options', 'rdp-settings', array(__CLASS__, 'setting'));
        add_submenu_page('rdp-dashboard', 'RDP simple' . ' Manual', ' Manual', 'manage_options', 'rdp-manual', array(__CLASS__, 'manual'));
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
    function subscript($arg, $pos = "L") {
        switch ($pos) {
            case 'L' :
                $sx = '
                        <form methos="post">
                        <table class="row rdp_newsletter_body" border=0 width="80%" style="margin: 5px; border-radius: 10px;">
                            <tr>
                                <td colspan=3 style="padding: 10px;"><h1>Newsletter</h1>
                                <p>
                                    Para receber atualizações
                                </p></td>
                            </tr>
                            <tr>
                                <td style="padding: 10px;"><span style="font-size: 65%;">Nome completo</span>
                                <input type="text" name="news_name" class="form-control" style="width:100%;" placeholder="Nome completo" value="' . $arg['name'] . '">
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px;"><span style="font-size: 65%;">email</span>
                                <input type="text" name="news_email" class="form-control" style="width:100%;" placeholder="email*"  value="' . $arg['email'] . '">
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px;">
                                <input type="submit" name="news_action" class="btn btn-primary rdp_newsletter_body_submit" value="Inscrever-se" style="width:100%;">
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; font-size: 20%;">&nbsp;</td>
                            </tr>
                        </table>            
                        </form>    
                ';
                $sx .= '</br><table class="row" border=0 width="80%" style="margin: 5px; border-radius: 10px;">' . cr();
                $sx .= '<tr><td>' . cr();
                $sx .= $arg[1] . cr();
                $sx .= '</td></tr></table>' . cr();
                break;
            default :
                $sx = readfile('view\form_subscript_portable.php');
                break;
        }
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

    function vmc_view($page = '', $data = array()) {
        $msg = array();
        $msg['$name'] = 'Nome';
        $msg['$email'] = 'e-mail';
        $msg['$submit'] = 'Inscriver-se';

        $f = plugin_dir_path(__FILE__) . '../' . $page;
        $f = readfile($f);
        return ($f);
    }

    function get($var = '') {
        $rs = '';
        if (isset($_POST[$var])) {
            $rs = $_POST[$var];
        }
        if (isset($_GET[$var])) {
            $rs = $_GET[$var];
        }
        return ($rs);
    }

    function subscript_user($name, $email) {
        global $wpdb;

        $erro = 0;
        $msg = '';

        if (validaemail($email)) {
            $msg = 'e-mail válido';
        } else {
            $erro = 630;
            $msg = '
            <div class="alert alert-danger">
              <strong>Erro 630!</strong> e-mail inválido.
            </div>';
        }

        /****************************************************************************/
        if ($erro == 0) {

            $sql = "select * from " . rdp_newsletter_TABLE_TEMPLAT . " 
                            where n_email = '$email' ";
            /*
             id_n serial NOT NULL,
             n_name char(100) NOT NULL,
             n_email char(100),
             n_status int(11),
             n_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
             n_area text
             )";
             *
             */
            //            $wpdb -> query($sql);
            $rst = $wpdb -> get_results($sql, OBJECT);
            if (count($rst) == 0) {
                $sql = "insert into " . rdp_newsletter_TABLE_TEMPLAT . "
                        ( n_name, n_email, n_status, n_area)
                        values
                        ('$name','$email','1','')";
                $wpdb -> query($sql);
            } else {
                $erro = 631;
                $msg = '
                    <div class="alert alert-danger">
                      <strong>Erro ' . $erro . '!</strong> e-mail "' . $email . '" já existente em nosso Newsletter.
                    </div>';

            }

        }

        return ( array($erro, $msg));
    }

}
?>