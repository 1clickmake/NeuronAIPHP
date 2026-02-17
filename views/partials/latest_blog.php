<?php
use App\Core\Database;

/**
 * Latest Blog Partial (List Style)
 */

if (!isset($board_slug)) $board_slug = 'blog';
if (!isset($limit)) $limit = 3;

$db = Database::getInstance();

$stmt = $db->prepare("SELECT * FROM boards WHERE slug = ?");
$stmt->execute([$board_slug]);
$board = $stmt->fetch();

if ($board) {
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
<div class="latest-blog-widget mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fa-solid fa-pen-nib me-2" style="color: #10b981; font-size: 0.9rem;"></i>
            <?= htmlspecialchars($board['title']) ?>
        </h4>
        <a href="/board/<?= $board_slug ?>" class="text-muted text-decoration-none" style="font-size: 0.8rem; font-weight: 500;">
            View All <i class="fa-solid fa-angle-right ms-1"></i>
        </a>
    </div>

    <div class="blog-list-wrapper d-flex flex-column gap-3">
        <?php foreach ($posts as $post): 
            $thumbnail = get_thumbnail($post);
            $decoded_content = htmlspecialchars_decode($post['content']);
            $summary = mb_strimwidth(strip_tags($decoded_content), 0, 200, '...');
        ?>
            <a href="/board/view/<?= $post['id'] ?>" class="blog-list-link text-decoration-none">
                <div class="glass-card p-0 d-flex overflow-hidden" style="min-height: 140px;">
                    <?php if ($thumbnail): ?>
                        <div class="blog-list-thumb" style="width: 200px; min-width: 200px; background: url('<?= htmlspecialchars($thumbnail) ?>') center/cover no-repeat;"></div>
                    <?php endif; ?>
                    
                    <div class="p-4 flex-grow-1 d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-muted" style="font-size: 0.75rem;"><i class="fa-regular fa-calendar me-1"></i> <?= date('Y.m.d', strtotime($post['created_at'])) ?></span>
                            <?php if ((time() - strtotime($post['created_at'])) < 86400): ?>
                                <span class="badge bg-danger ms-2" style="font-size: 0.5rem; padding: 0.1rem 0.3rem;">NEW</span>
                            <?php endif; ?>
                            <span class="ms-auto text-muted" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-user me-1"></i> <?= htmlspecialchars($post['username']) ?>
                            </span>
                        </div>
                        <h5 class="text-dark mb-2 text-truncate" style="font-weight: 700; line-height: 1.4;"><?= htmlspecialchars($post['title']) ?></h5>
                        <p class="text-muted mb-0 line-clamp-2" style="font-size: 0.85rem; line-height: 1.6;"><?= htmlspecialchars($summary) ?></p>
                        
                        <div class="mt-2 d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                             <span class="me-3"><i class="fa-solid fa-eye me-1"></i> <?= number_format($post['views'] ?? 0) ?></span>
                             <span><i class="fa-solid fa-comment me-1"></i> <?= number_format($post['comment_count']) ?></span>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
        <?php if (empty($posts)): ?>
            <div class="glass-card text-center p-5 text-muted">Awaiting interesting stories...</div>
        <?php endif; ?>
    </div>
</div>

<?php 
}
?>
