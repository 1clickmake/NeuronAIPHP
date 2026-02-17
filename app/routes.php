<?php

return function(FastRoute\RouteCollector $r) {
    // Install Routes
    $r->addRoute(['GET', 'POST'], '/install', ['App\Controllers\InstallController', 'index']);
    
    // Auth Routes
    $r->addRoute('GET', '/login', ['App\Controllers\AuthController', 'showLogin']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/register', ['App\Controllers\AuthController', 'showRegister']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthController', 'register']);
    $r->addRoute('GET', '/logout', ['App\Controllers\AuthController', 'logout']);
    $r->addRoute('GET', '/mypage', ['App\Controllers\AuthController', 'mypage']);
    $r->addRoute('POST', '/profile/update', ['App\Controllers\AuthController', 'updateProfile']);
    $r->addRoute('POST', '/profile/delete', ['App\Controllers\AuthController', 'deleteAccount']);
    $r->addRoute('GET', '/auth/check-duplicate', ['App\Controllers\AuthController', 'checkDuplicate']);

    // Admin Routes
    $r->addRoute('GET', '/admin', ['App\Controllers\AdminController', 'index']);
    $r->addRoute('GET', '/admin/config', ['App\Controllers\AdminController', 'config']);
    $r->addRoute('POST', '/admin/config', ['App\Controllers\AdminController', 'updateConfig']);
    $r->addRoute('POST', '/admin/config/create-template', ['App\Controllers\AdminController', 'createTemplate']);
    $r->addRoute('POST', '/admin/config/upload-image', ['App\Controllers\AdminController', 'uploadImage']);
    $r->addRoute('GET', '/admin/users', ['App\Controllers\AdminController', 'users']);
    $r->addRoute('POST', '/admin/users/create', ['App\Controllers\AdminController', 'createUser']);
    $r->addRoute('POST', '/admin/users/update', ['App\Controllers\AdminController', 'updateUser']);
    $r->addRoute('POST', '/admin/users/delete', ['App\Controllers\AdminController', 'deleteUser']);
    $r->addRoute('GET', '/admin/groups', ['App\Controllers\AdminController', 'groups']);
    $r->addRoute('POST', '/admin/groups', ['App\Controllers\AdminController', 'createGroup']);
    $r->addRoute('POST', '/admin/groups/update', ['App\Controllers\AdminController', 'updateGroup']);
    $r->addRoute('POST', '/admin/groups/delete', ['App\Controllers\AdminController', 'deleteGroup']);
    $r->addRoute('GET', '/admin/boards', ['App\Controllers\AdminController', 'boards']);
    $r->addRoute('POST', '/admin/boards', ['App\Controllers\AdminController', 'createBoard']);
    $r->addRoute('POST', '/admin/boards/update', ['App\Controllers\AdminController', 'updateBoard']);
    $r->addRoute('POST', '/admin/boards/delete', ['App\Controllers\AdminController', 'deleteBoard']);
    $r->addRoute('GET', '/admin/visitors', ['App\Controllers\AdminController', 'visitors']);
    $r->addRoute('POST', '/admin/visitors/cleanup', ['App\Controllers\AdminController', 'cleanupVisitors']);
    $r->addRoute('POST', '/admin/visitors/save-ips', ['App\Controllers\AdminController', 'saveVisitorIps']);
    $r->addRoute('GET', '/admin/point', ['App\Controllers\AdminController', 'point']);
    $r->addRoute('POST', '/admin/point/update', ['App\Controllers\AdminController', 'updatePoint']);
    $r->addRoute('POST', '/admin/point/bulk-delete', ['App\Controllers\AdminController', 'bulkDeletePoints']);
    
    // Mail Routes
    $r->addRoute('GET', '/admin/mail', ['App\Controllers\AdminController', 'mailForm']);
    $r->addRoute('POST', '/admin/mail/send', ['App\Controllers\AdminController', 'sendMail']);
    $r->addRoute('GET', '/admin/mail/logs', ['App\Controllers\AdminController', 'mailLogs']);
    $r->addRoute('POST', '/admin/mail/config', ['App\Controllers\AdminController', 'saveMailConfig']);
    $r->addRoute('POST', '/admin/mail/bulk-delete', ['App\Controllers\AdminController', 'bulkDeleteMailLogs']);

    // Page Manager Routes
    $r->addRoute('GET', '/admin/pages', ['App\Controllers\AdminController', 'pages']);
    $r->addRoute(['GET', 'POST'], '/admin/pages/create', ['App\Controllers\AdminController', 'createPage']);
    $r->addRoute(['GET', 'POST'], '/admin/pages/edit/{id:\d+}', ['App\Controllers\AdminController', 'editPage']);
    $r->addRoute('POST', '/admin/pages/delete', ['App\Controllers\AdminController', 'deletePage']);
    $r->addRoute('POST', '/admin/pages/upload-image', ['App\Controllers\AdminController', 'uploadPageImage']);

    // Board Routes
    $r->addRoute('GET', '/board/{slug}', ['App\Controllers\BoardController', 'index']);
    $r->addRoute('GET', '/board/view/{id:\d+}', ['App\Controllers\BoardController', 'show']);
    $r->addRoute(['GET', 'POST'], '/board/write/{slug}', ['App\Controllers\BoardController', 'write']);
    $r->addRoute(['GET', 'POST'], '/board/edit/{id:\d+}', ['App\Controllers\BoardController', 'edit']);
    $r->addRoute('POST', '/board/delete/{id:\d+}', ['App\Controllers\BoardController', 'delete']);
    $r->addRoute('POST', '/board/bulk-delete', ['App\Controllers\BoardController', 'bulkDelete']);
    $r->addRoute('POST', '/board/upload-image/{slug}', ['App\Controllers\BoardController', 'uploadEditorImage']);
    $r->addRoute('GET', '/board/download/{id:\d+}', ['App\Controllers\BoardController', 'download']);
    
    // Reply (답글) Routes
    $r->addRoute('POST', '/board/reply/{id:\d+}', ['App\Controllers\BoardController', 'writeReply']);
    $r->addRoute('POST', '/board/reply/delete/{id:\d+}', ['App\Controllers\BoardController', 'deleteReply']);
    
    // Comment (댓글) Routes
    $r->addRoute('POST', '/board/comment/add', ['App\Controllers\BoardController', 'addComment']);
    $r->addRoute('POST', '/board/comment/delete', ['App\Controllers\BoardController', 'deleteComment']);

    // Generic Page Route (must be near bottom)
    $r->addRoute('GET', '/page/{slug}', ['App\Controllers\HomeController', 'page']);

    // Frontend Routes
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    $r->addRoute('POST', '/contact/send', ['App\Controllers\HomeController', 'sendContact']);
};
