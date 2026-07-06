<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loading Flavory.id</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#fd7e14">
    @include('layouts.favicon')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* ══ Kontainer animasi ══ */
        #splash_container {
            background: #FF6600;
            border-radius: 28px;
            width: 160px;
            height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 20px 60px rgba(255,102,0,.45), 0 8px 24px rgba(0,0,0,.25);
            animation: container-in .5s cubic-bezier(.34,1.56,.64,1) both;
        }

        @keyframes container-in {
            from { opacity: 0; transform: scale(.72) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* ══ Logo SVG ══ */
        #splash-logo {
            width: 68px;
            height: auto;
            animation: logo-pulse 1.6s ease-in-out infinite;
        }

        .f-part {
            opacity: 0;
            animation: part-in .4s ease forwards;
        }

        .f-stem { animation-delay: .1s; }
        .f-top  { animation-delay: .25s; }
        .f-mid  { animation-delay: .4s; }

        @keyframes part-in {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes logo-pulse {
            0%, 100% { transform: scale(1); }
            50%      { transform: scale(1.08); }
        }

        /* ══ Wordmark ══ */
        .splash-wordmark {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: .9rem;
            letter-spacing: 3px;
            color: rgba(255,255,255,.9);
            text-transform: lowercase;
            animation: fade-up .4s .55s ease both;
        }

        /* ══ Titik bouncing ══ */
        .splash-dots {
            display: flex;
            gap: 4px;
            animation: fade-up .4s .65s ease both;
        }

        .splash-dots span {
            width: 5px;
            height: 5px;
            background: rgba(255,255,255,.85);
            border-radius: 50%;
            display: inline-block;
            animation: dot-bounce 1.1s ease-in-out infinite;
        }

        .splash-dots span:nth-child(2) { animation-delay: .18s; }
        .splash-dots span:nth-child(3) { animation-delay: .36s; }

        @keyframes dot-bounce {
            0%, 80%, 100% { transform: translateY(0); opacity: .45; }
            40%            { transform: translateY(-6px); opacity: 1; }
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══ Fade out sebelum redirect ══ */
        .fade-out {
            animation: splash-fade-out .4s ease forwards;
        }

        @keyframes splash-fade-out {
            to { opacity: 0; transform: scale(.9); }
        }
    </style>
</head>
<body>

    <div id="splash_container">
        <svg id="splash-logo" viewBox="0 0 200 220" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Flavory">
            <path class="f-part f-stem"
                d="M 38 30 C 38 18, 48 12, 58 12 L 58 208 C 58 208, 38 208, 38 195 Z"
                fill="white"/>
            <path class="f-part f-top"
                d="M 54 14 L 148 14 C 182 14, 190 30, 182 52 C 174 72, 148 80, 120 80
                   L 58 80 L 58 55 L 112 55 C 134 55, 155 50, 160 38
                   C 165 26, 152 36, 130 36 L 58 36 L 58 14 Z"
                fill="white"/>
            <path class="f-part f-mid"
                d="M 58 88 L 130 88 C 158 88, 168 100, 162 118
                   C 156 136, 136 144, 112 144 L 58 144 L 58 120
                   L 104 120 C 122 120, 138 116, 142 106
                   C 146 96, 134 110, 116 110 L 58 110 Z"
                fill="white"/>
        </svg>

        <span class="splash-wordmark">flavory.id</span>
        <span class="splash-dots">
            <span></span><span></span><span></span>
        </span>
    </div>

    <script>
        // ── Redirect ke login setelah animasi splash ──
        window.addEventListener('load', function() {
            const container = document.getElementById('splash_container');
            
            // Tunggu 1.8 detik untuk menikmati animasi
            setTimeout(function() {
                // Tambah efek fade-out
                container.classList.add('fade-out');
                
                // Redirect setelah fade-out selesai
                setTimeout(function() {
                    window.location.href = '/login';
                }, 400);
            }, 1800);
        });

        // ── Register Service Worker ──
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .catch(err => console.log('SW Reg Failed:', err));
            });
        }
    </script>

</body>
</html>
