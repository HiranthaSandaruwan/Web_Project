// Enhanced Hardware Request Management System - Complete JS
// Global utility functions (backwards compatibility)
function confirmDelete(){ return confirm('Are you sure you want to delete this item?'); }

document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    initializeTheme();
    initializeSidebar();
    initializeFormValidation();
    initializeDashboardAnimations();
    initializeTableInteractions();
    initializeNotifications();
}

// ========================================
// THEME SYSTEM
// ========================================
function initializeTheme() {
    const themeToggle = document.getElementById('theme-toggle');
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    // Apply saved theme
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeToggle(savedTheme);
    
    // Theme toggle functionality
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeToggle(newTheme);
    
    // Add smooth transition effect
    document.body.style.transition = 'all 0.3s ease';
    setTimeout(() => {
        document.body.style.transition = '';
    }, 300);
}

function updateThemeToggle(theme) {
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        const icon = theme === 'dark' ? '🌞' : '🌙';
        const text = theme === 'dark' ? 'Light' : 'Dark';
        themeToggle.innerHTML = `<span>${icon}</span> ${text}`;
    }
}

// ========================================
// SIDEBAR NAVIGATION
// ========================================
function initializeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    
    // Mobile menu toggle
    if (mobileMenuToggle && sidebar) {
        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (sidebar && !sidebar.contains(e.target) && !mobileMenuToggle?.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && sidebar) {
            sidebar.classList.remove('show');
        }
    });
    
    // Active page highlighting
    highlightActivePage();
}

function highlightActivePage() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (linkPath === currentPath || (currentPath.includes(linkPath) && linkPath !== '/')) {
            link.classList.add('active');
        }
    });
}

// ========================================
// FORM VALIDATION & INTERACTION
// ========================================
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Real-time validation
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
                // Real-time validation for certain fields
                if (this.type === 'email' || this.hasAttribute('data-validate-realtime')) {
                    validateField(this);
                }
            });
        });
        
        form.addEventListener('submit', function(e) {
            if(form.hasAttribute('data-skip-validate')) return;
            
            let hasErrors = false;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    hasErrors = true;
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
                showNotification('Please fix the errors before submitting.', 'error');
                // Focus on first error field
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Show loading state
                const submitBtn = form.querySelector('[type="submit"], .btn-primary');
                if (submitBtn) {
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = '<span class="loading"></span> Processing...';
                    submitBtn.disabled = true;
                    
                    // Reset after delay if form doesn't redirect
                    setTimeout(() => {
                        if (submitBtn) {
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                        }
                    }, 3000);
                }
            }
        });
    });
}

function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('data-label') || field.name || 'This field';
    let isValid = true;
    let message = '';
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        message = `${fieldName} is required.`;
    }
    
    // Email validation
    else if (field.type === 'email' && value && !isValidEmail(value)) {
        isValid = false;
        message = 'Please enter a valid email address.';
    }
    
    // Password validation
    else if (field.type === 'password' && value && value.length < 3) {
        isValid = false;
        message = 'Password must be at least 3 characters long.';
    }
    
    // Text length validation
    else if (field.type === 'text' && value && value.length < 2) {
        isValid = false;
        message = `${fieldName} must be at least 2 characters long.`;
    }
    
    // Select validation
    else if (field.tagName === 'SELECT' && field.hasAttribute('required') && (!value || value === '')) {
        isValid = false;
        message = 'Please select an option.';
    }
    
    // Custom validation patterns
    else if (field.hasAttribute('pattern') && value && !new RegExp(field.pattern).test(value)) {
        isValid = false;
        message = field.getAttribute('data-pattern-message') || `${fieldName} format is invalid.`;
    }
    
    // Display validation result
    if (!isValid) {
        showFieldError(field, message);
    } else {
        clearFieldMessages(field);
    }
    
    return isValid;
}

function showFieldError(field, message) {
    clearFieldMessages(field);
    field.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'form-error';
    errorDiv.innerHTML = `<span>❌</span> ${message}`;
    
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
}

function clearFieldError(field) {
    field.classList.remove('error');
    clearFieldMessages(field);
}

function clearFieldMessages(field) {
    const parent = field.parentNode;
    const existingError = parent.querySelector('.form-error');
    
    if (existingError) existingError.remove();
    
    field.classList.remove('error', 'success');
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// ========================================
// DASHBOARD ANIMATIONS & INTERACTIONS
// ========================================
function initializeDashboardAnimations() {
    // Animate dashboard statistics
    const statNumbers = document.querySelectorAll('.stat-number');
    if (statNumbers.length > 0) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateNumber(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });
        
        statNumbers.forEach(stat => observer.observe(stat));
    }
    
    // Animate cards on scroll
    const cards = document.querySelectorAll('.card');
    if (cards.length > 0) {
        const cardObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        });
        
        cards.forEach(card => cardObserver.observe(card));
    }
}

function animateNumber(element) {
    const target = parseInt(element.textContent) || 0;
    const duration = 1500;
    const step = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += step;
        element.textContent = Math.floor(current);
        
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        }
    }, 16);
}

// ========================================
// TABLE INTERACTIONS
// ========================================
function initializeTableInteractions() {
    // Enhanced table search
    const tableSearch = document.getElementById('table-search');
    if (tableSearch) {
        tableSearch.addEventListener('input', function() {
            filterTable(this.value);
        });
    }
}

function filterTable(searchTerm) {
    const tables = document.querySelectorAll('table');
    
    tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(searchTerm.toLowerCase());
            row.style.display = matches ? '' : 'none';
        });
    });
}

// ========================================
// NOTIFICATIONS SYSTEM
// ========================================
function initializeNotifications() {
    // Auto-hide existing alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            fadeOut(alert);
        }, 5000);
    });
}

function showNotification(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} fade-in`;
    notification.innerHTML = `
        <span class="alert-icon">${getAlertIcon(type)}</span>
        <span>${message}</span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()" style="background:none;border:none;float:right;cursor:pointer;font-size:18px;">&times;</button>
    `;
    
    // Insert at the top of the page content or body
    const pageContent = document.querySelector('.page-content') || document.querySelector('.container') || document.body;
    pageContent.insertBefore(notification, pageContent.firstChild);
    
    // Auto-hide after duration
    if (duration > 0) {
        setTimeout(() => {
            fadeOut(notification);
        }, duration);
    }
    
    return notification;
}

function getAlertIcon(type) {
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️',
        danger: '❌'
    };
    return icons[type] || icons.info;
}

function fadeOut(element) {
    element.style.opacity = '0';
    element.style.transform = 'translateY(-20px)';
    setTimeout(() => {
        element.remove();
    }, 300);
}

// ========================================
// UTILITY FUNCTIONS & BACKWARDS COMPATIBILITY
// ========================================
function showLoadingState(button) {
    const originalText = button.textContent;
    button.textContent = 'Please wait...';
    button.disabled = true;
    button.style.opacity = '0.7';
    
    setTimeout(function() {
        button.textContent = originalText;
        button.disabled = false;
        button.style.opacity = '1';
    }, 2000);
}

// Backwards compatibility functions
function showValidationError(field, message) {
    showFieldError(field, message);
}

function clearValidationError(field) {
    clearFieldError(field);
}

// ========================================
// GLOBAL FUNCTIONS FOR INLINE USAGE
// ========================================
window.showAlert = showNotification;
window.toggleTheme = toggleTheme;
window.validateForm = function(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
};

// ========================================
// KEYBOARD SHORTCUTS
// ========================================
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + D for dark mode toggle
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        toggleTheme();
    }
    
    // Escape key to close modals/menus
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && window.innerWidth <= 768) {
            sidebar.classList.remove('show');
        }
    }
});