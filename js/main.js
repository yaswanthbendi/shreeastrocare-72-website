// Main JavaScript for Shree Astro Care Website

// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    initializeWebsite();
});

function initializeWebsite() {
    setupMobileMenu();
    setupSmoothScrolling();
    setupHoroscope();
    setupContactForm();
    setupAnimations();
    setupScrollEffects();
}

// Mobile Menu Toggle
function setupMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('show');
        });
        
        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('show');
            });
        });
    }
}

// Smooth Scrolling for Navigation Links
function setupSmoothScrolling() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Horoscope Functionality
function setupHoroscope() {
    const zodiacSigns = document.querySelectorAll('.zodiac-sign');
    const horoscopeDisplay = document.getElementById('horoscope-display');
    const selectedSignElement = document.getElementById('selected-sign');
    const horoscopeTextElement = document.getElementById('horoscope-text');
    
    // Horoscope data
    const horoscopeData = {
        aries: {
            name: "Aries (March 21 - April 19)",
            text: "Today brings exciting opportunities for new beginnings. Your natural leadership qualities will be highlighted, making it an excellent time to start fresh projects. Trust your instincts and take bold action. The stars align to support your ambitious nature."
        },
        taurus: {
            name: "Taurus (April 20 - May 20)",
            text: "Stability and comfort are your themes today. Focus on building solid foundations in your personal and professional life. Your patience will be rewarded, and financial matters look promising. Take time to enjoy life's simple pleasures."
        },
        gemini: {
            name: "Gemini (May 21 - June 20)",
            text: "Communication is key today. Your wit and charm will open doors to new opportunities. Networking and social connections prove beneficial. Stay curious and embrace learning something new. Your adaptability is your greatest asset."
        },
        cancer: {
            name: "Cancer (June 21 - July 22)",
            text: "Your intuitive powers are especially strong today. Trust your emotional intelligence to guide important decisions. Family and home matters take priority. Nurture your relationships and create a harmonious environment around you."
        },
        leo: {
            name: "Leo (July 23 - August 22)",
            text: "Your creativity and charisma shine brightly today. Step into the spotlight and share your talents with the world. Recognition and appreciation come your way. Be generous with your time and energy for others."
        },
        virgo: {
            name: "Virgo (August 23 - September 22)",
            text: "Attention to detail serves you well today. Your analytical skills help solve complex problems. Focus on organization and efficiency in all your endeavors. Health and wellness activities bring positive results."
        }
    };
    
    zodiacSigns.forEach(sign => {
        sign.addEventListener('click', function() {
            const signKey = this.dataset.sign;
            const horoscope = horoscopeData[signKey];
            
            if (horoscope) {
                selectedSignElement.textContent = horoscope.name;
                horoscopeTextElement.textContent = horoscope.text;
                horoscopeDisplay.classList.remove('hidden');
                horoscopeDisplay.scrollIntoView({ behavior: 'smooth' });
            }
            
            // Remove active class from all signs
            zodiacSigns.forEach(s => s.classList.remove('ring-2', 'ring-stellar-gold'));
            // Add active class to clicked sign
            this.classList.add('ring-2', 'ring-stellar-gold');
        });
    });
}

// Contact Form Handling
function setupContactForm() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmission(this);
        });
    }
}

function handleFormSubmission(form) {
    // Get form data
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.innerHTML = '<span class="loading"></span> Processing...';
    submitButton.disabled = true;
    
    // Simulate form submission (replace with actual PHP endpoint)
    setTimeout(() => {
        // For demo purposes, we'll show success message
        showNotification('Thank you! Your request has been submitted successfully. We\'ll contact you within 24 hours.', 'success');
        
        // Reset form
        form.reset();
        
        // Reset button
        submitButton.textContent = originalText;
        submitButton.disabled = false;
        
        // In a real application, you would send data to PHP backend:
        // submitToBackend(data);
    }, 2000);
}

// Notification System
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    } text-white`;
    
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Animation Setup
function setupAnimations() {
    // Add floating animation to service cards
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.2}s`;
        card.classList.add('floating');
    });
    
    // Setup intersection observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    // Observe all sections
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        section.classList.add('fade-in');
        observer.observe(section);
    });
}

// Scroll Effects
function setupScrollEffects() {
    let lastScrollY = window.scrollY;
    const nav = document.querySelector('nav');
    
    window.addEventListener('scroll', function() {
        const currentScrollY = window.scrollY;
        
        // Navbar background opacity based on scroll
        if (currentScrollY > 100) {
            nav.style.backgroundColor = 'rgba(26, 31, 58, 0.95)';
            nav.style.backdropFilter = 'blur(10px)';
        } else {
            nav.style.backgroundColor = 'transparent';
            nav.style.backdropFilter = 'none';
        }
        
        lastScrollY = currentScrollY;
    });
}

// Service Button Handlers
function setupServiceButtons() {
    const serviceButtons = document.querySelectorAll('.service-card button');
    
    serviceButtons.forEach(button => {
        button.addEventListener('click', function() {
            const serviceCard = this.closest('.service-card');
            const serviceName = serviceCard.querySelector('h4').textContent;
            
            // Scroll to contact form
            document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });
            
            // Pre-fill service selection
            const serviceSelect = document.getElementById('service');
            if (serviceName.includes('Birth Chart')) {
                serviceSelect.value = 'birth-chart';
            } else if (serviceName.includes('Love Compatibility')) {
                serviceSelect.value = 'love-compatibility';
            } else if (serviceName.includes('Consultation')) {
                serviceSelect.value = 'consultation';
            }
        });
    });
}

// Call setup functions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    setupServiceButtons();
});

// Utility Functions
function getCurrentDate() {
    return new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function calculateAge(birthDate) {
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    
    return age;
}

// Backend Integration Functions (to be used with PHP backend)
async function submitToBackend(formData) {
    try {
        const response = await fetch('php/contact-handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Thank you! Your request has been submitted successfully.', 'success');
        } else {
            showNotification('There was an error submitting your request. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showNotification('There was an error submitting your request. Please try again.', 'error');
    }
}

async function getDailyHoroscope(sign) {
    try {
        const response = await fetch(`php/horoscope-api.php?sign=${sign}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching horoscope:', error);
        return null;
    }
}

// Initialize everything when the page loads
window.addEventListener('load', function() {
    console.log('Shree Astro Care website loaded successfully! ✨');
});