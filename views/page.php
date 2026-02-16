<?php $title = $page['title']; include_header($title, $siteConfig); ?>

<div class="container" style="margin-top: 5rem;">
    <!-- Breadcrumb or Title -->
    <div style="margin-bottom: 2rem;">
        <h1 class="page-title"><?= htmlspecialchars($page['title']) ?></h1>
        <div style="width: 60px; height: 4px; background: var(--primary); margin-top: 10px; border-radius: 2px;"></div>
    </div>

    <!-- Page Content Container -->
    <div class="glass-card page-card">
        <div class="page-content">
            <?= $_raw['page']['content'] ?>
        </div>
    </div>
</div>

<?php include_footer($siteConfig); ?>
