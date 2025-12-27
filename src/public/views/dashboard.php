<?php require_once ROOT_PATH . '/public/views/includes/header.php'; ?>

<div class="min-h-[100dvh] bg-slate-900 px-4 py-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <h1 class="text-3xl font-semibold text-white">Welcome, <?= h($_SESSION['username']); ?></h1>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-slate-800 border border-white/10 rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-white">Your Account Information</h2>
                    <button onclick="openModal()" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm px-3 py-1 rounded transition">Edit Profile</button>
                </div>

                <?php if ($userData): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-300">
                    <div>
                        <p class="text-sm text-slate-400">Username</p>
                        <p class="text-white"><?= h($userData['username']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Email</p>
                        <p class="text-white"><?= h($userData['email']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Joined</p>
                        <p class="text-white"><?= h($userData['created_at']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Account Status</p>
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-600 text-white">Active</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-slate-800 border border-white/10 rounded-xl shadow p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Account Security</h2>

                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-slate-400">Password</span>
                            <span class="text-xs font-semibold px-2 py-1 rounded bg-green-600 text-white">Strong</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full w-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-slate-400">Two-Factor Authentication</span>
                            <span class="text-xs font-semibold px-2 py-1 rounded bg-red-600 text-white">Disabled</span>
                        </div>
                        <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2 rounded transition">Enable 2FA</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-slate-800 border border-white/10 rounded-xl shadow p-6 overflow-x-auto">
            <h2 class="text-xl font-semibold text-white mb-4">Recent Activity</h2>
            <table class="w-full text-left text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="px-3 py-2">Activity</th>
                        <th class="px-3 py-2">IP Address</th>
                        <th class="px-3 py-2">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php if (!empty($userActivities)): ?>
                    <?php foreach ($userActivities as $activity): ?>
                    <tr>
                        <td class="px-3 py-2"><?= h($activity['activity_type']); ?></td>
                        <td class="px-3 py-2"><?= h($activity['ip_address']); ?></td>
                        <td class="px-3 py-2"><?= h($activity['created_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td class="px-3 py-2">Account created</td>
                        <td class="px-3 py-2"><?= $_SERVER['REMOTE_ADDR']; ?></td>
                        <td class="px-3 py-2"><?= $userData ? h($userData['created_at']) : date('Y-m-d H:i:s'); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="<?= BASE_URL ?>" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded transition">Home</a>
            <a href="<?= BASE_URL ?>logout" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded transition">Logout</a>
            <?php if (isAdmin()): ?>
            <a href="<?= BASE_URL ?>admin" class="bg-orange-600 hover:bg-orange-500 text-white px-4 py-2 rounded transition <?php echo ($currentPage ?? '') == 'admin' ? 'active' : ''; ?>">Admin Panel</a>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-slate-800 border border-white/10 rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-white">Edit Profile</h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <?php if (!empty($error)): ?>
        <div class="mb-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 text-sm">
            <?= htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>dashboard" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div>
                <label class="block text-sm text-slate-300 mb-1">New Username</label>
                <input
                    type="text"
                    name="new_username"
                    value="<?= h($_SESSION['username']) ?>"
                    required
                    class="w-full px-3 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Enter new username">
            </div>

            <div>
                <label class="block text-sm text-slate-300 mb-1">Current Password</label>
                <input
                    type="password"
                    name="current_password"
                    required
                    class="w-full px-3 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Confirm with your password">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition text-white font-medium">
                    Save Changes
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 transition text-white font-medium">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('editProfileModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
}

document.getElementById('editProfileModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

<?php if (!empty($error)): ?>
    openModal();
<?php endif; ?>
</script>

<?php require_once ROOT_PATH . '/public/views/includes/footer.php'; ?>
