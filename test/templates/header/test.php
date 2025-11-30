<?php
/**
 * Test Wrapper za Header komponentu
 * 
 * Ovaj fajl služi kao test okruženje za header/mega-menu.php
 * 
 * VAŽNO: Ovaj fajl UKLJUČUJE produkcijski kod, NIKAD ga ne duplicira!
 * Svi stilovi su u /header/mega-menu.css
 * Sav JS je u /header/mega-menu.js
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// =============================================================================
// BOOTSTRAP
// =============================================================================

require_once __DIR__ . '/../../core/bootstrap.php';

// =============================================================================
// LOAD MOCK DATA
// =============================================================================

$mock_data = require __DIR__ . '/mock-data.php';
load_mock_data($mock_data);

// =============================================================================
// APPLY SCENARIO
// =============================================================================

$current_scenario = $GLOBALS['pcz_current_scenario'] ?? $_GET['scenario'] ?? 'default';
$current_template = $GLOBALS['pcz_current_template'] ?? 'header';

if ($current_scenario !== 'default' && isset($mock_data['scenarios'][$current_scenario])) {
    apply_scenario($current_scenario, $mock_data);
}

// Dohvati dostupne scenarije
$available_scenarios = array_keys($mock_data['scenarios'] ?? ['default' => []]);

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: Header / Mega Menu | pcz Test Environment</title>
    
    <!-- ⚠️ PRODUKCIJSKI CSS - ne dupliciraj! -->
    <link rel="stylesheet" href="/header/mega-menu.css">
    
    <!-- Test Environment Styles ONLY -->
    <style>
        /* ==========================================================================
           TEST ENVIRONMENT STYLES
           
           NAPOMENA: Ovdje idu SAMO stilovi za test okruženje!
           Svi header/mega-menu stilovi su u /header/mega-menu.css
           
           Ako trebaš testirati nove stilove:
           1. Dodaj ih ovdje s !important
           2. Kad si zadovoljan, premjesti ih u /header/mega-menu.css (bez !important)
           3. Obriši ih odavde
           ========================================================================== */
        
        /* ✅ MEGA MENU REDESIGN V2 - Sada u produkciji! (/header/mega-menu.css) */
        
        /* Test-only: Brand-aware hero gradient */
        [data-brand="sportski-klub"] .test-content__hero {
            background: linear-gradient(135deg, #FF6B00 0%, #FF8C00 100%);
        }
        
        /* ===== Test Environment Variables ===== */
        :root {
            --test-bg: #1a1a24;
            --test-panel-bg: #22222e;
            --test-border: #333344;
            --test-accent: #C71585;
            --test-text: #f0f0f5;
            --test-text-muted: #9898a8;
            --test-font: 'Inter', -apple-system, sans-serif;
            --test-font-mono: 'JetBrains Mono', monospace;
        }

        /* ===== Test Page Base ===== */
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: var(--test-font);
        }

        /* ===== Test Controls Panel ===== */
        .test-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--test-panel-bg);
            border-top: 3px solid var(--test-accent);
            padding: 16px 24px;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            font-size: 14px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
        }

        .test-panel__section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .test-panel__label {
            color: var(--test-text-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .test-panel__title {
            color: var(--test-text);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .test-panel__title span {
            background: var(--test-accent);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
        }

        /* ===== Scenario Selector ===== */
        .test-panel__select {
            background: var(--test-bg);
            border: 1px solid var(--test-border);
            color: var(--test-text);
            padding: 8px 12px;
            border-radius: 6px;
            font-family: var(--test-font-mono);
            font-size: 13px;
            cursor: pointer;
            min-width: 160px;
        }

        .test-panel__select:hover {
            border-color: var(--test-accent);
        }

        .test-panel__select:focus {
            outline: none;
            border-color: var(--test-accent);
            box-shadow: 0 0 0 2px rgba(199, 21, 133, 0.2);
        }

        /* ===== Responsive Buttons ===== */
        .test-panel__responsive {
            display: flex;
            gap: 8px;
        }

        .test-panel__btn {
            background: var(--test-bg);
            border: 1px solid var(--test-border);
            color: var(--test-text-muted);
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
        }

        .test-panel__btn:hover {
            border-color: var(--test-accent);
            color: var(--test-text);
        }

        .test-panel__btn--active {
            background: var(--test-accent);
            border-color: var(--test-accent);
            color: white;
        }

        /* ===== Links ===== */
        .test-panel__link {
            color: var(--test-accent);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .test-panel__link:hover {
            text-decoration: underline;
        }

        /* ===== Preview Container ===== */
        .test-preview {
            min-height: calc(100vh - 80px);
            padding-bottom: 80px; /* Space for panel */
            transition: max-width 0.3s ease;
            margin: 0 auto;
        }

        .test-preview--mobile {
            max-width: 375px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
        }

        .test-preview--tablet {
            max-width: 768px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
        }

        .test-preview--desktop {
            max-width: 1440px;
        }

        /* ===== Component Wrapper ===== */
        .test-component {
            background: white;
        }

        /* ===== Mock Content Area ===== */
        .test-content {
            padding: 60px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .test-content__hero {
            background: linear-gradient(135deg, #C71585 0%, #a01269 100%);
            color: white;
            padding: 80px 40px;
            text-align: center;
            border-radius: 12px;
            margin-bottom: 40px;
        }

        .test-content__hero h1 {
            font-size: 2.5rem;
            margin-bottom: 16px;
        }

        .test-content__hero p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .test-content__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .test-content__card {
            background: #f8f8f8;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
        }

        .test-content__card-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .test-content__card h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: #333;
        }

        .test-content__card p {
            color: #666;
            font-size: 0.9rem;
        }

        /* ===== Responsive Content ===== */
        @media (max-width: 768px) {
            .test-panel {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 12px 16px;
            }

            .test-panel__section {
                justify-content: space-between;
            }

            .test-content__grid {
                grid-template-columns: 1fr;
            }

            .test-content__hero h1 {
                font-size: 1.75rem;
            }
        }
    </style>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
</head>
<?php
// Dohvati brand za data attribute
$current_brand_id = 'plesna-skola';
if ( function_exists( 'pcz_get_current_brand_id' ) ) {
    $current_brand_id = pcz_get_current_brand_id();
}
?>
<body data-brand="<?php echo esc_attr( $current_brand_id ); ?>">

<!-- Preview Container -->
<div class="test-preview" id="test-preview">
    
    <!-- ==================== -->
    <!-- COMPONENT START      -->
    <!-- ==================== -->
    <div class="test-component">
        <?php 
        // ⚠️ UKLJUČI produkcijsku komponentu - NIKAD ne dupliciraj kod!
        include pcz_HEADER_PATH . '/mega-menu.php'; 
        ?>
    </div>
    <!-- ==================== -->
    <!-- COMPONENT END        -->
    <!-- ==================== -->
    
    <!-- Mock Page Content (za kontekst) -->
    <div class="test-content">
        <div class="test-content__hero">
            <h1>Dobrodošli u Plesni Centar Zagreb</h1>
            <p>Ovo je mock sadržaj stranice za testiranje header komponente.</p>
        </div>
        
        <div class="test-content__grid">
            <div class="test-content__card">
                <div class="test-content__card-icon">💃</div>
                <h3>Tečajevi za odrasle</h3>
                <p>Naučite plesati uz profesionalne instruktore</p>
            </div>
            <div class="test-content__card">
                <div class="test-content__card-icon">👶</div>
                <h3>Programi za djecu</h3>
                <p>Plesni programi prilagođeni najmanjima</p>
            </div>
            <div class="test-content__card">
                <div class="test-content__card-icon">💍</div>
                <h3>Mladenački tečajevi</h3>
                <p>Savršeni prvi ples za vaše vjenčanje</p>
            </div>
        </div>
    </div>

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">
            ← Natrag
        </a>
        <div class="test-panel__title">
            <span>Header</span>
            Mega Menu Test
        </div>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Scenario:</span>
        <select class="test-panel__select" id="scenario-select" onchange="window.location.href=this.value">
            <?php foreach ($available_scenarios as $scenario): ?>
                <?php 
                $url = get_test_url($current_template, $scenario);
                $selected = ($scenario === $current_scenario) ? ' selected' : '';
                $label = ucfirst(str_replace(['_', '-'], ' ', $scenario));
                ?>
                <option value="<?php echo esc_attr($url); ?>"<?php echo $selected; ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Brand:</span>
        <div class="test-panel__responsive">
            <button type="button" class="test-panel__btn <?php echo $current_brand_id === 'plesna-skola' ? 'test-panel__btn--active' : ''; ?>" 
                    onclick="switchBrand('plesna-skola')" style="--btn-accent: #C71585;">
                💃 Plesna Škola
            </button>
            <button type="button" class="test-panel__btn <?php echo $current_brand_id === 'sportski-klub' ? 'test-panel__btn--active' : ''; ?>" 
                    onclick="switchBrand('sportski-klub')" style="--btn-accent: #FF6B00;">
                🏆 Sportski Klub
            </button>
        </div>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Prikaz:</span>
        <div class="test-panel__responsive">
            <button type="button" class="test-panel__btn" onclick="setPreview('mobile')">📱 Mobile</button>
            <button type="button" class="test-panel__btn" onclick="setPreview('tablet')">📱 Tablet</button>
            <button type="button" class="test-panel__btn" onclick="setPreview('desktop')">🖥️ Desktop</button>
            <button type="button" class="test-panel__btn test-panel__btn--active" onclick="setPreview('full')">⬜ Full</button>
        </div>
    </div>
</div>

<!-- ⚠️ PRODUKCIJSKI JS - ne dupliciraj! -->
<script src="/header/mega-menu.js"></script>

<!-- Test Environment JS ONLY -->
<script>
(function() {
    'use strict';
    
    const preview = document.getElementById('test-preview');
    const buttons = document.querySelectorAll('.test-panel__btn');
    
    // Viewport switcher za test panel
    window.setPreview = function(mode) {
        // Remove all mode classes
        preview.classList.remove('test-preview--mobile', 'test-preview--tablet', 'test-preview--desktop');
        
        // Add selected mode class
        if (mode !== 'full') {
            preview.classList.add('test-preview--' + mode);
        }
        
        // Update button states (only viewport buttons)
        document.querySelectorAll('.test-panel__responsive:last-child .test-panel__btn').forEach(btn => {
            btn.classList.remove('test-panel__btn--active');
        });
        event.target.classList.add('test-panel__btn--active');
        
        // Trigger resize event for responsive JS
        window.dispatchEvent(new Event('resize'));
    };
    
    // Brand switcher za test panel
    window.switchBrand = function(brandId) {
        // Redirect s brand parametrom
        const url = new URL(window.location.href);
        url.searchParams.set('brand', brandId);
        window.location.href = url.toString();
    };
    
    // Keyboard shortcuts za brzo mijenjanje viewporta
    document.addEventListener('keydown', function(e) {
        // Press 1-4 for different views
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') return;
        
        const viewportBtns = document.querySelectorAll('.test-panel__responsive:last-child .test-panel__btn');
        
        switch(e.key) {
            case '1': setPreview('mobile'); break;
            case '2': setPreview('tablet'); break;
            case '3': setPreview('desktop'); break;
            case '4': setPreview('full'); break;
            // Brand shortcuts
            case 'p': switchBrand('plesna-skola'); break;
            case 's': switchBrand('sportski-klub'); break;
        }
    });
    
    // Console info
    console.log('%c🧪 pcz Test Environment', 'font-size: 16px; font-weight: bold; color: #C71585;');
    console.log('Current template: header');
    console.log('Current scenario: <?php echo esc_js($current_scenario); ?>');
    console.log('Current brand: <?php echo esc_js($current_brand_id); ?>');
    console.log('Press 1-4 to change viewport size');
    console.log('Press P for Plesna Škola, S for Sportski Klub');
    
    // NAPOMENA: Mobile dropdown toggle je već u /header/mega-menu.js
    // Nema potrebe duplicirati ovdje!
    
})();
</script>

</body>
</html>
