<?php $title = 'My Page'; include_header($title, $siteConfig); ?>

<div class="container mypage-container">
    <div class="mypage-header-mb">
        <h1 class="mypage-header-title">My Page</h1>
        <div class="mypage-header-divider"></div>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success mypage-alert-success">
            Profile updated successfully.
        </div>
    <?php endif; ?>

    <div class="mypage-grid">
        <!-- Left: User Info -->
        <div class="glass-card glass-card-start">
            <div class="mypage-profile-header">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=6366f1&color=fff&size=128" 
                     class="mypage-profile-img">
                <h3 class="mypage-profile-name"><?= htmlspecialchars($user['username'] ?? '') ?></h3>
                <span class="mypage-profile-role"><?= strtoupper($user['role'] ?? 'USER') ?></span>
            </div>

            <form action="/profile/update" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="mypage-form-group">
                    <label>User ID</label>
                    <input type="text" class="form-control mypage-input-disabled" value="<?= htmlspecialchars($user['user_id'] ?? '') ?>" disabled>
                </div>
                <div class="mypage-form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                </div>
                <div class="mypage-form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
                <div class="mypage-form-group">
                    <label>Current Password (Required for changes)</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mypage-form-group">
                    <label>New Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="Optional">
                </div>
                <div class="mypage-form-group">
                    <label>Country (Auto Detected)</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['country'] ?? 'Unknown') ?>" disabled style="opacity: 0.6;">
                </div>
                
                <div class="mypage-btn-group">
                    <button type="submit" class="btn btn-primary btn-w-100">Update Profile</button>
                    <a href="/logout" class="btn btn-logout">Logout</a>
                </div>
            </form>

            <hr class="mypage-divider">

            <form action="/profile/delete" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete your account? This action cannot be undone.')">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <button type="submit" class="btn-delete-account">
                    Delete my account
                </button>
            </form>
        </div>

        <!-- Right: My Posts -->
        <div class="glass-card">
            <h3 class="mypage-section-title">My Recent Posts (Latest 10)</h3>
            
            <div class="table-responsive">
                <table class="table table-hover mypage-table">
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
                            <td><span class="mypage-badge"><?= htmlspecialchars($post['board_title']) ?></span></td>
                            <td>
                                <a href="/board/view/<?= $post['id'] ?>" class="mypage-post-link">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </td>
                            <td class="mypage-post-date"><?= date('Y-m-d', strtotime($post['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="3" class="mypage-empty-state">
                                <i class="fa-solid fa-pen-nib mypage-empty-icon"></i>
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

<?php include_footer($siteConfig); ?>
