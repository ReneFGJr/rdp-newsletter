<?php

add_action('admin_menu', 'rdp_register_my_custom_menu_page');

defined('ABSPATH') or die('No script kiddies please!');

/* definitions */
define("rdp_newsletter_VERSION", "v0.18.02.23");
define("rdp_newsletter_PLUGIN", "RDP-newsletters");
define("rdp_newsletter_DIR", "/wp-content/plugins/rdp-newsletter/");
define("rdp_newsletter_TABLE_TEMPLAT", "wp_rdp_newsletter");

function rdp_register_my_custom_menu_page() {
    $nw = new Rdp_newsletters();
    $nw -> main_menu();
}

function rdp_newsletter_admin_home() {
    $nw = new Rdp_newsletters();
    $nw -> cab();
    echo '<h1>ADMIN</h1>';
}


/**********************************************************************************/
/* active *************************************************************************/
/**********************************************************************************/
register_activation_hook(__FILE__, 'rdp_newsletter_activate');
function rdp_newsletter_activate() {
    $rdp = new Rdp_newsletters;
    $rdp -> install();
}

/**********************************************************************************/
/* desative ***********************************************************************/
/**********************************************************************************/
register_deactivation_hook(__FILE__, 'rdp_newsletter_desactivate');
function rdp_newsletter_desactivate() {
}

/************************************************************************************************************/
/* https://code.tutsplus.com/tutorials/a-guide-to-the-wordpress-http-api-automatic-plugin-updates--wp-25181 */
/************************************************************************************************************/
function getRemote_version() {
    $nw = new Rdp_newsletters();
    $request = wp_remote_post($nw -> update_path, array('body' => array('action' => 'version')));
    if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
        return $request['body'];
    }
    return false;
}

function rdp_newsletter_form() {
    $nw = new Rdp_newsletters;
    $name = $nw->get("news_name");
    $email = $nw->get("news_email");
    $action = $nw->get("news_action");
    $erro = '';
    $arg = array(0,'');
    if (strlen($action) > 0)
        {
            $arg = $nw->subscript_user($name,$email);
            $ok = $arg[0];
            if ($ok==0)
                {
                    $sx = '
                    <div class="alert alert-success">
                      <strong>Successo!</strong> Obrigado '.$name.', seu e-mail '.$email.' foi incluído em nossa Newsletter.
                    </div>
                    ';
                    return($sx);
                } else {
                	
                }
        }
    $arg['name'] = $name;
    $arg['email'] = $email;
    $sx .= $nw -> subscript($arg);
	//$sx .= $arg[1];
    return ($sx);
}

function check_update($transient) {
    if (empty($transient -> checked)) {
        return $transient;
    }

    // Get the remote version
    $remote_version = $this -> getRemote_version();

    // If a newer version is available, add the update
    if (version_compare($this -> current_version, $remote_version, '<')) {
        $obj = new stdClass();
        $obj -> slug = $this -> slug;
        $obj -> new_version = $remote_version;
        $obj -> url = $this -> update_path;
        $obj -> package = $this -> update_path;
        $transient -> response[$this -> plugin_slug] = $obj;
    }
    return $transient;
}
?>