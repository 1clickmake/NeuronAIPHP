<?php
use App\Core\Database;

/**
 * Latest Posts Partial
 * 
 * Variables:
 * $board_slug (string) - Slug of the board to fetch posts from
 * $limit (int) - Number of posts to show (default: 5)
 */

if (!isset($board_slug)) $board_slug = 'free';
if (!isset($limit)) $limit = 5;

$db = Database::getInstance();

// Get board info
$stmt = $db->prepare("SELECT * FROM boards WHERE slug = ?");
$stmt->execute([$board_slug]);
$board = $stmt->fetch();

if ($board) {
    // Get latest posts with user info and comment count
    $sql = "SELECT p.*, u.username,
            (SELECT filepath FROM post_files WHERE post_id = p.id ORDER BY id ASC LIMIT 1) as first_file,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count 
            FROM posts p 
            LEFT JOIN users u ON p.user_id = u.user_id
            WHERE p.board_id = ? 
            ORDER BY p.created_at DESC 
            LIMIT " . (int)$limit;
            
    $stmt = $db->prepare($sql);
    $stmt->execute([$board['id']]);
    $posts = $stmt->fetchAll();
?>
<div class="latest-posts-widget mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>
            <i class="fa-solid fa-list-ul me-2" style="color: #6366f1; font-size: 0.9rem;"></i>
            <?= htmlspecialchars($board['title']) ?>
        </h4>
        <a href="/board/<?= $board_slug ?>" class="text-muted text-decoration-none" style="font-size: 0.8rem; font-weight: 500;">
            More <i class="fa-solid fa-angle-right ms-1"></i>
        </a>
    </div>
    
    <div class="glass-card p-0 overflow-hidden">
        <?php if (empty($posts)): ?>
            <div class="p-4 text-center text-muted" style="font-size: 0.9rem;">No posts found.</div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($posts as $post): ?>
                    <a href="/board/view/<?= $post['id'] ?>" class="list-group-item list-group-item-action bg-transparent border-0 d-flex justify-content-between align-items-center py-2.5 px-4 post-item">
                        <div class="d-flex align-items-center overflow-hidden">
                             <span class="text-muted me-3" style="font-size: 0.75rem; min-width: 40px;"><?= date('m-d', strtotime($post['created_at'])) ?></span>
                             <span class="text-dark text-truncate" style="font-weight: 500; font-size: 0.9rem;">
                                <?= htmlspecialchars($post['title']) ?>
                             </span>
                             <?php if ($post['comment_count'] > 0): ?>
                                <span class="ms-2" style="color: #6366f1; font-size: 0.75rem; font-weight: 600;">
                                    [<?= $post['comment_count'] ?>]
                                </span>
                             <?php endif; ?>
                        </div>
                        <?php 
                            $isNew = (time() - strtotime($post['created_at'])) < 86400;
                            if ($isNew):
                        ?>
                            <span class="badge bg-danger ms-2" style="font-size: 0.55rem; padding: 0.15rem 0.35rem; border-radius: 3px;">N</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
}
?>
