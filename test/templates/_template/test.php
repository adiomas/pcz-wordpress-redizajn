<?php
/**
 * Test Wrapper Template
 * 
 * UPUTE ZA KORIŠTENJE:
 * 1. Kopiraj cijeli _template folder u test/templates/ime-komponente/
 * 2. Prilagodi mock-data.php s potrebnim ACF podacima
 * 3. Izmijeni donje include da pokazuje na pravu komponentu
 * 4. Testiraj na http://localhost:8080/?template=ime-komponente
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
$current_template = $GLOBALS['pcz_current_template'] ?? basename(__DIR__);

if ($current_scenario !== 'default' && isset($mock_data['scenarios'][$current_scenario])) {
    apply_scenario($current_scenario, $mock_data);
}

// Dohvati dostupne scenarije
$available_scenarios = array_keys($mock_data['scenarios'] ?? ['default' => []]);

// =============================================================================
// COMPONENT CONFIGURATION
// =============================================================================

// TODO: Izmijeni ove varijable za svoju komponentu
$component_name = 'Nova Komponenta';  // Naziv za prikaz
$component_path = null;               // Putanja do PHP fajla komponente
$component_css  = null;               // Putanja do CSS fajla (opcionalno)
$component_js   = null;               // Putanja do JS fajla (opcionalno)

// Primjer za footer:
// $component_name = 'Footer';
// $component_path = pcz_PROJECT_ROOT . '/footer/footer.php';
// $component_css  = '/footer/footer.css';
// $component_js   = '/footer/footer.js';

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: <?php echo esc_html($component_name); ?> | pcz Test Environment</title>
    
    <?php if ($component_css): ?>
    <!-- Original Component CSS -->
    <link rel="stylesheet" href="<?php echo esc_url($component_css); ?>">
    <?php endif; ?>
    
    <!-- Test Environment Styles -->
    <style>
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

        .test-panel__link {
            color: var(--test-accent);
            text-decoration: none;
        }

        .test-panel__link:hover {
            text-decoration: underline;
        }

        /* ===== Preview Container ===== */
        .test-preview {
            min-height: calc(100vh - 80px);
            padding-bottom: 80px;
            transition: max-width 0.3s ease;
            margin: 0 auto;
        }

        .test-preview--mobile { max-width: 375px; box-shadow: 0 0 40px rgba(0, 0, 0, 0.2); }
        .test-preview--tablet { max-width: 768px; box-shadow: 0 0 40px rgba(0, 0, 0, 0.2); }
        .test-preview--desktop { max-width: 1440px; }

        .test-component {
            background: white;
        }

        /* ===== Placeholder Message ===== */
        .test-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            text-align: center;
            padding: 40px;
            background: linear-gradient(135deg, #f8f8f8 0%, #f0f0f0 100%);
        }

        .test-placeholder__icon {
            font-size: 64px;
            margin-bottom: 24px;
            opacity: 0.5;
        }

        .test-placeholder__title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 12px;
        }

        .test-placeholder__message {
            color: #666;
            max-width: 500px;
            line-height: 1.6;
        }

        .test-placeholder__code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 16px 24px;
            border-radius: 8px;
            font-family: var(--test-font-mono);
            font-size: 13px;
            margin-top: 24px;
            text-align: left;
        }

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
        }
    </style>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
</head>
<body>

<!-- Preview Container -->
<div class="test-preview" id="test-preview">
    
    <!-- ==================== -->
    <!-- COMPONENT START      -->
    <!-- ==================== -->
    <div class="test-component">
        <?php if ($component_path && file_exists($component_path)): ?>
            <?php include $component_path; ?>
        <?php else: ?>
            <!-- Placeholder dok komponenta nije konfigurirana -->
            <div class="test-placeholder">
                <div class="test-placeholder__icon">🔧</div>
                <h2 class="test-placeholder__title">Komponenta nije konfigurirana</h2>
                <p class="test-placeholder__message">
                    Uredi <code>test.php</code> i postavi putanju do PHP fajla komponente u <code>$component_path</code> varijabli.
                </p>
                <div class="test-placeholder__code">
                    <pre>// Primjer konfiguracije:
$component_name = 'Footer';
$component_path = pcz_PROJECT_ROOT . '/footer/footer.php';
$component_css  = '/footer/footer.css';
$component_js   = '/footer/footer.js';</pre>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- ==================== -->
    <!-- COMPONENT END        -->
    <!-- ==================== -->

</div>

<!-- Test Controls Panel -->
<div class="test-panel">
    <div class="test-panel__section">
        <a href="/" class="test-panel__link">← Natrag</a>
        <div class="test-panel__title">
            <span><?php echo esc_html($current_template); ?></span>
            <?php echo esc_html($component_name); ?> Test
        </div>
    </div>
    
    <div class="test-panel__section">
        <span class="test-panel__label">Scenario:</span>
        <select class="test-panel__select" onchange="window.location.href=this.value">
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
        <span class="test-panel__label">Prikaz:</span>
        <div class="test-panel__responsive">
            <button type="button" class="test-panel__btn" onclick="setPreview('mobile')">📱 Mobile</button>
            <button type="button" class="test-panel__btn" onclick="setPreview('tablet')">📱 Tablet</button>
            <button type="button" class="test-panel__btn" onclick="setPreview('desktop')">🖥️ Desktop</button>
            <button type="button" class="test-panel__btn test-panel__btn--active" onclick="setPreview('full')">⬜ Full</button>
        </div>
    </div>
</div>

<?php if ($component_js): ?>
<!-- Original Component JS -->
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
        window.dispatchEvent(new Event('resize'));
    };
    
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
    console.log('Template: <?php echo esc_js($current_template); ?>');
    console.log('Scenario: <?php echo esc_js($current_scenario); ?>');
})();
</script>

</body>
</html>

