# Shree Astro Care - Astrology Website

A comprehensive astrology website built with HTML5, Tailwind CSS, JavaScript, and PHP backend. This modern, responsive website provides astrology services, daily horoscopes, birth chart analysis, and spiritual guidance.

## 🌟 Features

### Frontend
- **Modern Design**: Built with HTML5 and Tailwind CSS for a cosmic, mystical appearance
- **Responsive Layout**: Mobile-first design that works on all devices
- **Interactive Elements**: Smooth animations and hover effects
- **Accessibility**: Semantic HTML and proper ARIA labels
- **Performance**: Optimized loading and minimal JavaScript

### Core Functionality
- **Daily Horoscopes**: Interactive zodiac sign selection with personalized readings
- **Service Booking**: Contact forms for astrology services
- **Birth Chart Analysis**: Professional astrology reading services
- **Love Compatibility**: Relationship astrology consultations
- **Smooth Navigation**: Single-page application with smooth scrolling

### Backend Features
- **PHP API**: RESTful API endpoints for data management
- **Database Integration**: MySQL database for storing requests and horoscopes
- **Email Notifications**: Automated email system for contact requests
- **Security**: Input validation, sanitization, and CSRF protection
- **Rate Limiting**: API rate limiting to prevent abuse
- **Logging**: Comprehensive logging system for monitoring

## 🛠 Technology Stack

### Frontend
- **HTML5**: Semantic markup and modern web standards
- **Tailwind CSS**: Utility-first CSS framework for styling
- **JavaScript (ES6+)**: Modern JavaScript for interactivity
- **Font Awesome**: Icon library for visual elements
- **Custom CSS**: Additional styling for animations and effects

### Backend
- **PHP 7.4+**: Server-side scripting
- **MySQL**: Database management
- **JSON APIs**: RESTful API architecture
- **PDO**: Secure database connections
- **PHPMailer**: Email functionality (optional enhancement)

## 📁 Project Structure

```
shreeastrocare-72-website/
├── index.html              # Main HTML file
├── css/
│   └── style.css          # Custom CSS styles
├── js/
│   └── main.js            # JavaScript functionality
├── php/
│   ├── config.php         # Configuration settings
│   ├── contact-handler.php # Contact form processing
│   ├── horoscope-api.php  # Horoscope API endpoint
│   └── database-schema.sql # Database structure
├── api/
│   └── v1/
│       └── index.php      # API router
├── images/                # Image assets (to be added)
├── .gitignore            # Git ignore rules
└── README.md             # Project documentation
```

## 🚀 Installation & Setup

### Prerequisites
- Web server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/yaswanthbendi/shreeastrocare-72-website.git
   cd shreeastrocare-72-website
   ```

2. **Set up the database**
   ```bash
   mysql -u your_username -p < php/database-schema.sql
   ```

3. **Configure the application**
   ```bash
   cp php/config.php php/config-local.php
   # Edit php/config-local.php with your database and email settings
   ```

4. **Set up web server**
   - Point your web server document root to the project directory
   - Ensure PHP is enabled
   - Configure URL rewriting for the API (optional)

5. **Open in browser**
   ```
   http://localhost/shreeastrocare-72-website
   ```

### Database Configuration

Create a MySQL database and import the schema:

```sql
CREATE DATABASE shreeastrocare_db;
USE shreeastrocare_db;
SOURCE php/database-schema.sql;
```

Update the configuration in `php/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'shreeastrocare_db');
```

## 🎯 Usage

### Homepage
The homepage features a cosmic design with:
- Hero section with call-to-action buttons
- Service overview cards
- Interactive horoscope section
- About section
- Contact form

### Services
Three main astrology services:
1. **Birth Chart Analysis** ($49.99) - Complete natal chart reading
2. **Daily Horoscope** (Free) - Daily zodiac predictions
3. **Love Compatibility** ($29.99) - Relationship analysis

### API Endpoints

#### Get Daily Horoscope
```
GET /api/v1/horoscope?sign=aries
```

#### Submit Contact Request
```
POST /api/v1/contact
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "birthdate": "1990-01-15",
    "service": "birth-chart",
    "message": "I'm interested in a birth chart reading."
}
```

#### Get Services
```
GET /api/v1/services
```

#### Health Check
```
GET /api/v1/health
```

## 🎨 Customization

### Colors
The website uses a cosmic color scheme defined in Tailwind CSS:
- **Cosmic Blue**: `#1a1f3a` - Dark blue backgrounds
- **Stellar Gold**: `#ffd700` - Accent color for highlights
- **Mystic Purple**: `#6a0dad` - Secondary accent
- **Celestial Teal**: `#008080` - Tertiary accent

### Fonts
- **Main Font**: System fonts (sans-serif)
- **Mystical Font**: Georgia (serif) for headers
- **Icons**: Font Awesome 6

### Adding New Features

1. **New Service**: Add to the services array in JavaScript and PHP API
2. **New Horoscope**: Update the horoscope data in both frontend and backend
3. **New Section**: Follow the existing HTML structure and styling patterns

## 🔧 Development

### Frontend Development
```bash
# Watch for CSS changes (if using build tools)
npm run watch

# Lint JavaScript
npx eslint js/main.js

# Format code
npx prettier --write .
```

### Backend Development
```bash
# Check PHP syntax
php -l php/*.php

# Run local development server
php -S localhost:8000

# Check logs
tail -f php/logs/app.log
```

## 🧪 Testing

### Manual Testing
1. Test all form submissions
2. Verify horoscope functionality
3. Check responsive design on mobile
4. Test API endpoints
5. Validate email notifications

### Browser Compatibility
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## 📊 Performance

### Optimization Features
- Minified CSS/JS (production ready)
- Optimized images
- Lazy loading for images
- Efficient database queries
- Caching for API responses
- CDN for external resources

### Metrics
- **Page Load Time**: < 3 seconds
- **Lighthouse Score**: 90+ (Performance, Accessibility, SEO)
- **Mobile Friendly**: 100% responsive

## 🔐 Security

### Implemented Security Measures
- Input validation and sanitization
- SQL injection prevention (PDO prepared statements)
- XSS protection
- CSRF protection (configurable)
- Rate limiting
- Secure headers
- Error handling without information disclosure

### Recommendations
1. Use HTTPS in production
2. Implement proper authentication for admin features
3. Regular security updates
4. Monitor logs for suspicious activity
5. Backup database regularly

## 🚀 Deployment

### Production Checklist
- [ ] Enable HTTPS
- [ ] Update configuration for production
- [ ] Set up database backups
- [ ] Configure error logging
- [ ] Test email functionality
- [ ] Set up monitoring
- [ ] Configure caching
- [ ] Optimize images
- [ ] Enable compression

### Hosting Requirements
- PHP 7.4+
- MySQL 5.7+
- 100MB disk space minimum
- SSL certificate
- Email capabilities

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Style
- Use consistent indentation (2 spaces for HTML/CSS/JS, 4 spaces for PHP)
- Follow semantic HTML practices
- Use meaningful variable names
- Comment complex functionality
- Follow PSR-12 for PHP code

## 📞 Support

For support and questions:
- Email: info@shreeastrocare.com
- Phone: +1 (555) 123-STAR

## 📄 License

This project is proprietary. All rights reserved.

## 🙏 Acknowledgments

- Tailwind CSS for the utility-first CSS framework
- Font Awesome for beautiful icons
- PHP community for excellent documentation
- Astrology community for spiritual inspiration

---

**Built with cosmic energy and modern web technologies** ✨

*"May the stars guide your digital journey"*
