<?php
/**
 * Test Wrapper za Footer komponentu
 * 
 * Testira footer/footer.php komponentu s mock podacima
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
$current_template = $GLOBALS['pcz_current_template'] ?? 'footer';

if ($current_scenario !== 'default' && isset($mock_data['scenarios'][$current_scenario])) {
    apply_scenario($current_scenario, $mock_data);
}

$available_scenarios = array_keys($mock_data['scenarios'] ?? ['default' => []]);

// =============================================================================
// COMPONENT CONFIGURATION
// =============================================================================

$component_name = 'Footer';
$component_path = pcz_FOOTER_PATH . '/footer.php';

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: Footer | pcz Test Environment</title>
    
    <!-- ⚠️ PRODUKCIJSKI CSS - ne dupliciraj! -->
    <link rel="stylesheet" href="/footer/footer.css">
    
    <!-- Test Environment Styles ONLY -->
    <style>
        /* ==========================================================================
           TEST ENVIRONMENT STYLES
           
           NAPOMENA: Ovdje idu SAMO stilovi za test okruženje!
           Svi footer stilovi su u /footer/footer.css
           
           Ako trebaš testirati nove stilove:
           1. Dodaj ih ovdje s !important
           2. Kad si zadovoljan, premjesti ih u /footer/footer.css (bez !important)
           3. Obriši ih odavde
           ========================================================================== */
        :root {
            --test-bg: #1a1a24;
            --test-panel-bg: #22222e;
            --test-border: #333344;
            --test-accent: #C71585;
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
        .test-panel__label { color: var(--test-text-muted); font-size: 12px; text-transform: uppercase; }
        .test-panel__title { color: var(--test-text); font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .test-panel__title span { background: var(--test-accent); color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; }
        .test-panel__select { background: var(--test-bg); border: 1px solid var(--test-border); color: var(--test-text); padding: 8px 12px; border-radius: 6px; font-size: 13px; min-width: 160px; }
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

        /* Mock content above footer */
        .test-content {
            padding: 60px 40px;
            max-width: 1000px;
            margin: 0 auto;
            min-height: 40vh;
            background: white;
        }
        .test-content h1 { color: #333; margin-bottom: 20px; }
        .test-content p { color: #666; line-height: 1.8; margin-bottom: 16px; }

        /* Placeholder for missing component */
        .test-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            text-align: center;
            padding: 60px 40px;
            background: linear-gradient(135deg, #3d3d3d 0%, #2d2d2d 100%);
            color: white;
        }
        .test-placeholder__icon { font-size: 64px; margin-bottom: 24px; }
        .test-placeholder__title { font-size: 1.5rem; margin-bottom: 12px; }
        .test-placeholder__message { color: #aaa; max-width: 500px; }
        .test-placeholder__status {
            display: inline-block;
            background: rgba(76, 175, 80, 0.2);
            color: #4caf50;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 20px;
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
            .test-content { padding: 30px 20px; min-height: 30vh; }
        }
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="test-preview" id="test-preview">
    
    <!-- Mock Page Content -->
    <div class="test-content">
        <h1>Primjer stranice</h1>
        <p>Ovo je mock sadržaj koji simulira stranicu iznad footer-a. Footer komponenta se prikazuje ispod ovog sadržaja.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
    
    <!-- ==================== -->
    <!-- FOOTER COMPONENT     -->
    <!-- ==================== -->
    <div class="test-component">
        <?php if ($component_path && file_exists($component_path)): ?>
            <?php include $component_path; ?>
        <?php else: ?>
            <div class="test-placeholder">
                <div class="test-placeholder__icon">🦶</div>
                <h2 class="test-placeholder__title">Footer Komponenta</h2>
                <p class="test-placeholder__message">
                    Footer komponenta se učitava iz: <code><?php echo esc_html($component_path ?? 'N/A'); ?></code>
                </p>
                <span class="test-placeholder__status">❌ Komponenta nije pronađena</span>
            </div>
        <?php endif; ?>
    </div>
    <!-- ==================== -->
    <!-- /FOOTER COMPONENT    -->
    <!-- ==================== -->

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">← Natrag</a>
        <div class="test-panel__title">
            <span>Footer</span>
            Test
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
    
    console.log('%c🧪 pcz Test Environment', 'font-size: 16px; font-weight: bold; color: #C71585;');
    console.log('Current template: footer');
    console.log('Current scenario: <?php echo esc_js($current_scenario); ?>');
    console.log('Press 1-4 to change viewport size');
})();
</script>

</body>
</html>
