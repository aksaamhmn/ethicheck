<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ethicheck</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
        <style>
            :root {
                --bg-window: #d9d9db;
                --bg-card: #e8f6cc; /* soft green */
                --blue: #2d5fbf;
                --blue-dark: #224c99;
                --text: #1b4a7a;
                --ink: #2f5aa0;
                --white: #fff;
                --shadow: 0 8px 20px rgba(0,0,0,.08);
                --radius: 18px;
            }

            * { box-sizing: border-box; }
            html, body { height: 100%; }
            body {
                margin: 0;
                background: var(--bg-window);
                display: grid;
                place-items: center;
                font-family: Poppins, system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
                color: var(--text);
            }

            .window {
                width: min(1200px, 96vw);
                background: var(--bg-card);
                border-radius: var(--radius);
                position: relative;
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            /* traffic lights */
            .win-bar {
                height: 36px;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 0 14px;
                background: #efefef;
            }
            .dot { width: 12px; height: 12px; border-radius: 50%; }
            .dot.red { background: #ff5f56; }
            .dot.yellow { background: #ffbd2e; }
            .dot.green { background: #27c93f; }

            .tabs {
                position: absolute;
                top: 22px;
                left: 80px;
                display: flex;
                gap: 12px;
            }
            .tab {
                background: #e6ecff;
                color: #2f53a3;
                padding: 10px 18px;
                border-radius: 12px 12px 0 0;
                box-shadow: 0 2px 0 rgba(0,0,0,.05) inset;
                font-weight: 600;
                letter-spacing: .3px;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }
            .tab.active { background: #cfe0ff; }

            .wrap {
                padding: 28px 28px 36px;
            }

            .grid {
                display: grid;
                grid-template-columns: 1fr 1.1fr;
                gap: 32px;
                align-items: start;
            }
            @media (max-width: 900px) {
                .grid { grid-template-columns: 1fr; }
                .tabs { position: static; padding: 8px 14px 0; }
                .wrap { padding-top: 12px; }
            }

            .headline {
                font-size: clamp(28px, 5vw, 58px);
                line-height: 1.05;
                color: #2f64b3;
                margin: 18px 0 18px;
            }
            .headline span { color: #2e66cc; }

            .hero-logo { 
                margin-top: 16px;
                width: clamp(180px, 26vw, 320px);
                max-width: 340px;
                display: block;
                border-radius: 16px;
            }

            .panel {
                background: rgba(255,255,255,.85);
                border: 2px solid rgba(46, 102, 204, 0.2);
                border-radius: 16px;
                padding: 18px;
                box-shadow: var(--shadow);
            }
            .section-title {
                font-size: clamp(20px, 2.6vw, 36px);
                font-weight: 700;
                color: var(--ink);
                display: flex; align-items: center; gap: 8px;
                margin: 4px 0 12px;
            }
            .section-title .glass { font-size: 28px; }

            .checker { display: grid; gap: 10px; }
            textarea {
                width: 100%; min-height: 180px; resize: vertical;
                padding: 14px 16px; border-radius: 12px; border: 2px solid #b9d0ff; outline: none; font-size: 16px; line-height: 1.5; background: #fff;
                box-shadow: inset 0 1px 0 rgba(0,0,0,.03);
            }
            textarea::placeholder { color: #7aa0d6; }

            .btn {
                justify-self: end;
                padding: 10px 18px; border-radius: 24px; border: 0; cursor: pointer;
                background: var(--blue); color: var(--white); font-weight: 700;
                box-shadow: 0 6px 0 var(--blue-dark);
                transform: translateY(0);
                font-family: 'Patrick Hand', cursive;
                font-size: 20px;
                transition: transform .05s ease, box-shadow .05s ease, background .2s ease;
            }
            .btn:hover { background: #2b63cc; }
            .btn:active { transform: translateY(2px); box-shadow: 0 4px 0 var(--blue-dark); }

            .results-title {
                margin: 18px 0 8px; font-size: clamp(20px, 2.2vw, 34px); color: var(--ink); font-weight: 700;
            }
            .results-box {
                min-height: 130px; border-radius: 14px; background: #fff; border: 2px solid #b9d0ff; padding: 14px; box-shadow: inset 0 1px 0 rgba(0,0,0,.03);
            }

            footer.note { text-align: center; font-size: 12px; color: #4a6ba7; margin-top: 10px; opacity: .8; }
        </style>
        <script>
            // demo-only: keep form on page and show mock result
            addEventListener('DOMContentLoaded', () => {
                const form = document.querySelector('.checker');
                const textarea = document.querySelector('textarea');
                const out = document.querySelector('.results-box');
                form?.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const text = textarea.value.trim();
                    out.textContent = text ? `Preview: ${text.substring(0, 180)}${text.length>180 ? '…' : ''}` : 'No text submitted yet.';
                });
            });
        </script>
    </head>
    <body>
        <div class="window">
            <div class="win-bar">
                <span class="dot red"></span>
                <span class="dot yellow"></span>
                <span class="dot green"></span>
            </div>

            <div class="tabs">
                <a href="{{ route('ethicheck') }}" class="tab {{ request()->routeIs('ethicheck') ? 'active' : '' }}">Ethicheck</a>
                <a href="{{ route('sikata') }}" class="tab {{ request()->routeIs('sikata') ? 'active' : '' }}">SIKATA</a>
                <a href="{{ route('etipad') }}" class="tab {{ request()->routeIs('etipad') ? 'active' : '' }}">Etipad</a>
            </div>

            <div class="wrap">
                <div class="grid">
                    <section>
                        <h1 class="headline">Welcome to<br><span>Ethicheck!</span></h1>
                        <img src="{{ asset('images/Ethiceck-logo.png') }}" alt="Ethicheck logo" class="hero-logo">
                    </section>
                    <section>
                        <div class="panel">
                            <h2 class="section-title">Check the news here! <span class="glass">🔎</span></h2>
                            <form class="checker">
                                <textarea placeholder="write your text here..."></textarea>
                                <button class="btn" type="submit">Submit here</button>
                            </form>
                        </div>
                        <h3 class="results-title">Results are here...</h3>
                        <div class="results-box"></div>
                        <footer class="note">UI only • You can wire this form to your analyzer endpoint later.</footer>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
