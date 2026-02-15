<?php

namespace App\Controllers;

class BaseController {
    protected function view($path, $data = []) {
        // Fetch global site configuration
        try {
            $db = \App\Core\Database::getInstance();
            if ($db) {
                // Check if table exists query to avoid errors during clean install
                $stmt = $db->query("SHOW TABLES LIKE 'config'");
                if ($stmt->rowCount() > 0) {
                    $config = $db->query("SELECT * FROM config WHERE id = 1")->fetch();
                    $data['siteConfig'] = $config ?: [];
                }
            }
        } catch (\Exception $e) {
            // Site config table might not exist yet
            $data['siteConfig'] = [];
        }

        // Always pass CSRF token to views
        $data['csrf_token'] = \App\Core\Csrf::getToken();
        
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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/login');
        }
    }
}
