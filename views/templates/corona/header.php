<?php
// Corona 템플릿 전용 헤더 - 태양처럼 뜨거운 에너지
include CM_LAYOUT_PATH . '/header.php';
?>

<style>
/* Corona Header Enhancements */
.navbar {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.08), rgba(249, 115, 22, 0.05)) !important;
    box-shadow: 0 4px 20px rgba(249, 115, 22, 0.1) !important;
}

.navbar-brand {
    font-weight: 800 !important;
    background: linear-gradient(135deg, #fbbf24, #f97316, #dc2626);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.3s ease !important;
    text-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
}

.navbar-brand:hover {
    transform: scale(1.08);
    filter: brightness(1.3) drop-shadow(0 0 10px rgba(251, 191, 36, 0.5));
}

.nav-links a {
    position: relative;
    transition: all 0.3s ease !important;
}

.nav-links a::before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #fbbf24, #f97316);
    transition: width 0.3s ease;
    box-shadow: 0 0 10px rgba(249, 115, 22, 0.5);
}

.nav-links a:hover::before {
    width: 100%;
}

.nav-links a:hover {
    color: #f97316 !important;
    text-shadow: 0 0 15px rgba(249, 115, 22, 0.5);
}

.btn-primary {
    position: relative;
    overflow: hidden;
}

.btn-primary::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-primary:hover::after {
    width: 300px;
    height: 300px;
}
</style>
