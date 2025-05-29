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

function zoom_custom_login_form()
{
    ob_start();
    ?>
    <h2>Login</h2>
    <form id="zoom-login-form">
        <input type="text" name="username" placeholder="Username or Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <input type="hidden" name="action" value="zoom_login_user">
        <input type="hidden" name="security" value="<?php echo esc_attr(wp_create_nonce('frontend_nonce')); ?>">
        <div class="submitWrapper">
            <button type="submit">Login</button>
        </div>
        <div id="zoom-login-response"></div>
    </form>
    <?php
    return ob_get_clean();
}
