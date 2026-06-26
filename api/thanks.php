<?php
$fields = [
    'project_interest' => 'Project',
    'start_discussing' => 'Start discussing',
    'estimated_budget' => 'Estimated budget',
    'full_name' => 'Full name',
    'phone' => 'Phone',
    'email' => 'Email',
];

function raw_field_value($name) {
    $value = $_GET[$name] ?? '';

    if (is_array($value)) {
        $value = implode(', ', $value);
    }

    $value = trim((string) $value);
    $value = preg_replace('/\s+/', ' ', $value);

    return substr($value, 0, 1000);
}

function field_value($name) {
    return htmlspecialchars(raw_field_value($name), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You</title>
    <link rel="preload" href="fonts/Montserrat_400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="fonts/Montserrat_700.woff2" as="font" type="font/woff2" crossorigin>
    <?php
        $googleAdsId = preg_replace('/[^0-9]/', '', raw_field_value('utm_campaign')) ?: '18055916293';
        $googleAdsLabel = preg_replace('/[^A-Za-z0-9_-]/', '', raw_field_value('utm_medium')) ?: '5U14CJu5lrgcEIXW3aFD';
        $googleAdsConfigId = 'AW-' . $googleAdsId;
        $googleAdsSendTo = $googleAdsConfigId . '/' . $googleAdsLabel;
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($googleAdsConfigId, ENT_QUOTES, 'UTF-8'); ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', <?php echo json_encode($googleAdsConfigId); ?>);
    </script>
    <script>
      gtag('event', 'conversion', {
          'send_to': <?php echo json_encode($googleAdsSendTo); ?>,
          'value': 1.0,
          'currency': 'USD'
      });
    </script>
    <style>
        @font-face {
            font-family: Montserrat;
            src: url("fonts/Montserrat_400.woff2") format("woff2");
            font-weight: 400;
        }

        @font-face {
            font-family: Montserrat;
            src: url("fonts/Montserrat_700.woff2") format("woff2");
            font-weight: 700;
        }

        :root {
            --primary: #fefefe;
            --secondary: #102d43;
            --accent: #36b149;
            --accent-dark: #004b00;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background:
                radial-gradient(circle at 10% 15%, rgba(54,177,73,.22), transparent 28rem),
                linear-gradient(180deg, #f7fbf6 0%, #ffffff 44%, #102d43 44%, #102d43 100%);
            color: var(--secondary);
            font-family: Montserrat, Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        .thanks {
            margin: 0 auto;
            max-width: 980px;
            padding: clamp(2rem, 6vw, 5rem) 1rem;
        }

        .thanks__panel {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbf6 100%);
            border: 1px solid rgba(54,177,73,.22);
            border-radius: 1rem;
            box-shadow: 0 1.5rem 3.5rem rgba(16,45,67,.18);
            overflow: hidden;
        }

        .thanks__bar {
            background: linear-gradient(90deg, var(--accent), var(--accent-dark));
            height: .4rem;
        }

        .thanks__content {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1.1fr .9fr;
            padding: clamp(1.25rem, 4vw, 2.5rem);
        }

        .thanks__eyebrow {
            color: var(--accent-dark);
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin: 0 0 .75rem;
            text-transform: uppercase;
        }

        h1 {
            font-size: clamp(2rem, 7vw, 4rem);
            line-height: 1;
            margin: 0 0 1rem;
        }

        p {
            color: rgba(16,45,67,.78);
            font-size: 1.05rem;
            line-height: 1.65;
            margin: 0;
        }

        .thanks__steps {
            display: grid;
            gap: .75rem;
            margin: 1.5rem 0 0;
            padding: 0;
        }

        .thanks__steps li {
            align-items: center;
            display: flex;
            gap: .65rem;
            list-style: none;
        }

        .thanks__steps li::before {
            background: var(--accent);
            border-radius: 999px;
            color: #fff;
            content: "✓";
            display: inline-grid;
            font-size: .75rem;
            font-weight: 700;
            height: 1.35rem;
            place-items: center;
            width: 1.35rem;
        }

        .thanks__summary {
            background: #fff;
            border: 1px solid rgba(16,45,67,.1);
            border-radius: .85rem;
            padding: 1.25rem;
        }

        .thanks__summary h2 {
            font-size: 1.1rem;
            margin: 0 0 1rem;
        }

        dl {
            display: grid;
            gap: .8rem;
            margin: 0;
        }

        dt {
            color: rgba(16,45,67,.62);
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        dd {
            border-bottom: 1px solid #e4e4e7;
            margin: -.55rem 0 0;
            padding-bottom: .75rem;
        }

        .thanks__actions {
            margin-top: 1.5rem;
        }

        .thanks__button {
            align-items: center;
            background: var(--accent);
            border-radius: 999px;
            color: #fff;
            display: inline-flex;
            font-weight: 700;
            justify-content: center;
            padding: .9rem 1.25rem;
            text-decoration: none;
        }

        .thanks__footer {
            color: rgba(255,255,255,.78);
            font-size: .9rem;
            padding: 1.25rem 1rem 0;
            text-align: center;
        }

        @media (max-width: 760px) {
            body {
                background:
                    radial-gradient(circle at 10% 5%, rgba(54,177,73,.2), transparent 18rem),
                    linear-gradient(180deg, #f7fbf6 0%, #ffffff 58%, #102d43 58%, #102d43 100%);
            }

            .thanks__content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="thanks">
        <section class="thanks__panel">
            <div class="thanks__bar"></div>
            <div class="thanks__content">
                <div>
                    <p class="thanks__eyebrow">Request received</p>
                    <h1>Thank you.</h1>
                    <p>We received your bathroom remodel request for Houston and the surrounding areas. Our team will review your answers and contact you soon to discuss the next step.</p>
                    <ul class="thanks__steps">
                        <li>Your project details were sent successfully.</li>
                        <li>We will use your answers to prepare a focused follow-up.</li>
                        <li>You can expect a practical, no-pressure conversation.</li>
                    </ul>
                    <div class="thanks__actions">
                        <a class="thanks__button" href="./">Back to home</a>
                    </div>
                </div>
                <aside class="thanks__summary">
                    <h2>Your submitted details</h2>
                    <dl>
                        <?php foreach ($fields as $name => $label) : ?>
                            <?php if (field_value($name) !== '') : ?>
                                <dt><?php echo $label; ?></dt>
                                <dd><?php echo field_value($name); ?></dd>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </dl>
                </aside>
            </div>
        </section>
        <div class="thanks__footer">2026 - All rights reserved</div>
    </main>
</body>
</html>
