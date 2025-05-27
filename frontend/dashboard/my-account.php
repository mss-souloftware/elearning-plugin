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
                                <img src="" alt="">
                            </div>
                            <h3>Class title</h3>
                            <p class="authorBox">
                                Instructor Name
                            </p>
                            <p class="duration">30 min</p>
                            <p class="pricing">€ 15.99</p>
                            <a href="#">View Details</a>
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
                        <label>Class Title:<br>
                            <input type="text" name="class_title" required>
                        </label><br><br>

                        <label>Description:<br>
                            <textarea name="class_description" required></textarea>
                        </label><br><br>

                        <label>Start Date & Time:<br>
                            <input type="datetime-local" name="class_start_time" required>
                        </label><br><br>

                        <label>Price ($):<br>
                            <input type="number" name="class_price" min="0" step="0.01">
                        </label><br><br>

                        <button type="submit">Create Class</button>
                        <div id="zoom-create-class-response"></div>
                    </form>
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
