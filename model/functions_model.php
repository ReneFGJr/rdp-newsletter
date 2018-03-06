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
?>