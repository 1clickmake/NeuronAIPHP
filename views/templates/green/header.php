<?php
// Green 템플릿 전용 헤더 - 자연처럼 신선한 녹색
include CM_LAYOUT_PATH . '/header.php';
?>

<style>
/* Green Header Enhancements */
.navbar {
    background: linear-gradient(135deg, rgba(132, 204, 22, 0.08), rgba(16, 185, 129, 0.05)) !important;
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.1) !important;
}

.navbar-brand {
    font-weight: 700 !important;
    background: linear-gradient(135deg, #84cc16, #10b981, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.3s ease !important;
}

.navbar-brand:hover {
    transform: scale(1.05);
    filter: brightness(1.2) drop-shadow(0 0 8px rgba(132, 204, 22, 0.4));
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
    background: linear-gradient(90deg, #84cc16, #10b981);
    transform: translateX(-50%);
    transition: width 0.3s ease;
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);
}

.nav-links a:hover::after {
    width: 85%;
}

.nav-links a:hover {
    color: #10b981 !important;
}

/* Natural bounce effect on buttons */
.btn-primary {
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
}

.btn-primary:hover {
    transform: translateY(-3px) scale(1.05) !important;
}
</style>
