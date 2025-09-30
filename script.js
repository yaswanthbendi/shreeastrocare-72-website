// Navigation Toggle for Mobile
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');
const navLinks = document.querySelectorAll('.nav-link');

// Toggle mobile menu
navToggle.addEventListener('click', () => {
    navMenu.classList.toggle('active');
});

// Close mobile menu when clicking a link
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
    });
});

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
    if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
        navMenu.classList.remove('active');
    }
});

// Smooth Scrolling for Navigation Links
navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href');
        const targetSection = document.querySelector(targetId);
        
        if (targetSection) {
            const offsetTop = targetSection.offsetTop - 80; // Account for fixed navbar
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

// Highlight Active Navigation Link on Scroll
const sections = document.querySelectorAll('section[id]');

function highlightNavLink() {
    const scrollY = window.pageYOffset;
    
    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100;
        const sectionId = section.getAttribute('id');
        const correspondingLink = document.querySelector(`.nav-link[href="#${sectionId}"]`);
        
        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            navLinks.forEach(link => link.classList.remove('active'));
            if (correspondingLink) {
                correspondingLink.classList.add('active');
            }
        }
    });
}

window.addEventListener('scroll', highlightNavLink);

// Contact Form Handling
const contactForm = document.getElementById('contactForm');

contactForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    // Get form values
    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        service: document.getElementById('service').value,
        message: document.getElementById('message').value
    };
    
    // Validate form
    if (!validateForm(formData)) {
        return;
    }
    
    // Show success message (in production, this would send to a backend)
    showSuccessMessage();
    
    // Reset form
    contactForm.reset();
});

// Form Validation
function validateForm(data) {
    // Name validation
    if (data.name.trim().length < 2) {
        showError('Please enter a valid name');
        return false;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        showError('Please enter a valid email address');
        return false;
    }
    
    // Phone validation (basic)
    const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
    if (!phoneRegex.test(data.phone)) {
        showError('Please enter a valid phone number');
        return false;
    }
    
    // Service validation
    if (!data.service) {
        showError('Please select a service');
        return false;
    }
    
    // Message validation
    if (data.message.trim().length < 10) {
        showError('Please enter a message with at least 10 characters');
        return false;
    }
    
    return true;
}

// Show Error Message
function showError(message) {
    // Create error element if it doesn't exist
    let errorElement = document.getElementById('formError');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = 'formError';
        errorElement.style.cssText = `
            background: linear-gradient(135deg, #4169E1, #5B8BF5);
            color: #FFFFFF;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #FFD700;
            box-shadow: 0 4px 16px rgba(65, 105, 225, 0.2);
        `;
        contactForm.insertBefore(errorElement, contactForm.firstChild);
    }
    
    errorElement.textContent = message;
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        errorElement.remove();
    }, 5000);
}

// Show Success Message
function showSuccessMessage() {
    // Create success element
    const successElement = document.createElement('div');
    successElement.id = 'formSuccess';
    successElement.style.cssText = `
        background: linear-gradient(135deg, #FFD700, #FFE44D);
        color: #2C4BAD;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-weight: 600;
        text-align: center;
        box-shadow: 0 4px 16px rgba(255, 215, 0, 0.3);
    `;
    successElement.textContent = '✓ Thank you! Your message has been received. We will contact you soon.';
    
    contactForm.insertBefore(successElement, contactForm.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        successElement.remove();
    }, 5000);
}

// Add scroll animations for sections
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all sections
sections.forEach(section => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(section);
});

// Add parallax effect to hero section
const hero = document.querySelector('.hero');

window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = scrolled * 0.5;
    
    if (hero && scrolled < hero.offsetHeight) {
        hero.style.transform = `translateY(${parallax}px)`;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Trigger highlight for initial position
    highlightNavLink();
    
    // Add animation class to hero content
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        setTimeout(() => {
            heroContent.style.opacity = '1';
            heroContent.style.transform = 'translateY(0)';
        }, 100);
    }
});
