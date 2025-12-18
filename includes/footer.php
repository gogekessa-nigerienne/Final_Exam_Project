    </main>
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <p style="margin-top: 10px; color: #ccc;"></p>
                <p style="margin-top: 5px; color: #aaa;"></p>
            </div>
        </div>
    </footer>
    
    <script>
        // Simple JavaScript for mobile menu
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mainNav = document.querySelector('.main-nav');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    mainNav.classList.toggle('show');
                });
            }
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.site-header')) {
                    mainNav.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>