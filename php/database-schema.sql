-- Database Schema for Shree Astro Care Website
-- Execute this SQL to create the necessary tables

CREATE DATABASE IF NOT EXISTS shreeastrocare_db;
USE shreeastrocare_db;

-- Table for contact form submissions
CREATE TABLE contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    service ENUM('birth-chart', 'love-compatibility', 'consultation') NOT NULL,
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'in-progress', 'completed', 'cancelled') DEFAULT 'pending',
    assigned_astrologer VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_submitted_at (submitted_at)
);

-- Table for daily horoscopes
CREATE TABLE daily_horoscopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zodiac_sign ENUM('aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 
                     'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces') NOT NULL,
    horoscope_date DATE NOT NULL,
    horoscope_text TEXT NOT NULL,
    author VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_sign_date (zodiac_sign, horoscope_date),
    INDEX idx_date (horoscope_date)
);

-- Table for astrologers
CREATE TABLE astrologers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    specialization VARCHAR(255),
    experience_years INT,
    bio TEXT,
    photo_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for blog posts (optional for future expansion)
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(500),
    author_id INT,
    category VARCHAR(100),
    tags VARCHAR(500),
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES astrologers(id),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_category (category)
);

-- Table for birth chart calculations (for future features)
CREATE TABLE birth_charts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    birth_date DATE NOT NULL,
    birth_time TIME,
    birth_location VARCHAR(255),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    sun_sign VARCHAR(50),
    moon_sign VARCHAR(50),
    rising_sign VARCHAR(50),
    chart_data JSON,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES contact_requests(id),
    INDEX idx_birth_date (birth_date)
);

-- Insert sample astrologers
INSERT INTO astrologers (name, email, specialization, experience_years, bio) VALUES
('Dr. Priya Sharma', 'priya@shreeastrocare.com', 'Vedic Astrology', 15, 'Dr. Priya is a renowned Vedic astrologer with over 15 years of experience in birth chart analysis and spiritual guidance.'),
('Michael Chen', 'michael@shreeastrocare.com', 'Western Astrology', 12, 'Michael specializes in Western astrology and psychological astrology, helping clients understand their personality and relationships.'),
('Sarah Williams', 'sarah@shreeastrocare.com', 'Love & Relationships', 10, 'Sarah focuses on relationship compatibility and love astrology, guiding couples towards harmonious partnerships.');

-- Insert sample daily horoscopes for today
INSERT INTO daily_horoscopes (zodiac_sign, horoscope_date, horoscope_text, author) VALUES
('aries', CURDATE(), 'Today brings exciting opportunities for new beginnings. Your natural leadership qualities will be highlighted, making it an excellent time to start fresh projects. Trust your instincts and take bold action.', 'Dr. Priya Sharma'),
('taurus', CURDATE(), 'Stability and comfort are your themes today. Focus on building solid foundations in your personal and professional life. Your patience will be rewarded, and financial matters look promising.', 'Michael Chen'),
('gemini', CURDATE(), 'Communication is key today. Your wit and charm will open doors to new opportunities. Networking and social connections prove beneficial. Stay curious and embrace learning something new.', 'Sarah Williams'),
('cancer', CURDATE(), 'Your intuitive powers are especially strong today. Trust your emotional intelligence to guide important decisions. Family and home matters take priority. Nurture your relationships.', 'Dr. Priya Sharma'),
('leo', CURDATE(), 'Your creativity and charisma shine brightly today. Step into the spotlight and share your talents with the world. Recognition and appreciation come your way. Be generous with others.', 'Michael Chen'),
('virgo', CURDATE(), 'Attention to detail serves you well today. Your analytical skills help solve complex problems. Focus on organization and efficiency in all your endeavors. Health activities bring positive results.', 'Sarah Williams'),
('libra', CURDATE(), 'Balance and harmony are within reach today. Your diplomatic skills help resolve conflicts peacefully. Relationships flourish under your caring attention. Beauty calls to your aesthetic sense.', 'Dr. Priya Sharma'),
('scorpio', CURDATE(), 'Transformation is in the air. Deep insights and investigative abilities lead to powerful revelations. Trust your instincts about people and situations. Embrace meaningful change.', 'Michael Chen'),
('sagittarius', CURDATE(), 'Adventure calls to your restless spirit. Expand your horizons through new experiences and learning. Your optimistic outlook inspires others. Share your wisdom freely.', 'Sarah Williams'),
('capricorn', CURDATE(), 'Your ambitious nature is rewarded today. Hard work and persistence pay off significantly. Authority figures recognize your capabilities. Professional advancement is within reach.', 'Dr. Priya Sharma'),
('aquarius', CURDATE(), 'Innovation and originality set you apart today. Your unique ideas capture attention and admiration. Humanitarian causes align with your values. Technology fasccinates you.', 'Michael Chen'),
('pisces', CURDATE(), 'Your compassionate nature brings healing to others today. Dreams and intuition guide your path clearly. Creative expression soothes your soul. Spiritual practices bring peace.', 'Sarah Williams');

-- Create indexes for better performance
CREATE INDEX idx_contact_date ON contact_requests(submitted_at);
CREATE INDEX idx_horoscope_lookup ON daily_horoscopes(zodiac_sign, horoscope_date);

-- Create a view for active requests
CREATE VIEW active_requests AS
SELECT 
    cr.*,
    a.name as astrologer_name
FROM contact_requests cr
LEFT JOIN astrologers a ON cr.assigned_astrologer = a.email
WHERE cr.status IN ('pending', 'in-progress')
ORDER BY cr.submitted_at DESC;

-- Create a stored procedure for getting today's horoscope
DELIMITER //
CREATE PROCEDURE GetTodayHoroscope(IN sign_name VARCHAR(20))
BEGIN
    SELECT horoscope_text, author
    FROM daily_horoscopes
    WHERE zodiac_sign = sign_name 
    AND horoscope_date = CURDATE()
    LIMIT 1;
END //
DELIMITER ;

-- Sample usage:
-- CALL GetTodayHoroscope('aries');