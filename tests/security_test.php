<?php
// Mock session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../lib/common.lib.php';

echo "Running Security Tests...\n\n";

// --- 1. Test clean_html (XSS Prevention) ---
$xssPayloads = [
    '<script>alert(1)</script>' => '',
    '<img src=x onerror=alert(1)>' => '<img src="x">', // onerror should be removed
    '<a href="javascript:alert(1)">Click</a>' => '<a href="#">Click</a>',
    '<div style="background-image: url(javascript:alert(1))">Test</div>' => '<div>Test</div>',
    '<b onmouseover=alert(1)>Bold</b>' => '<b>Bold</b>',
    '<iframe src="http://evil.com"></iframe>' => '',
];

$passCount = 0;
$failCount = 0;

foreach ($xssPayloads as $input => $expected) {
    $output = clean_html($input);

    // Simple checks for dangerous content
    $isSafe = true;
    if (stripos($output, '<script') !== false) $isSafe = false;
    if (stripos($output, 'onerror') !== false) $isSafe = false;
    if (stripos($output, 'javascript:') !== false) $isSafe = false;
    if (stripos($output, '<iframe') !== false) $isSafe = false;
    if (stripos($output, 'onmouseover') !== false) $isSafe = false;

    if ($isSafe) {
        echo "[PASS] XSS Test: " . htmlspecialchars($input) . " -> Safe\n";
        $passCount++;
    } else {
        echo "[FAIL] XSS Test: " . htmlspecialchars($input) . " -> " . htmlspecialchars($output) . "\n";
        $failCount++;
    }
}

// --- 2. Test File Upload Whitelist Logic (Simulation) ---
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip'];
$testExtensions = [
    'php' => false,
    'exe' => false,
    'sh' => false,
    'html' => false,
    'jpg' => true,
    'png' => true,
    'zip' => true,
    'phtml' => false
];

foreach ($testExtensions as $ext => $shouldAllow) {
    $isAllowed = in_array($ext, $allowedExtensions);
    if ($isAllowed === $shouldAllow) {
        echo "[PASS] File Extension Test: .$ext -> " . ($isAllowed ? 'Allowed' : 'Blocked') . "\n";
        $passCount++;
    } else {
        echo "[FAIL] File Extension Test: .$ext -> " . ($isAllowed ? 'Allowed' : 'Blocked') . " (Expected: " . ($shouldAllow ? 'Allowed' : 'Blocked') . ")\n";
        $failCount++;
    }
}

// --- 3. Test Path Traversal Logic (Simulation) ---
$paths = [
    '/data/image.jpg' => true,
    '/data/subdir/image.jpg' => true,
    '/data/../config.php' => false,
    '/data/../../index.php' => false,
    '/etc/passwd' => false
];

foreach ($paths as $path => $shouldAllow) {
    $isAllowed = false;
    // Logic from BoardController (String check part)
    if (strpos($path, '/data/') === 0 && strpos($path, '..') === false) {
        $isAllowed = true;
    }

    if ($isAllowed === $shouldAllow) {
        echo "[PASS] Path Traversal Test: $path -> " . ($isAllowed ? 'Allowed' : 'Blocked') . "\n";
        $passCount++;
    } else {
         echo "[FAIL] Path Traversal Test: $path -> " . ($isAllowed ? 'Allowed' : 'Blocked') . "\n";
         $failCount++;
    }
}

echo "\nTotal Tests: " . ($passCount + $failCount) . "\n";
echo "Passed: $passCount\n";
echo "Failed: $failCount\n";

if ($failCount > 0) exit(1);
