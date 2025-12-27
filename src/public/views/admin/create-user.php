<?php
$pageTitle = 'Add User - Admin';
$currentPage = 'admin';
$useSidebar = false;
require_once ROOT_PATH . '/public/views/includes/header.php';
?>

<div class="min-h-screen bg-[var(--bg-primary)] p-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">Add New User</h1>
                <p class="text-[var(--text-muted)]">Create a new user account</p>
            </div>
            <a href="<?php echo BASE_URL; ?>admin/users" class="px-4 py-2 bg-[var(--bg-card)] border border-[var(--border-color)] text-white rounded-lg hover:bg-[var(--bg-secondary)] transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>

        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-xl p-6">
            <form id="createUserForm" onsubmit="createUser(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Username</label>
                    <input 
                        type="text"
                        id="username"
                        name="username"
                        required
                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-lg focus:outline-none focus:border-[var(--btn-primary)] transition-colors text-[var(--text-primary)]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email Address</label>
                    <input 
                        type="email"
                        id="email"
                        name="email"
                        required
                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-lg focus:outline-none focus:border-[var(--btn-primary)] transition-colors text-[var(--text-primary)]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input 
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="6"
                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-lg focus:outline-none focus:border-[var(--btn-primary)] transition-colors text-[var(--text-primary)]"
                    >
                    <p class="text-xs text-[var(--text-muted)] mt-1">Minimum 6 characters</p>
                </div>

                <div class="flex items-center gap-2">
                    <input 
                        type="checkbox"
                        id="is_admin"
                        name="is_admin"
                        class="w-4 h-4 rounded"
                    >
                    <label for="is_admin" class="text-sm">Admin privileges</label>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-[var(--btn-primary)] hover:bg-[var(--btn-hover)] text-white font-medium rounded-lg transition-colors">
                    Create User
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function createUser(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
    
    const formData = new FormData(form);
    
    fetch('<?php echo BASE_URL; ?>admin/create-user', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            setTimeout(() => {
                window.location.href = '<?php echo BASE_URL; ?>admin/users';
            }, 1000);
        } else {
            showNotification('error', data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        showNotification('error', 'An error occurred');
        console.error('Error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
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
