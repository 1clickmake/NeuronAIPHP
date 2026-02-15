<?php $title = $page['title']; include_header($title, $siteConfig); ?>

<div class="container" style="margin-top: 5rem;">
    <!-- Breadcrumb or Title -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white;"><?= htmlspecialchars($page['title']) ?></h1>
        <div style="width: 60px; height: 4px; background: var(--primary); margin-top: 10px; border-radius: 2px;"></div>
    </div>

    <!-- Page Content Container -->
    <div class="glass-card" style="background: rgba(30, 41, 59, 0.4); min-height: 400px;">
        <div class="page-content" style="color: #cbd5e1; line-height: 1.8; font-size: 1.1rem;">
            <?= $_raw['page']['content'] ?>
        </div>
    </div>
</div>

<style>
/* Style for editor content */
.page-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 1.5rem 0;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}
.page-content h1, .page-content h2, .page-content h3 {
    color: white;
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.page-content a {
    color: var(--primary);
    text-decoration: underline;
}
.page-content blockquote {
    border-left: 4px solid var(--primary);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    background: rgba(99, 102, 241, 0.05);
    padding: 1.5rem;
    border-radius: 0 12px 12px 0;
}
</style>

<?php include_footer($siteConfig); ?>
