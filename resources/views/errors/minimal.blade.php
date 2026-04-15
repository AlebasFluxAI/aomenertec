<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — FluxAi</title>
    <style>
        /* ── Reset & Base ────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background-color: #F5F7FA;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ── Card ────────────────────────────────────────────── */
        .error-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08), 0 3px 6px rgba(0, 0, 0, 0.06);
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
        }

        /* ── Error Code ──────────────────────────────────────── */
        .error-code {
            font-family: Poppins, system-ui, -apple-system, sans-serif;
            font-size: 6rem;
            font-weight: 700;
            line-height: 1;
            color: #0044A4;
            letter-spacing: -2px;
            margin-bottom: 0.5rem;
        }

        /* ── Title ───────────────────────────────────────────── */
        .error-title {
            font-family: Poppins, system-ui, -apple-system, sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }

        /* ── Message ─────────────────────────────────────────── */
        .error-message {
            font-size: 1rem;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 2rem;
        }

        /* ── Divider ─────────────────────────────────────────── */
        .error-divider {
            width: 48px;
            height: 4px;
            background: linear-gradient(135deg, #0044A4, #0C62DC);
            border-radius: 2px;
            margin: 0 auto 1.5rem;
        }

        /* ── Button ──────────────────────────────────────────── */
        .error-btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            background-color: #0044A4;
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 600;
            border-radius: 8px;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .error-btn:hover {
            background-color: #003380;
            box-shadow: 0 4px 12px rgba(0, 68, 164, 0.35);
        }

        .error-btn:active {
            background-color: #002a66;
        }

        /* ── Brand ───────────────────────────────────────────── */
        .error-brand {
            margin-top: 2rem;
            font-size: 0.8125rem;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .error-brand strong {
            color: #0C62DC;
            font-weight: 600;
        }

        /* ── Responsive ──────────────────────────────────────── */
        @media (max-width: 480px) {
            .error-card { padding: 2rem 1.5rem; }
            .error-code { font-size: 4.5rem; }
            .error-title { font-size: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">@yield('code')</div>
        <div class="error-divider"></div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
        <a href="/" class="error-btn">Volver al inicio</a>
        <div class="error-brand">Powered by <strong>FluxAi</strong></div>
    </div>
</body>
</html>
