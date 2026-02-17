<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;
use Mailer;
require_once __DIR__ . '/../../lib/mailer.lib.php';

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

            // If the updated user is the currently logged-in user, refresh session
            $isSameUser = false;
            if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id) $isSameUser = true;
            if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] == $userid) $isSameUser = true;

            if ($isSameUser) {
                $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $freshUser = $stmt->fetch();
                if ($freshUser) {
                    $_SESSION['user'] = $freshUser;
                    error_log("Session updated for user: " . $freshUser['username']);
                }
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

        // 1. Create Views Folder and Copy from 'basic' (Updated logic to copy folder)
        $sourceViewPath = CM_VIEWS_PATH . '/templates/basic';
        $targetViewPath = CM_VIEWS_PATH . '/templates/' . $name;
        
        // Ensure source exists
        if (!is_dir($sourceViewPath)) {
             die("Source template 'basic' not found.");
        }

        if (!is_dir($targetViewPath)) {
            $this->recursiveCopy($sourceViewPath, $targetViewPath);
        }

        // 2. Create Public Assets Folder and Copy from 'basic'
        // Note: Assets might be in public/assets/templates/basic
        // Assuming CM_ASSET_PATH points to public/assets
        $sourceAssetPath = CM_PUBLIC_PATH . '/assets/templates/basic';
        $targetAssetPath = CM_PUBLIC_PATH . '/assets/templates/' . $name;
        
        if (is_dir($sourceAssetPath) && !is_dir($targetAssetPath)) {
            $this->recursiveCopy($sourceAssetPath, $targetAssetPath);
        }

        $this->redirect('/admin/config?msg=Template created successfully');
    }

    // --- Mail Methods ---
    public function mailForm() {
        $db = Database::getInstance();
        // Use a fixed range for levels (1-10)
        $levels = range(1, 10);
        
        $smtpConfigured = !empty($_ENV['SMTP_HOST']) && !empty($_ENV['SMTP_USER']) && !empty($_ENV['SMTP_PASS']);

        $this->view('admin/mail_form', [
            'levels' => $levels,
            'smtpConfigured' => $smtpConfigured,
            'smtpConfig' => [
                'host' => $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com',
                'port' => $_ENV['SMTP_PORT'] ?? '465',
                'user' => $_ENV['SMTP_USER'] ?? '',
                'pass' => $_ENV['SMTP_PASS'] ?? '', // Usually hidden
                'from_name' => $_ENV['SMTP_FROM_NAME'] ?? 'Neuron AI Admin'
            ]
        ]);
    }

    public function saveMailConfig() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $host = $_POST['smtp_host'] ?? 'smtp.gmail.com';
        $port = $_POST['smtp_port'] ?? '465';
        $user = $_POST['smtp_user'] ?? '';
        $pass = $_POST['smtp_pass'] ?? '';
        $fromName = $_POST['smtp_from_name'] ?? 'Neuron AI Admin';

        // Update .env file
        $envPath = __DIR__ . '/../../.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            
            $settings = [
                'SMTP_HOST' => $host,
                'SMTP_PORT' => $port,
                'SMTP_USER' => $user,
                'SMTP_PASS' => $pass,
                'SMTP_FROM_NAME' => $fromName
            ];

            foreach ($settings as $key => $value) {
                // If key exists, replace it
                if (preg_match("/^$key=.*/m", $envContent)) {
                    $envContent = preg_replace("/^$key=.*/m", "$key=\"$value\"", $envContent);
                } else {
                    // If key doesn't exist, append it
                    $envContent .= "\n$key=\"$value\"";
                }
            }

            file_put_contents($envPath, $envContent);
        }

        $this->redirect('/admin/mail?msg=SMTP+Settings+Saved');
    }

    public function sendMail() {
        if (!\App\Core\Csrf::verify($_POST['csrf_token'] ?? '')) die("CSRF validation failed");

        $targetType = $_POST['target_type'] ?? 'all';
        $targetLevel = $_POST['target_level'] ?? 1;
        $targetIds = $_POST['target_ids'] ?? '';
        $targetEmails = $_POST['target_emails'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $content = $_POST['content'] ?? '';

        if (!$subject || !$content) {
            die("Subject and Content are required.");
        }

        // Handle Attachments
        $attachments = [];
        $attachmentNames = []; // For logging
        if (isset($_FILES['attachments'])) {
            $files = $_FILES['attachments'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpDir = sys_get_temp_dir() . '/mail_attachments/';
                    if (!is_dir($tmpDir)) mkdir($tmpDir, 0777, true);
                    
                    $cleanName = basename($files['name'][$i]);
                    $targetPath = $tmpDir . uniqid() . '_' . $cleanName;
                    
                    if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                        $attachments[] = [$targetPath, $cleanName]; // Pass array [path, name]
                        $attachmentNames[] = $cleanName;
                    }
                }
            }
        }

        $recipients = [];
        $targetInfo = '';
        $db = Database::getInstance();

        if ($targetType === 'all') {
            $users = $db->query("SELECT email FROM users WHERE email != ''")->fetchAll(PDO::FETCH_COLUMN);
            $recipients = $users;
            $targetInfo = 'All Members';
        } elseif ($targetType === 'level') {
            $stmt = $db->prepare("SELECT email FROM users WHERE level = ? AND email != ''");
            $stmt->execute([$targetLevel]);
            $recipients = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $targetInfo = 'Level ' . $targetLevel;
        } elseif ($targetType === 'select') {
            $ids = array_map('trim', explode(',', $targetIds));
            $ids = array_filter($ids);
            if (!empty($ids)) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $db->prepare("SELECT email FROM users WHERE user_id IN ($placeholders) AND email != ''");
                $stmt->execute($ids);
                $recipients = $stmt->fetchAll(PDO::FETCH_COLUMN);
            }
            $targetInfo = 'Member IDs: ' . implode(', ', $ids);
        } elseif ($targetType === 'email') {
            $emails = array_map('trim', explode(',', $targetEmails));
            $recipients = array_filter($emails, function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });
            $targetInfo = 'Direct Emails'; // Or display first few?
        }

        if (!empty($recipients)) {
             $successCount = 0;
             $failCount = 0;
             $db = Database::getInstance();
             
             // Send individually (privacy safe)
             foreach ($recipients as $email) {
                 $result = Mailer::send($email, $subject, $content, $attachments, false); // false = do not log individually
                 if ($result['success']) $successCount++;
                 else $failCount++;
             }
             
             // Log ONCE
             try {
                // We need to implement manual logging here since we disabled auto-logging in Mailer::send
                // Or I can update Mailer to have a logBatch method, or just straight INSERT here.
                // Let's do straight INSERT here for clarity since DB schema changed.
                
                // Recipients string
                $recipientStr = implode(', ', $recipients);
                $attachStr = implode(', ', $attachmentNames);
                $status = ($failCount === 0) ? 'success' : ($successCount === 0 ? 'fail' : 'partial');
                $errorMsg = ($failCount > 0) ? "$failCount failed" : null;

                $stmt = $db->prepare("INSERT INTO mail_logs (target_info, recipient, subject, content, attachments, status, error_message) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $targetInfo,
                    $recipientStr,
                    $subject,
                    $content,
                    $attachStr,
                    $status,
                    $errorMsg
                ]);

             } catch (\Exception $e) {
                 error_log("Mail logging failed: " . $e->getMessage());
             }
             
             // Cleanup Attachments
             foreach ($attachments as $att) {
                 if (is_array($att)) @unlink($att[0]);
             }
             if (isset($tmpDir) && is_dir($tmpDir)) @rmdir($tmpDir);

             $msg = "Sent: $successCount, Failed: $failCount";
        } else {
             $msg = "No recipients found.";
        }

        $this->redirect('/admin/mail?msg=' . urlencode($msg));
    }

    public function mailLogs() {
        $db = Database::getInstance();
        
        // Ensure Schema is updated
        try {
            // Check for 'target_info' column
            $checkInfo = $db->query("SHOW COLUMNS FROM mail_logs LIKE 'target_info'");
            if ($checkInfo->rowCount() == 0) {
                // Add target_info
                $db->exec("ALTER TABLE mail_logs ADD COLUMN target_info VARCHAR(255) NULL AFTER id");
                // Update recipient to LONGTEXT
                $db->exec("ALTER TABLE mail_logs MODIFY COLUMN recipient LONGTEXT");
                // Add attachments
                $db->exec("ALTER TABLE mail_logs ADD COLUMN attachments TEXT NULL AFTER content");
            }
        } catch (\PDOException $e) {
            // Ignore error if already exists or other issues, just log if necessary
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $totalItems = $db->query("SELECT COUNT(*) FROM mail_logs")->fetchAll(PDO::FETCH_COLUMN)[0];
        $totalPages = ceil($totalItems / $limit);

        $logs = $db->query("SELECT * FROM mail_logs ORDER BY sent_at DESC LIMIT $limit OFFSET $offset")->fetchAll();

        $this->view('admin/mail_logs', [
            'logs' => $logs,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
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
