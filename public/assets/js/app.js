// public/assets/js/app.js - DSKM Portal JavaScript

// Mobile sidebar toggle
function toggleSidebar() {
    document.querySelector('.sidebar')?.classList.toggle('active');
}

// Auto-hide flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
});

// Confirm delete actions
document.addEventListener('click', (e) => {
    if (e.target.hasAttribute('data-confirm')) {
        if (!confirm(e.target.getAttribute('data-confirm'))) {
            e.preventDefault();
        }
    }
});

// AJAX helper function
async function ajax(url, data = {}, method = 'POST') {
    try {
        const formData = new FormData();
        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });
        
        // Add CSRF token
        const csrfToken = document.querySelector('[name="_csrf_token"]')?.value || 
                         document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            formData.append('_csrf_token', csrfToken);
        }
        
        const response = await fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        return await response.json();
    } catch (error) {
        console.error('AJAX Error:', error);
        return { success: false, message: 'Request failed' };
    }
}

// Image preview before upload
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Form validation helper
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const inputs = form.querySelectorAll('[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('border-red-500');
            isValid = false;
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    return isValid;
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Live search functionality
function initLiveSearch(inputId, url, resultsId) {
    const input = document.getElementById(inputId);
    const resultsContainer = document.getElementById(resultsId);
    
    if (!input || !resultsContainer) return;
    
    const search = debounce(async (query) => {
        if (query.length < 2) {
            resultsContainer.innerHTML = '';
            return;
        }
        
        const response = await fetch(`${url}?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        resultsContainer.innerHTML = data.results.map(result => `
            <a href="${result.url}" class="block p-2 hover:bg-gray-100 rounded">
                ${result.name}
            </a>
        `).join('');
    }, 300);
    
    input.addEventListener('input', (e) => search(e.target.value));
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copied to clipboard!');
    });
}

// Show toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Update unread message count
async function updateUnreadCount() {
    try {
        const response = await fetch('/api/messages/unread-count');
        const data = await response.json();
        
        const badge = document.querySelector('.unread-badge');
        if (badge && data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline-block';
        } else if (badge) {
            badge.style.display = 'none';
        }
    } catch (error) {
        console.error('Failed to update unread count:', error);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Update unread messages count every 30 seconds
    if (document.querySelector('.unread-badge')) {
        updateUnreadCount();
        setInterval(updateUnreadCount, 30000);
    }
    
    // Add smooth scroll to anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});

// Export functions for global use
window.ajax = ajax;
window.showToast = showToast;
window.copyToClipboard = copyToClipboard;
window.toggleSidebar = toggleSidebar;
window.previewImage = previewImage;
window.validateForm = validateForm;
