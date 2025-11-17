import TomSelect from 'tom-select'
import '/node_modules/tom-select/dist/css/tom-select.bootstrap4.css';
import { Toast } from './toast.js';
import { initLazyLoading, optimizeTable, preconnectDomains, optimizeFormSubmissions } from './performance.js';

window.TomSelect = TomSelect;

// Initialize modern toast system
window.toast = new Toast();

// ============================================
// PERFORMANCE OPTIMIZATION
// ============================================

// Loading Progress Bar
class LoadingProgress {
    constructor() {
        this.bar = null;
        this.init();
    }

    init() {
        // Create progress bar
        const style = document.createElement('style');
        style.textContent = `
            #loading-bar {
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
                z-index: 99999;
                transition: width 0.3s ease, opacity 0.3s ease;
                box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
            }
            #loading-bar.hide {
                opacity: 0;
            }
        `;
        document.head.appendChild(style);

        this.bar = document.createElement('div');
        this.bar.id = 'loading-bar';
        this.bar.style.width = '0%';
        this.bar.style.opacity = '0';
        document.body.appendChild(this.bar);
    }

    start() {
        if (!this.bar) return;
        this.bar.classList.remove('hide');
        this.bar.style.opacity = '1';
        this.bar.style.width = '0%';
        
        // Simulate progress
        setTimeout(() => this.bar.style.width = '30%', 50);
        setTimeout(() => this.bar.style.width = '60%', 200);
        setTimeout(() => this.bar.style.width = '80%', 400);
    }

    done() {
        if (!this.bar) return;
        this.bar.style.width = '100%';
        setTimeout(() => {
            this.bar.classList.add('hide');
            setTimeout(() => this.bar.style.width = '0%', 300);
        }, 200);
    }
}

const loadingBar = new LoadingProgress();

// Livewire hooks for instant feedback
document.addEventListener('livewire:init', () => {
    // Show loading bar on Livewire requests
    Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
        loadingBar.start();
    });

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        loadingBar.done();
    });

    // Navigation hooks
    Livewire.hook('navigate', ({ uri, options, history }) => {
        loadingBar.start();
    });

    Livewire.hook('navigated', () => {
        loadingBar.done();
    });
});

// Instant click feedback
document.addEventListener('click', (e) => {
    const target = e.target.closest('a, button');
    if (target && !target.disabled) {
        target.style.transform = 'scale(0.98)';
        setTimeout(() => target.style.transform = '', 100);
    }
}, true);

// Prefetch links on hover for instant navigation
let prefetchTimeout;
document.addEventListener('mouseover', (e) => {
    const link = e.target.closest('a[wire\\:navigate]');
    if (link && link.href) {
        clearTimeout(prefetchTimeout);
        prefetchTimeout = setTimeout(() => {
            const url = new URL(link.href);
            if (url.origin === window.location.origin) {
                // Prefetch the page
                fetch(link.href, {
                    method: 'GET',
                    headers: { 'X-Livewire': 'true' }
                }).catch(() => {});
            }
        }, 100);
    }
});

// Debounce function for inputs
window.debounce = function(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

// ============================================
// DARK MODE
// ============================================
function initDarkMode() {
    // Check for saved preference or system preference
    const theme = localStorage.getItem('theme') || 
                  (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    }
    
    // Toggle function
    const toggleDarkMode = () => {
        document.documentElement.classList.toggle('dark');
        const isDark = document.documentElement.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    };
    
    // Attach to all dark mode toggles
    const toggles = document.querySelectorAll('#dark-mode-toggle, #dark-mode-toggle-mobile');
    toggles.forEach(toggle => {
        if (toggle) {
            // Remove old listeners
            toggle.replaceWith(toggle.cloneNode(true));
            const newToggle = document.getElementById(toggle.id);
            if (newToggle) {
                newToggle.addEventListener('click', toggleDarkMode);
            }
        }
    });
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    loadingBar.done();
    initLazyLoading();
    optimizeTable();
    preconnectDomains();
    optimizeFormSubmissions();
});

// Re-initialize after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    initDarkMode();
    initLazyLoading();
    optimizeTable();
});

