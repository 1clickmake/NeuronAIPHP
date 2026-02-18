
    
    <!-- 템플릿 전용 JS 동적 로드 -->
    <?= load_template_scripts($siteConfig ?? []) ?>
    <?php do_action('public_footer'); ?>
</body>
</html>
