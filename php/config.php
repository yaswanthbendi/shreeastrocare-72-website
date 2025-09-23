<?php
/**
 * Configuration file for Shree Astro Care Website
 * Update these settings according to your server environment
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'shreeastrocare_db');

// Email Configuration
define('ADMIN_EMAIL', 'admin@shreeastrocare.com');
define('FROM_EMAIL', 'noreply@shreeastrocare.com');
define('SMTP_HOST', 'smtp.gmail.com'); // or your SMTP server
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');

// Site Configuration
define('SITE_NAME', 'Shree Astro Care');
define('SITE_URL', 'https://www.shreeastrocare.com');
define('SITE_DESCRIPTION', 'Professional astrology services, horoscopes, birth charts, and spiritual guidance');

// Security Settings
define('ENABLE_CSRF_PROTECTION', true);
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 1800); // 30 minutes in seconds

// API Configuration
define('HOROSCOPE_API_KEY', 'your-external-api-key-if-needed');
define('RATE_LIMIT_REQUESTS', 100); // requests per hour per IP
define('RATE_LIMIT_WINDOW', 3600); // 1 hour

// File Upload Settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_PATH', __DIR__ . '/uploads/');

// Logging Configuration
define('ENABLE_LOGGING', true);
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR
define('LOG_FILE', __DIR__ . '/logs/app.log');

// Cache Configuration
define('ENABLE_CACHE', true);
define('CACHE_DURATION', 3600); // 1 hour
define('CACHE_PATH', __DIR__ . '/cache/');

// Timezone
date_default_timezone_set('America/New_York'); // Update to your timezone

// Error Reporting (set to false in production)
define('ENABLE_DEBUG', false);

if (ENABLE_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

/**
 * Database Connection Function
 */
function getDatabaseConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (ENABLE_DEBUG) {
                die('Database connection failed: ' . $e->getMessage());
            } else {
                die('Database connection failed. Please try again later.');
            }
        }
    }
    
    return $pdo;
}

/**
 * Logging Function
 */
function logMessage($level, $message, $context = []) {
    if (!ENABLE_LOGGING) {
        return;
    }
    
    $logLevels = ['DEBUG' => 0, 'INFO' => 1, 'WARNING' => 2, 'ERROR' => 3];
    $currentLevel = $logLevels[LOG_LEVEL] ?? 1;
    $messageLevel = $logLevels[$level] ?? 1;
    
    if ($messageLevel < $currentLevel) {
        return;
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $contextString = empty($context) ? '' : ' ' . json_encode($context);
    $logEntry = "[{$timestamp}] {$level}: {$message}{$contextString}" . PHP_EOL;
    
    // Ensure log directory exists
    $logDir = dirname(LOG_FILE);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
}

/**
 * Send Email Function (improved version)
 */
function sendEmailSMTP($to, $subject, $body, $altBody = '') {
    // You can use PHPMailer for better email handling
    // This is a basic implementation using PHP's mail() function
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . SITE_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To: ' . FROM_EMAIL,
        'X-Mailer: PHP/' . phpversion()
    ];
    
    $success = mail($to, $subject, $body, implode("\r\n", $headers));
    
    if ($success) {
        logMessage('INFO', "Email sent successfully to: {$to}");
    } else {
        logMessage('ERROR', "Failed to send email to: {$to}");
    }
    
    return $success;
}

/**
 * Rate Limiting Function
 */
function checkRateLimit($identifier = null) {
    if ($identifier === null) {
        $identifier = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    $cacheFile = CACHE_PATH . 'rate_limit_' . md5($identifier) . '.json';
    
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        $currentTime = time();
        
        // Clean old requests
        $data['requests'] = array_filter($data['requests'], function($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) < RATE_LIMIT_WINDOW;
        });
        
        if (count($data['requests']) >= RATE_LIMIT_REQUESTS) {
            return false; // Rate limit exceeded
        }
    } else {
        $data = ['requests' => []];
    }
    
    $data['requests'][] = time();
    
    // Ensure cache directory exists
    if (!is_dir(CACHE_PATH)) {
        mkdir(CACHE_PATH, 0755, true);
    }
    
    file_put_contents($cacheFile, json_encode($data));
    return true;
}

/**
 * CSRF Token Functions
 */
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!ENABLE_CSRF_PROTECTION) {
        return true;
    }
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Input Sanitization Function
 */
function sanitizeInput($input, $type = 'string') {
    switch ($type) {
        case 'email':
            return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
        case 'int':
            return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        case 'float':
            return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        case 'url':
            return filter_var(trim($input), FILTER_SANITIZE_URL);
        default:
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Response Helper Functions
 */
function sendJSONResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sendErrorResponse($message, $statusCode = 400) {
    sendJSONResponse([
        'success' => false,
        'error' => $message
    ], $statusCode);
}

function sendSuccessResponse($data = []) {
    sendJSONResponse(array_merge([
        'success' => true
    ], $data));
}

// Initialize logging
if (ENABLE_LOGGING) {
    logMessage('INFO', 'Configuration loaded successfully');
}
?>