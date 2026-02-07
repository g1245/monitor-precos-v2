<!-- Toast Container - Fixed position for notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-[9999] space-y-3 max-w-md">
    <!-- Toasts will be dynamically inserted here -->
</div>

<script>
    // Toast Manager
    window.ToastManager = {
        container: null,
        
        init() {
            if (!this.container) {
                this.container = document.getElementById('toast-container');
            }
        },
        
        show(message, type = 'success', duration = 6000) {
            this.init();
            
            const toast = this.createToast(message, type);
            this.container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Auto remove after duration
            const autoRemoveTimer = setTimeout(() => {
                this.remove(toast);
            }, duration);
            
            // Store timer on toast element
            toast._autoRemoveTimer = autoRemoveTimer;
            
            return toast;
        },
        
        createToast(message, type) {
            const toast = document.createElement('div');
            toast.className = 'transform transition-all duration-300 translate-x-full opacity-0';
            
            const bgColors = {
                success: 'bg-green-50',
                error: 'bg-red-50',
                warning: 'bg-yellow-50',
                info: 'bg-blue-50'
            };
            
            const textColors = {
                success: 'text-green-800',
                error: 'text-red-800',
                warning: 'text-yellow-800',
                info: 'text-blue-800'
            };
            
            const iconColors = {
                success: 'text-green-500',
                error: 'text-red-500',
                warning: 'text-yellow-500',
                info: 'text-blue-500'
            };
            
            const icons = {
                success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
                info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
            };
            
            const bgColor = bgColors[type] || bgColors.info;
            const textColor = textColors[type] || textColors.info;
            const iconColor = iconColors[type] || iconColors.info;
            const icon = icons[type] || icons.info;
            
            toast.innerHTML = `
                <div role="alert" class="${bgColor} border border-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'blue'}-200 rounded-lg p-4 shadow-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 ${iconColor} shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${icon}
                        </svg>
                        
                        <div class="flex-1">
                            <p class="${textColor} text-sm font-medium leading-relaxed">${message}</p>
                        </div>
                        
                        <button type="button" class="toast-close-btn shrink-0 rounded-lg p-1 hover:bg-black/5 transition-colors">
                            <svg class="w-4 h-4 ${textColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Progress bar -->
                    <div class="mt-3 h-1 bg-black/5 rounded-full overflow-hidden">
                        <div class="toast-progress h-full bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'blue'}-500 transition-all duration-[6000ms] ease-linear" style="width: 100%"></div>
                    </div>
                </div>
            `;
            
            // Close button handler
            const closeBtn = toast.querySelector('.toast-close-btn');
            closeBtn.addEventListener('click', () => {
                this.remove(toast);
            });
            
            // Start progress bar animation
            setTimeout(() => {
                const progressBar = toast.querySelector('.toast-progress');
                if (progressBar) {
                    progressBar.style.width = '0%';
                }
            }, 10);
            
            return toast;
        },
        
        remove(toast) {
            // Clear auto remove timer if exists
            if (toast._autoRemoveTimer) {
                clearTimeout(toast._autoRemoveTimer);
            }
            
            // Animate out
            toast.classList.add('translate-x-full', 'opacity-0');
            
            // Remove from DOM after animation
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        },
        
        success(message, duration = 6000) {
            return this.show(message, 'success', duration);
        },
        
        error(message, duration = 6000) {
            return this.show(message, 'error', duration);
        },
        
        warning(message, duration = 6000) {
            return this.show(message, 'warning', duration);
        },
        
        info(message, duration = 6000) {
            return this.show(message, 'info', duration);
        }
    };
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        window.ToastManager.init();
    });
</script>
