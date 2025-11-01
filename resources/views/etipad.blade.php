<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Etipad</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
        <style>
            :root { --bg-window:#d9d9db; --bg-card:#e8f6cc; --blue:#2d5fbf; --blue-dark:#224c99; --ink:#2f5aa0; --white:#fff; --shadow:0 8px 20px rgba(0,0,0,.08); --radius:18px; }
            *{box-sizing:border-box} html,body{height:100%}
            body{margin:0;background:var(--bg-window);display:grid;place-items:center;font-family:Poppins,system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial,'Noto Sans';color:#1b4a7a}
            .window{width:min(1200px,96vw);background:var(--bg-card);border-radius:var(--radius);position:relative;box-shadow:var(--shadow);overflow:hidden}
            .win-bar{height:36px;display:flex;align-items:center;gap:8px;padding:0 14px;background:#efefef}
            .dot{width:12px;height:12px;border-radius:50%}.red{background:#ff5f56}.yellow{background:#ffbd2e}.green{background:#27c93f}
            .tabs{position:absolute;top:22px;left:80px;display:flex;gap:12px}
            .tab{background:#e6ecff;color:#2f53a3;padding:10px 18px;border-radius:12px 12px 0 0;box-shadow:0 2px 0 rgba(0,0,0,.05) inset;font-weight:600;letter-spacing:.3px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;cursor:pointer}
            .tab.active{background:#cfe0ff}
            .wrap{padding:28px}
            .headline{font-size:clamp(28px,5vw,58px);line-height:1.05;color:#2f64b3;margin:18px 0}
            .panel{background:rgba(255,255,255,.85);border:2px solid rgba(46,102,204,.2);border-radius:16px;padding:18px;box-shadow:var(--shadow)}
        </style>
    </head>
    <body>
        <div class="window">
            <div class="win-bar">
                <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
            </div>
            <div class="tabs">
                <a href="{{ route('ethicheck') }}" class="tab {{ request()->routeIs('ethicheck') ? 'active' : '' }}">Ethicheck</a>
                <a href="{{ route('sikata') }}" class="tab {{ request()->routeIs('sikata') ? 'active' : '' }}">SIKATA</a>
                <a href="{{ route('etipad') }}" class="tab {{ request()->routeIs('etipad') ? 'active' : '' }}">Etipad</a>
            </div>
            <div class="wrap">
                <h1 class="headline">Welcome to <br><span>Etipad</span></h1>
                <div class="panel">
                    <p>Halaman Etipad siap dihubungkan. Silakan tentukan konten/fiturnya, nanti saya wiring sesuai kebutuhan.</p>
                </div>
            </div>
        </div>
    </body>
</html>
