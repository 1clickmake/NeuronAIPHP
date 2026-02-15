<?php $title = 'My Page'; include_header($title, $siteConfig); ?>

<div class="container" style="margin-top: 5rem;">
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.2rem; font-weight: 800; color: white;">My Page</h1>
        <div style="width: 50px; height: 4px; background: var(--primary); margin-top: 10px; border-radius: 2px;"></div>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; border-radius: 12px; padding: 1rem; margin-bottom: 2rem;">
            Profile updated successfully.
        </div>
    <?php endif; ?>

    <div class="mypage-grid">
        <!-- Left: User Info -->
        <div class="glass-card" style="align-self: start;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=6366f1&color=fff&size=128" 
                     style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid var(--primary); margin-bottom: 1rem;">
                <h3 style="margin: 0; color: white;"><?= htmlspecialchars($user['username'] ?? '') ?></h3>
                <span style="font-size: 0.85rem; color: var(--text-muted);"><?= strtoupper($user['role'] ?? 'USER') ?></span>
            </div>

            <form action="/profile/update" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="form-group">
                    <label>User ID</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['user_id'] ?? '') ?>" disabled style="opacity: 0.6;">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Current Password (Required for changes)</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>New Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="Optional">
                </div>
                <div class="form-group">
                    <label>Country (Auto Detected)</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['country'] ?? 'Unknown') ?>" disabled style="opacity: 0.6;">
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Update Profile</button>
                    <a href="/logout" class="btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; width: 100%; text-align: center;">Logout</a>
                </div>
            </form>

            <hr style="border-color: var(--glass-border); margin: 2rem 0;">

            <form action="/profile/delete" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete your account? This action cannot be undone.')">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <button type="submit" style="background: none; border: none; color: #64748b; font-size: 0.85rem; cursor: pointer; text-decoration: underline; padding: 0;">
                    Delete my account
                </button>
            </form>
        </div>

        <!-- Right: My Posts -->
        <div class="glass-card">
            <h3 style="color: white; margin-bottom: 1.5rem; font-size: 1.25rem;">My Recent Posts (Latest 10)</h3>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover" style="background: transparent;">
                    <thead>
                        <tr>
                            <th>Board</th>
                            <th>Title</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><span style="font-size: 0.75rem; background: rgba(99, 102, 241, 0.2); color: var(--primary); padding: 2px 8px; border-radius: 4px;"><?= htmlspecialchars($post['board_title']) ?></span></td>
                            <td>
                                <a href="/board/view/<?= $post['id'] ?>" style="color: #f8fafc; text-decoration: none; display: block; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-muted);"><?= date('Y-m-d', strtotime($post['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 3rem; color: #64748b;">
                                <i class="fa-solid fa-pen-nib" style="font-size: 2rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                                No posts written yet.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .mypage-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
    }

    .form-group { margin-bottom: 1.2rem; }
    .form-group label { font-size: 0.85rem; margin-bottom: 0.5rem; display: block; color: #94a3b8; }
    .table-hover tbody tr:hover { background: rgba(255, 255, 255, 0.03); }

    @media (max-width: 991.98px) {
        .mypage-grid {
            grid-template-columns: 1fr;
        }
        .container {
            margin-top: 2rem !important;
        }
        .glass-card {
            padding: 1.5rem !important;
        }
    }
</style>

<?php include_footer($siteConfig); ?>
