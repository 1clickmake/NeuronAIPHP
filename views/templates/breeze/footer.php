    <footer style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.8rem; border-top: 1px solid var(--glass-border);">
        &copy; <?= date('Y') ?> Neuron AI PHP Framework Demo. All rights reserved.
    </footer>
    <script>
        // Example of jQuery 4.0.0-beta usage
        $(document).ready(function() {
            // Subtle fade-in for cards
            $('.glass-card').css('opacity', 0).fadeTo(600, 1);
        });
    </script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
include CM_LAYOUT_PATH . '/footer.php';
?>
