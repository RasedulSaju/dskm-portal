    </div>
    <!-- End Main Content -->

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Mobile sidebar toggle
        const toggleSidebar = () => {
            document.querySelector('.sidebar').classList.toggle('active');
        };

        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Confirm delete actions
        document.querySelectorAll('[data-confirm]').forEach(el => {
            el.addEventListener('click', (e) => {
                if (!confirm(el.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });

        // AJAX helper
        const ajax = async (url, data = {}, method = 'POST') => {
            const formData = new FormData();
            Object.keys(data).forEach(key => formData.append(key, data[key]));
            formData.append('_csrf_token', '<?= csrf_token() ?>');
            
            const response = await fetch(url, {
                method,
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            return response.json();
        };
    </script>
</body>
</html>
