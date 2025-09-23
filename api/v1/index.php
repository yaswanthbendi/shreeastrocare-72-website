<?php
/**
 * API v1 Router
 * Handles API requests and routes them to appropriate handlers
 */

require_once '../../php/config.php';

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check rate limit
if (!checkRateLimit()) {
    sendErrorResponse('Rate limit exceeded. Please try again later.', 429);
}

// Get the request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/v1', '', $path);
$path = trim($path, '/');

// Route the request
switch ($path) {
    case 'horoscope':
        handleHoroscopeRequest($method);
        break;
    case 'contact':
        handleContactRequest($method);
        break;
    case 'services':
        handleServicesRequest($method);
        break;
    case 'health':
        handleHealthCheck($method);
        break;
    default:
        sendErrorResponse('Endpoint not found', 404);
}

function handleHoroscopeRequest($method) {
    if ($method !== 'GET') {
        sendErrorResponse('Method not allowed', 405);
    }
    
    $sign = $_GET['sign'] ?? '';
    
    if (empty($sign)) {
        sendErrorResponse('Zodiac sign is required');
    }
    
    $sign = strtolower(trim($sign));
    $validSigns = ['aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 
                   'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces'];
    
    if (!in_array($sign, $validSigns)) {
        sendErrorResponse('Invalid zodiac sign');
    }
    
    try {
        // For demo purposes, use static horoscopes
        $horoscope = getStaticHoroscope($sign);
        sendSuccessResponse([
            'sign' => $sign,
            'horoscope' => $horoscope,
            'date' => date('Y-m-d')
        ]);
    } catch (Exception $e) {
        logMessage('ERROR', 'Horoscope API error: ' . $e->getMessage());
        sendErrorResponse('Failed to fetch horoscope', 500);
    }
}

function handleContactRequest($method) {
    if ($method !== 'POST') {
        sendErrorResponse('Method not allowed', 405);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendErrorResponse('Invalid JSON input');
    }
    
    // Validate required fields
    $requiredFields = ['name', 'email', 'birthdate', 'service'];
    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            sendErrorResponse("Field '$field' is required");
        }
    }
    
    // Sanitize and validate input
    $name = sanitizeInput($input['name']);
    $email = sanitizeInput($input['email'], 'email');
    $birthdate = sanitizeInput($input['birthdate']);
    $service = sanitizeInput($input['service']);
    $message = sanitizeInput($input['message'] ?? '');
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendErrorResponse('Invalid email address');
    }
    
    if (!validateDate($birthdate)) {
        sendErrorResponse('Invalid birth date');
    }
    
    try {
        // For demo purposes, simulate successful submission
        $requestId = rand(1000, 9999);
        
        logMessage('INFO', "New contact request submitted", [
            'request_id' => $requestId,
            'email' => $email,
            'service' => $service
        ]);
        
        sendSuccessResponse([
            'message' => 'Your request has been submitted successfully',
            'requestId' => $requestId
        ]);
        
    } catch (Exception $e) {
        logMessage('ERROR', 'Contact API error: ' . $e->getMessage());
        sendErrorResponse('Failed to submit request', 500);
    }
}

function handleServicesRequest($method) {
    if ($method !== 'GET') {
        sendErrorResponse('Method not allowed', 405);
    }
    
    $services = [
        [
            'id' => 'birth-chart',
            'name' => 'Birth Chart Analysis',
            'description' => 'Complete natal chart reading revealing your personality, strengths, and life path.',
            'price' => 49.99,
            'duration' => '60 minutes',
            'features' => [
                'Detailed planetary analysis',
                'Personality insights',
                'Life path guidance',
                'Strengths and challenges',
                'Written report included'
            ]
        ],
        [
            'id' => 'love-compatibility',
            'name' => 'Love Compatibility',
            'description' => 'Discover relationship compatibility through detailed astrological analysis.',
            'price' => 29.99,
            'duration' => '45 minutes',
            'features' => [
                'Compatibility percentage',
                'Relationship strengths',
                'Areas of growth',
                'Communication tips',
                'Future outlook'
            ]
        ],
        [
            'id' => 'consultation',
            'name' => 'Personal Consultation',
            'description' => 'One-on-one consultation with our certified astrologers.',
            'price' => 79.99,
            'duration' => '90 minutes',
            'features' => [
                'Live video session',
                'Personalized guidance',
                'Q&A session',
                'Follow-up support',
                'Recording provided'
            ]
        ]
    ];
    
    sendSuccessResponse(['services' => $services]);
}

function handleHealthCheck($method) {
    if ($method !== 'GET') {
        sendErrorResponse('Method not allowed', 405);
    }
    
    sendSuccessResponse([
        'status' => 'healthy',
        'timestamp' => date('c'),
        'version' => '1.0.0'
    ]);
}

function getStaticHoroscope($sign) {
    $horoscopes = [
        'aries' => "Today brings exciting opportunities for new beginnings. Your natural leadership qualities shine.",
        'taurus' => "Stability and comfort guide your path today. Focus on building solid foundations.",
        'gemini' => "Communication opens doors to new possibilities. Your charm attracts positive interactions.",
        'cancer' => "Trust your intuitive powers today. Family and emotional connections bring joy.",
        'leo' => "Your creativity and charisma are highlighted. Step into the spotlight with confidence.",
        'virgo' => "Attention to detail serves you well. Organization brings satisfying results.",
        'libra' => "Balance and harmony are within reach. Your diplomatic skills resolve conflicts.",
        'scorpio' => "Transformation beckons. Deep insights lead to powerful personal growth.",
        'sagittarius' => "Adventure calls to your spirit. New experiences expand your horizons.",
        'capricorn' => "Your ambitious nature is rewarded. Hard work leads to recognition.",
        'aquarius' => "Innovation sets you apart today. Your unique ideas capture attention.",
        'pisces' => "Compassion flows naturally from you. Creative expression soothes your soul."
    ];
    
    return $horoscopes[$sign] ?? $horoscopes['aries'];
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}
?>