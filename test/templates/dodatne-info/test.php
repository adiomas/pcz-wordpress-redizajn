<?php
/**
 * Test Wrapper za "Dodatne Informacije" komponentu
 * 
 * Testira dodatne-info/dodatne-info.php komponentu s mock podacima
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// =============================================================================
// BOOTSTRAP
// =============================================================================

require_once __DIR__ . '/../../core/bootstrap.php';

// =============================================================================
// SET BRAND CONTEXT - Ova sekcija je SAMO za Sportski Klub
// =============================================================================

pcz_set_current_brand('sportski-klub');

// Set visibility class for components that check it
$visibility_class = 'pcz-dodatne-info--visible';

// =============================================================================
// LOAD MOCK DATA
// =============================================================================

$mock_data = require __DIR__ . '/mock-data.php';
load_mock_data($mock_data);

// =============================================================================
// APPLY SCENARIO
// =============================================================================

$current_scenario = $GLOBALS['pcz_current_scenario'] ?? $_GET['scenario'] ?? 'default';
$current_template = $GLOBALS['pcz_current_template'] ?? 'dodatne-info';

if ($current_scenario !== 'default' && isset($mock_data['scenarios'][$current_scenario])) {
    apply_scenario($current_scenario, $mock_data);
}

$available_scenarios = array_keys($mock_data['scenarios'] ?? ['default' => []]);

// =============================================================================
// COMPONENT CONFIGURATION
// =============================================================================

$component_name = 'Dodatne Informacije';
$component_path = pcz_PROJECT_ROOT . '/dodatne-info/dodatne-info.php';
$component_css  = '/dodatne-info/dodatne-info.css';
$component_js   = null; // Nema JavaScript za ovu komponentu

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: <?php echo esc_html($component_name); ?> | pcz Test Environment</title>
    
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
            --test-accent: #FF6B00; /* Narančasta za Sportski Klub */
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

        /* Mock content before section */
        .test-mock-hero {
            background: linear-gradient(135deg, #FF6B00 0%, #ff8533 100%);
            padding: 80px 40px 60px;
            text-align: center;
        }
        .test-mock-hero h1 {
            color: white;
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0 0 16px;
        }
        .test-mock-hero p {
            color: rgba(255,255,255,0.9);
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Mock content after section */
        .test-content {
            padding: 60px 40px;
            max-width: 900px;
            margin: 0 auto;
            background: #f5f5f5;
        }
        .test-content h2 { color: #333; margin-bottom: 16px; }
        .test-content p { color: #666; line-height: 1.8; }

        /* Placeholder */
        .test-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            text-align: center;
            padding: 60px 40px;
            background: linear-gradient(135deg, var(--test-accent) 0%, #e65c00 100%);
            color: white;
        }
        .test-placeholder__icon { font-size: 64px; margin-bottom: 24px; }
        .test-placeholder__title { font-size: 1.5rem; margin-bottom: 12px; }
        .test-placeholder__message { opacity: 0.9; max-width: 500px; }

        /* Brand indicator */
        .test-brand-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--test-accent);
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.4);
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
            .test-content { padding: 30px 20px; }
            .test-mock-hero { padding: 60px 20px 40px; }
        }
        
        /* =======================================================
           PRIVREMENI OVERRIDE STILOVI - samo za testiranje!
           Kad si zadovoljan, PRIMIJENI ove stilove u:
           /dodatne-info/dodatne-info.css (bez !important)
           pa OBRIŠI ovaj style blok.
           ======================================================= */
        
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body data-brand="sportski-klub">

<!-- Brand Indicator -->
<div class="test-brand-indicator">
    🏆 Sportski Klub Brand
</div>

<div class="test-preview" id="test-preview">
    
    <!-- Mock Hero Section (before component) -->
    <div class="test-mock-hero">
        <h1>Sportski Klub</h1>
        <p>Sportski plesni klub Plesnog centra Zagreb - natjecateljski program</p>
    </div>
    
    <!-- ==================== -->
    <!-- DODATNE INFORMACIJE COMPONENT -->
    <!-- ==================== -->
    <div class="test-component">
        <?php if (file_exists($component_path)): ?>
            <?php include $component_path; ?>
        <?php else: ?>
            <div class="test-placeholder">
                <div class="test-placeholder__icon">ℹ️</div>
                <h2 class="test-placeholder__title"><?php echo esc_html($component_name); ?></h2>
                <p class="test-placeholder__message">
                    Komponenta se učitava iz: <code><?php echo esc_html($component_path); ?></code>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <!-- ==================== -->
    <!-- /DODATNE INFORMACIJE COMPONENT -->
    <!-- ==================== -->
    
    <!-- Mock Content After -->
    <div class="test-content">
        <h2>Sadržaj ispod sekcije</h2>
        <p>Ovo je mock sadržaj koji simulira ostatak stranice ispod "Dodatne Informacije" sekcije. Gradient pozadina glatko prelazi u ovu sivu pozadinu (#f5f5f5).</p>
    </div>
    
    <!-- Mock Footer -->
    <div style="background: #3d3d3d; padding: 30px 40px; text-align: center;">
        <p style="color: rgba(255,255,255,0.7); font-size: 14px; margin: 0;">© 2025 Plesni Centar Zagreb by Nicolas. Sva prava pridržana.</p>
    </div>

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">← Natrag</a>
        <div class="test-panel__title">
            <span>SPK</span>
            <?php echo esc_html($component_name); ?> Test
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
    
    console.log('%c🏆 pcz Test Environment - Dodatne Informacije', 'font-size: 16px; font-weight: bold; color: #FF6B00;');
    console.log('Current template: dodatne-info');
    console.log('Current scenario: <?php echo esc_js($current_scenario); ?>');
    console.log('Press 1-4 to change viewport size');
})();
</script>

</body>
</html>


