<?php
$pageTitle = 'Manage Users - Admin';
require_once ROOT_PATH . '/public/views/includes/header.php';
?>

<div class="min-h-screen bg-[var(--bg-primary)] p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">Manage Users</h1>
                <p class="text-[var(--text-muted)]">View and manage all user accounts</p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo BASE_URL; ?>admin/create-user" class="px-4 py-2 bg-[var(--btn-primary)] hover:bg-[var(--btn-hover)] text-white rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add User
                </a>
                <a href="<?php echo BASE_URL; ?>" class="px-4 py-2 bg-[var(--bg-card)] border border-[var(--border-color)] text-white rounded-lg hover:bg-[var(--bg-secondary)] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[var(--bg-input)] border-b border-[var(--border-color)]">
                            <th class="text-left py-4 px-6 text-sm font-semibold">User</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold">Email</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold">Games</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold">Playtime</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold">Joined</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold">Role</th>
                            <th class="text-right py-4 px-6 text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="border-b border-[var(--border-color)] hover:bg-[var(--bg-input)]">
                            <td class="py-4 px-6">
                                <span class="font-medium"><?php echo h($user['username']); ?></span>
                            </td>
                            <td class="py-4 px-6 text-sm text-[var(--text-muted)]"><?php echo h($user['email']); ?></td>
                            <td class="py-4 px-6 text-sm"><?php echo $user['game_count']; ?></td>
                            <td class="py-4 px-6 text-sm"><?php echo number_format($user['total_hours'] ?? 0, 0); ?>h</td>
                            <td class="py-4 px-6 text-sm text-[var(--text-muted)]"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td class="py-4 px-6">
                                <?php if ($user['is_admin']): ?>
                                <span class="px-2 py-1 bg-[var(--btn-primary)]/20 text-[var(--btn-primary)] rounded text-xs font-semibold">Admin</span>
                                <?php else: ?>
                                <span class="px-2 py-1 bg-[var(--text-muted)]/20 text-[var(--text-muted)] rounded text-xs">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-end gap-2">
                                    <a href="<?php echo BASE_URL; ?>admin/edit-user?id=<?php echo $user['id']; ?>" class="px-3 py-1 bg-[var(--btn-primary)]/20 text-[var(--btn-primary)] rounded hover:bg-[var(--btn-primary)]/30 transition-colors text-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <button onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo h($user['username']); ?>')" class="px-3 py-1 bg-[var(--danger)]/20 text-[var(--danger)] rounded hover:bg-[var(--danger)]/30 transition-colors text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(userId, username) {
    if (!confirm(`Are you sure you want to delete user "${username}"? This action cannot be undone.`)) {
        return;
    }

    fetch('<?php echo BASE_URL; ?>admin/delete-user', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            user_id: userId
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            showNotification('error', 'An error occurred');
            console.error('Error:', error);
        });
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-xl max-w-md z-50 ${
type === 'success' ? 'bg-[var(--success)] text-white' : 'bg-[var(--danger)] text-white'
}`;
    notification.innerHTML = `
<div class="flex items-center gap-3">
    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
    <span>${message}</span>
</div>
`;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>

<?php
require_once ROOT_PATH . '/public/views/includes/footer.php';
?>
