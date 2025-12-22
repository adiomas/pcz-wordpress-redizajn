<?php
/**
 * Test Wrapper za "O Nama - Sportski Klub" komponentu
 * 
 * Testira o-nama-spk/o-nama-spk.php komponentu s mock podacima
 * NOVI SVIJETLI DIZAJN (stil upoznajte)
 * 
 * @package pcz_Test_Environment
 * @since 2.1.0
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
$current_template = $GLOBALS['pcz_current_template'] ?? 'o-nama-spk';

if ($current_scenario !== 'default' && isset($mock_data['scenarios'][$current_scenario])) {
    apply_scenario($current_scenario, $mock_data);
}

$available_scenarios = array_keys($mock_data['scenarios'] ?? ['default' => []]);

// =============================================================================
// COMPONENT CONFIGURATION
// =============================================================================

$component_name = 'O Nama - Sportski Klub';
$component_path = pcz_PROJECT_ROOT . '/o-nama-spk/o-nama-spk.php';
$component_css  = '/o-nama-spk/o-nama-spk.css';

// Brand CSS for proper theming
$brand_css = '/brand/brand.css';

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: <?php echo esc_html($component_name); ?> | pcz Test Environment</title>
    
    <!-- Google Fonts - Dancing Script za slogan -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Brand CSS (za CSS varijable) -->
    <?php if ($brand_css && file_exists(pcz_PROJECT_ROOT . $brand_css)): ?>
    <link rel="stylesheet" href="<?php echo esc_url($brand_css); ?>">
    <?php endif; ?>
    
    <!-- Component CSS -->
    <?php if ($component_css && file_exists(pcz_PROJECT_ROOT . $component_css)): ?>
    <link rel="stylesheet" href="<?php echo esc_url($component_css); ?>">
    <?php endif; ?>
    
    <style>
        /* ==========================================================================
           Test Environment Styles
           ========================================================================== */
        :root {
            --test-bg: #1a1a24;
            --test-panel-bg: #22222e;
            --test-border: #333344;
            --test-accent: #FF6B00;
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

        /* Mock Hero */
        .test-mock-hero {
            background: linear-gradient(135deg, var(--test-accent) 0%, #e55e00 100%);
            padding: 80px 40px;
            text-align: center;
            color: white;
        }
        .test-mock-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .test-mock-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .test-mock-hero__btn {
            display: inline-block;
            margin-top: 25px;
            background: white;
            color: var(--test-accent);
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Placeholder for missing component */
        .test-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            text-align: center;
            padding: 60px 40px;
            background: #f9f9f9;
            color: #666;
        }
        .test-placeholder__icon { font-size: 64px; margin-bottom: 24px; }
        .test-placeholder__title { font-size: 1.5rem; margin-bottom: 12px; color: var(--test-accent); }
        .test-placeholder__message { opacity: 0.9; max-width: 500px; }

        /* Brand badge */
        .test-brand-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--test-accent);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.3);
        }

        /* Mock content after section */
        .test-next-section {
            background: #f5f5f5;
            padding: 60px 40px;
            text-align: center;
        }
        .test-next-section h3 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .test-next-section p {
            color: #666;
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
            .test-mock-hero { padding: 50px 20px; }
            .test-mock-hero h1 { font-size: 1.75rem; }
        }
    </style>
</head>
<body data-brand="sportski-klub">

<!-- Brand Badge -->
<div class="test-brand-badge">🏆 Sportski Klub</div>

<div class="test-preview" id="test-preview">
    
    <!-- Mock Hero (simulates content above) -->
    <div class="test-mock-hero">
        <h1>SPORTSKI PLESNI KLUB ZAGREB</h1>
        <p>Najbolji sportski plesni klub u Hrvatskoj</p>
        <a href="#" class="test-mock-hero__btn">Prijavi se</a>
    </div>
    
    <!-- ==================== -->
    <!-- O NAMA SPK COMPONENT -->
    <!-- ==================== -->
    <div class="test-component">
        <?php if (file_exists($component_path)): ?>
            <?php include $component_path; ?>
        <?php else: ?>
            <div class="test-placeholder">
                <div class="test-placeholder__icon">📋</div>
                <h2 class="test-placeholder__title"><?php echo esc_html($component_name); ?></h2>
                <p class="test-placeholder__message">
                    Komponenta se učitava iz: <code><?php echo esc_html($component_path); ?></code>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <!-- ==================== -->
    <!-- /O NAMA SPK COMPONENT -->
    <!-- ==================== -->
    
    <!-- Mock content after section -->
    <div class="test-next-section">
        <h3>Sljedeća sekcija</h3>
        <p>Ovo je mock sadržaj koji simulira ostatak stranice...</p>
    </div>

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">← Natrag</a>
        <div class="test-panel__title">
            <span>O Nama SPK</span>
            Novi svijetli dizajn
        </div>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Scenario:</span>
        <select class="test-panel__select" onchange="window.location.href=this.value">
            <?php foreach ($available_scenarios as $scenario): ?>
                <option value="<?php echo esc_attr(get_test_url($current_template, $scenario)); ?>"
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
            <button type="button" class="test-panel__btn" onclick="setPreview('desktop')">🖥️ Desktop</button>
            <button type="button" class="test-panel__btn test-panel__btn--active" onclick="setPreview('full')">⬜ Full</button>
        </div>
    </div>
</div>

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
    };
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') return;
        
        switch(e.key) {
            case '1': setPreview('mobile'); buttons[0].classList.add('test-panel__btn--active'); break;
            case '2': setPreview('tablet'); buttons[1].classList.add('test-panel__btn--active'); break;
            case '3': setPreview('desktop'); buttons[2].classList.add('test-panel__btn--active'); break;
            case '4': setPreview('full'); buttons[3].classList.add('test-panel__btn--active'); break;
        }
    });
    
    console.log('%c🏆 pcz Test Environment - O Nama SPK', 'font-size: 16px; font-weight: bold; color: #FF6B00;');
    console.log('NOVI SVIJETLI DIZAJN (stil upoznajte)');
    console.log('Current scenario: <?php echo esc_js($current_scenario); ?>');
    console.log('Press 1-4 to change viewport size');
})();
</script>

</body>
</html>
