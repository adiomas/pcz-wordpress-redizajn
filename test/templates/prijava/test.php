<?php
/**
 * Test Wrapper za Prijava sekciju
 * 
 * Testira prijava/prijava.php komponentu s mock podacima
 * BRAND-AWARE: Podržava prebacivanje između brandova
 * 
 * @package PCZ_Test_Environment
 * @since 1.0.0
 */

// =============================================================================
// BOOTSTRAP
// =============================================================================

require_once __DIR__ . '/../../core/bootstrap.php';

// =============================================================================
// LOAD MOCK DATA - Brand + Prijava
// =============================================================================

// Učitaj brand mock data (za brand konfiguraciju)
$brand_mock_data = require __DIR__ . '/../brand/mock-data.php';

// Učitaj prijava mock data
$prijava_mock_data = require __DIR__ . '/mock-data.php';
load_mock_data($prijava_mock_data);

// =============================================================================
// BRAND DETECTION & CONFIGURATION
// =============================================================================

// Dohvati brand iz URL parametra
$current_brand = $_GET['brand'] ?? 'plesna-skola';
if (!isset($brand_mock_data['brands'][$current_brand])) {
    $current_brand = 'plesna-skola';
}

$brand_config = $brand_mock_data['brands'][$current_brand];
$all_brands = $brand_mock_data['brands'];

// Učitaj brand funkcije
require_once pcz_BRAND_PATH . '/brand.php';

// Simuliraj cookie za brand
$_COOKIE['pcz_brand'] = $current_brand;

// =============================================================================
// APPLY SCENARIO
// =============================================================================

$current_scenario = $GLOBALS['pcz_current_scenario'] ?? $_GET['scenario'] ?? 'default';
$current_template = $GLOBALS['pcz_current_template'] ?? 'prijava';

if ($current_scenario !== 'default' && isset($prijava_mock_data['scenarios'][$current_scenario])) {
    apply_scenario($current_scenario, $prijava_mock_data);
}

$available_scenarios = array_keys($prijava_mock_data['scenarios'] ?? ['default' => []]);

// =============================================================================
// COMPONENT CONFIGURATION
// =============================================================================

$component_name = 'Prijava Forma';
$prijava_path = pcz_PROJECT_ROOT . '/prijava/prijava.php';

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: <?php echo esc_html($component_name); ?> | PCZ Test Environment</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Georgia&display=swap" rel="stylesheet">
    
    <!-- Prijava CSS (BASE stilovi) -->
    <link rel="stylesheet" href="/prijava/prijava.css">
    
    <!-- Brand CSS (OVERRIDE stilovi) -->
    <link rel="stylesheet" href="/brand/brand.css">
    
    <!-- Brand CSS Variables - dinamički za trenutni brand -->
    <style id="pcz-brand-css">
    :root {
        /* Brand: <?php echo esc_html($brand_config['name']); ?> */
        --brand-primary: <?php echo esc_html($brand_config['primary_color']); ?>;
        --brand-primary-hover: <?php echo esc_html($brand_config['primary_hover']); ?>;
        --brand-secondary: <?php echo esc_html($brand_config['secondary_color']); ?>;
        --brand-accent: <?php echo esc_html($brand_config['accent_color']); ?>;
        --brand-gradient: <?php echo esc_html($brand_config['gradient']); ?>;
    }
    </style>
    
    <style>
        /* ==========================================================================
           Test Environment Styles
           ========================================================================== */
        :root {
            --test-bg: #1a1a24;
            --test-panel-bg: #22222e;
            --test-border: #333344;
            --test-accent: <?php echo esc_html($brand_config['primary_color']); ?>;
            --test-text: #f0f0f5;
            --test-text-muted: #9898a8;
            --test-font: 'Inter', -apple-system, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--test-font);
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        /* Test Panel */
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

        .test-panel__section { display: flex; align-items: center; gap: 16px; }
        .test-panel__label { color: var(--test-text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        .test-panel__title { color: var(--test-text); font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .test-panel__title span { background: var(--test-accent); color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; }
        .test-panel__select { background: var(--test-bg); border: 1px solid var(--test-border); color: var(--test-text); padding: 8px 12px; border-radius: 6px; font-size: 13px; min-width: 160px; cursor: pointer; }
        .test-panel__select:hover { border-color: var(--test-accent); }
        .test-panel__link { color: var(--test-accent); text-decoration: none; }
        .test-panel__link:hover { text-decoration: underline; }
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
        .test-panel__btn:hover { border-color: var(--test-accent); color: var(--test-text); }
        .test-panel__btn--active { background: var(--test-accent); border-color: var(--test-accent); color: white; }
        .test-panel__responsive { display: flex; gap: 8px; }

        /* Preview Container */
        .test-preview {
            min-height: calc(100vh - 80px);
            padding-bottom: 80px;
            transition: max-width 0.3s ease;
            margin: 0 auto;
        }
        .test-preview--mobile { max-width: 375px; box-shadow: 0 0 40px rgba(0, 0, 0, 0.2); }
        .test-preview--tablet { max-width: 768px; box-shadow: 0 0 40px rgba(0, 0, 0, 0.2); }
        .test-preview--desktop { max-width: 1440px; }

        /* Hero Context (pokazuje kako će izgledati nakon hero sekcije) */
        .test-hero-context {
            background: linear-gradient(135deg, #333 0%, #555 100%);
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            position: relative;
        }
        
        .test-hero-context::after {
            content: 'HERO SEKCIJA';
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            letter-spacing: 2px;
            opacity: 0.5;
        }

        .test-hero-cta {
            display: inline-block;
            padding: 14px 32px;
            background: var(--test-accent);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.2s;
        }
        
        .test-hero-cta:hover {
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .test-panel {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 12px 16px;
            }
            .test-panel__section { justify-content: space-between; }
        }
    </style>
</head>
<body class="pcz-brand-<?php echo esc_attr($current_brand); ?>" data-brand="<?php echo esc_attr($current_brand); ?>">

<div class="test-preview" id="test-preview">
    
    <!-- Hero Context - simulira scroll iz hero sekcije -->
    <div class="test-hero-context">
        <a href="#prijava" class="test-hero-cta">
            <?php echo $current_brand === 'sportski-klub' ? 'ŽELIM TRENIRATI!' : 'ŽELIM PLESATI!'; ?>
        </a>
    </div>
    
    <!-- ==================== -->
    <!-- PRIJAVA COMPONENT    -->
    <!-- ==================== -->
    <div class="test-component">
        <?php if (file_exists($prijava_path)): ?>
            <?php include $prijava_path; ?>
        <?php else: ?>
            <div style="background: #ff6b6b; color: white; padding: 40px; text-align: center;">
                <h2>📋 <?php echo esc_html($component_name); ?></h2>
                <p>Komponenta se učitava iz: <code><?php echo esc_html($prijava_path); ?></code></p>
            </div>
        <?php endif; ?>
    </div>
    <!-- ==================== -->
    <!-- /PRIJAVA COMPONENT   -->
    <!-- ==================== -->
    
    <!-- Context After -->
    <div style="background: #ffffff; padding: 60px 40px; text-align: center;">
        <p style="color: #666; font-size: 14px;">↑ Forma se nalazi iznad ↑</p>
        <p style="color: #999; font-size: 12px; margin-top: 10px;">
            Ostatak stranice (footer, itd.) bi išao ovdje.
        </p>
    </div>

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">← Natrag</a>
        <div class="test-panel__title">
            <span>Prijava</span>
            Forma Test
        </div>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Brand:</span>
        <select class="test-panel__select" onchange="window.location.href=this.value" style="min-width: 140px;">
            <?php foreach ($all_brands as $brand_id => $brand): ?>
                <option value="?template=prijava&brand=<?php echo esc_attr($brand_id); ?>&scenario=<?php echo esc_attr($current_scenario); ?>"
                    <?php echo ($brand_id === $current_brand) ? ' selected' : ''; ?>
                    style="color: <?php echo esc_attr($brand['primary_color']); ?>">
                    <?php echo esc_html($brand['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Scenario:</span>
        <select class="test-panel__select" onchange="window.location.href=this.value">
            <?php foreach ($available_scenarios as $scenario): ?>
                <option value="?template=prijava&brand=<?php echo esc_attr($current_brand); ?>&scenario=<?php echo esc_attr($scenario); ?>"
                    <?php echo ($scenario === $current_scenario) ? ' selected' : ''; ?>>
                    <?php echo esc_html(ucfirst(str_replace(['_', '-'], ' ', $scenario))); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Prikaz:</span>
        <div class="test-panel__responsive">
            <button type="button" class="test-panel__btn" onclick="setPreview('mobile')">📱 Mobile</button>
            <button type="button" class="test-panel__btn" onclick="setPreview('tablet')">📱 Tablet</button>
            <button type="button" class="test-panel__btn test-panel__btn--active" onclick="setPreview('full')">⬜ Full</button>
        </div>
    </div>
</div>

<!-- Brand JS -->
<script src="/brand/brand.js"></script>

<!-- Test Environment JS -->
<script>
(function() {
    'use strict';
    
    const preview = document.getElementById('test-preview');
    const buttons = document.querySelectorAll('.test-panel__btn');
    
    window.setPreview = function(mode) {
        preview.classList.remove('test-preview--mobile', 'test-preview--tablet', 'test-preview--desktop');
        
        if (mode !== 'full') {
            preview.classList.add('test-preview--' + mode);
        }
        
        buttons.forEach(btn => btn.classList.remove('test-panel__btn--active'));
        event.target.classList.add('test-panel__btn--active');
        
        window.dispatchEvent(new Event('resize'));
    };
    
    // Smooth scroll za anchor linkove
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    
    console.log('%c🧪 PCZ Test Environment', 'font-size: 16px; font-weight: bold; color: <?php echo esc_js($brand_config['primary_color']); ?>;');
    console.log('Current template: prijava');
    console.log('Current scenario: <?php echo esc_js($current_scenario); ?>');
    console.log('Current brand: <?php echo esc_js($current_brand); ?>');
})();
</script>

</body>
</html>

