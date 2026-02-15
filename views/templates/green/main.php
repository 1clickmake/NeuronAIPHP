<?php $title = 'Home'; include_header($title, $siteConfig); ?>

<div class="container">
    <div style="text-align: center; margin-bottom: 5rem; margin-top: 3rem;">
        <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
            <h1 style="font-size: 3.8rem; font-weight: 700; margin: 0; animation: grow 0.8s ease;">
                🌿 Green Theme
            </h1>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 140%; height: 140%; background: radial-gradient(circle, rgba(132, 204, 22, 0.12), transparent 70%); filter: blur(50px); z-index: -1; animation: naturalBreath 6s ease-in-out infinite;"></div>
        </div>
        
        <p style="color: rgba(255,255,255,0.85); font-size: 1.3rem; max-width: 700px; margin: 0 auto 1rem; animation: fadeInUp 0.8s ease 0.2s both;">
            Experience a fresh, eco-friendly design with vibrant green accents.
        </p>
        <p style="color: var(--text-muted); font-size: 1rem; max-width: 650px; margin: 0 auto; animation: fadeInUp 0.8s ease 0.3s both;">
            Perfect for nature-focused or sustainability-themed websites. Embrace the harmony of organic growth.
        </p>
        
        <div style="margin-top: 2rem; animation: fadeInUp 0.8s ease 0.4s both;">
            <span style="background: linear-gradient(135deg, rgba(132, 204, 22, 0.15), rgba(16, 185, 129, 0.1)); color: #84cc16; padding: 0.6rem 1.5rem; border-radius: 50px; font-size: 0.9rem; font-weight: 600; border: 1px solid rgba(16, 185, 129, 0.3); display: inline-block; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
                🌱 Active Template: <?= ucfirst($siteConfig['template'] ?? 'Green') ?>
            </span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($groups as $group): ?>
            <div class="glass-card" style="animation: fadeInUp 0.6s ease both; animation-delay: calc(var(--card-index, 0) * 0.1s);">
                <h2 style="color: #10b981; margin-bottom: 1rem; border-bottom: 2px solid rgba(16, 185, 129, 0.3); padding-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-leaf" style="font-size: 1.2rem; color: #84cc16; animation: leafFloat 4s ease-in-out infinite;"></i>
                    <?= htmlspecialchars($group['name']) ?>
                </h2>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.6;">
                    <?= htmlspecialchars($group['description']) ?>
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <?php foreach ($group['boards'] as $board): ?>
                            <a href="/board/<?= $board['slug'] ?>" class="green-board-link" style="background: linear-gradient(135deg, rgba(132, 204, 22, 0.08), rgba(16, 185, 129, 0.06)); padding: 0.85rem 1rem; border-radius: 10px; text-decoration: none; color: white; border: 1px solid rgba(16, 185, 129, 0.15); transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); display: block;">
                                <div style="font-weight: 600; color: #84cc16; margin-bottom: 0.25rem;"><?= htmlspecialchars($board['title']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($board['description']) ?></div>
                            </a>
                        <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($groups)): ?>
            <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 5rem;">
                <i class="fa-solid fa-seedling" style="font-size: 4rem; color: rgba(16, 185, 129, 0.3); margin-bottom: 1.5rem; animation: grow 2s ease-in-out infinite;"></i>
                <h3 style="color: var(--text-muted);">No categories available. Please log in as admin to create groups and boards.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
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

@keyframes grow {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
}

@keyframes leafFloat {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-8px) rotate(5deg);
    }
}

@keyframes naturalBreath {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
}

.green-board-link:hover {
    border-color: #10b981 !important;
    background: linear-gradient(135deg, rgba(132, 204, 22, 0.15), rgba(16, 185, 129, 0.12)) !important;
    transform: translateX(5px) scale(1.01) !important;
    box-shadow: 0 8px 30px rgba(16, 185, 129, 0.25) !important;
}

.green-board-link::before {
    content: '🌿';
    position: absolute;
    right: 1rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.green-board-link:hover::before {
    opacity: 0.3;
    right: 0.5rem;
}
</style>

<?php include_footer($siteConfig); ?>
