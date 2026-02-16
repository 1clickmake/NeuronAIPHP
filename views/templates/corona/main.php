<?php $title = 'Home'; include_header($title, $siteConfig); ?>

<div class="container">
    <div style="text-align: center; margin-bottom: 5rem; margin-top: 3rem;">
        <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
            <h1 style="font-size: 3.8rem; font-weight: 800; margin: 0; animation: fadeInScale 0.8s ease; position: relative;">
                ☀️ Corona Theme
            </h1>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 150%; height: 150%; background: radial-gradient(circle, rgba(251, 191, 36, 0.15), transparent 70%); filter: blur(40px); z-index: -1; animation: coronaPulse 4s ease-in-out infinite;"></div>
        </div>
        
        <p style="color: rgba(255,255,255,0.85); font-size: 1.3rem; max-width: 700px; margin: 0 auto 1rem; animation: fadeInUp 0.8s ease 0.2s both;">
            Ignite your website with warm, energetic colors.
        </p>
        <p style="color: rgba(255, 255, 255, 0.8); font-size: 1rem; max-width: 600px; margin: 0 auto; animation: fadeInUp 0.8s ease 0.3s both;">
            Perfect for bold, dynamic brands that want to stand out. Feel the warmth and energy of the sun's corona.
        </p>
        
        <div style="margin-top: 2rem; animation: fadeInUp 0.8s ease 0.4s both;">
            <span style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.15), rgba(249, 115, 22, 0.1)); color: #fbbf24; padding: 0.6rem 1.5rem; border-radius: 50px; font-size: 0.9rem; font-weight: 600; border: 1px solid rgba(249, 115, 22, 0.3); display: inline-block; box-shadow: 0 4px 15px rgba(249, 115, 22, 0.2);">
                🔥 Active Template: <?= ucfirst($siteConfig['template'] ?? 'Corona') ?>
            </span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($groups as $group): ?>
            <div class="glass-card" style="animation: fadeInUp 0.6s ease both; animation-delay: calc(var(--card-index, 0) * 0.1s);">
                <h2 style="margin-bottom: 1rem; border-bottom: 2px solid rgba(249, 115, 22, 0.3); padding-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-fire" style="font-size: 1.2rem; color: #f97316;"></i>
                    <?= htmlspecialchars($group['name']) ?>
                </h2>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.6;">
                    <?= htmlspecialchars($group['description']) ?>
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <?php foreach ($group['boards'] as $board): ?>
                            <a href="/board/<?= $board['slug'] ?>" class="corona-board-link" style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(249, 115, 22, 0.08)); padding: 0.85rem 1rem; border-radius: 10px; text-decoration: none; color: white; border: 1px solid rgba(249, 115, 22, 0.15); transition: all 0.35s ease; display: block; position: relative; overflow: hidden;">
                                <div style="font-weight: 600; color: #fbbf24; margin-bottom: 0.25rem;"><?= htmlspecialchars($board['title']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($board['description']) ?></div>
                            </a>
                        <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($groups)): ?>
            <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 5rem;">
                <i class="fa-solid fa-sun" style="font-size: 4rem; color: rgba(249, 115, 22, 0.3); margin-bottom: 1.5rem; animation: spin 20s linear infinite;"></i>
                <h3 style="color: var(--text-muted);">No categories available. Please log in as admin to create groups and boards.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.corona-board-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(251, 191, 36, 0.2), transparent);
    transition: left 0.5s ease;
}

.corona-board-link:hover::before {
    left: 100%;
}

.corona-board-link:hover {
    border-color: #f97316 !important;
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(249, 115, 22, 0.15)) !important;
    transform: translateX(8px) scale(1.02) !important;
    box-shadow: 0 8px 30px rgba(249, 115, 22, 0.3) !important;
}
</style>

<?php include_footer($siteConfig); ?>
