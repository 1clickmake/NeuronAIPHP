<?php

namespace App\Core;

class View {
    public static function render($path, $data = []) {
        // CSRF Token auto-injection
        $data['csrf_token'] = Csrf::getToken();
        
        // Escape data recursively for XSS protection
        $escapedData = self::escape($data);

        // Extract variables to local scope
        extract($escapedData);
        
        // Provide raw data for special cases (WYSIWYG, etc)
        $_raw = $data;

        // Define path
        $viewFile = __DIR__ . '/../../views/' . $path . '.php'; // Adjust path relative to App/Core

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            // Fallback for absolute paths or different structure if needed
            // But relying on relative path from root based on standard structure
            $rootViewFile = CM_Path . '/views/' . $path . '.php'; // CM_Path needs to be defined
            if (file_exists($rootViewFile)) {
                require $rootViewFile;
            } else {
                echo "View file not found: $path";
            }
        }
    }

    private static function escape($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::escape($value);
            }
        } elseif (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}
