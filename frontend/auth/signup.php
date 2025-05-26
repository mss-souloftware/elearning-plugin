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
function zoom_custom_register_form()
{
    ob_start();
    ?>
    <form id="zoom-register-form">
        <input type="text" name="fullname" placeholder="Full Name" required><br>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <label>
            Register as:
            <select name="role" required>
                <option value="student">Student</option>
                <option value="instructor">Instructor</option>
            </select>
        </label><br>

        <input type="hidden" name="action" value="zoom_register_user">
        <input type="hidden" name="security" value="<?php echo esc_attr(wp_create_nonce('frontend_nonce')); ?>">

        <button type="submit">Register</button>
        <div id="zoom-register-response"></div>
    </form>
    <?php
    return ob_get_clean();
}
