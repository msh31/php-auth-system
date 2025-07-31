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
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                        <h1 class="text-center fw-bold fs-4 mb-0">Create Account</h1>
                    </div>

                    <!-- Error Message -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>register" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-with-icon">
                                <i class="fa fa-envelope input-icon"></i>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-with-icon">
                                <i class="fa fa-user input-icon"></i>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-with-icon">
                                <i class="fa fa-lock input-icon"></i>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="confirm-password" class="form-label">Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fa fa-lock input-icon"></i>
                                <input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I accept the <a href="#">Terms and Conditions</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="submit" class="btn btn-primary w-100 py-2 mb-3">
                            Create Account
                        </button>

                        <!-- Login Link -->
                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>login" class="text-muted small">Login here</a>
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

            const confirmPassword = document.getElementById('confirm-password');
            if (confirmPassword && password) {
                confirmPassword.addEventListener('input', function() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity("Passwords don't match");
                    } else {
                        confirmPassword.setCustomValidity('');
                    }
                });
            }
        });
    </script>

<?php
require_once ROOT_PATH . '/public/views/includes/footer.php';
?>