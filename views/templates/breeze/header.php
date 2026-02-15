<?php
// Breeze 템플릿 전용 헤더 - 바람처럼 시원한 네비게이션
include CM_LAYOUT_PATH . '/header.php';
?>

<style>
/* Breeze Header Enhancements */
.navbar {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(59, 130, 246, 0.03)) !important;
}

.navbar-brand {
    font-weight: 700 !important;
    background: linear-gradient(135deg, #06b6d4, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.3s ease !important;
}

.navbar-brand:hover {
    transform: scale(1.05);
    filter: brightness(1.2);
}

.nav-links a {
    position: relative;
    transition: all 0.3s ease !important;
}

.nav-links a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #06b6d4, #3b82f6);
    transform: translateX(-50%);
    transition: width 0.3s ease;
}

.nav-links a:hover::after {
    width: 80%;
}

.nav-links a:hover {
    color: #06b6d4 !important;
}
</style>
