<?php
/**
 * pcz Brand System - Test Page
 * 
 * Testira brand switcher i theming sustav.
 * 
 * URL primjeri:
 * - http://localhost:8080/?template=brand
 * - http://localhost:8080/?template=brand&scenario=sportski_klub
 * - http://localhost:8080/?template=brand&scenario=switcher_pills
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// Bootstrap
require_once __DIR__ . '/../../core/bootstrap.php';

// Load mock data
$mock_data = require __DIR__ . '/mock-data.php';
load_mock_data($mock_data);

// Apply scenario if specified
$scenario = $GLOBALS['pcz_current_scenario'] ?? 'default';
if (isset($mock_data['scenarios'][$scenario])) {
    apply_scenario($scenario, $mock_data);
}

// Simuliraj brand iz query parametra
$current_brand = $_GET['brand'] ?? 'plesna-skola';
if (!isset($mock_data['brands'][$current_brand])) {
    $current_brand = 'plesna-skola';
}

$brand_config = $mock_data['brands'][$current_brand];
$all_brands = $mock_data['brands'];

// ACF fields
$acf = $GLOBALS['pcz_mock_data']['acf_fields'];
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand System Test | pcz Test Environment</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Open+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Brand CSS -->
    <link rel="stylesheet" href="/brand/brand.css">
    
    <!-- Component CSS -->
    <link rel="stylesheet" href="/header/mega-menu.css">
    <link rel="stylesheet" href="/hero/hero.css">
    <link rel="stylesheet" href="/poznati/poznati.css">
    <link rel="stylesheet" href="/footer/footer.css">
    
    <!-- Brand CSS Variables (inline za ovaj brand) -->
    <style id="pcz-brand-css">
    :root {
        /* Brand: <?php echo esc_html($brand_config['name']); ?> */
        --pcz-primary: <?php echo esc_html($brand_config['primary_color']); ?>;
        --pcz-primary-hover: <?php echo esc_html($brand_config['primary_hover']); ?>;
        --pcz-secondary: <?php echo esc_html($brand_config['secondary_color']); ?>;
        --pcz-accent: <?php echo esc_html($brand_config['accent_color']); ?>;
        --pcz-gradient: <?php echo esc_html($brand_config['gradient']); ?>;
    }
    
    /* Test page specific */
    .test-page {
        font-family: 'Open Sans', sans-serif;
    }
    
    .test-header {
        background: var(--pcz-dark, #3d3d3d);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .test-header__title {
        font-size: 14px;
        font-weight: 600;
        opacity: 0.8;
    }
    
    .test-header__badge {
        background: var(--pcz-primary);
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 700;
    }
    
    .test-section {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .test-section__title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--pcz-darker, #333);
    }
    
    .test-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    
    .test-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .test-card__title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--pcz-primary);
    }
    
    .color-swatch {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }
    
    .color-swatch__box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid rgba(0,0,0,0.1);
    }
    
    .color-swatch__label {
        font-size: 13px;
    }
    
    .color-swatch__value {
        font-family: monospace;
        font-size: 12px;
        color: #666;
    }
    
    /* Divider */
    .test-divider {
        height: 1px;
        background: #e0e0e0;
        margin: 40px 0;
    }
    
    /* Scenario badges */
    .scenario-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 20px;
    }
    
    .scenario-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #f0f0f0;
        border-radius: 20px;
        font-size: 12px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s;
    }
    
    .scenario-badge:hover {
        background: var(--pcz-primary);
        color: white;
    }
    
    .scenario-badge.is-active {
        background: var(--pcz-primary);
        color: white;
    }
    </style>
</head>
<body class="test-page pcz-brand-<?php echo esc_attr($current_brand); ?>" data-brand="<?php echo esc_attr($current_brand); ?>">

<!-- Test Header -->
<div class="test-header">
    <div class="test-header__title">🧪 pcz Test Environment / Brand System</div>
    <div class="test-header__badge"><?php echo esc_html($brand_config['name']); ?></div>
</div>

<!-- Brand Switcher Demo -->
<section style="background: var(--pcz-gradient); padding: 60px 20px; text-align: center;">
    <h2 style="color: white; font-size: 28px; margin-bottom: 30px;">Brand Switcher Demo</h2>
    
    <!-- Tabs Style -->
    <div class="pcz-brand-switcher pcz-brand-switcher--tabs pcz-brand-switcher--animated" 
         style="justify-content: center; max-width: 500px; margin: 0 auto;"
         role="tablist">
        <?php foreach ($all_brands as $brand_id => $brand): 
            $is_active = ($brand_id === $current_brand);
            $brand_url = '?template=brand&brand=' . $brand_id;
        ?>
        <a href="<?php echo esc_url($brand_url); ?>"
           class="pcz-brand-switcher__tab <?php echo $is_active ? 'is-active' : ''; ?>"
           role="tab"
           aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
           data-brand="<?php echo esc_attr($brand_id); ?>"
           style="--tab-color: <?php echo esc_attr($brand['primary_color']); ?>;">
            <span class="pcz-brand-switcher__label"><?php echo esc_html(strtoupper($brand['name'])); ?></span>
        </a>
        <?php endforeach; ?>
        <span class="pcz-brand-switcher__indicator" aria-hidden="true"></span>
    </div>
    
    <p style="color: rgba(255,255,255,0.8); margin-top: 30px; font-size: 14px;">
        👆 Klikni za promjenu branda (page reload)
    </p>
</section>

<!-- Color Palette Section -->
<section class="test-section">
    <h2 class="test-section__title">Color Palette: <?php echo esc_html($brand_config['name']); ?></h2>
    
    <div class="test-grid">
        <div class="test-card">
            <h3 class="test-card__title">Brand Colors</h3>
            
            <div class="color-swatch">
                <div class="color-swatch__box" style="background: var(--pcz-primary);"></div>
                <div>
                    <div class="color-swatch__label">Primary</div>
                    <div class="color-swatch__value"><?php echo esc_html($brand_config['primary_color']); ?></div>
                </div>
            </div>
            
            <div class="color-swatch">
                <div class="color-swatch__box" style="background: var(--pcz-primary-hover);"></div>
                <div>
                    <div class="color-swatch__label">Primary Hover</div>
                    <div class="color-swatch__value"><?php echo esc_html($brand_config['primary_hover']); ?></div>
                </div>
            </div>
            
            <div class="color-swatch">
                <div class="color-swatch__box" style="background: var(--pcz-secondary);"></div>
                <div>
                    <div class="color-swatch__label">Secondary</div>
                    <div class="color-swatch__value"><?php echo esc_html($brand_config['secondary_color']); ?></div>
                </div>
            </div>
            
            <div class="color-swatch">
                <div class="color-swatch__box" style="background: var(--pcz-accent);"></div>
                <div>
                    <div class="color-swatch__label">Accent</div>
                    <div class="color-swatch__value"><?php echo esc_html($brand_config['accent_color']); ?></div>
                </div>
            </div>
        </div>
        
        <div class="test-card">
            <h3 class="test-card__title">Gradient</h3>
            <div style="height: 100px; border-radius: 8px; background: var(--pcz-gradient);"></div>
            <p style="font-size: 12px; color: #666; margin-top: 12px; font-family: monospace;">
                <?php echo esc_html($brand_config['gradient']); ?>
            </p>
        </div>
        
        <div class="test-card">
            <h3 class="test-card__title">UI Elements</h3>
            
            <button style="background: var(--pcz-primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; margin-bottom: 12px; display: block; width: 100%;">
                Primary Button
            </button>
            
            <button style="background: transparent; color: var(--pcz-primary); border: 2px solid var(--pcz-primary); padding: 10px 22px; border-radius: 8px; font-weight: 600; cursor: pointer; display: block; width: 100%;">
                Secondary Button
            </button>
            
            <div style="margin-top: 16px; padding: 12px; background: rgba(0,0,0,0.05); border-left: 4px solid var(--pcz-primary); border-radius: 0 4px 4px 0;">
                <span style="color: var(--pcz-primary); font-weight: 600;">Info Box</span>
                <p style="font-size: 13px; margin: 4px 0 0;">Ovo je primjer info boxa s brand bojom.</p>
            </div>
        </div>
    </div>
</section>

<div class="test-divider"></div>

<!-- Switcher Styles -->
<section class="test-section">
    <h2 class="test-section__title">Switcher Stilovi</h2>
    
    <div class="test-grid">
        <!-- Tabs -->
        <div class="test-card">
            <h3 class="test-card__title">Tabs (Default)</h3>
            <div class="pcz-brand-switcher pcz-brand-switcher--tabs" style="margin-top: 20px;">
                <a href="#" class="pcz-brand-switcher__tab is-active" style="--tab-color: #C71585;">
                    <span class="pcz-brand-switcher__label">PLESNA ŠKOLA</span>
                </a>
                <a href="#" class="pcz-brand-switcher__tab" style="--tab-color: #FF6B00;">
                    <span class="pcz-brand-switcher__label">SPORTSKI KLUB</span>
                </a>
            </div>
        </div>
        
        <!-- Pills -->
        <div class="test-card">
            <h3 class="test-card__title">Pills</h3>
            <div class="pcz-brand-switcher pcz-brand-switcher--pills" style="margin-top: 20px;">
                <a href="#" class="pcz-brand-switcher__tab is-active" style="--tab-color: #C71585;">
                    <span class="pcz-brand-switcher__label">PLESNA ŠKOLA</span>
                </a>
                <a href="#" class="pcz-brand-switcher__tab" style="--tab-color: #FF6B00;">
                    <span class="pcz-brand-switcher__label">SPORTSKI KLUB</span>
                </a>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="test-card">
            <h3 class="test-card__title">Buttons</h3>
            <div class="pcz-brand-switcher pcz-brand-switcher--buttons" style="margin-top: 20px;">
                <a href="#" class="pcz-brand-switcher__tab is-active" style="--tab-color: #C71585;">
                    <span class="pcz-brand-switcher__label">PLESNA ŠKOLA</span>
                </a>
                <a href="#" class="pcz-brand-switcher__tab" style="--tab-color: #FF6B00;">
                    <span class="pcz-brand-switcher__label">SPORTSKI KLUB</span>
                </a>
            </div>
        </div>
    </div>
</section>

<div class="test-divider"></div>

<!-- Test Scenarios -->
<section class="test-section">
    <h2 class="test-section__title">Testiraj Scenarije</h2>
    
    <div class="scenario-list">
        <?php 
        $scenarios = array_keys($mock_data['scenarios']);
        foreach ($scenarios as $scn): 
            $is_active = ($scn === $scenario);
            $url = '?template=brand&scenario=' . $scn . '&brand=' . $current_brand;
        ?>
        <a href="<?php echo esc_url($url); ?>" 
           class="scenario-badge <?php echo $is_active ? 'is-active' : ''; ?>">
            <?php echo esc_html($scn); ?>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<div class="test-divider"></div>

<!-- Live Components Preview -->
<section class="test-section">
    <h2 class="test-section__title">Live Component Preview</h2>
    <p style="color: #666; margin-bottom: 20px;">
        Komponente ispod koriste CSS varijable i automatski se prilagođavaju aktivnom brandu.
    </p>
    
    <div style="display: flex; gap: 12px; margin-bottom: 30px;">
        <a href="?template=header&brand=<?php echo esc_attr($current_brand); ?>" 
           style="padding: 10px 20px; background: var(--pcz-primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Header Test →
        </a>
        <a href="?template=hero&brand=<?php echo esc_attr($current_brand); ?>" 
           style="padding: 10px 20px; background: var(--pcz-primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Hero Test →
        </a>
        <a href="?template=poznati&brand=<?php echo esc_attr($current_brand); ?>" 
           style="padding: 10px 20px; background: var(--pcz-primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Poznati Test →
        </a>
    </div>
</section>

<!-- Brand JSON Data (za JavaScript) -->
<script id="pcz-brand-data" type="application/json">
<?php echo json_encode([
    'current' => $current_brand,
    'brands'  => $all_brands,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
</script>

<!-- Brand JavaScript -->
<script src="/brand/brand.js"></script>

<!-- Back to home -->
<div style="text-align: center; padding: 40px;">
    <a href="/" style="color: var(--pcz-primary); font-weight: 600;">← Natrag na početnu</a>
</div>

</body>
</html>

