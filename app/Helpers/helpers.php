<?php

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('clean_extra_spaces')) {
    function clean_extra_spaces($string) {
        return
            preg_replace(
                "/\s+/", " ",
                $string
            );
    }
}
