<?php

use Plugins\chatbot\Controllers\ChatbotController;

return function($r) {
    $r->addRoute('POST', '/api/chatbot/ask', [ChatbotController::class, 'ask']);
    $r->addRoute('GET', '/admin/chatbot/logs', [ChatbotController::class, 'adminLogs']);
    $r->addRoute('POST', '/admin/chatbot/logs/cleanup', [ChatbotController::class, 'cleanupLogs']);

    // Config Routes
    $r->addRoute('GET', '/admin/chatbot/config', [ChatbotController::class, 'config']);
    $r->addRoute('POST', '/admin/chatbot/config', [ChatbotController::class, 'saveConfig']);
};
