<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ethicheck</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    @php $cssP = public_path('css/ethicheck.css'); $cssV = file_exists($cssP) ? filemtime($cssP) : time(); $jsP = public_path('js/ethicheck.js'); $jsV = file_exists($jsP) ? filemtime($jsP) : time(); @endphp
    <link rel="stylesheet" href="{{ asset('css/ethicheck.css') }}?v={{ $cssV }}">
    <script>window.ETHICHECK_CONFIG = { analyzeUrl: "{{ route('ethicheck.analyze') }}", csrf: "{{ csrf_token() }}" };</script>
    <script src="{{ asset('js/ethicheck.js') }}?v={{ $jsV }}" defer></script>
</head>

<body>
    <div class="window">
        <div class="win-bar">
            <span class="dot red"></span>
            <span class="dot yellow"></span>
            <span class="dot green"></span>

            <div class="tabs">
                <a href="{{ route('ethicheck') }}" class="tab {{ request()->routeIs('ethicheck') ? 'active' : '' }}">Ethicheck</a>
                <a href="{{ route('sikata') }}" class="tab {{ request()->routeIs('sikata') ? 'active' : '' }}">SIKATA</a>
                <a href="{{ route('etipad') }}" class="tab {{ request()->routeIs('etipad') ? 'active' : '' }}">Ethipad</a>
            </div>
        </div>



        <div class="wrap">
            <div class="ornaments">
                <div class="o o-bubble"></div>
                <div class="o o-bubble b2"></div>
                <div class="o o-ring"></div>
                <div class="o o-dots"></div>
                <div class="o o-wave"></div>
                <div class="o o-spark"></div>
                <div class="o o-quote">“</div>
                <div class="o o-badge">✓</div>
            </div>
            <div class="grid">
                <section>
                    <h1 class="headline">Welcome to<br><span>Ethicheck!</span></h1>
                    <img src="{{ asset('images/Ethiceck-logo.png') }}" alt="Ethicheck logo" class="hero-logo">
                </section>
                <section>
                    <div class="panel">
                        <h2 class="section-title"> Check the news here! <span class="glass">🔎</span></h2>

                        <form class="checker" id="checker-form">
                            @csrf
                            <textarea id="news-text-input" name="text" placeholder="write your text here..." minlength="50" required></textarea>
                            <button class="btn" type="submit" id="submit-btn">Submit here</button>
                        </form>
                    </div>

                    <h3 class="results-title"><span class="mini-icon">✅</span> Results are here...</h3>

                    <div class="results-box" id="results-box">
                    </div>

                    <footer class="note">Analysis powered by AI. Always double-check with a human editor.</footer>
                </section>
            </div>
        </div>
    </div>
</body>

</html>