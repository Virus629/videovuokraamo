<?php
    function checkPhoneNumber($number) { // SOURCE: https://www.codespeedy.com/how-to-validate-phone-number-in-php/
        $filterPhoneNumber = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
        $numberToCheck = str_replace("-", "", $filterPhoneNumber);

        if(strlen($numberToCheck) < 10 || strlen($numberToCheck) > 14) {
            return false;
        } else {
            return true;
        }
    }
?>