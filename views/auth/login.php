<?php include_header('Login', $siteConfig); ?>

<div class="container" style="max-width: 450px; margin-top: 100px;">
    <div class="glass-card">
        <h2 style="margin-bottom: 2rem; text-align: center;">Login</h2>
        
        <?php if (isset($error)): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fecaca; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="form-group mb-3">
                <label>User ID</label>
                <input type="text" name="user_id" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Sign In</button>
        </form>
        
        <p style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Don't have an account? <a href="/register" style="color: var(--primary); text-decoration: none;">Register here</a>
        </p>
    </div>
</div>

<?php include_footer($siteConfig); ?>
