<?php
/**
 * Contact Form Handler
 * Handles form submissions from the contact page
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration (update with your database details)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'shreeastrocare_db';

// Email configuration
$adminEmail = 'admin@shreeastrocare.com';
$fromEmail = 'noreply@shreeastrocare.com';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    // Validate required fields
    $requiredFields = ['name', 'email', 'birthdate', 'service'];
    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    // Sanitize input data
    $name = sanitizeInput($input['name']);
    $email = sanitizeInput($input['email']);
    $birthdate = sanitizeInput($input['birthdate']);
    $service = sanitizeInput($input['service']);
    $message = sanitizeInput($input['message'] ?? '');
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    // Validate birthdate
    if (!validateDate($birthdate)) {
        throw new Exception('Invalid birth date');
    }
    
    // Connect to database
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Insert into database
    $stmt = $pdo->prepare("
        INSERT INTO contact_requests 
        (name, email, birthdate, service, message, submitted_at, status) 
        VALUES (?, ?, ?, ?, ?, NOW(), 'pending')
    ");
    
    $stmt->execute([$name, $email, $birthdate, $service, $message]);
    $requestId = $pdo->lastInsertId();
    
    // Send email notification to admin
    $emailSubject = "New Astrology Reading Request - #{$requestId}";
    $emailBody = generateEmailBody($name, $email, $birthdate, $service, $message, $requestId);
    
    if (sendEmail($adminEmail, $emailSubject, $emailBody)) {
        // Send confirmation email to client
        $confirmationSubject = "Thank You for Your Astrology Reading Request";
        $confirmationBody = generateConfirmationEmail($name, $service, $requestId);
        sendEmail($email, $confirmationSubject, $confirmationBody);
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Your request has been submitted successfully',
        'requestId' => $requestId
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function generateEmailBody($name, $email, $birthdate, $service, $message, $requestId) {
    $serviceNames = [
        'birth-chart' => 'Birth Chart Analysis - $49.99',
        'love-compatibility' => 'Love Compatibility - $29.99',
        'consultation' => 'Personal Consultation - $79.99'
    ];
    
    $serviceName = $serviceNames[$service] ?? $service;
    
    return "
        <html>
        <head><title>New Astrology Reading Request</title></head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                <h2 style='color: #6a0dad; text-align: center;'>New Astrology Reading Request</h2>
                
                <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h3>Request Details:</h3>
                    <p><strong>Request ID:</strong> #{$requestId}</p>
                    <p><strong>Name:</strong> {$name}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Date of Birth:</strong> {$birthdate}</p>
                    <p><strong>Service Requested:</strong> {$serviceName}</p>
                    <p><strong>Submitted:</strong> " . date('Y-m-d H:i:s') . "</p>
                </div>
                
                " . (!empty($message) ? "
                <div style='background: #fff; padding: 15px; border-left: 4px solid #6a0dad; margin: 20px 0;'>
                    <h3>Additional Message:</h3>
                    <p>{$message}</p>
                </div>
                " : "") . "
                
                <div style='text-align: center; margin-top: 30px;'>
                    <p>Please follow up with the client within 24 hours.</p>
                </div>
            </div>
        </body>
        </html>
    ";
}

function generateConfirmationEmail($name, $service, $requestId) {
    $serviceNames = [
        'birth-chart' => 'Birth Chart Analysis',
        'love-compatibility' => 'Love Compatibility',
        'consultation' => 'Personal Consultation'
    ];
    
    $serviceName = $serviceNames[$service] ?? $service;
    
    return "
        <html>
        <head><title>Thank You for Your Request</title></head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #6a0dad;'>✨ Shree Astro Care ✨</h1>
                    <h2>Thank You for Your Request!</h2>
                </div>
                
                <p>Dear {$name},</p>
                
                <p>Thank you for submitting your astrology reading request. We have received your request for <strong>{$serviceName}</strong> and our certified astrologers are excited to provide you with cosmic insights.</p>
                
                <div style='background: #f0f8ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h3 style='color: #6a0dad;'>Your Request Details:</h3>
                    <p><strong>Request ID:</strong> #{$requestId}</p>
                    <p><strong>Service:</strong> {$serviceName}</p>
                    <p><strong>Submitted:</strong> " . date('Y-m-d H:i:s') . "</p>
                </div>
                
                <h3 style='color: #6a0dad;'>What Happens Next?</h3>
                <ul>
                    <li>Our astrologers will review your birth details within 24 hours</li>
                    <li>We'll prepare your personalized reading based on current planetary positions</li>
                    <li>You'll receive your detailed astrology report via email</li>
                    <li>Follow-up consultation will be available if needed</li>
                </ul>
                
                <div style='background: #fff5cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p><strong>Questions?</strong> Feel free to contact us at <a href='mailto:info@shreeastrocare.com'>info@shreeastrocare.com</a> or call +1 (555) 123-STAR</p>
                </div>
                
                <p>May the stars guide your path!</p>
                
                <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
                    <p><strong>Shree Astro Care</strong><br>
                    Your Guide to the Stars<br>
                    <a href='mailto:info@shreeastrocare.com'>info@shreeastrocare.com</a> | +1 (555) 123-STAR</p>
                </div>
            </div>
        </body>
        </html>
    ";
}

function sendEmail($to, $subject, $body) {
    global $fromEmail;
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        "From: Shree Astro Care <{$fromEmail}>",
        "Reply-To: {$fromEmail}",
        'X-Mailer: PHP/' . phpversion()
    ];
    
    return mail($to, $subject, $body, implode("\r\n", $headers));
}
?>