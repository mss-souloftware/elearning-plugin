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
function zoom_my_account_dashboard()
{
    if (!is_user_logged_in()) {
        return '<p>You must be logged in to view this page. <a href="' . site_url('/login') . '">Login</a></p>';
    }

    $current_user = wp_get_current_user();
    $role = $current_user->roles[0]; // Get first role
    ob_start();
    ?>
    <div class="zoom-my-account">
        <h2>Welcome, <?php echo esc_html($current_user->display_name); ?>!</h2>
        <p>Role: <strong><?php echo ucfirst($role); ?></strong></p>

        <div class="zoom-dashboard-menu">
            <button class="zoom-tab-btn" data-tab="dashboard">Dashboard</button>
            <?php if ($role === 'instructor'): ?>
                <button class="zoom-tab-btn" data-tab="my-classes">My Classes</button>
                <button class="zoom-tab-btn" data-tab="create-class">Create Class</button>
                <button class="zoom-tab-btn" data-tab="earnings">Earnings</button>
            <?php elseif ($role === 'student'): ?>
                <button class="zoom-tab-btn" data-tab="my-enrollments">My Enrollments</button>
                <button class="zoom-tab-btn" data-tab="orders">Orders</button>
            <?php endif; ?>
            <button class="zoom-tab-btn"
                onclick="window.location.href='<?php echo wp_logout_url(site_url('/login')); ?>'">Logout</button>
        </div>

        <div class="zoom-tab-content" id="zoom-tab-dashboard">
            <h3>Dashboard</h3>
            <p>This is your general account dashboard.</p>
        </div>

        <div class="zoom-tab-content" id="zoom-tab-my-classes" style="display:none;">
            <h3>My Classes</h3>
            <p>Instructor's classes will be shown here.</p>
        </div>

        <div class="zoom-tab-content" id="zoom-tab-create-class" style="display:none;">
            <h3>Create a Class</h3>
            <p>Form to create a new class here.</p>
        </div>

        <div class="zoom-tab-content" id="zoom-tab-earnings" style="display:none;">
            <h3>Earnings</h3>
            <p>Earnings summary here.</p>
        </div>

        <div class="zoom-tab-content" id="zoom-tab-my-enrollments" style="display:none;">
            <h3>My Enrollments</h3>
            <p>Student's enrolled classes will be shown here.</p>
        </div>

        <div class="zoom-tab-content" id="zoom-tab-orders" style="display:none;">
            <h3>Orders</h3>
            <p>Student's order history here.</p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.zoom-tab-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                document.querySelectorAll('.zoom-tab-content').forEach(tab => tab.style.display = 'none');
                const id = this.getAttribute('data-tab');
                if (id) {
                    document.getElementById('zoom-tab-' + id).style.display = 'block';
                }
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
