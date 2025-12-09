<?php
/**
 * pcz Test Environment - Router / Launcher
 * 
 * Glavni entry point za testiranje komponenti.
 * 
 * Korištenje:
 * - http://localhost:8080/                     → Landing page s listom template-a
 * - http://localhost:8080/?template=header     → Testira header komponentu
 * - http://localhost:8080/?template=header&scenario=no_logo → Testira bez loga
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// =============================================================================
// BOOTSTRAP
// =============================================================================

require_once __DIR__ . '/core/bootstrap.php';

// =============================================================================
// ROUTING
// =============================================================================

$template = $_GET['template'] ?? null;
$scenario = $_GET['scenario'] ?? 'default';

// Ako je template specificiran, preusmjeri na njegov test.php
if ($template) {
    $template = preg_replace('/[^a-z0-9_-]/i', '', $template); // Sanitize
    $test_file = pcz_TEST_TEMPLATES . '/' . $template . '/test.php';
    
    if (file_exists($test_file)) {
        // Proslijedi scenario u globalnu varijablu
        $GLOBALS['pcz_current_scenario'] = $scenario;
        $GLOBALS['pcz_current_template'] = $template;
        
        require $test_file;
        exit;
    }
    
    // Template nije pronađen
    $error = "Template '{$template}' nije pronađen.";
}

// =============================================================================
// DISCOVER TEMPLATES
// =============================================================================

$templates = discover_templates();

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pcz Test Environment</title>
    <style>
        /* ===== CSS Variables ===== */
        :root {
            --bg-primary: #0f0f14;
            --bg-secondary: #1a1a24;
            --bg-card: #22222e;
            --bg-card-hover: #2a2a3a;
            --accent-primary: #C71585;
            --accent-secondary: #ff6b9d;
            --accent-yellow: #ffc107;
            --text-primary: #f0f0f5;
            --text-secondary: #9898a8;
            --text-muted: #6868786;
            --success: #4caf50;
            --warning: #ff9800;
            --error: #f44336;
            --border-color: #333344;
            --font-mono: 'JetBrains Mono', 'Fira Code', 'SF Mono', monospace;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --radius: 12px;
            --radius-sm: 6px;
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            --transition: 0.2s ease;
        }

        /* ===== Reset ===== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ===== Base ===== */
        body {
            font-family: var(--font-sans);
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ===== Background Pattern ===== */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(199, 21, 133, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 193, 7, 0.06) 0%, transparent 50%),
                linear-gradient(135deg, var(--bg-primary) 0%, #12121a 100%);
            z-index: -1;
        }

        /* ===== Container ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        /* ===== Header ===== */
        .header {
            text-align: center;
            margin-bottom: 60px;
            padding-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
        }

        .header__logo {
            display: inline-flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .header__icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .header__title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--text-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header__subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ===== Error Alert ===== */
        .alert {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid var(--error);
            border-radius: var(--radius);
            padding: 16px 20px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert__icon {
            font-size: 24px;
        }

        /* ===== Section ===== */
        .section {
            margin-bottom: 48px;
        }

        .section__title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section__title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: var(--accent-primary);
            border-radius: 2px;
        }

        /* ===== Template Grid ===== */
        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        /* ===== Template Card ===== */
        .template-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 24px;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .template-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-yellow));
            opacity: 0;
            transition: opacity var(--transition);
        }

        .template-card:hover {
            background: var(--bg-card-hover);
            border-color: var(--accent-primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .template-card:hover::before {
            opacity: 1;
        }

        .template-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .template-card__name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .template-card__status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .template-card__status--ready {
            background: rgba(76, 175, 80, 0.15);
            color: var(--success);
        }

        .template-card__status--planned {
            background: rgba(255, 152, 0, 0.15);
            color: var(--warning);
        }

        .template-card__status--incomplete {
            background: rgba(244, 67, 54, 0.15);
            color: var(--error);
        }

        .template-card__status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .template-card__description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 20px;
            min-height: 44px;
        }

        /* ===== Scenarios ===== */
        .template-card__scenarios {
            margin-bottom: 20px;
        }

        .template-card__scenarios-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .template-card__scenarios-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .scenario-tag {
            display: inline-block;
            padding: 4px 10px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-family: var(--font-mono);
            text-decoration: none;
            transition: all var(--transition);
        }

        .scenario-tag:hover {
            background: var(--accent-primary);
            border-color: var(--accent-primary);
            color: white;
        }

        /* ===== Actions ===== */
        .template-card__actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all var(--transition);
            border: none;
        }

        .btn--primary {
            background: var(--accent-primary);
            color: white;
            flex: 1;
        }

        .btn--primary:hover {
            background: #a01269;
            transform: scale(1.02);
        }

        .btn--secondary {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        .btn--secondary:hover {
            border-color: var(--accent-primary);
            color: var(--accent-primary);
        }

        .btn--disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* ===== Quick Start ===== */
        .quickstart {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 24px;
        }

        .quickstart__title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--accent-yellow);
        }

        .quickstart__code {
            background: var(--bg-primary);
            border-radius: var(--radius-sm);
            padding: 16px;
            font-family: var(--font-mono);
            font-size: 0.85rem;
            overflow-x: auto;
        }

        .quickstart__code pre {
            margin: 0;
        }

        .quickstart__code .comment {
            color: #6a9955;
        }

        .quickstart__code .command {
            color: var(--accent-secondary);
        }

        .quickstart__code .url {
            color: var(--accent-yellow);
        }

        /* ===== Footer ===== */
        .footer {
            margin-top: 60px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .footer a {
            color: var(--accent-primary);
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .container {
                padding: 24px 16px;
            }

            .header__title {
                font-size: 1.75rem;
            }

            .templates-grid {
                grid-template-columns: 1fr;
            }

            .template-card__actions {
                flex-direction: column;
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    
    <!-- Header -->
    <header class="header">
        <div class="header__logo">
            <div class="header__icon">🧪</div>
            <h1 class="header__title">pcz Test Environment</h1>
        </div>
        <p class="header__subtitle">
            Modularno okruženje za testiranje WordPress/Oxygen template-a bez potrebe za instaliranim WordPressom.
        </p>
    </header>

    <!-- Error Alert -->
    <?php if (isset($error)): ?>
    <div class="alert">
        <span class="alert__icon">⚠️</span>
        <span><?php echo esc_html($error); ?></span>
    </div>
    <?php endif; ?>

    <!-- Templates Section -->
    <section class="section">
        <h2 class="section__title">Dostupni Template-i</h2>
        
        <div class="templates-grid">
            <?php foreach ($templates as $id => $tpl): ?>
            <div class="template-card">
                <div class="template-card__header">
                    <h3 class="template-card__name"><?php echo esc_html($tpl['name']); ?></h3>
                    <span class="template-card__status template-card__status--<?php echo esc_attr($tpl['status']); ?>">
                        <span class="template-card__status-dot"></span>
                        <?php 
                        $status_labels = [
                            'ready' => 'Spremno',
                            'planned' => 'Planirano',
                            'incomplete' => 'Nepotpuno',
                        ];
                        echo esc_html($status_labels[$tpl['status']] ?? $tpl['status']); 
                        ?>
                    </span>
                </div>
                
                <p class="template-card__description">
                    <?php echo esc_html($tpl['description'] ?: 'Opis nije dostupan.'); ?>
                </p>
                
                <?php if (!empty($tpl['scenarios'])): ?>
                <div class="template-card__scenarios">
                    <div class="template-card__scenarios-label">Scenariji:</div>
                    <div class="template-card__scenarios-list">
                        <?php foreach ($tpl['scenarios'] as $scn): ?>
                        <a href="<?php echo esc_url(get_test_url($id, $scn)); ?>" class="scenario-tag">
                            <?php echo esc_html($scn); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="template-card__actions">
                    <?php if ($tpl['status'] === 'ready'): ?>
                    <a href="<?php echo esc_url(get_test_url($id)); ?>" class="btn btn--primary">
                        <span>▶</span> Pokreni Test
                    </a>
                    <?php else: ?>
                    <span class="btn btn--primary btn--disabled">
                        <span>🔒</span> Nije Dostupno
                    </span>
                    <?php endif; ?>
                    
                    <a href="/templates/<?php echo esc_attr($id); ?>/" class="btn btn--secondary" title="Otvori folder">
                        📁
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($templates)): ?>
            <div class="template-card">
                <p class="template-card__description">
                    Nema dostupnih template-a. Kreiraj novi template u <code>test/templates/</code> folderu.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Quick Start -->
    <section class="section">
        <h2 class="section__title">Brzi Početak</h2>
        
        <div class="quickstart">
            <h3 class="quickstart__title">📦 Pokretanje Test Servera</h3>
            <div class="quickstart__code">
<pre><span class="comment"># Pokreni PHP development server iz test/ direktorija</span>
<span class="command">cd</span> pcz-redizajn/test
<span class="command">php</span> -S localhost:8080

<span class="comment"># Otvori u browseru:</span>
<span class="url">http://localhost:8080</span>                    <span class="comment"># Landing page</span>
<span class="url">http://localhost:8080/?template=header</span>  <span class="comment"># Header test</span>
</pre>
            </div>
        </div>
        
        <div class="quickstart" style="margin-top: 16px;">
            <h3 class="quickstart__title">➕ Kreiranje Novog Template-a</h3>
            <div class="quickstart__code">
<pre><span class="comment"># 1. Kopiraj template placeholder</span>
<span class="command">cp</span> -r test/templates/_template test/templates/nova-komponenta

<span class="comment"># 2. Prilagodi mock-data.php s ACF podacima</span>
<span class="comment"># 3. Prilagodi test.php da uključuje pravi PHP template</span>
<span class="comment"># 4. Testiraj na:</span>
<span class="url">http://localhost:8080/?template=nova-komponenta</span>
</pre>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>
            pcz Test Environment v1.0.0 &bull; 
            <a href="https://github.com/plesni-centar-zagreb" target="_blank">GitHub</a> &bull;
            Izrađeno za Plesni Centar Zagreb
        </p>
    </footer>

</div>

</body>
</html>



