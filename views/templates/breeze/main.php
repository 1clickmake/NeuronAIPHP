<?php $title = 'Home'; include_header($title, $siteConfig); ?>

<div class="container">
    <div style="text-align: center; margin-bottom: 5rem; margin-top: 3rem;">
        <h1 style="font-size: 3.5rem; font-weight: 700; margin-bottom: 1.5rem; animation: fadeInUp 0.8s ease;">
            🌊 Breeze Theme
        </h1>
        <p style="color: rgba(255,255,255,0.8); font-size: 1.3rem; max-width: 700px; margin: 0 auto 1rem; animation: fadeInUp 0.8s ease 0.2s both;">
            Feel the refreshing breeze with cool cyan and sky blue tones.
        </p>
        <p style="color: rgba(255, 255, 255, 0.8); font-size: 1rem; max-width: 600px; margin: 0 auto; animation: fadeInUp 0.8s ease 0.3s both;">
            Perfect for modern, tech-focused, or ocean-inspired designs. Experience the smooth flow of creativity.
        </p>
        
        <div style="margin-top: 2rem; animation: fadeInUp 0.8s ease 0.4s both;">
            <span style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(59, 130, 246, 0.1)); color: #06b6d4; padding: 0.6rem 1.5rem; border-radius: 50px; font-size: 0.9rem; font-weight: 600; border: 1px solid rgba(6, 182, 212, 0.3); display: inline-block;">
                ✨ Active Template: <?= ucfirst($siteConfig['template'] ?? 'Breeze') ?>
            </span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($groups as $group): ?>
            <div class="glass-card" style="animation: fadeInUp 0.6s ease both; animation-delay: calc(var(--card-index, 0) * 0.1s);">
                <h2 style="color: #06b6d4; margin-bottom: 1rem; border-bottom: 2px solid rgba(6, 182, 212, 0.3); padding-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-water" style="font-size: 1.2rem;"></i>
                    <?= htmlspecialchars($group['name']) ?>
                </h2>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.6;">
                    <?= htmlspecialchars($group['description']) ?>
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <?php foreach ($group['boards'] as $board): ?>
                            <a href="/board/<?= $board['slug'] ?>" class="breeze-board-link" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(59, 130, 246, 0.05)); padding: 0.85rem 1rem; border-radius: 10px; text-decoration: none; color: white; border: 1px solid rgba(6, 182, 212, 0.1); transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); display: block;">
                                <div style="font-weight: 600; color: #06b6d4; margin-bottom: 0.25rem;"><?= htmlspecialchars($board['title']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($board['description']) ?></div>
                            </a>
                        <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($groups)): ?>
            <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 5rem;">
                <i class="fa-solid fa-wind" style="font-size: 4rem; color: rgba(6, 182, 212, 0.3); margin-bottom: 1.5rem;"></i>
                <h3 style="color: var(--text-muted);">No categories available. Please log in as admin to create groups and boards.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php include_footer($siteConfig); ?>
