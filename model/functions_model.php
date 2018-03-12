<?php
//if (!function_exists('troca'))
{
    function trocar($qutf, $qc, $qt) {
        if (is_array($qutf)) {
            return ('erro');
        }
        return (str_replace(array($qc), array($qt), $qutf));
    }

}

if (!function_exists('validaemail')) {
    function validaemail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($alias, $domain) = explode("@", $email);
            if (checkdnsrr($domain, "MX")) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
if (!function_exists('cr')) {
    function cr() {
    	return(chr(13).chr(10));
    } }
}
?>