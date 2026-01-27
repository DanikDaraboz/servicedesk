@if(session('success') || session('error') || session('info') || session('warning'))
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-3">
    @if(session('success'))
    <div class="notification notification-success bg-green-600 shadow-2xl rounded-lg p-5 min-w-[380px] max-w-lg transform transition-all duration-300 ease-in-out" role="alert" style="opacity: 1 !important; background-color: #16a34a !important;">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-base font-semibold text-white">Успешно!</p>
                <p class="mt-1 text-base text-white">{{ session('success') }}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="this.closest('.notification').remove()" class="inline-flex text-white hover:text-green-100 focus:outline-none">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="notification notification-error bg-white border-l-4 border-red-500 shadow-lg rounded-lg p-4 min-w-[320px] max-w-md transform transition-all duration-300 ease-in-out translate-x-0 opacity-100" role="alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900">Ошибка!</p>
                <p class="mt-1 text-sm text-gray-600">{{ session('error') }}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="this.closest('.notification').remove()" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="notification notification-info bg-white border-l-4 border-blue-500 shadow-lg rounded-lg p-4 min-w-[320px] max-w-md transform transition-all duration-300 ease-in-out translate-x-0 opacity-100" role="alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900">Информация</p>
                <p class="mt-1 text-sm text-gray-600">{{ session('info') }}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="this.closest('.notification').remove()" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="notification notification-warning bg-white border-l-4 border-yellow-500 shadow-lg rounded-lg p-4 min-w-[320px] max-w-md transform transition-all duration-300 ease-in-out translate-x-0 opacity-100" role="alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900">Внимание!</p>
                <p class="mt-1 text-sm text-gray-600">{{ session('warning') }}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="this.closest('.notification').remove()" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.notification');
    
    notifications.forEach((notification, index) => {
        // Анимация появления
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
            // Для успешных уведомлений принудительно устанавливаем непрозрачность
            if (notification.classList.contains('notification-success')) {
                notification.style.opacity = '1';
                notification.style.backgroundColor = '#16a34a';
            }
        }, index * 100);
        
        // Автоматическое скрытие через 7 секунд
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 7000);
    });
    
    // Обработка клика на кнопку закрытия
    document.querySelectorAll('.notification button').forEach(button => {
        button.addEventListener('click', function() {
            const notification = this.closest('.notification');
            notification.style.transform = 'translateX(400px)';
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
    });
});
</script>

<style>
.notification {
    transform: translateX(400px);
    opacity: 0;
}

.notification-success {
    animation: slideInRight 0.3s ease-out forwards;
    background-color: #16a34a !important; /* green-600 */
    opacity: 1 !important;
}

.notification-error {
    animation: slideInRight 0.3s ease-out forwards;
}

.notification-info {
    animation: slideInRight 0.3s ease-out forwards;
}

.notification-warning {
    animation: slideInRight 0.3s ease-out forwards;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1 !important;
    }
}

/* Убираем любую прозрачность у успешных уведомлений */
.notification-success {
    opacity: 1 !important;
    background-color: #16a34a !important;
}

.notification-success * {
    opacity: 1 !important;
    color: white !important;
}
</style>
@endif
