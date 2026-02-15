<?php $title = 'Home'; include_header($title, $siteConfig); ?>

<div class="container">
    <div style="text-align: center; margin-bottom: 5rem; margin-top: 3rem;">
        <h1 style="font-size: 3.5rem; font-weight: 700; margin-bottom: 1rem;">
            <span style="color: #10b981;">🌿 Green</span> Theme
        </h1>
        <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 700px; margin: 0 auto;">
            Experience a fresh, eco-friendly design with vibrant green accents.
            Perfect for nature-focused or sustainability-themed websites.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($groups as $group): ?>
            <div class="glass-card">
                <h2 style="color: #10b981; margin-bottom: 1rem; border-bottom: 1px solid rgba(16, 185, 129, 0.3); padding-bottom: 0.5rem;">
                    <?= htmlspecialchars($group['name']) ?>
                </h2>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <?= htmlspecialchars($group['description']) ?>
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <?php foreach ($group['boards'] as $board): ?>
                            <a href="/board/<?= $board['slug'] ?>" style="background: rgba(16, 185, 129, 0.05); padding: 0.75rem 1rem; border-radius: 8px; text-decoration: none; color: white; border: 1px solid transparent; transition: all 0.3s;" onmouseover="this.style.borderColor='#10b981'; this.style.backgroundColor='rgba(16, 185, 129, 0.1)'; this.style.transform='translateX(5px)'" onmouseout="this.style.borderColor='transparent'; this.style.backgroundColor='rgba(16, 185, 129, 0.05)'; this.style.transform='none'">
                                <div style="font-weight: 600;"><?= htmlspecialchars($board['title']) ?></div>
                                <div style="font-size: 0.7rem; color: var(--text-muted);"><?= htmlspecialchars($board['description']) ?></div>
                            </a>
                        <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($groups)): ?>
            <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 5rem;">
                <h3 style="color: var(--text-muted);">No categories available. Please log in as admin to create groups and boards.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_footer($siteConfig); ?>
