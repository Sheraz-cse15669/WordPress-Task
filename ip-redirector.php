<?php
/*
Plugin Name: IP Redirector
Description: Redirect users based on IP address.
Version: 1.0
Author: Sheraz Ahmad
*/

function ip_based_redirect() {
    $allowed_ip_prefix = '77.29';

    // Get user's IP address
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Check if the IP address starts with the specified prefix
    if (strpos($user_ip, $allowed_ip_prefix) === 0) {
        // Redirect users with the specified IP prefix
        wp_redirect('https://your-redirect-url.com');
        exit();
    }
}

add_action('init', 'ip_based_redirect');
