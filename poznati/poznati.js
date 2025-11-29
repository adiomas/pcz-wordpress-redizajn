/**
 * pcz "Poznati o PCZ-u" - JavaScript
 * 
 * Mobile slider functionality s touch/swipe podrškom
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    /**
     * pcz Poznati Slider Class
     */
    class pczPoznatiSlider {
        constructor(container) {
            this.container = container;
            this.track = container.querySelector('.pcz-poznati__slider-track');
            this.dots = container.querySelectorAll('.pcz-poznati__dot');
            this.prevBtn = container.querySelector('.pcz-poznati__arrow--prev');
            this.nextBtn = container.querySelector('.pcz-poznati__arrow--next');
            this.slides = container.querySelectorAll('.pcz-poznati__slide');
            
            this.currentIndex = 0;
            this.totalSlides = this.slides.length;
            this.touchStartX = 0;
            this.touchEndX = 0;
            this.isDragging = false;
            
            if (this.track && this.totalSlides > 0) {
                this.init();
            }
        }
        
        init() {
            // Dot navigation
            this.dots.forEach((dot, index) => {
                dot.addEventListener('click', () => this.goToSlide(index));
            });
            
            // Arrow navigation
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.prev());
            }
            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.next());
            }
            
            // Touch/Swipe support
            this.track.addEventListener('touchstart', (e) => this.onTouchStart(e), { passive: true });
            this.track.addEventListener('touchmove', (e) => this.onTouchMove(e), { passive: true });
            this.track.addEventListener('touchend', (e) => this.onTouchEnd(e));
            
            // Mouse drag support
            this.track.addEventListener('mousedown', (e) => this.onMouseDown(e));
            this.track.addEventListener('mousemove', (e) => this.onMouseMove(e));
            this.track.addEventListener('mouseup', (e) => this.onMouseUp(e));
            this.track.addEventListener('mouseleave', (e) => this.onMouseUp(e));
            
            // Keyboard navigation (when focused)
            this.container.addEventListener('keydown', (e) => this.onKeyDown(e));
            
            this.updateButtons();
        }
        
        goToSlide(index) {
            if (index < 0) index = 0;
            if (index >= this.totalSlides) index = this.totalSlides - 1;
            
            this.currentIndex = index;
            this.track.style.transform = `translateX(-${index * 100}%)`;
            
            // Update dots
            this.dots.forEach((dot, i) => {
                dot.classList.toggle('is-active', i === index);
            });
            
            this.updateButtons();
        }
        
        next() {
            if (this.currentIndex < this.totalSlides - 1) {
                this.goToSlide(this.currentIndex + 1);
            }
        }
        
        prev() {
            if (this.currentIndex > 0) {
                this.goToSlide(this.currentIndex - 1);
            }
        }
        
        updateButtons() {
            if (this.prevBtn) {
                this.prevBtn.disabled = this.currentIndex === 0;
            }
            if (this.nextBtn) {
                this.nextBtn.disabled = this.currentIndex >= this.totalSlides - 1;
            }
        }
        
        // Touch Events
        onTouchStart(e) {
            this.touchStartX = e.touches[0].clientX;
        }
        
        onTouchMove(e) {
            this.touchEndX = e.touches[0].clientX;
        }
        
        onTouchEnd() {
            this.handleSwipe();
        }
        
        // Mouse Events
        onMouseDown(e) {
            this.isDragging = true;
            this.touchStartX = e.clientX;
            this.track.style.cursor = 'grabbing';
        }
        
        onMouseMove(e) {
            if (!this.isDragging) return;
            this.touchEndX = e.clientX;
        }
        
        onMouseUp() {
            if (this.isDragging) {
                this.handleSwipe();
            }
            this.isDragging = false;
            this.track.style.cursor = 'grab';
        }
        
        // Keyboard navigation
        onKeyDown(e) {
            if (e.key === 'ArrowLeft') {
                this.prev();
            } else if (e.key === 'ArrowRight') {
                this.next();
            }
        }
        
        handleSwipe() {
            const diff = this.touchStartX - this.touchEndX;
            const threshold = 50;
            
            if (diff > threshold) {
                this.next(); // Swipe left
            } else if (diff < -threshold) {
                this.prev(); // Swipe right
            }
            
            // Reset
            this.touchStartX = 0;
            this.touchEndX = 0;
        }
    }
    
    /**
     * Initialize all sliders on the page
     */
    function initPoznatiSliders() {
        const containers = document.querySelectorAll('.pcz-poznati');
        containers.forEach(container => {
            new pczPoznatiSlider(container);
        });
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPoznatiSliders);
    } else {
        initPoznatiSliders();
    }
    
    // Re-initialize on window resize (in case of dynamic content)
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(initPoznatiSliders, 250);
    });
    
})();

