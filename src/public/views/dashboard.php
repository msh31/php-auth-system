<?php
require_once ROOT_PATH . '/public/views/includes/header.php';
?>

    <div class="container py-5">
        <h1 class="page-title mb-4">Welcome, <?php echo h($_SESSION['username']); ?></h1>

        <div class="row g-4 mb-4">
            <!-- Account Information Card -->
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="dashboard-card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Account Information</h5>
                        <a href="#" class="btn btn-sm btn-primary">Edit Profile</a>
                    </div>
                    <div class="dashboard-card-body">
                        <?php if ($userData): ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Username</p>
                                    <p class="mb-0"><?php echo h($userData['username']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Email</p>
                                    <p class="mb-0"><?php echo h($userData['email']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Joined</p>
                                    <p class="mb-0"><?php echo h($userData['created_at']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Account Status</p>
                                    <span class="badge badge-success">Active</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Account Security Card -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-card-header">
                        <h5 class="mb-0">Account Security</h5>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Password</span>
                                <span class="badge badge-success">Strong</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Two-Factor Authentication</span>
                                <span class="badge badge-danger">Disabled</span>
                            </div>
                            <button class="btn btn-primary w-100">Enable 2FA</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="table-container">
                <table class="activity-table">
                    <thead>
                    <tr>
                        <th>Activity</th>
                        <th>IP Address</th>
                        <th>Date & Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($userActivities)): ?>
                        <?php foreach ($userActivities as $activity): ?>
                            <tr>
                                <td><?php echo h($activity['activity_type']); ?></td>
                                <td><?php echo h($activity['ip_address']); ?></td>
                                <td><?php echo h($activity['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td>Account created</td>
                            <td><?php echo $_SERVER['REMOTE_ADDR']; ?></td>
                            <td><?php echo $userData ? h($userData['created_at']) : date('Y-m-d H:i:s'); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Home</a>
<!--            <a href="#" class="btn btn-primary">Account Settings</a>-->
            <a href="<?php echo BASE_URL; ?>logout" class="btn btn-danger">Logout</a>
        </div>
    </div>

<?php
require_once ROOT_PATH . '/public/views/includes/footer.php';
?>