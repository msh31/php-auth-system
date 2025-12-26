document.addEventListener('DOMContentLoaded', function() {
    const notificationContainer = document.getElementById('alertPlaceholder');
    if (!notificationContainer) return;

    function hideNotification(notification) {
        notification.classList.add('-translate-x-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }

    const notifications = document.querySelectorAll('.notification');
    notifications.forEach((notification) => {
        setTimeout(() => notification.classList.remove('-translate-x-full'), 50);
        setTimeout(() => hideNotification(notification), 5000);

        const btn = notification.querySelector('button');
        if (btn) btn.addEventListener('click', () => hideNotification(notification));
    });

    window.showNotification = function(message, type='success') {
        const notificationElement = document.createElement('div');
        const color = type === 'error' ? 'bg-red-600' : 'bg-green-600';
        const icon = type === 'error' ? '<i class="fa fa-exclamation-circle"></i>' : '<i class="fa fa-check-circle"></i>';

        notificationElement.className = `notification ${color} relative w-full mb-4 p-4 pr-10 rounded-lg shadow-lg text-white flex items-start transition-transform duration-300 transform -translate-x-full`;
        notificationElement.setAttribute('role', 'alert');

        notificationElement.innerHTML = `
            <span class="mr-3">${icon}</span>
            <span class="flex-1">${message}</span>
            <button class="absolute top-2 right-3 text-white text-lg font-bold hover:text-gray-200">&times;</button>
        `;

        notificationContainer.appendChild(notificationElement);

        setTimeout(() => notificationElement.classList.remove('-translate-x-full'), 50);
        setTimeout(() => hideNotification(notificationElement), 5000);

        notificationElement.querySelector('button').addEventListener('click', () => hideNotification(notificationElement));
    };
});
