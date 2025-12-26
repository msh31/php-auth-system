<?php require_once ROOT_PATH . '/public/views/includes/header.php'; ?>

<div class="min-h-[100dvh] flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4">
    <div class="w-full max-w-md bg-slate-800 border border-white/10 rounded-xl shadow-lg p-6">

        <div class="text-center mb-6">
            <div class="flex justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-white">Sign In</h1>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="mb-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 text-sm">
                Registration successful! Please login.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 text-sm">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>login" method="post" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div>
                <label class="block text-sm text-slate-300 mb-1">Username</label>
                <div class="relative">
                    <i class="fa fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="username"
                        required
                        class="w-full pl-10 pr-3 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter your username">
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-300 mb-1">Password</label>
                <div class="relative">
                    <i class="fa fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full pl-10 pr-10 py-2 rounded-lg bg-slate-700 border border-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="••••••••">
                    <button type="button" id="togglePassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="remember" class="rounded border-white/20 bg-slate-700">
                Remember me
            </label>

            <button class="w-full py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition text-white font-medium">
                Sign In
            </button>

            <p class="text-center text-sm text-slate-400">
                Don’t have an account?
                <a href="<?= BASE_URL ?>register" class="text-indigo-400 hover:underline">Sign up</a>
            </p>
        </form>
    </div>
</div>

<script>
    document.getElementById('togglePassword')?.addEventListener('click', function () {
        const p = document.getElementById('password');
        p.type = p.type === 'password' ? 'text' : 'password';
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>

<?php require_once ROOT_PATH . '/public/views/includes/footer.php'; ?>
