<?php require_once ROOT_PATH . '/public/views/includes/header.php'; ?>

<div class="min-h-[100dvh] flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4">
    <div class="w-full max-w-md bg-slate-800 border border-white/10 rounded-xl shadow-lg p-6">

        <div class="text-center mb-6">
            <div class="flex justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-white">Create Account</h1>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 text-sm">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>register" method="post" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div>
                <label class="block text-sm text-slate-300 mb-1">Email Address</label>
                <div class="relative">
                    <i class="fa fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="email"
                        name="email"
                        required
                        class="w-full pl-10 pr-3 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="name@example.com">
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-300 mb-1">Username</label>
                <div class="relative">
                    <i class="fa fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="username"
                        required
                        class="w-full pl-10 pr-3 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Choose a username">
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-300 mb-1">Password</label>
                <div class="relative">
                    <i class="fa fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full pl-10 pr-10 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="••••••••">
                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-300 mb-1">Confirm Password</label>
                <div class="relative">
                    <i class="fa fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        id="confirm-password"
                        type="password"
                        name="confirm-password"
                        required
                        class="w-full pl-10 pr-3 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="••••••••">
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="terms" required class="rounded border-white/20 bg-slate-700">
                I accept the <a href="#" class="text-indigo-400 hover:underline">Terms and Conditions</a>
            </label>

            <button type="submit" class="w-full py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition text-white font-medium">
                Create Account
            </button>

            <p class="text-center text-sm text-slate-400">
                Already have an account?
                <a href="<?= BASE_URL ?>login" class="text-indigo-400 hover:underline">Login here</a>
            </p>
        </form>
    </div>
</div>

<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    toggle?.addEventListener('click', () => {
        password.type = password.type === 'password' ? 'text' : 'password';
        toggle.querySelector('i').classList.toggle('fa-eye');
        toggle.querySelector('i').classList.toggle('fa-eye-slash');
    });

    const confirmPassword = document.getElementById('confirm-password');
    confirmPassword?.addEventListener('input', () => {
        confirmPassword.setCustomValidity(password.value !== confirmPassword.value ? "Passwords don't match" : "");
    });
</script>

<?php require_once ROOT_PATH . '/public/views/includes/footer.php'; ?>
