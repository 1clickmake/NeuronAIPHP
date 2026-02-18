        </div> <!-- End admin-content -->
        
        <footer style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.8rem; border-top: 1px solid var(--border-color); background: rgba(0,0,0,0.1);">
            &copy; <?= date('Y') ?> Admin Dashboard. All rights reserved.
        </footer>
    </main> <!-- End admin-main -->
</div> <!-- End admin-wrapper -->

<script>
    $(document).ready(function() {
        // Highlight current menu item based on ID set in header/view
        // or we can do it by URL if needed. 
        // The id-based one is already implemented in views like $('#link-dashboard').addClass('active')
        
        // Mobile sidebar backdrop click (optional but nice)
        $(document).on('click', function(e) {
            if ($(window).width() < 992) {
                if (!$(e.target).closest('#sidebar, .mobile-toggle').length && $('#sidebar').hasClass('active')) {
                    $('#sidebar').removeClass('active');
                }
            }
        });

        // Subtle animation for cards (only if they are initially meant to be visible)
        $('.glass-card:not([hidden]):not([style*="display: none"])').css('opacity', 0).fadeTo(500, 1);
    });
</script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
