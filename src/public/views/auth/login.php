<?php
require_once ROOT_PATH . '/public/views/includes/header.php';
?>

    <div class="container py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-container">
            <div class="auth-card">
                <div class="p-4">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <h1 class="text-center fw-bold fs-4 mb-0">Sign In</h1>
                    </div>

                    <!-- Success Message -->
                    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fa fa-check-circle me-2"></i>
                            Registration successful! Please login with your credentials.
                        </div>
                    <?php endif; ?>

                    <!-- Error Message -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>login" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-with-icon">
                                <i class="fa fa-user input-icon"></i>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label">Password</label>
                                <a href="#" class="text-muted small">Forgot password?</a>
                            </div>
                            <div class="input-with-icon">
                                <i class="fa fa-lock input-icon"></i>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="submit" class="btn btn-primary w-100 py-2 mb-3">
                            Sign In
                        </button>

                        <!-- Register Link -->
                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>register" class="text-muted small">Sign up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    const eyeIcon = this.querySelector('i');
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>

<?php
require_once ROOT_PATH . '/public/views/includes/footer.php';
?>