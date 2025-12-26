<?php
require_once 'includes/header.php';
?>

<div class="min-h-[100dvh] flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-6">
    <div class="max-w-3xl w-full text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            Marco's PHP Auth System
        </h1>

        <p class="text-slate-300 text-lg mb-10">
            A simple, secure authentication system built with PHP and Tailwind CSS.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?= BASE_URL ?>login"
               class="px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-500 transition">
                Login
            </a>

            <a href="<?= BASE_URL ?>register"
               class="px-6 py-3 rounded-lg bg-slate-700 text-white font-medium hover:bg-slate-600 transition">
                Register
            </a>

            <a href="<?= BASE_URL ?>dashboard"
               class="px-6 py-3 rounded-lg border border-slate-600 text-slate-200 hover:bg-slate-800 transition">
                Dashboard
            </a>

            <a href="<?= BASE_URL ?>logout"
               class="px-6 py-3 rounded-lg text-red-400 border border-red-500/30 hover:bg-red-500/10 transition">
                Logout
            </a>
        </div>

        <p class="mt-12 text-sm text-slate-500">
            Built by Marco using plain PHP & Tailwind CSS
        </p>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
