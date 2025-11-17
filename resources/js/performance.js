// ============================================
// PERFORMANCE OPTIMIZATION UTILITIES
// ============================================

/**
 * Image lazy loading
 */
export function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

/**
 * Optimize table rendering
 */
export function optimizeTable() {
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        // Add will-change for better performance
        table.style.willChange = 'transform';
        
        // Remove after animation
        setTimeout(() => {
            table.style.willChange = 'auto';
        }, 1000);
    });
}

/**
 * Preconnect to external domains
 */
export function preconnectDomains() {
    const domains = [
        'https://fonts.bunny.net',
        'https://cdn.jsdelivr.net'
    ];

    domains.forEach(domain => {
        const link = document.createElement('link');
        link.rel = 'preconnect';
        link.href = domain;
        document.head.appendChild(link);
    });
}

/**
 * Optimize form submissions
 */
export function optimizeFormSubmissions() {
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (form.tagName === 'FORM') {
            // Disable submit button to prevent double submission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalText = submitBtn.textContent;
                submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 inline mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                
                // Re-enable after timeout (fallback)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = submitBtn.dataset.originalText;
                }, 10000);
            }
        }
    });
}

/**
 * Cache API responses
 */
export class ResponseCache {
    constructor(maxAge = 300000) { // 5 minutes default
        this.cache = new Map();
        this.maxAge = maxAge;
    }

    get(key) {
        const item = this.cache.get(key);
        if (!item) return null;

        const now = Date.now();
        if (now - item.timestamp > this.maxAge) {
            this.cache.delete(key);
            return null;
        }

        return item.data;
    }

    set(key, data) {
        this.cache.set(key, {
            data,
            timestamp: Date.now()
        });
    }

    clear() {
        this.cache.clear();
    }
}

export const apiCache = new ResponseCache();
