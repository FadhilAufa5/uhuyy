import TomSelect from 'tom-select'
import '/node_modules/tom-select/dist/css/tom-select.bootstrap4.css';
import { Toast } from './toast.js';

window.TomSelect = TomSelect;

// Initialize modern toast system
window.toast = new Toast();

// Dark mode toggle functionality
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', initDarkMode);

// Re-initialize after Livewire navigation
document.addEventListener('livewire:navigated', initDarkMode);

