<?php
/**
 * Test Wrapper za "Poznati o PCZ-u" komponentu
 * 
 * Testira poznati/poznati.php komponentu s mock podacima
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
$current_template = $GLOBALS['pcz_current_template'] ?? 'poznati';

if ($current_scenario !== 'default' && isset($mock_data['scenarios'][$current_scenario])) {
    apply_scenario($current_scenario, $mock_data);
}

$available_scenarios = array_keys($mock_data['scenarios'] ?? ['default' => []]);

// =============================================================================
// COMPONENT CONFIGURATION
// =============================================================================

$component_name = 'Poznati o PCZ-u';
$component_path = pcz_POZNATI_PATH . '/poznati.php';
$component_css  = '/poznati/poznati.css';
$component_js   = '/poznati/poznati.js';

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

        /* Mock content */
        .test-content {
            padding: 60px 40px;
            max-width: 1000px;
            margin: 0 auto;
            background: white;
        }
        .test-content h2 { color: #333; margin-bottom: 16px; }
        .test-content p { color: #666; line-height: 1.8; }

        /* Placeholder for missing component */
        .test-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            text-align: center;
            padding: 60px 40px;
            background: linear-gradient(135deg, var(--test-accent) 0%, #a01269 100%);
            color: white;
        }
        .test-placeholder__icon { font-size: 64px; margin-bottom: 24px; }
        .test-placeholder__title { font-size: 1.5rem; margin-bottom: 12px; }
        .test-placeholder__message { opacity: 0.9; max-width: 500px; }

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
        }
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div class="test-preview" id="test-preview">
    
    <!-- Mock Content Before -->
    <div class="test-content">
        <h2>Stranica iznad sekcije</h2>
        <p>Ovo je mock sadržaj koji simulira stranicu iznad "Poznati o PCZ-u" sekcije. Sekcija ispod ima gradient koji ide u prozirno.</p>
    </div>
    
    <!-- ==================== -->
    <!-- POZNATI COMPONENT    -->
    <!-- ==================== -->
    <div class="test-component">
        <?php if (file_exists($component_path)): ?>
            <?php include $component_path; ?>
        <?php else: ?>
            <div class="test-placeholder">
                <div class="test-placeholder__icon">⭐</div>
                <h2 class="test-placeholder__title"><?php echo esc_html($component_name); ?></h2>
                <p class="test-placeholder__message">
                    Komponenta se učitava iz: <code><?php echo esc_html($component_path); ?></code>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <!-- ==================== -->
    <!-- /POZNATI COMPONENT   -->
    <!-- ==================== -->
    
    <!-- Mock Footer Contact Section (simulira pravi footer) -->
    <div class="test-footer-mock" style="background: #f5f5f5; padding: 50px 40px;">
        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <div>
                <h3 style="color: #C71585; font-size: 14px; font-weight: 700; text-transform: uppercase; margin: 0 0 12px;">RADNO VRIJEME RECEPCIJE:</h3>
                <p style="color: #333; font-size: 15px; line-height: 1.7; margin: 0;">
                    <strong>PON - PET:</strong> od 14:00 do 22:00 sata<br>
                    <strong>SUB:</strong> od 10:00 do 14:00 sati<br>
                    <strong>NED:</strong> Ne Radimo
                </p>
                <h3 style="color: #C71585; font-size: 14px; font-weight: 700; text-transform: uppercase; margin: 25px 0 12px;">NAŠA ADRESA I KONTAKTI:</h3>
                <p style="color: #333; font-size: 15px; line-height: 1.7; margin: 0;">
                    <strong>TEL:</strong> <a href="tel:+385989157443" style="color: #333;">+385 98 9157 443</a><br>
                    <strong>MAIL:</strong> <a href="mailto:info@pcz.hr" style="color: #333;">info@pcz.hr</a><br>
                    <strong>ADRESA:</strong> Ozaljska 93, Zagreb
                </p>
            </div>
            <div style="background: #ddd; border-radius: 8px; height: 200px; display: flex; align-items: center; justify-content: center; color: #666;">
                [Google Maps Placeholder]
            </div>
        </div>
    </div>
    
    <!-- Mock Dark Footer -->
    <div style="background: #3d3d3d; padding: 30px 40px; text-align: center;">
        <p style="color: rgba(255,255,255,0.7); font-size: 14px; margin: 0;">© 2025 Plesni Centar Zagreb by Nicolas. Sva prava pridržana.</p>
    </div>

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">← Natrag</a>
        <div class="test-panel__title">
            <span>Poznati</span>
            Testimonijali Test
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

<!-- Component JavaScript -->
<?php if ($component_js && file_exists(pcz_PROJECT_ROOT . $component_js)): ?>
<script src="<?php echo esc_url($component_js); ?>"></script>
<?php endif; ?>

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
        
        // Trigger resize for slider reinitialization
        window.dispatchEvent(new Event('resize'));
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
    console.log('Current template: poznati');
    console.log('Current scenario: <?php echo esc_js($current_scenario); ?>');
    console.log('Press 1-4 to change viewport size');
})();
</script>

</body>
</html>
