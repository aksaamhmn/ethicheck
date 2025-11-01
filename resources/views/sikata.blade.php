<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIKATA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-window: #d9d9db;
            --bg-card: #e8f6cc;
            /* soft green */
            --blue: #2d5fbf;
            --blue-dark: #224c99;
            --text: #1b4a7a;
            --ink: #2f5aa0;
            --white: #fff;
            --shadow: 0 8px 20px rgba(0, 0, 0, .08);
            --radius: 18px;

            /* --- STYLE BARU UNTUK HASIL AI --- */
            --red-highlight: #ffc9c9;
            --red-border: #f03e3e;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            /* height: 100%; */
        }

        body {
            margin: 0;
            background: var(--bg-window);
            /* display: grid;
            place-items: center; */
            font-family: Poppins, system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            color: var(--text);
            padding: 40px 10px;
        }

        /* ... (Semua style Anda yang lain tetap sama) ... */
        .window {
            width: min(1200px, 96vw);
            background: var(--bg-card);
            border-radius: var(--radius);
            position: relative;
            box-shadow: var(--shadow);
            /* overflow: hidden; */
            margin: 0 auto;
        }

        .win-bar {
            height: 42px;
            display: flex;
            align-items: center;
            background: #efefef;
            /* abu-abu bar */
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
            padding: 0 16px;
            position: relative;
            z-index: 20;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .dot.red {
            background: #ff5f56;
        }

        .dot.yellow {
            background: #ffbd2e;
        }

        .dot.green {
            background: #27c93f;
        }

        .tabs {
            display: flex;
            align-items: center;
            margin-left: 28px;
            /* jarak antara dot dan tab pertama */
            height: 100%;
        }

        .tab {
            background: #d4e3ff;
            color: #2f53a3;
            padding: 10px 26px 9px;
            border-radius: 12px 12px 0 0;
            text-decoration: none;
            cursor: pointer;
            font-family: 'Patrick Hand', cursive;
            font-size: 25px;
            line-height: 1;
            display: flex;
            align-items: center;
            height: 100%;
            transition: all 0.15s ease;
            border-right: 1px solid #c6d8ff;
            /* buat garis pembatas lembut antar tab */
            margin-right: -1px;
            /* hapus gap visual antar tab */
        }

        .tab:last-child {
            border-right: none;
            /* hilangkan garis di tab terakhir */
        }

        .tab.active {
            background: var(--bg-card);
            color: var(--blue);
            font-weight: 700;
            z-index: 21;
        }

        .tab:hover {
            background: #c7dcff;
        }

        .wrap {
            /* Ubah padding-top agar konten mulai TEPAT di bawah tab */
            padding: 18px 28px 36px;
            position: relative;
            z-index: 9;
            /* Di bawah tab */
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            gap: 32px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .tabs {
                position: static;
                padding: 8px 14px 0;
            }

            .wrap {
                padding-top: 12px;
            }
        }

        .headline {
            font-size: clamp(28px, 5vw, 58px);
            line-height: 1.05;
            color: #2f64b3;
            margin: 18px 0 18px;
        }

        .headline span {
            color: #2e66cc;
        }

        .hero-logo {
            margin-top: 16px;
            width: clamp(180px, 26vw, 320px);
            max-width: 340px;
            display: block;
            border-radius: 16px;
        }

        .panel {
            background: rgba(255, 255, 255, .85);
            border: 2px solid rgba(46, 102, 204, 0.2);
            border-radius: 16px;
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .section-title {
            font-size: clamp(20px, 2.6vw, 36px);
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0 12px;
        }

        .section-title .glass {
            font-size: 28px;
        }

        .checker {
            display: grid;
            gap: 10px;
        }

        textarea {
            width: 100%;
            min-height: 180px;
            resize: vertical;
            padding: 14px 16px;
            border-radius: 12px;
            border: 2px solid #b9d0ff;
            outline: none;
            font-size: 16px;
            line-height: 1.5;
            background: #fff;
            box-shadow: inset 0 1px 0 rgba(0, 0, 0, .03);
        }

        textarea::placeholder {
            color: #7aa0d6;
        }

        .btn {
            justify-self: end;
            padding: 10px 18px;
            border-radius: 24px;
            border: 0;
            cursor: pointer;
            background: var(--blue);
            color: var(--white);
            font-weight: 700;
            box-shadow: 0 6px 0 var(--blue-dark);
            transform: translateY(0);
            font-family: 'Patrick Hand', cursive;
            font-size: 20px;
            transition: transform .05s ease, box-shadow .05s ease, background .2s ease;
        }

        .btn:hover {
            background: #2b63cc;
        }

        .btn:active {
            transform: translateY(2px);
            box-shadow: 0 4px 0 var(--blue-dark);
        }

        .results-title {
            margin: 18px 0 8px;
            font-size: clamp(20px, 2.2vw, 34px);
            color: var(--ink);
            font-weight: 700;
        }

        .results-box {
            min-height: 130px;
            border-radius: 14px;
            background: #fff;
            border: 2px solid #b9d0ff;
            padding: 14px;
            box-shadow: inset 0 1px 0 rgba(0, 0, 0, .03);
            line-height: 1.6;
        }

        /* --- STYLE BARU UNTUK HASIL AI (WAJIB ADA) --- */
        .results-box mark {
            background-color: var(--red-highlight);
            border-bottom: 2px solid var(--red-border);
            padding: 2px 0;
            border-radius: 4px;
            cursor: pointer;
        }

        .explanations-list {
            list-style-type: none;
            padding-left: 0;
            margin-top: 20px;
            border-top: 2px dashed #b9d0ff;
            padding-top: 15px;
        }

        .explanations-list li {
            display: flex;
            gap: 12px;
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            background: #f8f9ff;
            border: 1px solid #dbe4ff;
        }

        .explanations-list .violation-id {
            flex-shrink: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--red-border);
            color: var(--white);
            font-weight: 700;
            display: grid;
            place-items: center;
            font-size: 16px;
        }

        .explanations-list .violation-details {
            font-size: 14px;
        }

        .explanations-list .violation-rule {
            font-weight: 700;
            color: #d9480f;
            font-size: 15px;
        }

        /* Perbaikan bug class dari sebelumnya */
        .explanations-list .violation-reasoning {
            margin: 8px 0 0;
            color: var(--text);
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(45, 95, 191, 0.3);
            border-radius: 50%;
            border-top-color: var(--blue);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* --- AKHIR STYLE BARU --- */

        footer.note {
            text-align: center;
            font-size: 12px;
            color: #4a6ba7;
            margin-top: 10px;
            opacity: .8;
        }
    </style>
</head>

<body>
    <div class="window">
        <div class="win-bar">
            <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
            <div class="tabs">
                <a href="{{ route('ethicheck') }}" class="tab {{ request()->routeIs('ethicheck') ? 'active' : '' }}">Ethicheck</a>
                <a href="{{ route('sikata') }}" class="tab {{ request()->routeIs('sikata') ? 'active' : '' }}">SIKATA</a>
                <a href="{{ route('etipad') }}" class="tab {{ request()->routeIs('etipad') ? 'active' : '' }}">Etipad</a>
            </div>
        </div>

        <div class="wrap">
            <h1 class="headline">Welcome to <br><span>SIKATA</span></h1>
            <div class="panel">
                <p>Halaman SIKATA siap dihubungkan. Silakan tentukan konten/fiturnya, nanti saya wiring sesuai kebutuhan.</p>
            </div>
        </div>
    </div>
</body>

</html>