<?php

namespace App\Controllers;

class BaseController {
    protected static $cachedSiteConfig = null;
    protected static $cachedFooterPages = null;

    protected function view($path, $data = []) {
        // Fetch global site configuration with static caching
        try {
            $db = \App\Core\Database::getInstance();
            if ($db) {
                if (self::$cachedSiteConfig === null) {
                    $stmt = $db->query("SHOW TABLES LIKE 'config'");
                    if ($stmt->rowCount() > 0) {
                        $config = $db->query("SELECT * FROM config WHERE id = 1")->fetch();
                        self::$cachedSiteConfig = $config ?: [];
                    } else {
                        self::$cachedSiteConfig = [];
                    }
                }
                $data['siteConfig'] = self::$cachedSiteConfig;

                // Fetch pages for footer links with static caching
                if (self::$cachedFooterPages === null) {
                    $stmtPages = $db->query("SHOW TABLES LIKE 'pages'");
                    if ($stmtPages->rowCount() > 0) {
                        $pages = $db->query("SELECT title, slug FROM pages ORDER BY id ASC")->fetchAll();
                        self::$cachedFooterPages = $pages ?: [];
                    } else {
                        self::$cachedFooterPages = [];
                    }
                }
                $data['footerPages'] = self::$cachedFooterPages;
            }
        } catch (\Exception $e) {
            // Site config table might not exist yet
            $data['siteConfig'] = [];
            $data['footerPages'] = [];
        }

        // Always pass CSRF token to views
        $data['csrf_token'] = \App\Core\Csrf::getToken();

        // Inject user variables to views using AuthService
        $data['is_member'] = \App\Services\AuthService::isMember();
        $data['is_guest']  = \App\Services\AuthService::isGuest();
        $data['is_super']  = \App\Services\AuthService::isSuperAdmin();
        $data['is_admin']  = \App\Services\AuthService::isAdmin();
        $data['user']      = \App\Services\AuthService::user();
        
        \App\Core\View::render($path, $data);
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected function checkAdmin() {
        if (!\App\Services\AuthService::isAdmin()) {
            $this->redirect('/login');
        }
    }
}
