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

    <div class="mainPanel">
        <div class="sideBar">
            <h1>eLearning</h1>
            <ul>
                <?php if ($role === 'instructor'): ?>
                    <li>
                        <a class="zoom-tab-btn" data-tab="dashboard" href="#">Dashboard</a>
                    </li>
                    <li>
                        <a class="zoom-tab-btn" data-tab="my-classes" href="#">My Classes</a>
                    </li>
                    <li>
                        <a class="zoom-tab-btn" data-tab="create-class" href="#">Add New Class</a>
                    </li>
                    <li>
                        <a class="zoom-tab-btn" data-tab="students" href="#">Students</a>
                    </li>
                    <li>
                        <a class="zoom-tab-btn" data-tab="earnings" href="#">Earnings</a>
                    </li>
                <?php elseif ($role === 'student'): ?>
                    <li>
                        <a class="zoom-tab-btn" data-tab="my-enrollments" href="#">My Enrollments</a>
                    </li>
                    <li>
                        <a class="zoom-tab-btn" data-tab="orders" href="#">Orders</a>
                    </li>
                <?php endif; ?>
                <li>
                    <a class="zoom-tab-btn" href="<?php echo wp_logout_url(site_url('/sample-page')); ?>">Logout</a>
                </li>
            </ul>
        </div>
        <div class="contentBox">
            <?php if ($role === 'instructor'): ?>
                <div class="zoom-tab-content" id="zoom-tab-dashboard">
                    <h2>Overview</h2>
                    <div class="gcPanel">
                        <div class="gcCard">
                            <p>
                                Total Classes
                            </p>
                            <h2>5</h2>
                        </div>
                        <div class="gcCard">
                            <p>
                                Upcoming Classes
                            </p>
                            <h2>2</h2>
                        </div>
                        <div class="gcCard">
                            <p>
                                Total Student
                            </p>
                            <h2>52</h2>
                        </div>
                        <div class="gcCard">
                            <p>
                                Total Earnings
                            </p>
                            <h2>€ 1255</h2>
                        </div>
                    </div>

                    <h2>Recent Added</h2>
                    <div class="classesPanel">
                        <div class="classesBox">
                            <div class="thumbnail">
                                <img src="https://img-c.udemycdn.com/course/240x135/3142166_a637_3.jpg" alt="">
                            </div>
                            <h3>Class title</h3>
                            <p class="authorBox">
                                Category Name
                            </p>
                            <p class="duration">Duration: 30 min</p>
                            <p class="pricing">Price: € 15.99</p>
                            <div class="actionsPanel">
                                <a href="#">Edit Details</a>
                                <a href="#">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- My Classes Tab -->
                <div class="zoom-tab-content" id="zoom-tab-my-classes" style="display:none;">
                    <h3>My Classes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Zoom Link</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="zoom-my-classes-list">
                            <tr>
                                <td>Sample Class</td>
                                <td>2025-06-01</td>
                                <td><a href="#">Join</a></td>
                                <td>Published</td>
                                <td><a href="#">Edit</a> | <a href="#">Delete</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Create Class Tab -->
                <div class="zoom-tab-content" id="zoom-tab-create-class" style="display:none;">
                    <h3>Create New Class</h3>

                    <form id="zoom-create-class-form">
                        <label>Meeting Title</label>
                        <input type="text" name="class_title" required>

                        <label>Class Description</label>
                        <textarea name="class_description" rows="4" required></textarea>

                        <label>Start Date</label>
                        <input type="datetime-local" name="start_date" required>

                        <label>Timezone</label>
                        <select name="timezone" required>
                            <?php foreach (timezone_identifiers_list() as $tz): ?>
                                <option value="<?php echo esc_attr($tz); ?>"><?php echo esc_html($tz); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Duration (Hours)</label>
                        <input type="number" name="option_duration_hour" min="0" max="10" value="1" required>

                        <label>Duration (Minutes)</label>
                        <input type="number" name="option_duration_minutes" min="0" max="59" value="0" required>

                        <label>Meeting Password</label>
                        <input type="text" name="password" value="<?php echo wp_generate_password(8, false); ?>" required>

                        <input type="submit" name="create_zoom_meeting" value="Create Meeting">
                    </form>

                    <div id="zoom-create-class-response" style="margin-top: 10px;"></div>

                </div>

                <!-- Students Tab -->
                <div class="zoom-tab-content" id="zoom-tab-students" style="display:none;">
                    <h3>All Students</h3>
                    <p>Your all students will appear here. Coming soon!</p>
                </div>

                <!-- Earnings Tab -->
                <div class="zoom-tab-content" id="zoom-tab-earnings" style="display:none;">
                    <h3>Earnings Summary</h3>
                    <p>Your earnings will appear here. Coming soon!</p>
                </div>
            <?php elseif ($role === 'student'): ?>
                <!-- Enrolments Tab -->
                <div class="zoom-tab-content" id="zoom-tab-my-enrollments">
                    <h3>Enrollments Summary</h3>
                    <p>Your earnings will appear here. Coming soon!</p>
                </div>

                <!-- Orders Tab -->
                <div class="zoom-tab-content" id="zoom-tab-orders" style="display:none;">
                    <h3>Order Summary</h3>
                    <p>Your orders will appear here. Coming soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.zoom-tab-btn').forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelectorAll('.zoom-tab-content').forEach(tab => tab.style.display = 'none');

                    const id = this.getAttribute('data-tab');
                    const tabElement = document.getElementById('zoom-tab-' + id);

                    if (tabElement) {
                        tabElement.style.display = 'block';
                    } else {
                        console.warn(`Tab content not found for ID: zoom-tab-${id}`);
                    }
                });
            });
        });

    </script>
    <?php
    return ob_get_clean();
}
