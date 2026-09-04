
'use strict';

const App = {
    init() {
        this.handleMobileMenu();
        this.handleFlashMessages();
        this.highlightActiveLinks();
        this.setupGlobalEvents();
        this.fixLayoutIssues();
    },
    handleMobileMenu() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if (!btn || !menu) return;

        btn.addEventListener('click', () => {
            const isHidden = menu.classList.toggle('hidden');
            const icon = btn.querySelector('i');

            if (icon) {
                icon.className = isHidden 
                    ? 'fa-solid fa-bars text-xl' 
                    : 'fa-solid fa-xmark text-xl';
            }
        });
    },

    handleFlashMessages() {
        const toast = document.getElementById('notification-toast');
        if (!toast) return;

        setTimeout(() => {
            toast.style.transition = 'all 0.5s ease-in-out';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';

            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        }, 3000);
    },

    highlightActiveLinks() {
        const currentUrl = window.location.href;
        const navLinks = document.querySelectorAll('nav a');

        navLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('text-brand-600', 'bg-brand-50', 'font-bold');
                link.classList.remove('text-slate-600', 'hover:text-brand-600');
            }
        });
    },

    setupGlobalEvents() {
        document.addEventListener('click', (e) => {
            const deleteBtn = e.target.closest('.btn-delete');
            
            if (deleteBtn) {
                const message = deleteBtn.getAttribute('data-confirm') || 
                              'Bạn có chắc chắn muốn xóa mục này không? Hành động này không thể hoàn tác.';
                
                if (!confirm(message)) {
                    e.preventDefault();
                }
            }
        });
    },

    fixLayoutIssues() {
        if (history.scrollRestoration) {
            history.scrollRestoration = 'manual';
        }

        const wrapper = document.getElementById('booking-wrapper') || document.getElementById('auth-wrapper');
        const mainContent = document.getElementById('app-content');

        if (wrapper && mainContent && wrapper.parentNode !== mainContent) {
            mainContent.appendChild(wrapper);
        }
    }
};
document.addEventListener('DOMContentLoaded', () => App.init());