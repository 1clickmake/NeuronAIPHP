<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class AdminController extends BaseController {
    public function __construct() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Optional: Global CSRF check for Admin
            // But let's do per-method to be safe if I missed a view
        }
    }

    public function index() {
        $this->view('admin/dashboard');
    }

    public function config() {
        $db = Database::getInstance();
        $config = $db->query("SELECT * FROM config WHERE id = 1")->fetch();
        
        // Get available templates from filesystem
        $templatePath = CM_VIEWS_PATH . '/templates/';
        $templates = [];
        if (is_dir($templatePath)) {
            $items = scandir($templatePath);
            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..' && is_dir($templatePath . $item)) {
                    $templates[] = [
                        'value' => $item,
                        'label' => ucfirst($item)
                    ];
                }
            }
        }
        
        // Fallback if no templates found
        if (empty($templates)) {
            $templates = [
                ['value' => 'basic', 'label' => 'Basic'],
                ['value' => 'green', 'label' => 'Green'],
                ['value' => 'corona', 'label' => 'Corona'],
                ['value' => 'breeze', 'label' => 'Breeze']
            ];
        }
        
        $this->view('admin/config', [
            'config' => $config,
            'templates' => $templates
        ]);
    }

    public function updateConfig() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $db = Database::getInstance();
        
        $logoType = $_POST['logo_type'] ?? 'text';
        $logoText = $_POST['logo_text'] ?? '';
        $logoImage = $_POST['current_logo_image'] ?? '';
        $template = $_POST['template'] ?? 'basic';

        // Handle Logo Image Upload
        if ($logoType === 'image' && isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/data/config/';
            $fullPath = CM_PUBLIC_PATH . $uploadDir;
            if (!is_dir($fullPath)) mkdir($fullPath, 0777, true);

            $ext = pathinfo($_FILES['logo_image']['name'], PATHINFO_EXTENSION);
            $newName = 'logo_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $fullPath . $newName)) {
                $logoImage = $uploadDir . $newName;
            }
        }

        $stmt = $db->prepare("UPDATE config SET 
            site_name = :site_name,
            company_name = :company_name,
            company_owner = :company_owner,
            company_license_num = :company_license_num,
            company_tel = :company_tel,
            company_email = :company_email,
            company_address = :company_address,
            logo_type = :logo_type,
            logo_text = :logo_text,
            logo_image = :logo_image,
            template = :template,
            join_point = :join_point,
            join_level = :join_level
            WHERE id = 1");
        
        $stmt->execute([
            'site_name' => $_POST['site_name'] ?? '',
            'company_name' => $_POST['company_name'] ?? '',
            'company_owner' => $_POST['company_owner'] ?? '',
            'company_license_num' => $_POST['company_license_num'] ?? '',
            'company_tel' => $_POST['company_tel'] ?? '',
            'company_email' => $_POST['company_email'] ?? '',
            'company_address' => $_POST['company_address'] ?? '',
            'logo_type' => $logoType,
            'logo_text' => $logoText,
            'logo_image' => $logoImage,
            'template' => $template,
            'join_point' => $_POST['join_point'] ?? 0,
            'join_level' => $_POST['join_level'] ?? 1
        ]);

        $this->redirect('/admin/config');
    }

    public function uploadImage() {
        $uploadDir = '/data/config/';
        $fullPath = CM_PUBLIC_PATH . $uploadDir;

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $originalName = basename($_FILES['image']['name']);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = 'config_' . uniqid() . '.' . $extension;
            $targetFile = $fullPath . $newName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Use a simplified resize if available or needed
                // For now, just return the path
                echo json_encode(['url' => $uploadDir . $newName]);
                exit;
            }
        }

        header('Content-Type: application/json', true, 500);
        echo json_encode(['error' => 'Upload failed']);
    }

    public function createUser() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $userid = $_POST['user_id'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $country = $_POST['country'] ?? 'Unknown';
        $password = $_POST['password'] ?? '';
        $point = $_POST['point'] ?? 0;
        $level = $_POST['level'] ?? 1;

        if ($userid && $username && $email && $password) {
            $db = Database::getInstance();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (user_id, username, email, role, password, country, point, level) VALUES (:user_id, :username, :email, :role, :password, :country, :point, :level)");
            $stmt->execute([
                'user_id' => $userid,
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'password' => $hash,
                'country' => $country,
                'point' => $point,
                'level' => $level
            ]);
        }
        $this->redirect('/admin/users');
    }

    public function users() {
        $db = Database::getInstance();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 30; // Default 30 rows
        $offset = ($page - 1) * $limit;

        $totalItems = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        $stmt = $db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll();

        $this->view('admin/users', [
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function point() {
        $db = Database::getInstance();

        // Table check
        $check = $db->query("SHOW TABLES LIKE 'point_log'");
        if ($check->rowCount() == 0) {
            die("The 'point_log' table does not exist. Please run setup.sql or create the table manually.");
        }

        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $where = "";
        $params = [];
        if ($search) {
            $where = " WHERE user_id LIKE :search OR rel_msg LIKE :search";
            $params['search'] = "%$search%";
        }

        // Total count
        $stmt = $db->prepare("SELECT COUNT(*) FROM point_log" . $where);
        $stmt->execute($params);
        $totalItems = $stmt->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        // Fetch logs
        $stmt = $db->prepare("SELECT * FROM point_log" . $where . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        foreach ($params as $key => $val) {
            $stmt->bindValue(":$key", $val);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll();

        $this->view('admin/point', [
            'logs' => $logs,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function updatePoint() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $userid = $_POST['user_id'] ?? '';
        $point = $_POST['point'] ?? 0;
        $msg = $_POST['rel_msg'] ?? 'Admin manual adjustment';

        if ($userid && $point != 0) {
            add_point($userid, (int)$point, $msg);
        }

        $this->redirect('/admin/point?msg=Point updated successfully');
    }

    public function bulkDeletePoints() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $ids = $_POST['ids'] ?? [];
        if (!empty($ids)) {
            $db = Database::getInstance();
            // Use IN clause for efficient deletion
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM point_log WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        }

        $this->redirect('/admin/point?msg=Selected logs deleted successfully');
    }

    public function deleteUser() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
        $this->redirect('/admin/users');
    }

    public function updateUser() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $id = $_POST['id'] ?? null;
        $userid = $_POST['user_id'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $country = $_POST['country'] ?? 'Unknown';
        $password = $_POST['password'] ?? '';
        $point = $_POST['point'] ?? 0;
        $level = $_POST['level'] ?? 1;

        if ($id) {
            $db = Database::getInstance();
            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET user_id = :user_id, username = :username, email = :email, role = :role, country = :country, password = :password, point = :point, level = :level WHERE id = :id");
                $stmt->execute(['user_id' => $userid, 'username' => $username, 'email' => $email, 'role' => $role, 'country' => $country, 'password' => $hash, 'point' => $point, 'level' => $level, 'id' => $id]);
            } else {
                $stmt = $db->prepare("UPDATE users SET user_id = :user_id, username = :username, email = :email, role = :role, country = :country, point = :point, level = :level WHERE id = :id");
                $stmt->execute(['user_id' => $userid, 'username' => $username, 'email' => $email, 'role' => $role, 'country' => $country, 'point' => $point, 'level' => $level, 'id' => $id]);
            }
        }
        $this->redirect('/admin/users');
    }

    public function groups() {
        $db = Database::getInstance();
        $groups = $db->query("SELECT * FROM board_groups ORDER BY created_at DESC")->fetchAll();
        $this->view('admin/groups', ['groups' => $groups]);
    }

    public function createGroup() {
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $name));
        $desc = $_POST['description'] ?? '';

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO board_groups (name, slug, description) VALUES (:name, :slug, :description)");
        $stmt->execute(['name' => $name, 'slug' => $slug, 'description' => $desc]);
        
        $this->redirect('/admin/groups');
    }

    public function updateGroup() {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $desc = $_POST['description'] ?? '';

        if ($id) {
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE board_groups SET name = :name, slug = :slug, description = :description WHERE id = :id");
            $stmt->execute(['name' => $name, 'slug' => $slug, 'description' => $desc, 'id' => $id]);
        }
        $this->redirect('/admin/groups');
    }

    public function deleteGroup() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM board_groups WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
        $this->redirect('/admin/groups');
    }

    public function boards() {
        $db = Database::getInstance();

        // Ensure columns exist
        try {
            $db->query("SELECT page_rows, page_buttons FROM boards LIMIT 1");
        } catch (\PDOException $e) {
            try {
                // Try adding page_rows if missing
                try { $db->exec("ALTER TABLE boards ADD COLUMN page_rows INT DEFAULT 20 COMMENT 'Rows per page' AFTER allow_comments"); } catch (\Exception $ex) {}
                // Try adding page_buttons if missing
                try { $db->exec("ALTER TABLE boards ADD COLUMN page_buttons INT DEFAULT 5 COMMENT 'Number of page buttons' AFTER page_rows"); } catch (\Exception $ex) {}
            } catch (\PDOException $e2) {}
        }

        $boards = $db->query("SELECT b.*, bg.name as group_name FROM boards b JOIN board_groups bg ON b.group_id = bg.id ORDER BY b.created_at DESC")->fetchAll();
        $groups = $db->query("SELECT * FROM board_groups")->fetchAll();
        
        // Get available skins from filesystem
        $skinPath = CM_VIEWS_PATH . '/board/skins/';
        $skins = [];
        if (is_dir($skinPath)) {
            $items = scandir($skinPath);
            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..' && is_dir($skinPath . $item)) {
                    $skins[] = [
                        'value' => $item,
                        'label' => ucfirst($item)
                    ];
                }
            }
        }
        
        // Fallback if no skins found
        if (empty($skins)) {
            $skins = [
                ['value' => 'basic', 'label' => 'Basic'],
                ['value' => 'gallery', 'label' => 'Gallery']
            ];
        }
        
        $this->view('admin/boards', [
            'boards' => $boards, 
            'groups' => $groups,
            'skins' => $skins
        ]);
    }

    public function createBoard() {
        $groupId = $_POST['group_id'] ?? null;
        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $title));
        $desc = $_POST['description'] ?? '';
        $skin = $_POST['skin'] ?? 'basic';
        $maxReplies = intval($_POST['max_replies'] ?? 3);
        $allowComments = intval($_POST['allow_comments'] ?? 1);
        $pageRows = intval($_POST['page_rows'] ?? 20);
        $pageButtons = intval($_POST['page_buttons'] ?? 5);

        if ($groupId && $title) {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO boards (group_id, title, slug, description, skin, max_replies, allow_comments, page_rows, page_buttons, level_list, level_view, level_write, level_comment, point_write, point_view, point_comment) VALUES (:group_id, :title, :slug, :description, :skin, :max_replies, :allow_comments, :page_rows, :page_buttons, :level_list, :level_view, :level_write, :level_comment, :point_write, :point_view, :point_comment)");
            $stmt->execute([
                'group_id' => $groupId,
                'title' => $title,
                'slug' => $slug,
                'description' => $desc,
                'skin' => $skin,
                'max_replies' => $maxReplies,
                'allow_comments' => $allowComments,
                'page_rows' => $pageRows,
                'page_buttons' => $pageButtons,
                'level_list' => intval($_POST['level_list'] ?? 1),
                'level_view' => intval($_POST['level_view'] ?? 1),
                'level_write' => intval($_POST['level_write'] ?? 1),
                'level_comment' => intval($_POST['level_comment'] ?? 1),
                'point_write' => intval($_POST['point_write'] ?? 0),
                'point_view' => intval($_POST['point_view'] ?? 0),
                'point_comment' => intval($_POST['point_comment'] ?? 0)
            ]);
        }
        $this->redirect('/admin/boards');
    }

    public function updateBoard() {
        $id = $_POST['id'] ?? null;
        $groupId = $_POST['group_id'] ?? null;
        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        $skin = $_POST['skin'] ?? 'basic';
        $maxReplies = intval($_POST['max_replies'] ?? 3);
        $allowComments = intval($_POST['allow_comments'] ?? 1);
        $pageRows = intval($_POST['page_rows'] ?? 20);
        $pageButtons = intval($_POST['page_buttons'] ?? 5);

        if ($id && $title) {
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE boards SET group_id = :group_id, title = :title, description = :description, skin = :skin, max_replies = :max_replies, allow_comments = :allow_comments, page_rows = :page_rows, page_buttons = :page_buttons, level_list = :level_list, level_view = :level_view, level_write = :level_write, level_comment = :level_comment, point_write = :point_write, point_view = :point_view, point_comment = :point_comment WHERE id = :id");
            $stmt->execute([
                'group_id' => $groupId,
                'title' => $title,
                'description' => $desc,
                'skin' => $skin,
                'max_replies' => $maxReplies,
                'allow_comments' => $allowComments,
                'page_rows' => $pageRows,
                'page_buttons' => $pageButtons,
                'level_list' => intval($_POST['level_list'] ?? 1),
                'level_view' => intval($_POST['level_view'] ?? 1),
                'level_write' => intval($_POST['level_write'] ?? 1),
                'level_comment' => intval($_POST['level_comment'] ?? 1),
                'point_write' => intval($_POST['point_write'] ?? 0),
                'point_view' => intval($_POST['point_view'] ?? 0),
                'point_comment' => intval($_POST['point_comment'] ?? 0),
                'id' => $id
            ]);
        }
        $this->redirect('/admin/boards');
    }

    public function deleteBoard() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM boards WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
        $this->redirect('/admin/boards');
    }

    public function visitors() {
        $db = Database::getInstance();
        
        // Auto-fix: Ensure IP columns exist (Migration)
        try {
            $db->query("SELECT allowed_ips FROM config LIMIT 1");
        } catch (\PDOException $e) {
            try {
                $db->exec("ALTER TABLE config ADD COLUMN allowed_ips TEXT COMMENT 'Whitelist'");
                $db->exec("ALTER TABLE config ADD COLUMN blocked_ips TEXT COMMENT 'Blacklist'");
            } catch (\PDOException $e2) {}
        }

        $config = $db->query("SELECT * FROM config WHERE id = 1")->fetch();

        // 1. Today's unique visitors
        $today = date('Y-m-d');
        $stmt = $db->prepare("SELECT COUNT(*) FROM visitor_logs WHERE visit_date = ?");
        $stmt->execute([$today]);
        $stats['today'] = $stmt->fetchColumn();

        // 2. Yesterday's unique visitors
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $stmt = $db->prepare("SELECT COUNT(*) FROM visitor_logs WHERE visit_date = ?");
        $stmt->execute([$yesterday]);
        $stats['yesterday'] = $stmt->fetchColumn();

        // 3. Currently Active (last 5 minutes)
        $stats['active'] = $db->query("SELECT COUNT(*) FROM visitor_logs WHERE last_active_at > (NOW() - INTERVAL 5 MINUTE)")->fetchColumn();

        // 4. Total unique visitors
        $stats['total'] = $db->query("SELECT COUNT(*) FROM visitor_logs")->fetchColumn();

        // 4. Daily stats (last 15 days)
        $dailyData = $db->query("SELECT visit_date, COUNT(*) as count FROM visitor_logs GROUP BY visit_date ORDER BY visit_date DESC LIMIT 15")->fetchAll();
        
        // 5. Paginated Access Logs
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 30; // 30 rows as requested
        $offset = ($page - 1) * $limit;

        $totalItems = $db->query("SELECT COUNT(*) FROM visitor_logs")->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        $stmt = $db->prepare("SELECT * FROM visitor_logs ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $recentLogs = $stmt->fetchAll();

        $this->view('admin/visitors', [
            'stats' => $stats,
            'dailyData' => array_reverse($dailyData),
            'recentLogs' => $recentLogs,
            'config' => $config,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function cleanupVisitors() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $period = intval($_POST['period'] ?? 0);
        
        if (in_array($period, [3, 6, 12])) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM visitor_logs WHERE visit_date < DATE_SUB(NOW(), INTERVAL :period MONTH)");
            $stmt->execute(['period' => $period]);
        }
        
        $this->redirect('/admin/visitors');
    }

    public function saveVisitorIps() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE config SET allowed_ips = :allowed, blocked_ips = :blocked WHERE id = 1");
        $stmt->execute([
            'allowed' => $_POST['allowed_ips'] ?? '',
            'blocked' => $_POST['blocked_ips'] ?? ''
        ]);
        
        $this->redirect('/admin/visitors');
    }

    // --- Page Manager Methods ---
    
    public function pages() {
        $db = Database::getInstance();
        $pages = $db->query("SELECT * FROM pages ORDER BY created_at DESC")->fetchAll();
        $this->view('admin/pages', ['pages' => $pages]);
    }

    public function createPage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance();
            $title = $_POST['title'] ?? '';
            $slug = $_POST['slug'] ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
            $content = $_POST['content'] ?? '';

            $stmt = $db->prepare("INSERT INTO pages (title, slug, content) VALUES (:title, :slug, :content)");
            $stmt->execute([
                'title' => $title,
                'slug' => $slug,
                'content' => $content
            ]);
            $this->redirect('/admin/pages');
        } else {
            $this->view('admin/page_form', ['mode' => 'create']);
        }
    }

    public function editPage($vars) {
        $db = Database::getInstance();
        $id = $vars['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $content = $_POST['content'] ?? '';

            $stmt = $db->prepare("UPDATE pages SET title = :title, slug = :slug, content = :content WHERE id = :id");
            $stmt->execute([
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'id' => $id
            ]);
            $this->redirect('/admin/pages');
        } else {
            $page = $db->prepare("SELECT * FROM pages WHERE id = ?");
            $page->execute([$id]);
            $this->view('admin/page_form', ['mode' => 'edit', 'page' => $page->fetch()]);
        }
    }

    public function deletePage() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM pages WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
        $this->redirect('/admin/pages');
    }

    public function uploadPageImage() {
        $uploadDir = '/data/page/';
        $fullPath = CM_PUBLIC_PATH . $uploadDir;
        if (!is_dir($fullPath)) mkdir($fullPath, 0777, true);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newName = 'page_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $fullPath . $newName)) {
                echo json_encode(['url' => $uploadDir . $newName]);
                exit;
            }
        }
        header('Content-Type: application/json', true, 500);
        echo json_encode(['error' => 'Upload failed']);
    }

    public function createTemplate() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $name = strtolower(trim($_POST['template_name'] ?? ''));
        if (!$name || !preg_match('/^[a-z0-9_-]+$/', $name)) {
            die("Invalid template name.");
        }

        // 1. Create Views Folder and Copy from 'basic'
        $sourceViewPath = CM_VIEWS_PATH . '/templates/basic';
        $targetViewPath = CM_VIEWS_PATH . '/templates/' . $name;
        if (!is_dir($targetViewPath) && is_dir($sourceViewPath)) {
            $this->recursiveCopy($sourceViewPath, $targetViewPath);
        }

        // 2. Create Public Assets Folder and Copy from 'basic'
        $sourceAssetPath = CM_ASSET_PATH . '/templates/basic';
        $targetAssetPath = CM_ASSET_PATH . '/templates/' . $name;
        if (!is_dir($targetAssetPath) && is_dir($sourceAssetPath)) {
            $this->recursiveCopy($sourceAssetPath, $targetAssetPath);
        }

        $this->redirect('/admin/config?msg=Template created successfully');
    }

    private function recursiveCopy($src, $dst) {
        if (!is_dir($dst)) {
            mkdir($dst, 0777, true);
        }
        $dir = opendir($src);
        while(false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
