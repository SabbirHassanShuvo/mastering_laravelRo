<?php
/**
 * Flutter Deep Link Verification Handler
 * 
 * Instructions:
 * 1. Place this file at: public/.well-known/index.php
 * 2. Ensure your server redirects requests from:
 *    - .well-known/assetlinks.json
 *    - .well-known/apple-app-site-association
 *    to this index.php file.
 *    (For Laravel, you might need to add rules in public/.htaccess or Nginx config)
 */

// ============ APP CONFIGURATION ============
// Replace with your actual Flutter app details
define('ANDROID_PACKAGE_NAME', 'com.yourcompany.appname');
define('ANDROID_SHA256', 'YOUR_SHA256_CERT_FINGERPRINT_HERE');

define('IOS_TEAM_ID', 'YOUR_TEAM_ID_HERE'); 
define('IOS_BUNDLE_ID', 'com.yourcompany.appname');
// ===========================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get requested file
$file = isset($_GET['file']) ? basename($_GET['file']) : '';

// Also handle direct path
if (empty($file)) {
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, 'assetlinks.json') !== false) $file = 'assetlinks.json';
    if (strpos($uri, 'apple-app-site-association') !== false) $file = 'apple-app-site-association';
}

switch ($file) {
    case 'assetlinks.json':
        serveAndroidVerification();
        break;
    
    case 'apple-app-site-association':
        serveIOSVerification();
        break;
    
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Verification file not found', 'requested' => $file]);
        break;
}

function serveAndroidVerification() {
    $assetLinks = [
        [
            "relation" => ["delegate_permission/common.handle_all_urls"],
            "target" => [
                "namespace" => "android_app",
                "package_name" => ANDROID_PACKAGE_NAME,
                "sha256_cert_fingerprints" => [ANDROID_SHA256]
            ]
        ]
    ];
    echo json_encode($assetLinks, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

function serveIOSVerification() {
    $appleSiteAssociation = [
        "applinks" => [
            "apps" => [],
            "details" => [
                [
                    "appID" => IOS_TEAM_ID . "." . IOS_BUNDLE_ID,
                    "paths" => ["/share/*", "/product/*", "/*"]
                ]
            ]
        ],
        "webcredentials" => [
            "apps" => [IOS_TEAM_ID . "." . IOS_BUNDLE_ID]
        ]
    ];
    echo json_encode($appleSiteAssociation, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}
?>
