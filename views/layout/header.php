<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteConfig['site_name'] ?? 'Neuron AI PHP') ?> - <?= $title ?? 'Welcome' ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-4.0.0-beta.min.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- 기본 템플릿 스타일 css-->
	<link href="<?= CM_BASE_URL ?>/css/basic.css?v=<?= filemtime(CM_PUBLIC_PATH . '/css/basic.css') ?>" rel="stylesheet">
	
	<!-- 템플릿 전용 CSS 동적 로드 -->
    <?= load_template_assets($siteConfig ?? []) ?>
</head>
<body>

