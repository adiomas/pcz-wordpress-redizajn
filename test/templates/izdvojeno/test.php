<?php
/**
 * Test Wrapper za "Izdvojeno iz ponude" sekciju
 * 
 * Testira izdvojeno/izdvojeno.php komponentu s mock podacima
 * Podržava oba branda: Plesna škola i Sportski klub.
 * 
 * @package PCZ_Test_Environment
 * @since 1.0.0
 */

// =============================================================================
// BOOTSTRAP
// =============================================================================

require_once __DIR__ . '/../../core/bootstrap.php';

// =============================================================================
// LOAD MOCK DATA
// =============================================================================

// Učitaj brand mock data (za brand konfiguraciju)
$brand_mock_data = require __DIR__ . '/../brand/mock-data.php';

// Učitaj izdvojeno mock data
$izdvojeno_mock_data = require __DIR__ . '/mock-data.php';
load_mock_data($izdvojeno_mock_data);

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
$current_template = $GLOBALS['pcz_current_template'] ?? 'izdvojeno';

if ($current_scenario !== 'default' && isset($izdvojeno_mock_data['scenarios'][$current_scenario])) {
    $scenario_data = $izdvojeno_mock_data['scenarios'][$current_scenario];
    
    // Ako scenario ima brand, koristi ga
    if (isset($scenario_data['brand'])) {
        $current_brand = $scenario_data['brand'];
        $brand_config = $brand_mock_data['brands'][$current_brand] ?? $brand_config;
        $_COOKIE['pcz_brand'] = $current_brand;
    }
    
    apply_scenario($current_scenario, $izdvojeno_mock_data);
}

$available_scenarios = array_keys($izdvojeno_mock_data['scenarios'] ?? ['default' => []]);

// =============================================================================
// COMPONENT CONFIGURATION
// =============================================================================

$component_name = ($current_brand === 'sportski-klub') ? 'Sportske discipline' : 'Izdvojeno iz ponude';
$izdvojeno_path = pcz_PROJECT_ROOT . '/izdvojeno/izdvojeno.php';

// Proslijedi brand izdvojeno.php templatu
$izdvojeno_brand = $current_brand;

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
    
    <!-- Izdvojeno CSS (BASE stilovi) -->
    <link rel="stylesheet" href="/izdvojeno/izdvojeno.css">
    
    <!-- Brand CSS (OVERRIDE stilovi) -->
    <link rel="stylesheet" href="/brand/brand.css">
    
    <!-- Brand CSS Variables -->
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
        
        .test-panel__badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .test-panel__badge--plesna-skola {
            background: #CB007C;
            color: white;
        }
        .test-panel__badge--sportski-klub {
            background: #FF6B00;
            color: white;
        }

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
    
    <!-- ==================== -->
    <!-- IZDVOJENO COMPONENT  -->
    <!-- ==================== -->
    <div class="test-component">
        <?php if (file_exists($izdvojeno_path)): ?>
            <?php include $izdvojeno_path; ?>
        <?php else: ?>
            <div style="background: #ff6b6b; color: white; padding: 40px; text-align: center;">
                <h2>📋 <?php echo esc_html($component_name); ?></h2>
                <p>Komponenta se učitava iz: <code><?php echo esc_html($izdvojeno_path); ?></code></p>
            </div>
        <?php endif; ?>
    </div>
    <!-- ==================== -->
    <!-- /IZDVOJENO COMPONENT -->
    <!-- ==================== -->
    
    <!-- Context After -->
    <div style="background: #f5f5f5; padding: 60px 40px; text-align: center;">
        <p style="color: #666; font-size: 14px;">↑ Sekcija "<?php echo esc_html($component_name); ?>" ↑</p>
        <p style="color: #999; font-size: 12px; margin-top: 10px;">
            Nastavak stranice bi išao ovdje (Poznati o PCZ-u, Prijava forma, Footer...)
        </p>
    </div>

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">← Natrag</a>
        <div class="test-panel__title">
            <span><?php echo ($current_brand === 'sportski-klub') ? 'Discipline' : 'Izdvojeno'; ?></span>
            Kartice Test
        </div>
        <span class="test-panel__badge test-panel__badge--<?php echo esc_attr($current_brand); ?>">
            <?php echo ($current_brand === 'sportski-klub') ? '🏆 Sportski Klub' : '🎭 Plesna Škola'; ?>
        </span>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Brand:</span>
        <select class="test-panel__select" onchange="window.location.href=this.value" style="min-width: 140px;">
            <?php foreach ($all_brands as $brand_id => $brand): ?>
                <option value="?template=izdvojeno&brand=<?php echo esc_attr($brand_id); ?>&scenario=<?php echo ($brand_id === 'sportski-klub') ? 'sportski_klub' : 'default'; ?>"
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
                <?php 
                // Filtriraj scenarije ovisno o brandu
                $is_sk_scenario = strpos($scenario, 'sportski_klub') !== false;
                $show_scenario = ($current_brand === 'sportski-klub') 
                    ? ($is_sk_scenario || $scenario === 'default' || $scenario === 'empty' || $scenario === 'no_cta')
                    : !$is_sk_scenario;
                
                if (!$show_scenario && $scenario !== 'default') continue;
                ?>
                <option value="?template=izdvojeno&brand=<?php echo esc_attr($current_brand); ?>&scenario=<?php echo esc_attr($scenario); ?>"
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
    
    console.log('%c🧪 PCZ Test Environment', 'font-size: 16px; font-weight: bold; color: <?php echo esc_js($brand_config['primary_color']); ?>;');
    console.log('Current template: izdvojeno');
    console.log('Current scenario: <?php echo esc_js($current_scenario); ?>');
    console.log('Current brand: <?php echo esc_js($current_brand); ?>');
})();
</script>

</body>
</html>
