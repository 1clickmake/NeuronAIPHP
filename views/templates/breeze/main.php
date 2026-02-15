<?php $title = 'Home'; include_header($title, $siteConfig); ?>

<div class="container">
    <div style="text-align: center; margin-bottom: 5rem; margin-top: 3rem;">
        <h1 style="font-size: 3.5rem; font-weight: 700; margin-bottom: 1rem; background: linear-gradient(135deg, #06b6d4, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            🌊 Breeze Theme
        </h1>
        <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 700px; margin: 0 auto;">
            Feel the refreshing breeze with cool cyan and blue tones.
            Perfect for modern, tech-focused, or ocean-inspired designs.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($groups as $group): ?>
            <div class="glass-card" style="backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);">
                <h2 style="color: #06b6d4; margin-bottom: 1rem; border-bottom: 2px solid rgba(6, 182, 212, 0.3); padding-bottom: 0.5rem;">
                    <?= htmlspecialchars($group['name']) ?>
                </h2>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <?= htmlspecialchars($group['description']) ?>
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <?php foreach ($group['boards'] as $board): ?>
                            <a href="/board/<?= $board['slug'] ?>" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(59, 130, 246, 0.05)); padding: 0.75rem 1rem; border-radius: 10px; text-decoration: none; color: white; border: 1px solid rgba(6, 182, 212, 0.1); transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.borderColor='#06b6d4'; this.style.background='linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(59, 130, 246, 0.1))'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(6, 182, 212, 0.2)'" onmouseout="this.style.borderColor='rgba(6, 182, 212, 0.1)'; this.style.background='linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(59, 130, 246, 0.05))'; this.style.transform='none'; this.style.boxShadow='none'">
                                <div style="font-weight: 600; color: #06b6d4;"><?= htmlspecialchars($board['title']) ?></div>
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
