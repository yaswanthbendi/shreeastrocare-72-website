// Mobile Menu Toggle
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');

if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking on a link
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
        });
    });
}

// Navbar Scroll Effect
const navbar = document.getElementById('navbar');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        navbar.style.boxShadow = '0 4px 20px rgba(30, 58, 138, 0.1)';
    } else {
        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
    }
    
    lastScroll = currentScroll;
});

// Smooth Scroll for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const offsetTop = target.offsetTop - 80;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

// Testimonial Slider
let currentTestimonial = 0;
const testimonialTrack = document.getElementById('testimonial-track');
const testimonialDots = document.querySelectorAll('.testimonial-dot');
const totalTestimonials = testimonialDots.length;

function showTestimonial(index) {
    currentTestimonial = index;
    if (testimonialTrack) {
        testimonialTrack.style.transform = `translateX(-${index * 100}%)`;
    }
    
    testimonialDots.forEach((dot, i) => {
        if (i === index) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

testimonialDots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        showTestimonial(index);
    });
});

// Auto-play testimonials
let testimonialInterval = setInterval(() => {
    currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
    showTestimonial(currentTestimonial);
}, 5000);

// Pause auto-play on hover
const testimonialSlider = document.querySelector('.testimonial-slider');
if (testimonialSlider) {
    testimonialSlider.addEventListener('mouseenter', () => {
        clearInterval(testimonialInterval);
    });

    testimonialSlider.addEventListener('mouseleave', () => {
        testimonialInterval = setInterval(() => {
            currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
            showTestimonial(currentTestimonial);
        }, 5000);
    });
}

// Scroll Animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, observerOptions);

// Observe service cards
document.querySelectorAll('.service-card').forEach((card, index) => {
    card.classList.add('scroll-animate');
    card.style.transitionDelay = `${index * 0.1}s`;
    observer.observe(card);
});

// Observe stat cards
document.querySelectorAll('.stat-card').forEach((card, index) => {
    card.classList.add('scroll-animate');
    card.style.transitionDelay = `${index * 0.1}s`;
    observer.observe(card);
});

// Observe gallery cards
document.querySelectorAll('.gallery-card').forEach((card, index) => {
    card.classList.add('scroll-animate');
    card.style.transitionDelay = `${index * 0.1}s`;
    observer.observe(card);
});

// Observe contact cards
document.querySelectorAll('.contact-card').forEach((card, index) => {
    card.classList.add('scroll-animate');
    card.style.transitionDelay = `${index * 0.1}s`;
    observer.observe(card);
});

// Parallax Effect
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.parallax');
    
    parallaxElements.forEach(element => {
        const speed = element.dataset.speed || 0.5;
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// Form Submission
const bookingForm = document.getElementById('booking-form');
if (bookingForm) {
    bookingForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Create success message
        const formData = new FormData(bookingForm);
        const successMessage = document.createElement('div');
        successMessage.className = 'fixed top-20 right-6 bg-white border-2 border-gold rounded-lg shadow-2xl p-6 z-50 animate-fade-in-up';
        successMessage.innerHTML = `
            <div class="flex items-center">
                <div class="text-4xl mr-4">✨</div>
                <div>
                    <h3 class="text-xl font-display font-bold text-royal-blue mb-1">Booking Received!</h3>
                    <p class="text-gray-600">We'll contact you shortly to confirm your consultation.</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(successMessage);
        
        // Reset form
        bookingForm.reset();
        
        // Remove message after 5 seconds
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(successMessage);
            }, 300);
        }, 5000);
    });
}

// Cosmic Background Animation
function createStarField() {
    const cosmicBg = document.querySelector('.cosmic-background');
    if (!cosmicBg) return;
    
    for (let i = 0; i < 50; i++) {
        const star = document.createElement('div');
        star.className = 'absolute rounded-full';
        star.style.width = Math.random() * 3 + 'px';
        star.style.height = star.style.width;
        star.style.left = Math.random() * 100 + '%';
        star.style.top = Math.random() * 100 + '%';
        star.style.background = Math.random() > 0.5 ? 'var(--color-gold)' : 'var(--color-royal-blue)';
        star.style.opacity = Math.random() * 0.5 + 0.2;
        star.style.animation = `twinkle ${Math.random() * 3 + 2}s ease-in-out infinite`;
        star.style.animationDelay = Math.random() * 2 + 's';
        cosmicBg.appendChild(star);
    }
}

// Initialize on load
window.addEventListener('load', () => {
    createStarField();
    
    // Add entrance animations
    const heroSection = document.querySelector('#home');
    if (heroSection) {
        heroSection.style.opacity = '0';
        setTimeout(() => {
            heroSection.style.transition = 'opacity 1s ease';
            heroSection.style.opacity = '1';
        }, 100);
    }
});

// Booking Button Click Handlers
document.querySelectorAll('.btn-primary').forEach(btn => {
    btn.addEventListener('click', (e) => {
        if (!e.target.closest('form')) {
            e.preventDefault();
            const bookingSection = document.getElementById('booking');
            if (bookingSection) {
                const offsetTop = bookingSection.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }
    });
});

// Service Card Learn More Buttons
document.querySelectorAll('.service-card .btn-secondary').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const serviceCard = e.target.closest('.service-card');
        const serviceName = serviceCard.querySelector('h3').textContent;
        
        // Show modal or more info (for now, scroll to booking)
        const bookingSection = document.getElementById('booking');
        if (bookingSection) {
            const offsetTop = bookingSection.offsetTop - 80;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
            
            // Pre-select the service in the form
            const serviceSelect = document.querySelector('#booking-form select');
            if (serviceSelect) {
                const options = Array.from(serviceSelect.options);
                const matchingOption = options.find(option => 
                    option.textContent.toLowerCase().includes(serviceName.toLowerCase().split(' ')[0])
                );
                if (matchingOption) {
                    serviceSelect.value = matchingOption.value;
                }
            }
        }
    });
});

// Number Counter Animation for Stats
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target + (element.textContent.includes('+') ? '+' : '') + (element.textContent.includes('%') ? '%' : '');
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current) + (element.textContent.includes('+') ? '+' : '') + (element.textContent.includes('%') ? '%' : '');
        }
    }, 16);
}

// Animate stats when they come into view
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.dataset.animated) {
            const statNumber = entry.target.querySelector('div:first-child');
            const text = statNumber.textContent;
            const number = parseInt(text.replace(/\D/g, ''));
            statNumber.textContent = '0';
            animateCounter(statNumber, number);
            entry.target.dataset.animated = 'true';
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-card').forEach(card => {
    statsObserver.observe(card);
});

// Add hover effects to gallery cards
document.querySelectorAll('.gallery-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Active nav link highlight on scroll
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');

window.addEventListener('scroll', () => {
    let current = '';
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 100) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});

// Easter egg: Cosmic particle effect on click
document.addEventListener('click', (e) => {
    if (Math.random() > 0.9) { // 10% chance
        createParticle(e.clientX, e.clientY);
    }
});

function createParticle(x, y) {
    const particle = document.createElement('div');
    particle.className = 'fixed pointer-events-none z-50';
    particle.style.left = x + 'px';
    particle.style.top = y + 'px';
    particle.style.fontSize = '20px';
    particle.innerHTML = '✦';
    particle.style.color = Math.random() > 0.5 ? 'var(--color-gold)' : 'var(--color-royal-blue)';
    particle.style.animation = 'particle-fade 1s ease-out forwards';
    
    document.body.appendChild(particle);
    
    setTimeout(() => {
        document.body.removeChild(particle);
    }, 1000);
}

// Add particle animation
const style = document.createElement('style');
style.textContent = `
    @keyframes particle-fade {
        0% {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }
        100% {
            opacity: 0;
            transform: translate(0, -50px) scale(0);
        }
    }
`;
document.head.appendChild(style);
