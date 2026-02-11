<?php
function formatPhoneNumber($number) {
    // Remove any non-digit characters
    $cleaned = preg_replace('/\D+/', '', $number);
    
    // Format the number as (XXX) XXX-XXXX
    if (strlen($cleaned) == 10) {
        return sprintf("(%s) %s-%s", 
            substr($cleaned, 0, 3), 
            substr($cleaned, 3, 3), 
            substr($cleaned, 6, 4)
        );
    } else {
        return "Invalid phone number";
    }
}
?>