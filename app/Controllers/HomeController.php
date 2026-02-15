<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class HomeController extends BaseController {
    public function index() {
        $db = Database::getInstance();
        
        // Get site configuration (including template)
        $config = $db->query("SELECT * FROM config WHERE id = 1")->fetch();
        $template = $config['template'] ?? 'basic';
        
        // Get board groups
        $groups = $db->query("SELECT * FROM board_groups")->fetchAll();
        
        foreach ($groups as &$group) {
            $stmt = $db->prepare("SELECT * FROM boards WHERE group_id = :id");
            $stmt->execute(['id' => $group['id']]);
            $group['boards'] = $stmt->fetchAll();
        }

        // Load template-specific main page
        $templatePath = "templates/{$template}/main";
        $this->view($templatePath, ['groups' => $groups, 'config' => $config]);
    }

    public function page($vars) {
        $db = Database::getInstance();
        $slug = $vars['slug'];
        
        $stmt = $db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $page = $stmt->fetch();

        if (!$page) {
            http_response_code(404);
            $this->view('404'); // Or just echo for now
            return;
        }

        $this->view('page', ['page' => $page]);
    }
}
