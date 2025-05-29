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

require_once WP_PLUGIN_DIR . '/video-conferencing-with-zoom-api/includes/api/class-zvc-zoom-api-v2.php';



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

function zoom_handle_login()
{
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

add_action('wp_ajax_zoom_create_class', 'zoom_handle_create_class');
add_action('wp_ajax_nopriv_zoom_create_class', 'zoom_handle_create_class');

function zoom_handle_create_class()
{
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'frontend_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }

    $current_user = wp_get_current_user();
    if (!user_can($current_user, 'edit_posts')) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    // Sanitize form inputs
    $title = sanitize_text_field($_POST['class_title']);
    $desc = sanitize_textarea_field($_POST['class_description']);
    $start_time = sanitize_text_field($_POST['start_date']);

    $timezone = sanitize_text_field($_POST['timezone']);
    $duration_hour = intval($_POST['option_duration_hour']);
    $duration_minutes = intval($_POST['option_duration_minutes']);
    $password = sanitize_text_field($_POST['password']);

    // Load Zoom API class
    if (!class_exists('Zoom_Video_Conferencing_Api')) {
        wp_send_json_error(['message' => 'Zoom API class not found']);
    }

    $zoom_api = new Zoom_Video_Conferencing_Api();

    // Create meeting on Zoom
    $duration = ($duration_hour * 60) + $duration_minutes;
    $meeting_args = [
        'topic' => $title,
        'type' => 2,
        'start_time' => date('Y-m-d\TH:i:s', strtotime($start_time)),
        'duration' => $duration,
        'timezone' => $timezone,
        'password' => $password,
        'settings' => [
            'host_video' => false,
            'participant_video' => false,
            'join_before_host' => false,
            'mute_upon_entry' => false,
            'approval_type' => 2,
            'audio' => 'voip',
            'auto_recording' => 'none',
            'waiting_room' => true,
        ],
    ];

    // Create Zoom meeting
    $zoom_response = $zoom_api->createAMeeting($current_user->ID, $meeting_args);

    if (is_wp_error($zoom_response) || isset($zoom_response->code)) {
        wp_send_json_error(['message' => 'Zoom meeting creation failed', 'error' => $zoom_response]);
    }

    // Create WP post
    $post_id = wp_insert_post([
        'post_type' => 'zoom-meetings',
        'post_title' => $title,
        'post_content' => $desc,
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
    ]);

    // Save metadata
    update_post_meta($post_id, 'start_date', $start_time);
    update_post_meta($post_id, 'timezone', $timezone);
    update_post_meta($post_id, 'option_duration_hour', $duration_hour);
    update_post_meta($post_id, 'option_duration_minutes', $duration_minutes);
    update_post_meta($post_id, 'password', $password);

    // Zoom plugin required meta fields
    update_post_meta($post_id, '_meeting_type', 2);
    update_post_meta($post_id, '_vczapi_meeting_type', 'meeting');
    update_post_meta($post_id, '_meeting_field_start_date_utc', $zoom_response->start_time);
    update_post_meta($post_id, '_meeting_zoom_meeting_id', $zoom_response->id);
    update_post_meta($post_id, '_meeting_zoom_join_url', $zoom_response->join_url);
    update_post_meta($post_id, '_meeting_zoom_start_url', $zoom_response->start_url);
    update_post_meta($post_id, '_meeting_zoom_details', $zoom_response);

    // Serialize _meeting_fields array (same as admin-created)
    $meeting_fields = [
        'userId' => $zoom_response->host_id,
        'meeting_type' => 2,
        'start_date' => $start_time,
        'timezone' => $timezone,
        'duration' => $duration,
        'password' => $password,
        'disable_waiting_room' => null,
        'meeting_authentication' => null,
        'option_host_video' => null,
        'option_auto_recording' => 'none',
        'alternative_host_ids' => null,
        'join_before_host' => null,
        'jbh_time' => 0,
        'option_participants_video' => null,
        'option_mute_participants' => null,
        'site_option_logged_in' => null,
        'site_option_browser_join' => null,
        'site_option_enable_debug_log' => null,
    ];

    update_post_meta($post_id, '_meeting_fields', $meeting_fields);

    wp_send_json_success(['message' => 'Class created successfully!', 'post_id' => $post_id]);
}
