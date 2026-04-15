/**
 * Home Manager Component
 * Handles the animated mockup grids and the 3D-perspective testimonial carousel
 * for the MiniJobz landing page.
 */
export class HomeManager {
    constructor(options = {}) {
        this.options = {
            testimonials: [],
            ...options
        };

        this.currentIndex = 0;
        this.isAnimating = false;
        this.autoScrollTimer = null;

        this.init();
    }

    init() {
        this.initMockupGrids();
        this.initTestimonials();
    }

    initMockupGrids() {
        this.duplicateCards('task-cards-left', 'col-left');
        this.duplicateCards('task-cards-right', 'col-right');
    }

    duplicateCards(templateId, containerId) {
        const template = document.getElementById(templateId);
        const container = document.getElementById(containerId);
        if (template && container) {
            for (let i = 0; i < 3; i++) {
                container.appendChild(template.content.cloneNode(true));
            }
        }
    }

    initTestimonials() {
        if (!this.options.testimonials.length) return;

        this.quotes = document.getElementById('t-quote');
        this.name = document.getElementById('t-name');
        this.role = document.getElementById('t-role');
        this.img = document.getElementById('t-img');
        this.nextBtn = document.getElementById('nextBtn');
        this.prevBtn = document.getElementById('prevBtn');

        if (this.nextBtn && this.prevBtn) {
            this.nextBtn.addEventListener('click', () => this.nextTestimonial());
            this.prevBtn.addEventListener('click', () => this.prevTestimonial());
            this.resetAutoScroll();
        }
    }

    updateTestimonial(index, direction = 'next') {
        if (this.isAnimating) return;
        this.isAnimating = true;

        const container = document.querySelector('.perspective-container');
        const currentContent = document.getElementById('testimonial-content');
        if (!container || !currentContent) return;

        // Clone current content for exit animation
        const clone = currentContent.cloneNode(true);
        clone.removeAttribute('id');
        clone.querySelectorAll('[id]').forEach(el => el.removeAttribute('id'));
        clone.style.position = 'absolute';
        clone.style.top = '0';
        clone.style.left = '0';
        clone.style.width = '100%';
        clone.style.height = '100%';
        clone.style.zIndex = '10';
        container.appendChild(clone);

        // Update real element with NEW data
        const data = this.options.testimonials[index];
        if (this.quotes) this.quotes.textContent = `"${data.quote}"`;
        if (this.name) this.name.textContent = data.name;
        if (this.role) this.role.textContent = data.role;
        if (this.img) this.img.src = data.img;

        // Prepare positions
        currentContent.style.transition = 'none';
        clone.style.transition = 'none';

        if (direction === 'next') {
            currentContent.style.transform = 'translateX(100%)';
            currentContent.style.opacity = '0';
        } else {
            currentContent.style.transform = 'translateX(-100%)';
            currentContent.style.opacity = '0';
        }

        void currentContent.offsetWidth; // Force reflow

        const duration = 0.6;
        const ease = 'cubic-bezier(0.25, 1, 0.5, 1)';
        currentContent.style.transition = `transform ${duration}s ${ease}, opacity ${duration}s ${ease}`;
        clone.style.transition = `transform ${duration}s ${ease}, opacity ${duration}s ${ease}`;

        requestAnimationFrame(() => {
            if (direction === 'next') {
                clone.style.transform = 'translateX(-100%)';
                clone.style.opacity = '0';
            } else {
                clone.style.transform = 'translateX(100%)';
                clone.style.opacity = '0';
            }
            currentContent.style.transform = 'translateX(0)';
            currentContent.style.opacity = '1';
        });

        setTimeout(() => {
            if (clone.parentNode) clone.parentNode.removeChild(clone);
            this.isAnimating = false;
        }, duration * 1000);
    }

    nextTestimonial() {
        this.currentIndex = (this.currentIndex + 1) % this.options.testimonials.length;
        this.updateTestimonial(this.currentIndex, 'next');
        this.resetAutoScroll();
    }

    prevTestimonial() {
        this.currentIndex = (this.currentIndex - 1 + this.options.testimonials.length) % this.options.testimonials.length;
        this.updateTestimonial(this.currentIndex, 'prev');
        this.resetAutoScroll();
    }

    resetAutoScroll() {
        clearInterval(this.autoScrollTimer);
        this.autoScrollTimer = setInterval(() => this.nextTestimonial(), 6000);
    }
}
