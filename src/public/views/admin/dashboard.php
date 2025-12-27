<?php
$pageTitle = 'Admin Dashboard - Steam Tracker';
$currentPage = 'admin';
$useSidebar = false;
require_once ROOT_PATH . '/public/views/includes/header.php';
?>

<div class="min-h-screen bg-[var(--bg-primary)] p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
                <p class="text-[var(--text-muted)]">Manage users and system settings</p>
            </div>
            <a href="<?php echo BASE_URL; ?>dashboard" class="px-4 py-2 bg-[var(--bg-card)] border border-[var(--border-color)] text-white rounded-lg hover:bg-[var(--bg-secondary)] transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to App
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-xl p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-[var(--text-secondary)]">Total Users</h3>
                    <i class="fas fa-users text-[var(--btn-primary)] text-xl"></i>
                </div>
                <p class="text-3xl font-bold"><?php echo count($users); ?></p>
            </div>
        </div>

        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-xl p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Quick Actions</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="<?php echo BASE_URL; ?>admin/users" class="p-4 bg-[var(--bg-input)] rounded-lg hover:bg-[var(--bg-secondary)] transition-colors">
                    <i class="fas fa-users text-2xl text-[var(--btn-primary)] mb-2"></i>
                    <h3 class="font-semibold mb-1">Manage Users</h3>
                    <p class="text-sm text-[var(--text-muted)]">View, edit, and delete users</p>
                </a>

                <a href="<?php echo BASE_URL; ?>admin/create-user" class="p-4 bg-[var(--bg-input)] rounded-lg hover:bg-[var(--bg-secondary)] transition-colors">
                    <i class="fas fa-user-plus text-2xl text-[var(--success)] mb-2"></i>
                    <h3 class="font-semibold mb-1">Add User</h3>
                    <p class="text-sm text-[var(--text-muted)]">Create a new user account</p>
                </a>

                <!-- <a href="<?php echo BASE_URL; ?>settings" class="p-4 bg-[var(--bg-input)] rounded-lg hover:bg-[var(--bg-secondary)] transition-colors"> -->
                <!--     <i class="fas fa-cog text-2xl text-[var(--text-muted)] mb-2"></i> -->
                <!--     <h3 class="font-semibold mb-1">Settings</h3> -->
                <!--     <p class="text-sm text-[var(--text-muted)]">System configuration</p> -->
                <!-- </a> -->
            </div>
        </div>

        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-xl p-6">
            <h2 class="text-xl font-bold mb-4">Recent Users</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[var(--border-color)]">
                            <th class="text-left py-3 px-4 text-sm font-medium text-[var(--text-secondary)]">User</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[var(--text-secondary)]">Email</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[var(--text-secondary)]">Joined</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[var(--text-secondary)]">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($users, 0, 5) as $user): ?>
                        <tr class="border-b border-[var(--border-color)] hover:bg-[var(--bg-input)]">
                            <td class="py-3 px-4">
                                <span class="font-medium"><?php echo h($user['username']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-sm text-[var(--text-muted)]"><?php echo h($user['email']); ?></td>
                            <td class="py-3 px-4 text-sm text-[var(--text-muted)]"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td class="py-3 px-4">
                                <?php if ($user['is_admin']): ?>
                                <span class="px-2 py-1 bg-[var(--btn-primary)]/20 text-[var(--btn-primary)] rounded text-xs font-semibold">Admin</span>
                                <?php else: ?>
                                <span class="px-2 py-1 bg-[var(--text-muted)]/20 text-[var(--text-muted)] rounded text-xs">User</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <a href="<?php echo BASE_URL; ?>admin/users" class="text-sm text-[var(--btn-primary)] hover:underline">
                    View all users →
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once ROOT_PATH . '/public/views/includes/footer.php';
?>
