<?php
/**
 * Horoscope API
 * Provides daily horoscope data for zodiac signs
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $sign = $_GET['sign'] ?? '';
    
    if (empty($sign)) {
        throw new Exception('Zodiac sign is required');
    }
    
    $sign = strtolower(trim($sign));
    
    // Validate zodiac sign
    $validSigns = ['aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 
                   'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces'];
    
    if (!in_array($sign, $validSigns)) {
        throw new Exception('Invalid zodiac sign');
    }
    
    // Get horoscope data (you can integrate with external API or use database)
    $horoscope = getDailyHoroscope($sign);
    
    echo json_encode([
        'success' => true,
        'sign' => $sign,
        'horoscope' => $horoscope,
        'date' => date('Y-m-d')
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

function getDailyHoroscope($sign) {
    // This could be connected to an external horoscope API or database
    // For demo purposes, using static data with some randomization
    
    $horoscopes = [
        'aries' => [
            "Today brings exciting opportunities for new beginnings. Your natural leadership qualities will be highlighted.",
            "Energy levels are high today. Channel your enthusiasm into productive activities and watch things flourish.",
            "Bold decisions made today will pay off in the long run. Trust your instincts and take calculated risks.",
            "Your competitive spirit is strong today. Use it to achieve your goals, but remember to be fair to others."
        ],
        'taurus' => [
            "Stability and comfort are your themes today. Focus on building solid foundations in all areas of life.",
            "Financial opportunities may present themselves. Your practical nature will help you make wise choices.",
            "Take time to enjoy life's simple pleasures. A calm and steady approach will serve you well today.",
            "Your patience will be rewarded. What you've been working towards slowly is about to bear fruit."
        ],
        'gemini' => [
            "Communication is key today. Your wit and charm will open doors to new opportunities.",
            "Your curiosity leads to interesting discoveries. Stay open to learning something completely new.",
            "Social connections bring unexpected benefits. Network and share your ideas with others.",
            "Mental agility is your superpower today. Use it to solve complex problems with creative solutions."
        ],
        'cancer' => [
            "Your intuitive powers are especially strong today. Trust your emotional intelligence in decisions.",
            "Family and home matters take priority. Creating a harmonious environment brings deep satisfaction.",
            "Nurturing others comes naturally to you today. Your compassion makes a significant difference.",
            "Past experiences provide valuable guidance for current challenges. Reflect on lessons learned."
        ],
        'leo' => [
            "Your creativity and charisma shine brightly today. Step into the spotlight with confidence.",
            "Recognition and appreciation come your way. Your generous nature attracts positive attention.",
            "Leadership opportunities arise. Your natural ability to inspire others is particularly strong today.",
            "Express yourself boldly and authentically. Your unique perspective is exactly what's needed now."
        ],
        'virgo' => [
            "Attention to detail serves you well today. Your analytical skills help solve complex problems.",
            "Organization and efficiency are your allies. Streamlining processes brings satisfying results.",
            "Health and wellness activities bring positive results. Small improvements lead to big changes.",
            "Your practical approach impresses others. Your reliability makes you invaluable in any situation."
        ],
        'libra' => [
            "Balance and harmony are within reach today. Your diplomatic skills help resolve conflicts.",
            "Relationships take center stage. Your natural charm attracts positive interactions.",
            "Artistic pursuits bring joy and fulfillment. Beauty in all forms speaks to your soul today.",
            "Fair and just decisions are required. Your balanced perspective helps others see all sides."
        ],
        'scorpio' => [
            "Transformation is in the air. Embrace change as an opportunity for profound growth.",
            "Your investigative nature uncovers hidden truths. Deep insights lead to powerful revelations.",
            "Intensity and passion drive your actions today. Channel this energy into meaningful pursuits.",
            "Trust your instincts about people and situations. Your psychic abilities are particularly sharp."
        ],
        'sagittarius' => [
            "Adventure calls to your restless spirit today. Expand your horizons through new experiences.",
            "Your optimistic outlook inspires others. Share your wisdom and philosophical insights freely.",
            "Learning and teaching go hand in hand today. Knowledge gained is meant to be shared.",
            "Freedom and exploration are essential to your wellbeing. Break free from limiting routines."
        ],
        'capricorn' => [
            "Your ambitious nature is rewarded today. Hard work and persistence pay off significantly.",
            "Structure and discipline create the foundation for lasting success. Stay focused on long-term goals.",
            "Authority figures recognize your capabilities. Professional advancement is within reach.",
            "Traditional approaches work best today. Your conservative strategy proves to be the wisest choice."
        ],
        'aquarius' => [
            "Innovation and originality set you apart today. Your unique ideas capture attention.",
            "Humanitarian causes align with your values. Contributing to the greater good brings fulfillment.",
            "Technology and progress fascinate you today. Embrace new ways of doing familiar things.",
            "Independence is crucial to your happiness. Make choices that honor your authentic self."
        ],
        'pisces' => [
            "Your compassionate nature brings healing to others. Emotional support flows naturally from you.",
            "Dreams and intuition guide your path today. Pay attention to subtle signs and synchronicities.",
            "Creative expression soothes your soul. Art, music, or poetry provide perfect outlets for emotions.",
            "Spiritual practices bring deep peace and clarity. Connect with your inner wisdom through meditation."
        ]
    ];
    
    // Get random horoscope for the sign (simulates daily variation)
    $signHoroscopes = $horoscopes[$sign] ?? $horoscopes['aries'];
    $randomIndex = (date('z') + crc32($sign)) % count($signHoroscopes); // Pseudo-random based on day of year
    
    return $signHoroscopes[$randomIndex];
}

// You can also add functions to integrate with external APIs like:
function getHoroscopeFromExternalAPI($sign) {
    // Example integration with external horoscope API
    $apiUrl = "https://api.horoscope-api.com/horoscope/today/{$sign}";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'User-Agent: Shree Astro Care Website'
        ]
    ]);
    
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response === false) {
        return null;
    }
    
    $data = json_decode($response, true);
    return $data['horoscope'] ?? null;
}

// Database function to store and retrieve horoscopes
function getHoroscopeFromDatabase($sign) {
    // Database configuration
    $dbHost = 'localhost';
    $dbUser = 'your_username';
    $dbPass = 'your_password';
    $dbName = 'shreeastrocare_db';
    
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            SELECT horoscope_text 
            FROM daily_horoscopes 
            WHERE zodiac_sign = ? AND horoscope_date = CURDATE()
        ");
        
        $stmt->execute([$sign]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['horoscope_text'] : null;
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}
?>