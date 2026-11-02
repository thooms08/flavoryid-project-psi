{{--
|--------------------------------------------------------------------------
| Flavory AI Robot — resources/views/components/robot.blade.php
|--------------------------------------------------------------------------
| Pemakaian:
|   <x-robot />
|   <x-robot greeting="Halo!" :interval="60" :delay="1.2" size="clamp(90px, 12vw, 160px)" />
|
| Props:
|   greeting : teks speech bubble
|   interval : jeda pengulangan dalam DETIK (default 60)
|   delay    : jeda kemunculan pertama setelah halaman load, dalam DETIK (default 1.2)
|   size     : nilai CSS untuk lebar robot (default clamp(84px, 11vw, 148px))
|
| Komponen ini mandiri: CSS ikut di sini, logika animasi ada di public/js/robot.js.
| Dibungkus @once supaya tidak terduplikasi bila terpanggil lebih dari sekali.
--}}
@props([
    'greeting' => 'Halo! Aku Flavory AI Consultant Senang bertemu denganmu!',
    'interval' => 60,
    'delay' => 1.2,
    'size' => null,
])

@php
    $robotAttrs = ['class' => 'flv-robot'];
    if ($size) {
        $robotAttrs['style'] = '--flv-size: ' . $size . ';';
    }
@endphp

@once
<style>
/* =====================================================================
   FLAVORY AI ROBOT — semua class diawali "flv-" agar tidak bentrok
   ===================================================================== */

/* ---------- Root (posisi fixed, digerakkan JS lewat transform) ---------- */
.flv-robot {
    --_s: var(--flv-size, clamp(84px, 11vw, 148px));   /* lebar robot */
    --_ground: var(--flv-ground, 12px);                /* jarak "tanah" dari bawah layar */
    --_z: var(--flv-z, 9999);

    /* variabel mata (dikontrol ekspresi) */
    --lx: 0px; --ly: 0px; --sx: 1; --sy: 1;

    position: fixed;
    left: 0;
    bottom: calc(var(--_ground) + env(safe-area-inset-bottom, 0px));
    width: var(--_s);
    height: calc(var(--_s) * 1.5);
    z-index: var(--_z);
    pointer-events: none;
    user-select: none;
    -webkit-user-select: none;
    visibility: hidden;
    transform: translate3d(110vw, 0, 0);
}
.flv-robot.is-active { visibility: visible; will-change: transform; }

.flv-tilt { width: 100%; height: 100%; transform-origin: 50% 42%; will-change: transform; }
.flv-bob  { width: 100%; height: 100%; }
.flv-robot.is-hover .flv-bob { animation: flv-bob 2.2s ease-in-out infinite; }
.flv-svg  { display: block; width: 100%; height: 100%; overflow: visible; }

/* ---------- Speech bubble ---------- */
.flv-bubble {
    position: absolute;
    right: 0;
    bottom: calc(100% + 6px);
    width: max-content;
    max-width: min(260px, calc(100vw - 32px));
    padding: .62em 1em .62em 1.1em;
    background: #fff;
    color: #3A2316;
    border: 2px solid #FF8A2B;
    border-radius: 18px;
    box-shadow: inset 4px 0 0 #FF8A2B, 0 10px 26px -6px rgba(242, 106, 15, .38), 0 2px 6px rgba(58, 35, 22, .08);
    font: 600 clamp(13px, .55vw + 11px, 16px)/1.4 ui-rounded, "Nunito", "Segoe UI", system-ui, -apple-system, Roboto, "Helvetica Neue", Arial, sans-serif;
    overflow-wrap: anywhere;
    opacity: 0;
    transform: translateY(8px) scale(.82);
    transform-origin: calc(100% - var(--_s) / 2) 100%;
    transition: opacity .2s ease-in, transform .22s ease-in;
}
.flv-bubble.is-visible {
    opacity: 1;
    transform: translateY(0) scale(1);
    transition: opacity .22s ease-out, transform .42s cubic-bezier(.34, 1.56, .64, 1);
}
.flv-bubble::after {          /* ekor bubble menunjuk ke kepala robot */
    content: "";
    position: absolute;
    bottom: -9px;
    right: calc(var(--_s) / 2 - 8px);
    width: 14px; height: 14px;
    background: #fff;
    border-right: 2px solid #FF8A2B;
    border-bottom: 2px solid #FF8A2B;
    border-radius: 0 0 5px 0;
    transform: rotate(45deg);
}
.flv-bubble__text { display: block; }

/* ---------- Roket / api ---------- */
.flv-flame {
    transform-origin: 100px 224px;
    transform: scale(.2, 0);
    opacity: 0;
    transition: transform .28s cubic-bezier(.3, 1.4, .5, 1), opacity .2s ease;
}
.flv-robot[data-thrust="hover"]  .flv-flame { transform: scale(.62, .5);  opacity: 1; }
.flv-robot[data-thrust="cruise"] .flv-flame { transform: scale(1, 1);     opacity: 1; }
.flv-robot[data-thrust="boost"]  .flv-flame { transform: scale(1.2, 1.75); opacity: 1; }
/* padam seketika */
.flv-robot[data-thrust="off"] .flv-flame { transition: transform .06s ease-out, opacity .06s linear; }

.flv-fl { transform-origin: 100px 224px; animation: flv-flicker .16s ease-in-out infinite alternate; }
.flv-fl--mid  { animation-duration: .12s; }
.flv-fl--core { animation-duration: .09s; }
.flv-spark { transform-box: fill-box; transform-origin: center; animation: flv-spark .55s linear infinite; opacity: 0; }
.flv-spark:nth-child(2) { animation-delay: .18s; }
.flv-spark:nth-child(3) { animation-delay: .36s; }
.flv-sparks { opacity: 0; transition: opacity .15s; }
.flv-robot[data-thrust="cruise"] .flv-sparks,
.flv-robot[data-thrust="boost"]  .flv-sparks { opacity: 1; }

/* ---------- Detail dekoratif ---------- */
.flv-orb-glow { animation: flv-pulse 1.8s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
.flv-led { animation: flv-led 1.6s ease-in-out infinite; }
.flv-led:nth-child(2) { animation-delay: .25s; }
.flv-led:nth-child(3) { animation-delay: .5s; }

/* ---------- Mata ---------- */
.flv-eyes { transform-box: fill-box; transform-origin: center; animation: flv-blink 4.6s infinite; }
.flv-eye {
    fill: #FFE9CC;
    transform-box: fill-box;
    transform-origin: center;
    transform: translate(var(--lx), var(--ly)) scale(var(--sx), var(--sy));
    transition: transform .2s ease, opacity .15s ease;
}
.flv-eye-hl { fill: #fff; }
.flv-arc   { fill: none; stroke: #FFE9CC; stroke-width: 5; stroke-linecap: round; opacity: 0; transition: opacity .15s; }
.flv-brow  { fill: none; stroke: #FFB067; stroke-width: 4; stroke-linecap: round; opacity: 0; transition: opacity .15s; }

.flv-robot[data-look="left"] { --lx: -5px; }
.flv-robot[data-look="down"] { --ly: 4px; }

.flv-robot[data-expression="surprised"] { --sx: 1.2;  --sy: 1.32; }
.flv-robot[data-expression="panic"]     { --sx: 1.12; --sy: 1.22; }
.flv-robot[data-expression="sad"]       { --sx: 1;    --sy: .86; --ly: 3px; }
.flv-robot[data-expression="sad"] .flv-brow { opacity: 1; }
.flv-robot[data-expression="shy"] .flv-eye  { opacity: 0; }
.flv-robot[data-expression="shy"] .flv-arc  { opacity: 1; }

/* ---------- Mulut ---------- */
.flv-mouth { opacity: 0; transition: opacity .12s; fill: none; stroke: #FFB067; stroke-width: 4.5; stroke-linecap: round; stroke-linejoin: round; }
.flv-mouth--happy     { fill: #FF9B4A; stroke: #FF9B4A; stroke-width: 3; }
.flv-mouth--surprised { fill: #2A1810; stroke-width: 4; }
.flv-robot[data-expression="smile"]     .flv-mouth--smile,
.flv-robot[data-expression="happy"]     .flv-mouth--happy,
.flv-robot[data-expression="surprised"] .flv-mouth--surprised,
.flv-robot[data-expression="shy"]       .flv-mouth--shy,
.flv-robot[data-expression="sad"]       .flv-mouth--sad,
.flv-robot[data-expression="panic"]     .flv-mouth--panic { opacity: 1; }

/* ---------- Pipi, keringat, air mata, bintang pusing ---------- */
.flv-cheek { opacity: 0; transition: opacity .25s ease; }
.flv-robot[data-expression="smile"] .flv-cheek { opacity: .18; }
.flv-robot[data-expression="happy"] .flv-cheek { opacity: .3; }
.flv-robot[data-expression="shy"]   .flv-cheek { opacity: .95; }
.flv-robot[data-expression="panic"] .flv-cheek { opacity: .65; }

.flv-sweat, .flv-tear { opacity: 0; }
.flv-robot[data-expression="panic"] .flv-sweat { animation: flv-drip .7s ease-in infinite; }
.flv-robot[data-expression="sad"]   .flv-tear  { animation: flv-drip 1.6s ease-in infinite; }

.flv-dizzy { opacity: 0; transition: opacity .2s; }
.flv-robot[data-dizzy="on"] .flv-dizzy { opacity: 1; }
.flv-star { transform-box: fill-box; transform-origin: center; }
.flv-robot[data-dizzy="on"] .flv-star { animation: flv-twinkle .8s ease-in-out infinite alternate; }
.flv-robot[data-dizzy="on"] .flv-star:nth-child(2) { animation-delay: .2s; }
.flv-robot[data-dizzy="on"] .flv-star:nth-child(3) { animation-delay: .4s; }
.flv-robot[data-dizzy="on"] .flv-star:nth-child(4) { animation-delay: .6s; }

.flv-robot[data-expression="panic"] .flv-head { animation: flv-shake .08s linear infinite alternate; }

/* ---------- Lengan ---------- */
.flv-arm { transition: transform .3s ease; }
.flv-arm--l { transform-origin: 40px 152px;  transform: rotate(5deg); }
.flv-arm--r { transform-origin: 160px 152px; transform: rotate(-5deg); }

.flv-robot[data-arms="wave"]  .flv-arm--r { animation: flv-wave .5s ease-in-out infinite alternate; }
.flv-robot[data-arms="flail"] .flv-arm--l { animation: flv-flail-l .2s ease-in-out infinite alternate; }
.flv-robot[data-arms="flail"] .flv-arm--r { animation: flv-flail-r .2s ease-in-out infinite alternate .05s; }
.flv-robot[data-arms="panic"] .flv-arm--l { animation: flv-panic-l .12s ease-in-out infinite alternate; }
.flv-robot[data-arms="panic"] .flv-arm--r { animation: flv-panic-r .12s ease-in-out infinite alternate; }
.flv-robot[data-arms="shy"]   .flv-arm--l { animation: flv-shy-l 1.1s ease-in-out infinite alternate; }
.flv-robot[data-arms="shy"]   .flv-arm--r { animation: flv-shy-r 1.1s ease-in-out infinite alternate; }

/* ---------- Keyframes ---------- */
@keyframes flv-bob      { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-3.5%); } }
@keyframes flv-flicker  { from { transform: scale(1, 1); } 50% { transform: scale(.9, 1.14); } to { transform: scale(1.06, .93); } }
@keyframes flv-spark    { 0% { transform: translateY(0) scale(1); opacity: .95; } 100% { transform: translateY(42px) scale(.2); opacity: 0; } }
@keyframes flv-pulse    { 0%, 100% { opacity: .22; transform: scale(.9); } 50% { opacity: .5; transform: scale(1.15); } }
@keyframes flv-led      { 0%, 100% { opacity: .35; } 50% { opacity: 1; } }
@keyframes flv-blink    { 0%, 92%, 100% { transform: scaleY(1); } 95% { transform: scaleY(.08); } }
@keyframes flv-drip     { 0% { transform: translateY(0); opacity: 0; } 20% { opacity: 1; } 100% { transform: translateY(14px); opacity: 0; } }
@keyframes flv-twinkle  { from { transform: scale(.55) rotate(0deg); opacity: .5; } to { transform: scale(1.15) rotate(25deg); opacity: 1; } }
@keyframes flv-shake    { from { transform: translateX(-1.4px); } to { transform: translateX(1.4px); } }
@keyframes flv-wave     { from { transform: rotate(-122deg); } to { transform: rotate(-158deg); } }
@keyframes flv-flail-l  { from { transform: rotate(15deg); }  to { transform: rotate(150deg); } }
@keyframes flv-flail-r  { from { transform: rotate(-15deg); } to { transform: rotate(-150deg); } }
@keyframes flv-panic-l  { from { transform: rotate(10deg); }  to { transform: rotate(70deg); } }
@keyframes flv-panic-r  { from { transform: rotate(-10deg); } to { transform: rotate(-70deg); } }
@keyframes flv-shy-l    { from { transform: rotate(-14deg); } to { transform: rotate(-30deg); } }
@keyframes flv-shy-r    { from { transform: rotate(14deg); }  to { transform: rotate(30deg); } }

/* ---------- Aksesibilitas & cetak ---------- */
@media (prefers-reduced-motion: reduce) {
    .flv-robot *, .flv-robot *::before, .flv-robot *::after { animation: none !important; }
    .flv-robot .flv-flame { display: none; }
    .flv-robot .flv-bubble,
    .flv-robot .flv-bubble.is-visible { transform: none; transition: opacity .3s linear; }
}
@media print { .flv-robot { display: none !important; } }
</style>

<div {{ $attributes->merge($robotAttrs) }}
     id="flv-robot"
     data-flavory-robot
     data-greeting="{{ $greeting }}"
     data-interval="{{ $interval }}"
     data-delay="{{ $delay }}"
     data-expression="smile"
     data-thrust="off"
     data-arms="rest"
     data-look="none"
     data-dizzy="off"
     aria-hidden="true">

    {{-- Speech bubble (tidak ikut berputar saat robot miring) --}}
    <div class="flv-bubble" data-flv-bubble>
        <span class="flv-bubble__text" data-flv-text></span>
    </div>

    <div class="flv-tilt" data-flv-tilt>
        <div class="flv-bob">
            <svg class="flv-svg" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg" focusable="false">
                <defs>
                    <linearGradient id="flv-g-body" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#FFA24A"/>
                        <stop offset="1" stop-color="#EE5F08"/>
                    </linearGradient>
                    <linearGradient id="flv-g-head" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#FFB460"/>
                        <stop offset="1" stop-color="#F26A0F"/>
                    </linearGradient>
                    <radialGradient id="flv-g-glow">
                        <stop offset="0" stop-color="#FFB347" stop-opacity=".85"/>
                        <stop offset="1" stop-color="#FF7A1A" stop-opacity="0"/>
                    </radialGradient>
                    <path id="flv-star" d="M0 -6 L1.8 -1.8 L6 0 L1.8 1.8 L0 6 L-1.8 1.8 L-6 0 L-1.8 -1.8 Z"/>
                </defs>

                {{-- ===== API ROKET (di belakang badan) ===== --}}
                <g class="flv-flame">
                    <ellipse cx="100" cy="240" rx="34" ry="30" fill="url(#flv-g-glow)"/>
                    <path class="flv-fl flv-fl--outer" d="M85 222 Q78 252 100 292 Q122 252 115 222 Z" fill="#FF7A1A"/>
                    <path class="flv-fl flv-fl--mid"   d="M91 222 Q87 246 100 274 Q113 246 109 222 Z" fill="#FFB23E"/>
                    <path class="flv-fl flv-fl--core"  d="M95.5 222 Q94 238 100 254 Q106 238 104.5 222 Z" fill="#FFF3C4"/>
                    <g class="flv-sparks">
                        <circle class="flv-spark" cx="92"  cy="244" r="2.6" fill="#FFC15A"/>
                        <circle class="flv-spark" cx="108" cy="250" r="2.2" fill="#FF9A2E"/>
                        <circle class="flv-spark" cx="100" cy="258" r="2"   fill="#FFE08A"/>
                    </g>
                </g>

                {{-- ===== LENGAN (di belakang badan) ===== --}}
                <g class="flv-arm flv-arm--l">
                    <rect x="30" y="146" width="20" height="40" rx="10" fill="#E85D0C"/>
                    <circle cx="40" cy="190" r="10" fill="#FFF4E8"/>
                </g>
                <g class="flv-arm flv-arm--r">
                    <rect x="150" y="146" width="20" height="40" rx="10" fill="#E85D0C"/>
                    <circle cx="160" cy="190" r="10" fill="#FFF4E8"/>
                </g>

                {{-- ===== LEHER + BADAN ===== --}}
                <rect x="86" y="126" width="28" height="14" rx="4" fill="#3A2316"/>
                <path d="M48 146 Q48 134 62 134 L138 134 Q152 134 152 146 L148 180 Q144 208 120 212 L80 212 Q56 208 52 180 Z" fill="url(#flv-g-body)"/>
                <path d="M60 144 Q60 140 68 140 L88 140" fill="none" stroke="#fff" stroke-opacity=".35" stroke-width="4" stroke-linecap="round"/>
                <path d="M53 198 Q100 208 147 198" fill="none" stroke="#C94E05" stroke-opacity=".5" stroke-width="3" stroke-linecap="round"/>

                {{-- Panel dada dengan logo-512.png --}}
                <rect x="72" y="148" width="56" height="34" rx="12" fill="#FFF4E8"/>
                <image href="{{ asset('logo-512.png') }}" x="78" y="151" width="44" height="28" preserveAspectRatio="xMidYMid meet" clip-path="url(#flv-chest-clip)"/>
                <clipPath id="flv-chest-clip">
                    <rect x="72" y="148" width="56" height="34" rx="12"/>
                </clipPath>
                <g>
                    <circle class="flv-led" cx="88"  cy="192" r="2.2" fill="#FFD27A"/>
                    <circle class="flv-led" cx="100" cy="192" r="2.2" fill="#FFD27A"/>
                    <circle class="flv-led" cx="112" cy="192" r="2.2" fill="#FFD27A"/>
                </g>

                {{-- Nozzle roket --}}
                <path d="M78 208 L122 208 L116 224 L84 224 Z" fill="#3A2316"/>
                <rect x="76" y="204" width="48" height="8" rx="4" fill="#FFF4E8"/>

                {{-- ===== KEPALA ===== --}}
                <g class="flv-head">
                    {{-- telinga --}}
                    <rect x="20"  y="70" width="20" height="34" rx="10" fill="#FFF4E8"/>
                    <rect x="25"  y="80" width="8"  height="14" rx="4"  fill="#F26A0F"/>
                    <rect x="160" y="70" width="20" height="34" rx="10" fill="#FFF4E8"/>
                    <rect x="167" y="80" width="8"  height="14" rx="4"  fill="#F26A0F"/>

                    {{-- antena --}}
                    <rect x="97" y="18" width="6" height="20" rx="3" fill="#3A2316"/>
                    <circle class="flv-orb-glow" cx="100" cy="14" r="13" fill="#FFD27A"/>
                    <circle cx="100" cy="14" r="8" fill="#FFD27A"/>
                    <circle cx="97.5" cy="11.5" r="2.5" fill="#fff"/>

                    {{-- cangkang kepala --}}
                    <rect x="34" y="34" width="132" height="98" rx="44" fill="url(#flv-g-head)"/>
                    <path d="M52 52 Q60 42 82 40" fill="none" stroke="#fff" stroke-opacity=".45" stroke-width="5" stroke-linecap="round"/>

                    {{-- layar wajah --}}
                    <rect x="46" y="48" width="108" height="70" rx="32" fill="#2A1810"/>
                    <rect x="46" y="48" width="108" height="70" rx="32" fill="none" stroke="#FFB067" stroke-opacity=".35" stroke-width="2"/>
                    <path d="M62 56 Q76 50 96 52" fill="none" stroke="#fff" stroke-opacity=".12" stroke-width="4" stroke-linecap="round"/>

                    {{-- alis (sedih) --}}
                    <path class="flv-brow" d="M67 68 L89 60"/>
                    <path class="flv-brow" d="M133 68 L111 60"/>

                    {{-- mata bulat --}}
                    <g class="flv-eyes">
                        <g class="flv-eye flv-eye--l">
                            <ellipse cx="78" cy="78" rx="9" ry="12"/>
                            <circle class="flv-eye-hl" cx="81" cy="73" r="3"/>
                        </g>
                        <g class="flv-eye flv-eye--r">
                            <ellipse cx="122" cy="78" rx="9" ry="12"/>
                            <circle class="flv-eye-hl" cx="125" cy="73" r="3"/>
                        </g>
                    </g>
                    {{-- mata tersipu (^ ^) --}}
                    <path class="flv-arc" d="M68 82 Q78 69 88 82"/>
                    <path class="flv-arc" d="M112 82 Q122 69 132 82"/>

                    {{-- pipi merona --}}
                    <g class="flv-cheek">
                        <ellipse cx="63"  cy="101" rx="10" ry="6" fill="#FF6F8A"/>
                        <ellipse cx="137" cy="101" rx="10" ry="6" fill="#FF6F8A"/>
                        <path d="M58 98 l3 6 M63 98 l3 6 M68 98 l3 6 M132 98 l3 6 M137 98 l3 6 M142 98 l3 6" fill="none" stroke="#FFD0DA" stroke-width="1.8" stroke-linecap="round"/>
                    </g>

                    {{-- variasi mulut --}}
                    <path class="flv-mouth flv-mouth--smile"     d="M88 98 Q100 110 112 98"/>
                    <path class="flv-mouth flv-mouth--happy"     d="M88 96 Q100 116 112 96 Z"/>
                    <ellipse class="flv-mouth flv-mouth--surprised" cx="100" cy="106" rx="6" ry="8"/>
                    <path class="flv-mouth flv-mouth--shy"       d="M91 101 Q95.5 96 100 101 T109 101"/>
                    <path class="flv-mouth flv-mouth--sad"       d="M90 108 Q100 98 110 108"/>
                    <path class="flv-mouth flv-mouth--panic"     d="M87 104 L92 98 L97 105 L102 98 L107 105 L113 99"/>

                    {{-- air mata & keringat --}}
                    <path class="flv-tear"  d="M70 94 Q74 101 70 105 Q66 101 70 94 Z" fill="#8FD8FF"/>
                    <path class="flv-sweat" d="M152 46 Q158 56 152 62 Q146 56 152 46 Z" fill="#8FD8FF"/>
                </g>

                {{-- bintang pusing (saat terjatuh) --}}
                <g class="flv-dizzy" fill="#FFD27A">
                    <use class="flv-star" href="#flv-star" x="56"  y="30"/>
                    <use class="flv-star" href="#flv-star" x="144" y="30"/>
                    <use class="flv-star" href="#flv-star" x="76"  y="9"/>
                    <use class="flv-star" href="#flv-star" x="124" y="9"/>
                </g>
            </svg>
        </div>
    </div>
</div>
@endonce