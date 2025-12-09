/**
 * PCZ Anchor Scroll - Generičko Rješenje
 * 
 * Omogućuje glatki scroll na anchor sekcije s offsetom za header.
 * 
 * ZNAČAJKE:
 * - Automatski smooth scroll za sve # linkove
 * - Prilagodljiv offset za fiksni header
 * - Podrška za prefers-reduced-motion
 * - URL hash update bez page jump-a
 * - Callback funkcionalnost za custom akcije
 * 
 * KORIŠTENJE:
 * 1. Uključi ovaj JS file
 * 2. Za custom offset: PCZAnchorScroll.setOffset(120);
 * 3. Za scroll na element: PCZAnchorScroll.scrollTo('#prijava');
 * 
 * @package PCZ_Redizajn
 * @since 1.0.0
 */

(function() {
    'use strict';

    // =============================================================================
    // CONFIGURATION
    // =============================================================================
    
    const CONFIG = {
        // Offset za fiksni header (u pikselima)
        headerOffset: 100,
        
        // Trajanje animacije (ms) - koristi se za fallback
        duration: 600,
        
        // Selektori koji aktiviraju scroll
        anchorSelectors: 'a[href^="#"]:not([href="#"])',
        
        // CSS klasa za target element tijekom scroll-a
        highlightClass: 'pcz-scroll-target',
        
        // Ukloni highlight nakon (ms)
        highlightDuration: 1500,
        
        // Debug mod
        debug: false
    };

    // =============================================================================
    // MAIN SCROLL HANDLER
    // =============================================================================

    /**
     * Scroll do elementa s offsetom
     * 
     * @param {string|Element} target - ID selektore (#id) ili DOM element
     * @param {Object} options - Opcije (offset, callback, updateHash)
     */
    function scrollToTarget(target, options = {}) {
        const opts = {
            offset: CONFIG.headerOffset,
            updateHash: true,
            highlight: true,
            callback: null,
            ...options
        };

        // Pronađi element
        let element;
        if (typeof target === 'string') {
            // Provjeri je li validan selektor
            try {
                element = document.querySelector(target);
            } catch (e) {
                if (CONFIG.debug) console.warn('[PCZ Scroll] Invalid selector:', target);
                return false;
            }
        } else {
            element = target;
        }

        if (!element) {
            if (CONFIG.debug) console.warn('[PCZ Scroll] Element not found:', target);
            return false;
        }

        // Izračunaj poziciju
        const elementRect = element.getBoundingClientRect();
        const absoluteTop = elementRect.top + window.pageYOffset;
        const targetPosition = absoluteTop - opts.offset;

        // Provjeri prefers-reduced-motion
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Scroll
        if (prefersReducedMotion) {
            window.scrollTo(0, targetPosition);
        } else {
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }

        // Update URL hash bez scroll jump-a
        if (opts.updateHash && typeof target === 'string' && target.startsWith('#')) {
            history.pushState(null, null, target);
        }

        // Highlight effect
        if (opts.highlight && CONFIG.highlightClass) {
            element.classList.add(CONFIG.highlightClass);
            setTimeout(() => {
                element.classList.remove(CONFIG.highlightClass);
            }, CONFIG.highlightDuration);
        }

        // Callback
        if (typeof opts.callback === 'function') {
            // Procijeni vrijeme scroll-a i pozovi callback nakon
            const scrollDistance = Math.abs(window.pageYOffset - targetPosition);
            const estimatedTime = prefersReducedMotion ? 0 : Math.min(scrollDistance / 2, CONFIG.duration);
            
            setTimeout(opts.callback, estimatedTime);
        }

        if (CONFIG.debug) {
            console.log('[PCZ Scroll] Scrolling to:', target, 'Position:', targetPosition);
        }

        return true;
    }

    // =============================================================================
    // EVENT HANDLERS
    // =============================================================================

    /**
     * Klik handler za anchor linkove
     */
    function handleAnchorClick(event) {
        const link = event.currentTarget;
        const href = link.getAttribute('href');

        // Preskočimo ako je samo "#" ili eksterni link
        if (!href || href === '#' || href.includes('://')) {
            return;
        }

        // Provjeri postoji li target element
        const targetId = href.startsWith('#') ? href : '#' + href;
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            event.preventDefault();
            scrollToTarget(targetId);
        }
    }

    /**
     * Inicijalizacija - dodaj event listenere
     */
    function initialize() {
        // Dodaj click listenere na sve anchor linkove
        const anchors = document.querySelectorAll(CONFIG.anchorSelectors);
        
        anchors.forEach(anchor => {
            // Provjeri je li već ima listener (data attribute)
            if (!anchor.dataset.pczScrollInit) {
                anchor.addEventListener('click', handleAnchorClick);
                anchor.dataset.pczScrollInit = 'true';
            }
        });

        // Handle initial hash u URL-u (za direktne linkove)
        if (window.location.hash) {
            // Mali delay da se stranica učita
            setTimeout(() => {
                scrollToTarget(window.location.hash);
            }, 100);
        }

        if (CONFIG.debug) {
            console.log('[PCZ Scroll] Initialized with', anchors.length, 'anchor links');
        }
    }

    // =============================================================================
    // PUBLIC API
    // =============================================================================

    const PCZAnchorScroll = {
        /**
         * Scroll do targeta
         * @param {string|Element} target - Selektor ili element
         * @param {Object} options - Opcije
         */
        scrollTo: scrollToTarget,

        /**
         * Postavi offset za header
         * @param {number} offset - Offset u pikselima
         */
        setOffset: function(offset) {
            CONFIG.headerOffset = parseInt(offset, 10) || 100;
            // Također ažuriraj CSS varijablu ako postoji
            document.documentElement.style.setProperty('--pcz-scroll-offset', CONFIG.headerOffset + 'px');
            return this;
        },

        /**
         * Dohvati trenutni offset
         */
        getOffset: function() {
            return CONFIG.headerOffset;
        },

        /**
         * Reinicijaliziraj (npr. nakon AJAX učitavanja)
         */
        refresh: initialize,

        /**
         * Uključi/isključi debug mod
         */
        debug: function(enabled) {
            CONFIG.debug = !!enabled;
            return this;
        },

        /**
         * Konfiguriraj postavke
         * @param {Object} options - Objekt s postavkama
         */
        configure: function(options) {
            Object.assign(CONFIG, options);
            return this;
        }
    };

    // =============================================================================
    // INITIALIZATION
    // =============================================================================

    // Čekaj DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }

    // Expose globalno
    window.PCZAnchorScroll = PCZAnchorScroll;

})();

