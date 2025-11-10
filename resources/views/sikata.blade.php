<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIKATA — Ethicheck Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sikata.css') . '?v=' . filemtime(public_path('css/sikata.css')) }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script defer src="{{ asset('js/sikata.js') . '?v=' . filemtime(public_path('js/sikata.js')) }}"></script>
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

        <!-- Decorative ornaments layer -->
        <div class="ornaments" aria-hidden="true">
            <div class="orn o-bubble o-b1"></div>
            <div class="orn o-bubble o-b2"></div>
            <div class="orn o-dots o-d1"></div>
            <div class="orn o-ring o-r1"></div>
            <div class="orn o-spark o-s1"></div>
            <div class="orn o-spark o-s2"></div>
            <!-- extra ornaments: faint cards and accents -->
            <div class="orn o-card oc-s">S</div>
            <div class="orn o-card oc-k">K</div>
            <div class="orn o-card oc-t">T</div>
            <div class="orn o-grid o-g1"></div>
            <div class="orn o-wave o-w1"></div>
        </div>

        <div class="wrap">
            <div class="grid">
                <div>
                    <h1 class="headline">Welcome to <br><span>SIKATA</span></h1>
                    <div class="panel" id="infoPanel">
                        <p id="gameInfo">Pilih topik kuis di kanan. Setelah memilih kartu, Anda akan melihat studi kasus dan diminta memilih kalimat yang salah secara etika. Sistem akan memberi penjelasan dan skor seketika.</p>
                    </div>
                </div>
                <div>
                    <div class="panel">
                        <div class="section-title" id="topicTitle">Pilih Topik</div>
                        <div id="topicChosen" class="topic-chosen" style="display:none"></div>
                        <div class="topics" id="topics"></div>
                        <div class="quiz" id="quiz">
                            <div class="quiz-header" id="quizHeader" style="display:flex;align-items:center;justify-content:space-between">
                                <div class="section-title" style="margin:0">Soal</div>
                                <div class="progress">Soal <span id="qnum">0</span>/<span id="qtotal">0</span> • Skor: <span id="score">0</span></div>
                            </div>
                            <div class="question-box" id="questionText" style="margin-top:10px"></div>
                            <div class="options" id="options"></div>
                            <div class="controls">
                                <button class="btn secondary" id="backBtn" style="display:none">Kembali ke topik</button>
                                <button class="btn" id="nextBtn" disabled>Lanjut</button>
                            </div>
                            <div class="feedback" id="explanation"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>