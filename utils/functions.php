<?php
/**
 * 
 * @package Zoom Learning Platform
 * @subpackage M. Sufyan Shaikh
 * 
 */


if (!defined('ABSPATH')) {
    exit;
}

//Frontend Templates
require_once plugin_dir_path(__DIR__) . '/frontend/auth/signup.php';
require_once plugin_dir_path(__DIR__) . '/frontend/auth/login.php';
require_once plugin_dir_path(__DIR__) . '/frontend/dashboard/my-account.php';


// Shortcode 
add_shortcode('zlp_register_form', 'zoom_custom_register_form');
add_shortcode('zoom_login_form', 'zoom_custom_login_form');
add_shortcode('zoom_my_account', 'zoom_my_account_dashboard');




// Enqueue the JS

function zlp_frontend_script()
{
    wp_enqueue_script('zlp_frontenScript', plugins_url('../assets/js/script.js', __FILE__), ['jquery'], null, true);
    wp_enqueue_style('frontenStyle', plugins_url('../assets/css/style.css', __FILE__), array(), false);

    wp_localize_script('zlp_frontenScript', 'ajax_variables', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('frontend_nonce')
    ));
}   
add_action('wp_enqueue_scripts', 'zlp_frontend_script');


// Registration function
add_action('wp_ajax_nopriv_zoom_register_user', 'zoom_handle_registration');

function zoom_handle_registration()
{
    check_ajax_referer('frontend_nonce', 'security');

    $fullname = sanitize_text_field($_POST['fullname']);
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($role)) {
        wp_send_json_error('Please fill in all fields.');
    }

    if (username_exists($username) || email_exists($email)) {
        wp_send_json_error('Username or email already exists.');
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error('Registration failed.');
    }

    // Set role and full name
    wp_update_user([
        'ID' => $user_id,
        'display_name' => $fullname,
        'first_name' => $fullname, // Optional
    ]);

    $user = new WP_User($user_id);
    $user->set_role($role);

    // Auto-login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    do_action('wp_login', $username, $user);

    wp_send_json_success(['redirect' => site_url('/my-account')]);
}


add_action('wp_ajax_nopriv_zoom_login_user', 'zoom_handle_login');

function zoom_handle_login() {
    check_ajax_referer('frontend_nonce', 'security');

    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        wp_send_json_error('Please fill in both fields.');
    }

    $user = wp_signon([
        'user_login' => $username,
        'user_password' => $password,
        'remember' => true,
    ]);

    if (is_wp_error($user)) {
        wp_send_json_error('Invalid username or password.');
    }

    wp_send_json_success(['redirect' => site_url('/my-account')]);
}
