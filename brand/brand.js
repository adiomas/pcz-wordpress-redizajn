/**
 * pcz Multi-Brand System - JavaScript
 * 
 * Handles:
 * - Smooth brand switching without page reload (AJAX)
 * - CSS variable updates
 * - Brand switcher UI interactions
 * - localStorage/cookie persistence
 * 
 * @package pcz_Redizajn
 * @since 4.0.0
 */

(function() {
    'use strict';
    
    // =============================================================================
    // CONFIGURATION
    // =============================================================================
    
    const CONFIG = {
        // Selectors
        selectors: {
            switcher: '.pcz-brand-switcher',
            tab: '.pcz-brand-switcher__tab',
            indicator: '.pcz-brand-switcher__indicator',
            toggle: '.pcz-brand-toggle',
            brandData: '#pcz-brand-data',
            brandCss: '#pcz-brand-css',
        },
        
        // Animation settings
        animation: {
            duration: 300,
            easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
        },
        
        // Storage keys
        storage: {
            brand: 'pcz_brand',
            timestamp: 'pcz_brand_timestamp',
        },
        
        // AJAX endpoint (WordPress)
        ajaxUrl: typeof pczAjax !== 'undefined' ? pczAjax.ajaxUrl : '/wp-admin/admin-ajax.php',
        nonce: typeof pczAjax !== 'undefined' ? pczAjax.nonce : '',
    };
    
    // =============================================================================
    // STATE
    // =============================================================================
    
    let state = {
        currentBrand: null,
        brands: {},
        isTransitioning: false,
    };
    
    // =============================================================================
    // INITIALIZATION
    // =============================================================================
    
    function init() {
        // Load brand data
        loadBrandData();
        
        // Get current brand from body attribute
        state.currentBrand = document.body.dataset.brand || 'plesna-skola';
        
        // Initialize UI
        initSwitcher();
        initToggle();
        updateIndicator();
        
        // Handle browser back/forward
        window.addEventListener('popstate', handlePopState);
        
        // Restore scroll position if coming from reload fallback
        restoreScrollFromUrl();
        
        console.log('[pcz Brand] Initialized:', state.currentBrand);
    }
    
    /**
     * Load brand configuration from JSON data
     */
    function loadBrandData() {
        const dataElement = document.querySelector(CONFIG.selectors.brandData);
        
        if (dataElement) {
            try {
                const data = JSON.parse(dataElement.textContent);
                state.currentBrand = data.current;
                state.brands = data.brands;
            } catch (e) {
                console.error('[pcz Brand] Failed to parse brand data:', e);
            }
        }
        
        // Fallback: Build from defaults
        if (Object.keys(state.brands).length === 0) {
            state.brands = {
                'plesna-skola': {
                    id: 'plesna-skola',
                    name: 'Plesna Škola',
                    shortName: 'PCZ',
                    primaryColor: '#C71585',
                    hoverColor: '#a01269',
                },
                'sportski-klub': {
                    id: 'sportski-klub',
                    name: 'Sportski Klub',
                    shortName: 'SPK',
                    primaryColor: '#FF6B00',
                    hoverColor: '#CC5500',
                },
            };
        }
    }
    
    // =============================================================================
    // SWITCHER INTERACTIONS
    // =============================================================================
    
    function initSwitcher() {
        const switchers = document.querySelectorAll(CONFIG.selectors.switcher);
        
        switchers.forEach(switcher => {
            const tabs = switcher.querySelectorAll(CONFIG.selectors.tab);
            
            tabs.forEach(tab => {
                tab.addEventListener('click', handleTabClick);
            });
        });
    }
    
    function initToggle() {
        const toggles = document.querySelectorAll(CONFIG.selectors.toggle);
        
        toggles.forEach(toggle => {
            toggle.addEventListener('click', handleToggleClick);
        });
    }
    
    /**
     * Handle tab click - smooth brand switch
     */
    function handleTabClick(event) {
        const tab = event.currentTarget;
        const newBrand = tab.dataset.brand;
        
        // If same brand, do nothing
        if (newBrand === state.currentBrand) {
            event.preventDefault();
            return;
        }
        
        // Check if we should do smooth transition
        const useSmoothTransition = shouldUseSmoothTransition();
        
        if (useSmoothTransition) {
            event.preventDefault();
            switchBrand(newBrand, tab.href);
        }
        // Otherwise, let the link navigate normally
    }
    
    function handleToggleClick(event) {
        const toggle = event.currentTarget;
        const newBrand = toggle.dataset.brand;
        
        if (newBrand && shouldUseSmoothTransition()) {
            event.preventDefault();
            switchBrand(newBrand, toggle.href);
        }
    }
    
    /**
     * Check if smooth transition should be used
     * (Disable for accessibility, slow connections, etc.)
     * 
     * OPTIMIZIRANO: Koristi smooth CSS transition za brže prebacivanje.
     * CSS varijable se ažuriraju odmah, a page reload samo ako je nužno.
     */
    function shouldUseSmoothTransition() {
        // Check for reduced motion preference
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return false;
        }
        
        // Check for slow connection
        if (navigator.connection && navigator.connection.saveData) {
            return false;
        }
        
        // OPTIMIZACIJA: Uvijek koristi smooth transition
        // CSS varijable će se ažurirati odmah, bez reloada
        // Elementi koji koriste CSS varijable automatski mijenjaju boju
        return true;
    }
    
    /**
     * Provjeri može li se sadržaj prebaciti samo s CSS-om
     * Ako ne, potreban je page reload za dohvat novog sadržaja
     */
    function canSwitchWithCssOnly() {
        // Elementi koji se mogu prebaciti samo s CSS-om:
        // - Sekcije s klasom pcz-poznati (imaju CSS visibility)
        // - Elementi koji koriste CSS varijable za boje
        
        // Elementi koji ZAHTIJEVAJU reload:
        // - Menu sadržaj (različite stavke za svaki brand)
        // - Dinamički sadržaj iz ACF-a specifičan za brand
        
        // Za sada, ako je samo homepage, može CSS-only
        const isHomepage = window.location.pathname === '/' || 
                          window.location.pathname === '/index.php' ||
                          document.body.classList.contains('home');
        
        // Provjeri ima li brand-specific menu sadržaja
        const hasBrandMenu = document.querySelector('[data-brand-menu]');
        
        return isHomepage && !hasBrandMenu;
    }
    
    // =============================================================================
    // BRAND SWITCHING
    // =============================================================================
    
    /**
     * Switch to a new brand - OPTIMIZED for speed
     * 
     * OPTIMIZACIJA:
     * 1. CSS varijable se ažuriraju ODMAH (instant color change)
     * 2. Overlay se prikazuje kratko (samo 150ms)
     * 3. Cookie se postavlja za persistence
     * 4. Page reload SAMO ako je potreban za sadržaj koji ovisi o brandu
     */
    async function switchBrand(newBrand, url) {
        if (state.isTransitioning) return;
        if (!state.brands[newBrand]) return;
        
        state.isTransitioning = true;
        const oldBrand = state.currentBrand;
        
        // Sačuvaj scroll poziciju PRIJE tranzicije
        const scrollPosition = window.scrollY;
        
        // OPTIMIZACIJA: Odmah ažuriraj CSS - korisnik vidi promjenu INSTANT
        updateCssVariables(newBrand);
        
        // Update body attribute - CSS selektori reagiraju odmah
        document.body.dataset.brand = newBrand;
        document.body.classList.remove(`pcz-brand-${oldBrand}`);
        document.body.classList.add(`pcz-brand-${newBrand}`);
        
        // Save to storage ODMAH (prije bilo čega drugog)
        saveBrandToStorage(newBrand);
        
        // Update switcher UI
        updateSwitcherUI(newBrand);
        
        // Update state
        state.currentBrand = newBrand;
        
        // Trigger custom event (za ostale komponente koje slušaju)
        dispatchBrandChangeEvent(newBrand, oldBrand);
        
        // Provjeri treba li page reload (za sadržaj koji ovisi o brandu)
        const needsContentReload = checkIfContentReloadNeeded();
        
        if (needsContentReload && url) {
            // Kratki overlay za smooth vizualni prijelaz
            showTransitionOverlay(newBrand);
            await delay(100); // Kratka pauza da korisnik vidi promjenu boje
            
            // Dodaj scroll parameter za restore nakon reload-a
            const separator = url.includes('?') ? '&' : '?';
            window.location.href = `${url}${separator}_scrollY=${scrollPosition}`;
            return;
        }
        
        // SMOOTH TRANSITION - bez reload-a
        // Update URL without reload
        if (url) {
            history.pushState({ brand: newBrand, scrollY: scrollPosition }, '', url);
        }
        
        // Kratka animacija za vizualni feedback
        await delay(50);
        
        state.isTransitioning = false;
    }
    
    /**
     * Provjeri trebaju li elementi page reload za novi sadržaj
     * 
     * NAPOMENA: Hero, Poznati, Footer koriste CSS varijable - ne trebaju reload.
     * Menu može imati brand-specific sadržaj - treba reload.
     */
    function checkIfContentReloadNeeded() {
        // Elementi koji ZAHTIJEVAJU reload (imaju brand-specific sadržaj)
        const needsReload = document.querySelectorAll('[data-brand-content-needs-reload]');
        if (needsReload.length > 0) {
            return true;
        }
        
        // Mega menu - provjeri ima li brand-specific stavke
        const megaMenu = document.querySelector('.pcz-mega-dropdown');
        if (megaMenu && megaMenu.hasAttribute('data-brand-menu')) {
            return true;
        }
        
        // Default: ne treba reload, CSS je dovoljan
        return false;
    }
    
    /**
     * Restore scroll position from URL parameter (after page reload fallback)
     */
    function restoreScrollFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const scrollY = urlParams.get('_scrollY');
        
        if (scrollY && !isNaN(parseInt(scrollY))) {
            // Kratka odgoda da se stranica renderira
            setTimeout(() => {
                window.scrollTo({
                    top: parseInt(scrollY),
                    behavior: 'instant'
                });
                
                // Očisti URL parameter
                urlParams.delete('_scrollY');
                const newUrl = urlParams.toString() 
                    ? `${window.location.pathname}?${urlParams.toString()}`
                    : window.location.pathname;
                history.replaceState(null, '', newUrl);
            }, 100);
        }
    }
    
    /**
     * Update CSS custom properties for new brand
     */
    function updateCssVariables(brandId) {
        const brand = state.brands[brandId];
        if (!brand) return;
        
        const root = document.documentElement;
        
        root.style.setProperty('--pcz-primary', brand.primaryColor);
        root.style.setProperty('--pcz-primary-hover', brand.hoverColor);
        
        // Update gradient
        const secondaryColor = brandId === 'sportski-klub' ? '#FFA500' : '#ff6b9d';
        root.style.setProperty('--pcz-secondary', secondaryColor);
        root.style.setProperty('--pcz-gradient', 
            `linear-gradient(135deg, ${brand.primaryColor} 0%, ${secondaryColor} 100%)`
        );
        
        // Update shadow
        const shadowColor = brandId === 'sportski-klub' 
            ? 'rgba(255, 107, 0, 0.3)' 
            : 'rgba(199, 21, 133, 0.3)';
        root.style.setProperty('--pcz-shadow-brand', `0 4px 15px ${shadowColor}`);
    }
    
    /**
     * Update switcher UI to reflect new brand
     */
    function updateSwitcherUI(brandId) {
        const tabs = document.querySelectorAll(CONFIG.selectors.tab);
        
        tabs.forEach(tab => {
            const isActive = tab.dataset.brand === brandId;
            tab.classList.toggle('is-active', isActive);
            tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        
        // Update indicator position
        updateIndicator();
        
        // Update toggle text
        updateToggleUI(brandId);
    }
    
    /**
     * Update animated indicator position
     */
    function updateIndicator() {
        const switchers = document.querySelectorAll(CONFIG.selectors.switcher);
        
        switchers.forEach(switcher => {
            const indicator = switcher.querySelector(CONFIG.selectors.indicator);
            const activeTab = switcher.querySelector(`${CONFIG.selectors.tab}.is-active`);
            
            if (!indicator || !activeTab) return;
            
            const tabRect = activeTab.getBoundingClientRect();
            const switcherRect = switcher.getBoundingClientRect();
            
            indicator.style.left = `${activeTab.offsetLeft}px`;
            indicator.style.width = `${tabRect.width}px`;
            indicator.style.backgroundColor = activeTab.dataset.primaryColor || 
                getComputedStyle(document.documentElement).getPropertyValue('--pcz-primary');
        });
    }
    
    /**
     * Update toggle UI
     */
    function updateToggleUI(currentBrandId) {
        const toggles = document.querySelectorAll(CONFIG.selectors.toggle);
        
        toggles.forEach(toggle => {
            const currentSpan = toggle.querySelector('.pcz-brand-toggle__current');
            const otherSpan = toggle.querySelector('.pcz-brand-toggle__other');
            
            if (!currentSpan || !otherSpan) return;
            
            const currentBrand = state.brands[currentBrandId];
            const otherBrandId = Object.keys(state.brands).find(id => id !== currentBrandId);
            const otherBrand = state.brands[otherBrandId];
            
            if (currentBrand && otherBrand) {
                currentSpan.textContent = currentBrand.shortName;
                otherSpan.textContent = otherBrand.shortName;
                toggle.dataset.brand = otherBrandId;
                // UVIJEK koristi brand parameter - za oba branda
                toggle.href = `${window.location.pathname}?brand=${otherBrandId}`;
            }
        });
    }
    
    // =============================================================================
    // TRANSITION OVERLAY
    // =============================================================================
    
    /**
     * Show lightweight transition overlay - OPTIMIZED
     * 
     * Koristi lagani fade umjesto blur efekta za bolju performansu.
     * Kraći duration za bržu percepciju.
     */
    function showTransitionOverlay(targetBrand) {
        let overlay = document.querySelector('.pcz-brand-transition-overlay');
        
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'pcz-brand-transition-overlay';
            overlay.innerHTML = `
                <div class="pcz-brand-transition-overlay__content">
                    <div class="pcz-brand-transition-overlay__spinner"></div>
                </div>
            `;
            document.body.appendChild(overlay);
            
            // Inject CSS for overlay if not exists - OPTIMIZED styles
            if (!document.querySelector('#pcz-brand-overlay-styles')) {
                const style = document.createElement('style');
                style.id = 'pcz-brand-overlay-styles';
                style.textContent = `
                    .pcz-brand-transition-overlay {
                        position: fixed;
                        inset: 0;
                        background: rgba(255, 255, 255, 0.85);
                        z-index: 99999;
                        opacity: 0;
                        visibility: hidden;
                        transition: opacity 0.1s ease-out, visibility 0.1s ease-out;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        will-change: opacity;
                    }
                    .pcz-brand-transition-overlay.is-active {
                        opacity: 1;
                        visibility: visible;
                    }
                    .pcz-brand-transition-overlay__content {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .pcz-brand-transition-overlay__spinner {
                        width: 32px;
                        height: 32px;
                        border: 3px solid rgba(0, 0, 0, 0.1);
                        border-top-color: var(--pcz-primary, #C71585);
                        border-radius: 50%;
                        animation: pcz-spin 0.6s linear infinite;
                        will-change: transform;
                    }
                    @keyframes pcz-spin {
                        to { transform: rotate(360deg); }
                    }
                `;
                document.head.appendChild(style);
            }
        }
        
        // Odmah ažuriraj boju spinnera za novi brand
        const spinner = overlay.querySelector('.pcz-brand-transition-overlay__spinner');
        if (spinner && targetBrand && state.brands[targetBrand]) {
            spinner.style.borderTopColor = state.brands[targetBrand].primaryColor;
        }
        
        // Force reflow
        overlay.offsetHeight;
        overlay.classList.add('is-active');
    }
    
    function hideTransitionOverlay() {
        const overlay = document.querySelector('.pcz-brand-transition-overlay');
        if (overlay) {
            overlay.classList.remove('is-active');
        }
    }
    
    // =============================================================================
    // STORAGE
    // =============================================================================
    
    function saveBrandToStorage(brandId) {
        try {
            localStorage.setItem(CONFIG.storage.brand, brandId);
            localStorage.setItem(CONFIG.storage.timestamp, Date.now().toString());
            
            // Also set cookie for server-side detection
            document.cookie = `pcz_brand=${brandId};path=/;max-age=${30 * 24 * 60 * 60}`;
        } catch (e) {
            console.warn('[pcz Brand] Storage not available:', e);
        }
    }
    
    function loadBrandFromStorage() {
        try {
            return localStorage.getItem(CONFIG.storage.brand);
        } catch (e) {
            return null;
        }
    }
    
    // =============================================================================
    // BROWSER NAVIGATION
    // =============================================================================
    
    function handlePopState(event) {
        if (event.state && event.state.brand) {
            switchBrand(event.state.brand);
        } else {
            // Check URL for brand parameter
            const urlParams = new URLSearchParams(window.location.search);
            const brandParam = urlParams.get('brand');
            
            if (brandParam && state.brands[brandParam]) {
                switchBrand(brandParam);
            } else {
                switchBrand('plesna-skola');
            }
        }
    }
    
    // =============================================================================
    // CUSTOM EVENTS
    // =============================================================================
    
    function dispatchBrandChangeEvent(newBrand, oldBrand) {
        const event = new CustomEvent('pczBrandChange', {
            detail: {
                newBrand: newBrand,
                oldBrand: oldBrand,
                brandData: state.brands[newBrand],
            },
            bubbles: true,
        });
        
        document.dispatchEvent(event);
    }
    
    // =============================================================================
    // UTILITIES
    // =============================================================================
    
    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    
    // =============================================================================
    // PUBLIC API
    // =============================================================================
    
    window.pczBrand = {
        /**
         * Get current brand ID
         */
        getCurrent: function() {
            return state.currentBrand;
        },
        
        /**
         * Get brand data
         */
        getBrand: function(brandId) {
            return state.brands[brandId || state.currentBrand];
        },
        
        /**
         * Get all brands
         */
        getAllBrands: function() {
            return { ...state.brands };
        },
        
        /**
         * Switch to brand programmatically
         */
        switchTo: function(brandId) {
            if (state.brands[brandId]) {
                // UVIJEK koristi brand parameter u URL-u
                // Ovo osigurava da se cookie postavi ispravno
                const url = `${window.location.pathname}?brand=${brandId}`;
                switchBrand(brandId, url);
            }
        },
        
        /**
         * Check if brand is current
         */
        isBrand: function(brandId) {
            return state.currentBrand === brandId;
        },
        
        /**
         * Listen to brand changes
         */
        onChange: function(callback) {
            document.addEventListener('pczBrandChange', function(event) {
                callback(event.detail);
            });
        },
    };
    
    // =============================================================================
    // INIT ON DOM READY
    // =============================================================================
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Re-init on resize (for indicator position)
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(updateIndicator, 100);
    });
    
})();

