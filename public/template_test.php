<?php
/**
 * Template Color Test Page
 * 각 템플릿의 색상을 빠르게 확인할 수 있는 테스트 페이지
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db = App\Core\Database::getInstance();

// 현재 템플릿 확인
$stmt = $db->query("SELECT template FROM config WHERE id = 1");
$currentTemplate = $stmt->fetch()['template'] ?? 'basic';

// 사용 가능한 템플릿 목록
$templates = [
    'basic' => ['name' => 'Basic (Gray)', 'color' => '#64748b', 'desc' => '깔끔한 밝은 회색 테마'],
    'breeze' => ['name' => 'Breeze (Teal)', 'color' => '#0d9488', 'desc' => '차분한 청록색'],
    'dark' => ['name' => 'Dark (Gray)', 'color' => '#71717a', 'desc' => '프리미엄 다크 회색'],
    'corona' => ['name' => 'Corona (Orange)', 'color' => '#ea580c', 'desc' => '따뜻한 진한 오렌지'],
    'green' => ['name' => 'Green (Emerald)', 'color' => '#059669', 'desc' => '자연 진한 에메랄드']
];

// 템플릿 변경 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_template'])) {
    $newTemplate = $_POST['template'] ?? 'basic';
    if (array_key_exists($newTemplate, $templates)) {
        $stmt = $db->prepare("UPDATE config SET template = :template WHERE id = 1");
        $stmt->execute(['template' => $newTemplate]);
        $currentTemplate = $newTemplate;
        $message = "✅ 템플릿이 '{$templates[$newTemplate]['name']}'(으)로 변경되었습니다!";
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Color Test - NeuronAI PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .test-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .test-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin-bottom: 2rem;
        }
        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .template-item {
            border: 3px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .template-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .template-item.active {
            border-color: #10b981;
            background: #f0fdf4;
        }
        .template-item.active::before {
            content: '✓ 현재 적용';
            position: absolute;
            top: -12px;
            right: 10px;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .color-preview {
            width: 100%;
            height: 60px;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .template-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .template-desc {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .btn-apply {
            width: 100%;
            margin-top: 1rem;
            padding: 0.75rem;
            font-weight: 600;
        }
        .alert-success {
            background: #d1fae5;
            border: 2px solid #10b981;
            color: #065f46;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .quick-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
        .quick-link {
            flex: 1;
            min-width: 200px;
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 8px;
            text-decoration: none;
            color: #374151;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s;
        }
        .quick-link:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="test-card">
            <h1 style="color: #1f2937; margin-bottom: 0.5rem;">🎨 템플릿 색상 테스트</h1>
            <p style="color: #6b7280;">각 템플릿을 선택하여 색상 테마를 변경하고 확인하세요.</p>
            
            <?php if (isset($message)): ?>
                <div class="alert-success">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="templateForm">
                <div class="template-grid">
                    <?php foreach ($templates as $key => $tmpl): ?>
                        <div class="template-item <?= $currentTemplate === $key ? 'active' : '' ?>" 
                             onclick="selectTemplate('<?= $key ?>')">
                            <div class="color-preview" style="background: <?= $tmpl['color'] ?>;">
                                <?= $tmpl['color'] ?>
                            </div>
                            <div class="template-name"><?= $tmpl['name'] ?></div>
                            <div class="template-desc"><?= $tmpl['desc'] ?></div>
                            <input type="radio" name="template" value="<?= $key ?>" 
                                   <?= $currentTemplate === $key ? 'checked' : '' ?> 
                                   style="display: none;" id="tmpl_<?= $key ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" name="change_template" class="btn btn-primary btn-apply">
                    선택한 템플릿 적용하기
                </button>
            </form>

            <div class="quick-links">
                <a href="/" class="quick-link">🏠 메인 페이지</a>
                <a href="/board/free" class="quick-link">📝 자유게시판</a>
                <a href="/board/gallery" class="quick-link">🖼️ 갤러리</a>
                <a href="/admin" class="quick-link">⚙️ 관리자</a>
            </div>

            <div style="margin-top: 2rem; padding: 1rem; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <strong>💡 팁:</strong> 템플릿을 변경한 후 위의 링크를 클릭하여 각 페이지에서 색상이 제대로 적용되는지 확인하세요!
            </div>
        </div>

        <div class="test-card">
            <h2 style="color: #1f2937; margin-bottom: 1rem;">📋 현재 설정</h2>
            <table class="table">
                <tr>
                    <th>현재 템플릿</th>
                    <td><strong><?= $templates[$currentTemplate]['name'] ?></strong></td>
                </tr>
                <tr>
                    <th>Primary Color</th>
                    <td>
                        <span style="display: inline-block; width: 20px; height: 20px; background: <?= $templates[$currentTemplate]['color'] ?>; border-radius: 4px; vertical-align: middle;"></span>
                        <code><?= $templates[$currentTemplate]['color'] ?></code>
                    </td>
                </tr>
                <tr>
                    <th>설명</th>
                    <td><?= $templates[$currentTemplate]['desc'] ?></td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        function selectTemplate(key) {
            document.getElementById('tmpl_' + key).checked = true;
            
            // 모든 템플릿 아이템에서 active 클래스 제거
            document.querySelectorAll('.template-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // 선택된 아이템에 active 클래스 추가
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>
