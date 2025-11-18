<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ethipad</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    @php $cssPath = public_path('css/etipad.css'); $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time(); @endphp
    <link rel="stylesheet" href="{{ asset('css/etipad.css') }}?v={{ $cssVersion }}">
</head>

<body>
    <div class="window">
        <div class="win-bar">
            <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
            <div class="tabs">
                <a href="{{ route('ethicheck') }}" class="tab {{ request()->routeIs('ethicheck') ? 'active' : '' }}">Ethicheck</a>
                <a href="{{ route('sikata') }}" class="tab {{ request()->routeIs('sikata') ? 'active' : '' }}">SIKATA</a>
                <a href="{{ route('etipad') }}" class="tab {{ request()->routeIs('etipad') ? 'active' : '' }}">Ethipad</a>
            </div>
        </div>

        <div class="wrap">
            <div class="decor-eti">
                <div class="d d-stripes"></div>
                <div class="d d-columns"></div>
                <div class="d d-tape"></div>
                <div class="d d-stamp"></div>
                <div class="d d-clip"></div>
            </div>
            <h1 class="headline">Welcome to <br><span>Ethipad</span></h1>
            <div class="head-switch">
                <button type="button" id="btnPasal" class="active">Pasal</button>
                <button type="button" id="btnBerita">Berita</button>
            </div>
            <!-- PASAL (Dokumen) SECTION -->
            <div class="panel" id="etipadApp" style="display:flex;gap:24px;flex-wrap:wrap">
                <div style="flex:0 0 240px;display:flex;flex-direction:column;gap:10px">
                    <div class="section-title" style="font-size:22px;margin:0 0 4px">Dokumen</div>
                    <div id="docList" style="display:flex;flex-direction:column;gap:8px"></div>
                </div>
                <div style="flex:1;min-width:320px">
                    <div id="docViewer" style="background:#fff;border:2px solid #b9d0ff;border-radius:14px;padding:16px;min-height:360px;line-height:1.55;font-size:15px;overflow:auto">
                        <em style="color:#5b7fb5">Pilih dokumen di kiri untuk melihat isi.</em>
                    </div>
                </div>
            </div>
            <!-- BERITA SECTION -->
            <div class="news-wrapper" id="newsWrapper">
                <div class="news-grid" id="newsGrid"></div>
                <!-- Detail page -->
                <div class="news-detail" id="newsDetail">
                    <button class="news-back" id="btnNewsBack">← Kembali ke Berita</button>
                    <div class="article">
                        <div class="hero" id="newsHero"></div>
                        <h2 class="title" id="newsTitle"></h2>
                        <div class="meta" id="newsMeta"></div>
                        <div class="content" id="newsContent"></div>
                    </div>
                </div>
            </div>
            @php $jsPath = public_path('js/etipad.js'); $jsVersion = file_exists($jsPath) ? filemtime($jsPath) : time(); @endphp
            <script src="{{ asset('js/etipad.js') }}?v={{ $jsVersion }}" defer></script>
        </div>
    </div>
</body>

</html>