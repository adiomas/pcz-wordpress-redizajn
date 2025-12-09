/**
 * pcz Custom Header - JavaScript
 * 
 * Kontrolira:
 * - Mega menu dropdown hover/click
 * - Mobile menu toggle (SAMO class toggling - CSS kontrolira display)
 * - Mobile dropdown toggle
 * - Keyboard accessibility
 * 
 * REDIZAJN 3.0 - Clean Class-Only Approach
 * JS SAMO toggle-a klase, CSS kontrolira SVU vidljivost
 * 
 * @package pcz_Redizajn
 * @since 3.0.0
 */

(function() {
    'use strict';
    
    // ============================================
    // DEBUG MODE - postavi na false za produkciju
    // ============================================
    var DEBUG = true;
    
    function log(msg, data) {
        if (DEBUG) {
            console.log('%c[pcz Header v3] ' + msg, 'color: #C71585; font-weight: bold;', data || '');
        }
    }
    
    function warn(msg, data) {
        console.warn('[pcz Header] ' + msg, data || '');
    }
    
    function error(msg, data) {
        console.error('[pcz Header] ' + msg, data || '');
    }

    log('Script loaded, readyState:', document.readyState);

    // Čekaj DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Backup na window.load
    window.addEventListener('load', function() {
        var toggle = document.getElementById('pcz-nav-toggle');
        if (toggle && !toggle.hasAttribute('data-pcz-init')) {
            log('Backup init on window.load');
            init();
        }
    });

    function init() {
        log('=== INIT START ===');
        try {
            initMobileMenu();
            initMegaDropdown();
            initMobileDropdownToggle();
            initStickyHeader();
            log('=== INIT COMPLETE ===');
        } catch (e) {
            error('Init error:', e);
        }
    }

    /**
     * Mobile Menu Toggle - CLEAN VERSION
     * JS SAMO toggle-a klase - NIKAKVE inline style manipulacije!
     * CSS s !important kontrolira display
     */
    function initMobileMenu() {
        log('initMobileMenu() called');
        
        var toggle = document.getElementById('pcz-nav-toggle');
        var navList = document.querySelector('.pcz-nav__list');
        
        if (!toggle || !navList) {
            warn('Toggle or navList not found!');
            return;
        }
        
        // Spriječi dvostruku inicijalizaciju
        if (toggle.hasAttribute('data-pcz-init')) {
            log('Already initialized, skipping');
            return;
        }
        
        toggle.setAttribute('data-pcz-init', 'true');
        log('Toggle found, attaching listener');

        // ČISTI CLICK HANDLER - samo toggle klase!
        function handleToggleClick(e) {
            log('>>> CLICK! <<<');
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Toggle state - SAMO KLASE, BEZ INLINE STILOVA!
            var isCurrentlyOpen = navList.classList.contains('is-active');
            
            if (isCurrentlyOpen) {
                // ZATVORI
                log('Closing menu...');
                toggle.classList.remove('is-active');
                navList.classList.remove('is-active');
                document.body.classList.remove('pcz-menu-open');
                toggle.setAttribute('aria-expanded', 'false');
            } else {
                // OTVORI
                log('Opening menu...');
                toggle.classList.add('is-active');
                navList.classList.add('is-active');
                document.body.classList.add('pcz-menu-open');
                toggle.setAttribute('aria-expanded', 'true');
            }
            
            log('Menu is now:', !isCurrentlyOpen ? 'OPEN' : 'CLOSED');
            log('NavList classes:', navList.className);
        }
        
        // Koristi capture phase za prioritet
        toggle.addEventListener('click', handleToggleClick, true);
        
        // Također dodaj touchend za iOS
        toggle.addEventListener('touchend', function(e) {
            // Spriječi ghost click
            e.preventDefault();
            handleToggleClick(e);
        }, { passive: false });
        
        log('Click + touch handlers attached');

        // Close on link click (mobile) - ali ne za dropdown iteme
        navList.querySelectorAll('.pcz-nav__link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Ne zatvaraj za iteme s dropdown-om
                var parentItem = this.closest('.pcz-nav__item.has-dropdown') || 
                                 this.closest('.pcz-nav__item[class*="--ponuda"]');
                if (parentItem) {
                    return;
                }
                
                // Zatvaraj menu samo na mobileu
                if (window.innerWidth <= 768) {
                    toggle.classList.remove('is-active');
                    navList.classList.remove('is-active');
                    document.body.classList.remove('pcz-menu-open');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        });

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navList.classList.contains('is-active')) {
                toggle.classList.remove('is-active');
                navList.classList.remove('is-active');
                document.body.classList.remove('pcz-menu-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });
        
        // Close on click outside (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && 
                navList.classList.contains('is-active') &&
                !navList.contains(e.target) && 
                !toggle.contains(e.target)) {
                toggle.classList.remove('is-active');
                navList.classList.remove('is-active');
                document.body.classList.remove('pcz-menu-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /**
     * Mobile Dropdown Toggle - Generička funkcija za sve dropdown iteme
     */
    function initMobileDropdownToggle() {
        // Pronađi sve nav iteme koji imaju mega dropdown
        const dropdownItems = document.querySelectorAll(
            '.pcz-nav__item.has-dropdown, ' +
            '.pcz-nav__item[class*="--ponuda"], ' +
            '.pcz-nav__item[aria-haspopup="true"]'
        );
        
        if (!dropdownItems || dropdownItems.length === 0) return;
        
        dropdownItems.forEach(function(item) {
            const link = item.querySelector('.pcz-nav__link');
            if (!link) return;
            
            // ZAŠTITA OD DVOSTRUKE INICIJALIZACIJE
            // Ovo je kritično jer se script može učitati više puta
            if (link.hasAttribute('data-pcz-dropdown-init')) {
                log('Dropdown already initialized for:', link.textContent.trim());
                return;
            }
            link.setAttribute('data-pcz-dropdown-init', 'true');
            
            // Dodaj click event za mobile
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle mega menu
                    item.classList.toggle('is-open');
                    
                    // Update ARIA
                    const isOpen = item.classList.contains('is-open');
                    this.setAttribute('aria-expanded', isOpen);
                    
                    log('Mobile dropdown toggled:', isOpen ? 'OPEN' : 'CLOSED');
                }
            });
        });
    }

    /**
     * Mega Menu Dropdown - Desktop hover/focus
     */
    function initMegaDropdown() {
        // Pronađi sve nav iteme koji imaju dropdown
        const dropdownItems = document.querySelectorAll(
            '.pcz-nav__item.has-dropdown, ' +
            '.pcz-nav__item[class*="--ponuda"]'
        );
        
        if (!dropdownItems || dropdownItems.length === 0) return;
        
        // Inicijaliziraj svaki dropdown
        dropdownItems.forEach(function(item) {
            initSingleDropdown(item);
        });
    }
    
    /**
     * Inicijaliziraj pojedinačni dropdown (desktop)
     */
    function initSingleDropdown(dropdownItem) {
        const dropdownId = dropdownItem.getAttribute('data-dropdown-id');
        const dropdown = dropdownId ? document.getElementById(dropdownId) : 
                         dropdownItem.querySelector('.pcz-mega-dropdown');
        
        if (!dropdown) return;
        
        const navLink = dropdownItem.querySelector('.pcz-nav__link');
        if (!navLink) return;
        
        // ZAŠTITA OD DVOSTRUKE INICIJALIZACIJE (desktop hover)
        if (dropdownItem.hasAttribute('data-pcz-desktop-dropdown-init')) {
            return;
        }
        dropdownItem.setAttribute('data-pcz-desktop-dropdown-init', 'true');
        
        let hoverTimeout;
        let closeTimeout;
        const HOVER_DELAY = 50;
        const CLOSE_DELAY = 250;

        // Desktop Hover Events
        function showDropdown() {
            if (window.innerWidth <= 768) return;
            
            clearTimeout(closeTimeout);
            hoverTimeout = setTimeout(function() {
                dropdown.classList.add('is-active');
                navLink.setAttribute('aria-expanded', 'true');
            }, HOVER_DELAY);
        }

        function hideDropdown() {
            if (window.innerWidth <= 768) return;
            
            clearTimeout(hoverTimeout);
            closeTimeout = setTimeout(function() {
                dropdown.classList.remove('is-active');
                navLink.setAttribute('aria-expanded', 'false');
            }, CLOSE_DELAY);
        }

        // Mouse Events
        dropdownItem.addEventListener('mouseenter', showDropdown);
        dropdownItem.addEventListener('mouseleave', hideDropdown);

        // Keep open when hovering dropdown
        dropdown.addEventListener('mouseenter', function() {
            clearTimeout(closeTimeout);
        });
        dropdown.addEventListener('mouseleave', hideDropdown);

        // Keyboard Navigation
        navLink.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                e.preventDefault();
                
                if (dropdown.classList.contains('is-active')) {
                    dropdown.classList.remove('is-active');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    dropdown.classList.add('is-active');
                    this.setAttribute('aria-expanded', 'true');
                    
                    // Focus first link
                    setTimeout(function() {
                        const firstLink = dropdown.querySelector('a');
                        if (firstLink) firstLink.focus();
                    }, 100);
                }
            }
            
            if (e.key === 'Escape') {
                dropdown.classList.remove('is-active');
                this.setAttribute('aria-expanded', 'false');
                this.focus();
            }
        });

        // Close on Escape from anywhere in dropdown
        dropdown.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.classList.remove('is-active');
                navLink.setAttribute('aria-expanded', 'false');
                navLink.focus();
            }
        });

        // Focus trap in dropdown
        const focusableElements = dropdown.querySelectorAll('a[href], button');
        if (focusableElements.length > 0) {
            const firstFocusable = focusableElements[0];
            const lastFocusable = focusableElements[focusableElements.length - 1];

            dropdown.addEventListener('keydown', function(e) {
                if (e.key !== 'Tab') return;

                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        e.preventDefault();
                        lastFocusable.focus();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        e.preventDefault();
                        firstFocusable.focus();
                    }
                }
            });
        }

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownItem.contains(e.target)) {
                dropdown.classList.remove('is-active');
                navLink.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /**
     * Sticky Header (opcionalno)
     */
    function initStickyHeader() {
        const header = document.getElementById('pcz-header');
        if (!header) return;

        let lastScroll = 0;
        const scrollThreshold = 100;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > scrollThreshold) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
            
            lastScroll = currentScroll;
        }, { passive: true });
    }

})();
