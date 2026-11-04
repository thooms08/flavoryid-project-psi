(function (window, document) {
    'use strict';

    if (window.FlavoryRobot) return; // cegah pemuatan ganda

    /* ------------------------------------------------------------------
     * Konstanta
     * ------------------------------------------------------------------ */
    var DEFAULT_GREETING = 'Halo! Aku Flavory AI Consultant Senang bertemu denganmu!';

    // Durasi tiap fase dalam milidetik — ubah di sini atau lewat FlavoryRobotConfig.timing
    var DEFAULT_TIMING = {
        enter: 2000,        // Fase 1: terbang masuk dari kanan
        settle: 300,        // jeda kecil setelah berhenti
        greetHold: 3200,    // Fase 2: lama speech bubble tampil
        bubbleOut: 350,     // jeda setelah bubble menghilang
        flyLeft: 1700,      // Fase 3: terbang ke kiri sebelum oleng
        wobble: 800,        // Fase 3: kehilangan keseimbangan
        fall: 900,          // Fase 3: jatuh
        bounce: 480,        // Fase 3: memantul saat mendarat
        groundHold: 1300,   // Fase 3: diam dalam posisi terjatuh
        rise: 1400,         // Fase 4: bangkit
        shyHold: 1900,      // Fase 4: menikmati ekspresi malu
        windup: 300,        // Fase 5: ancang-ancang
        dash: 1250,         // Fase 5: terbang terburu-buru keluar layar
        reducedHold: 3800   // mode reduced-motion: lama tampil
    };

    // Proporsi terhadap lebar robot (1 unit SVG = size / 200)
    var UP_GROUND = 0.38;   // geser ke bawah agar badan menyentuh "tanah" (area api di bawah badan)
    var LAY_GROUND = 0.5;   // geser ke bawah saat robot terbaring miring
    var HOVER_LIFT = 0.14;  // tinggi melayang saat bangkit

    var MIN_GAP = 1500;     // jeda minimum antar-putaran (ms)

    /* ------------------------------------------------------------------
     * Utilitas
     * ------------------------------------------------------------------ */
    function clamp(v, lo, hi) { return Math.max(lo, Math.min(hi, v)); }
    function now() { return window.performance && performance.now ? performance.now() : Date.now(); }
    function abortError() {
        var e = new Error('Aborted');
        e.name = 'AbortError';
        return e;
    }
    function num(v, fallback) {
        var n = parseFloat(v);
        return isFinite(n) ? n : fallback;
    }

    function readConfig(root) {
        var d = root.dataset || {};
        var user = window.FlavoryRobotConfig || {};
        var timing = {};
        var k;
        for (k in DEFAULT_TIMING) timing[k] = DEFAULT_TIMING[k];
        if (user.timing) for (k in user.timing) timing[k] = user.timing[k];

        return {
            greeting: user.greeting != null ? user.greeting : (d.greeting || DEFAULT_GREETING),
            interval: Math.max(MIN_GAP, (user.interval != null ? num(user.interval, 60) : num(d.interval, 60)) * 1000),
            firstDelay: Math.max(0, (user.firstDelay != null ? num(user.firstDelay, 1.2) : num(d.delay, 1.2)) * 1000),
            timing: timing
        };
    }

    /* ------------------------------------------------------------------
     * Kelas utama
     * ------------------------------------------------------------------ */
    function Mascot(root) {
        this.root = root;
        this.tilt = root.querySelector('[data-flv-tilt]');
        this.bubble = root.querySelector('[data-flv-bubble]');
        this.text = root.querySelector('[data-flv-text]');
        this.cfg = readConfig(root);

        this.pose = { x: 0, y: 0, r: 0 };
        this.running = false;
        this.abortCtl = null;     // controller sederhana: { aborted, listeners[] }
        this.timer = null;
        this.resizeTimer = null;
        this.nextDue = 0;
        this.resume = false;
        this.lastW = document.documentElement.clientWidth;

        this._onVisibility = this._onVisibility.bind(this);
        this._onResize = this._onResize.bind(this);
    }

    var P = Mascot.prototype;

    /* ---------- Siklus hidup ---------- */
    P.start = function () {
        document.addEventListener('visibilitychange', this._onVisibility);
        window.addEventListener('resize', this._onResize);
        this._schedule(this.cfg.firstDelay);
    };

    P.destroy = function () {
        document.removeEventListener('visibilitychange', this._onVisibility);
        window.removeEventListener('resize', this._onResize);
        this._clearTimer();
        clearTimeout(this.resizeTimer);
        this.resizeTimer = null;
        this._cancelRun();
        this._reset();
        this.running = false;
    };

    P.replay = function () {
        if (this.running || document.hidden) return;
        this._clearTimer();
        this._play();
    };

    /* ---------- Penjadwalan (setTimeout berantai, bukan setInterval) ---------- */
    P._schedule = function (delay) {
        var self = this;
        this._clearTimer();
        this.nextDue = now() + delay;
        this.timer = setTimeout(function () {
            self.timer = null;
            if (document.hidden) return;       // tab tidak aktif → ditunda, dilanjutkan di visibilitychange
            self._play();
        }, delay);
    };

    P._clearTimer = function () {
        if (this.timer) { clearTimeout(this.timer); this.timer = null; }
    };

    P._onVisibility = function () {
        if (document.hidden) {
            this._clearTimer();
            if (this.running) { this._cancelRun(); this.resume = true; }
        } else if (this.resume) {
            this.resume = false;
            this._schedule(1200);
        } else if (!this.running && !this.timer) {
            this._schedule(Math.max(800, this.nextDue - now()));
        }
    };

    P._onResize = function () {
        var self = this;
        var w = document.documentElement.clientWidth;
        if (w === this.lastW) return;          // abaikan perubahan tinggi (address bar mobile)
        this.lastW = w;
        clearTimeout(this.resizeTimer);
        this.resizeTimer = setTimeout(function () {
            self.resizeTimer = null;
            if (self.running) {
                self._cancelRun();
                self._clearTimer();
                self._schedule(2500);
            }
        }, 200);
    };

    /* ---------- Eksekusi satu putaran ---------- */
    P._play = function () {
        var self = this;
        if (this.running) return Promise.resolve();   // anti-duplikasi

        this.running = true;
        this.resume = false;
        var startedAt = now();
        var ctl = this.abortCtl = this._makeController();
        var completed = false;
        var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        return Promise.resolve()
            .then(function () { return reduced ? self._sequenceReduced(ctl) : self._sequence(ctl); })
            .then(function () { completed = true; })
            .catch(function (err) {
                if (!err || err.name !== 'AbortError') {
                    if (window.console) console.error('[FlavoryRobot]', err);
                }
            })
            .then(function () {
                self._reset();
                self.running = false;
                if (self.abortCtl === ctl) self.abortCtl = null;
                if (completed) {
                    // Interval dihitung dari AWAL animasi, sehingga siklus tetap 60 detik.
                    var elapsed = now() - startedAt;
                    self._schedule(Math.max(MIN_GAP, self.cfg.interval - elapsed));
                }
            });
    };

    P._makeController = function () {
        return {
            aborted: false,
            fns: [],
            on: function (fn) { this.fns.push(fn); },
            off: function (fn) {
                var i = this.fns.indexOf(fn);
                if (i > -1) this.fns.splice(i, 1);
            },
            abort: function () {
                if (this.aborted) return;
                this.aborted = true;
                var list = this.fns.slice();
                this.fns.length = 0;
                for (var i = 0; i < list.length; i++) list[i]();
            }
        };
    };

    P._cancelRun = function () {
        if (this.abortCtl) this.abortCtl.abort();
    };

    /* ---------- Primitif: sleep & animate (keduanya bisa dibatalkan) ---------- */
    P._sleep = function (ms, ctl) {
        return new Promise(function (resolve, reject) {
            if (ctl.aborted) return reject(abortError());
            var id;
            var onAbort = function () { clearTimeout(id); reject(abortError()); };
            id = setTimeout(function () { ctl.off(onAbort); resolve(); }, ms);
            ctl.on(onAbort);
        });
    };

    P._animate = function (el, keyframes, options, ctl, commit) {
        return new Promise(function (resolve, reject) {
            if (ctl.aborted) return reject(abortError());
            var anim = el.animate(keyframes, options);
            var onAbort = function () { anim.cancel(); reject(abortError()); };
            ctl.on(onAbort);
            anim.onfinish = function () {
                ctl.off(onAbort);
                if (commit) commit();   // simpan posisi akhir sebagai inline style…
                anim.cancel();          // …lalu lepas animasi (tanpa kedipan)
                resolve();
            };
        });
    };

    /* ---------- Gerak: translasi (root) + rotasi (tilt) berjalan paralel ---------- */
    P._place = function (pose) {
        this.pose = { x: pose.x, y: pose.y, r: pose.r };
        this.root.style.transform = 'translate3d(' + pose.x + 'px,' + pose.y + 'px,0)';
        this.tilt.style.transform = 'rotate(' + pose.r + 'deg)';
    };

    /**
     * frames: [{ at: 0..1, x, y, r }, ...]  (frame terakhir harus at:1)
     * Posisi awal diambil otomatis dari this.pose.
     */
    P._move = function (frames, duration, easing, ctl) {
        var self = this;
        var all = [{ at: 0, x: this.pose.x, y: this.pose.y, r: this.pose.r }].concat(frames);
        var end = all[all.length - 1];

        function build(fn) {
            return all.map(function (f) { return { offset: f.at, transform: fn(f) }; });
        }
        var opts = { duration: duration, easing: easing || 'linear', fill: 'forwards' };

        return Promise.all([
            this._animate(this.root, build(function (f) {
                return 'translate3d(' + f.x + 'px,' + f.y + 'px,0)';
            }), opts, ctl, function () {
                self.root.style.transform = 'translate3d(' + end.x + 'px,' + end.y + 'px,0)';
            }),
            this._animate(this.tilt, build(function (f) {
                return 'rotate(' + f.r + 'deg)';
            }), opts, ctl, function () {
                self.tilt.style.transform = 'rotate(' + end.r + 'deg)';
            })
        ]).then(function () {
            self.pose = { x: end.x, y: end.y, r: end.r };
        });
    };

    /* ---------- Ekspresi, roket, lengan, bubble ---------- */
    P._set = function (state) {
        for (var k in state) this.root.dataset[k] = state[k];
    };

    P._showBubble = function (text) {
        this.text.textContent = text;
        this.bubble.classList.add('is-visible');
    };

    P._hideBubble = function () {
        this.bubble.classList.remove('is-visible');
    };

    P._metrics = function () {
        var vw = document.documentElement.clientWidth || window.innerWidth;
        var vh = window.innerHeight;
        var size = this.root.offsetWidth || 120;
        var margin = clamp(vw * 0.04, 12, 64);
        var maxAlt = Math.max(40, vh - size * 1.5 - 120);       // sisakan ruang untuk bubble
        var alt = Math.min(clamp(vh * 0.22, 60, 200), maxAlt);  // tinggi terbang
        return { vw: vw, vh: vh, size: size, margin: margin, alt: alt };
    };

    /* ---------- Reset bersih (idempotent) ---------- */
    P._reset = function () {
        this.bubble.classList.remove('is-visible');
        this.root.classList.remove('is-hover', 'is-active');
        this._set({ expression: 'smile', thrust: 'off', arms: 'rest', look: 'none', dizzy: 'off' });
        this.root.style.transform = '';
        this.root.style.opacity = '';
        this.tilt.style.transform = '';
        this.pose = { x: 0, y: 0, r: 0 };
    };

    /* ==================================================================
     * TIMELINE UTAMA
     * ================================================================== */
    P._sequence = function (ctl) {
        var self = this;
        var t = this.cfg.timing;
        var M = this._metrics();
        var s = M.size;

        var greetX = M.vw - s - M.margin;
        var startX = M.vw + s * 0.3;
        var fallX = Math.max(
            M.margin + s * 1.15,
            Math.min(greetX - s * 0.7, M.vw * 0.4 - s / 2)
        );
        var groundY = s * LAY_GROUND;
        var upY = s * (UP_GROUND - HOVER_LIFT);
        var alt = M.alt;

        function sleep(ms) { return self._sleep(ms, ctl); }
        function move(frames, dur, ease) { return self._move(frames, dur, ease, ctl); }

        // Posisi awal: di luar layar sebelah kanan, lalu tampilkan elemen
        this._place({ x: startX, y: -alt, r: -8 });
        this._set({ expression: 'smile', thrust: 'cruise', arms: 'rest', look: 'left', dizzy: 'off' });
        this.root.classList.add('is-active');

        return Promise.resolve()

            /* ---------- FASE 1 — Muncul dari sisi kanan ---------- */
            .then(function () {
                return move([
                    { at: 0.6, x: startX + (greetX - startX) * 0.82, y: -alt - 14, r: -9 },
                    { at: 1,   x: greetX, y: -alt, r: 0 }
                ], t.enter, 'cubic-bezier(.2,.7,.25,1)');
            })
            .then(function () {
                self.root.classList.add('is-hover');
                self._set({ thrust: 'hover', look: 'none' });
                return sleep(t.settle);
            })

            /* ---------- FASE 2 — Menyapa pengguna ---------- */
            .then(function () {
                self._set({ expression: 'happy', arms: 'wave' });
                self._showBubble(self.cfg.greeting);
                return sleep(t.greetHold);
            })
            .then(function () {
                self._hideBubble();
                self._set({ arms: 'rest', expression: 'smile' });
                return sleep(t.bubbleOut);
            })

            /* ---------- FASE 3 — Terbang ke kiri, kehilangan keseimbangan, jatuh ---------- */
            .then(function () {
                self._set({ thrust: 'cruise', look: 'left' });
                return move([
                    { at: 0.5, x: (greetX + fallX) / 2, y: -alt - 8, r: -9 },
                    { at: 1,   x: fallX, y: -alt, r: -10 }
                ], t.flyLeft, 'cubic-bezier(.45,.05,.4,1)');
            })
            .then(function () {
                // oleng + mesin tersendat lalu padam
                self._set({ expression: 'surprised', arms: 'flail', look: 'none' });
                var x = self.pose.x;
                var sputter = self._sleep(t.wobble * 0.38, ctl)
                    .then(function () { self._set({ thrust: 'hover' }); return self._sleep(t.wobble * 0.12, ctl); })
                    .then(function () { self._set({ thrust: 'cruise' }); return self._sleep(t.wobble * 0.12, ctl); })
                    .then(function () { self._set({ thrust: 'off' }); });   // api padam seketika
                return Promise.all([
                    move([
                        { at: 0.2, x: x - 0.04 * s, y: -alt + 0.02 * s, r: -8 },
                        { at: 0.4, x: x - 0.09 * s, y: -alt + 0.05 * s, r: 7 },
                        { at: 0.6, x: x - 0.14 * s, y: -alt + 0.09 * s, r: -18 },
                        { at: 0.8, x: x - 0.20 * s, y: -alt + 0.14 * s, r: 12 },
                        { at: 1,   x: x - 0.26 * s, y: -alt + 0.20 * s, r: -30 }
                    ], t.wobble, 'cubic-bezier(.4,0,.7,1)'),
                    sputter
                ]);
            })
            .then(function () {
                // jatuh dipercepat gravitasi sambil berputar
                var x = self.pose.x;
                return move([
                    { at: 0.78, x: x - 0.50 * s, y: groundY - 0.04 * s, r: -78 },
                    { at: 1,    x: x - 0.60 * s, y: groundY,            r: -98 }
                ], t.fall, 'cubic-bezier(.5,0,.85,.55)');
            })
            .then(function () {
                // memantul sekali
                var x = self.pose.x;
                return move([
                    { at: 0.45, x: x - 0.10 * s, y: groundY - 0.20 * s, r: -88 },
                    { at: 1,    x: x - 0.16 * s, y: groundY,            r: -84 }
                ], t.bounce, 'ease-in-out');
            })
            .then(function () {
                self._set({ expression: 'sad', arms: 'rest', dizzy: 'on' });
                return sleep(t.groundHold);
            })

            /* ---------- FASE 4 — Bangkit & tersipu malu ---------- */
            .then(function () {
                var x = self.pose.x;
                self._set({ dizzy: 'off', thrust: 'cruise', expression: 'smile' });
                return move([
                    { at: 0.45, x: x - 0.04 * s, y: groundY - 0.55 * s, r: -40 },
                    { at: 1,    x: x,            y: upY,               r: 0 }
                ], t.rise, 'cubic-bezier(.4,0,.2,1)');
            })
            .then(function () {
                self._set({ thrust: 'hover', expression: 'shy', arms: 'shy' });
                return sleep(t.shyHold);
            })
            .then(function () {
                self._set({ expression: 'happy', arms: 'wave' });
                self._showBubble('Haaiiiii...');
                return sleep(t.greetHold);
            })
            .then(function () {
                self._hideBubble();
                self._set({ arms: 'rest', expression: 'smile' });
                return sleep(t.bubbleOut);
            })

            /* ---------- FASE 5 — Terbang terburu-buru keluar layar kiri ---------- */
            .then(function () {
                var x = self.pose.x;
                self._set({ expression: 'panic', arms: 'panic', thrust: 'cruise', look: 'left' });
                return move([                                     // ancang-ancang: condong ke belakang
                    { at: 1, x: x + 0.05 * s, y: upY + 0.05 * s, r: 10 }
                ], t.windup, 'ease-out');
            })
            .then(function () {
                var x = self.pose.x;
                var outX = -s * 1.8;
                self._set({ thrust: 'boost' });
                return move([
                    { at: 0.35, x: x + (outX - x) * 0.12, y: upY - 0.1 * s, r: -18 },
                    { at: 1,    x: outX,                  y: -alt * 0.8,    r: -24 }
                ], t.dash, 'cubic-bezier(.6,0,.9,.5)');
            });
        // Pembersihan akhir (reset elemen) ditangani _play() → _reset()
    };

    /* ------------------------------------------------------------------
     * Mode prefers-reduced-motion: tanpa terbang/jatuh, hanya muncul-sapa-hilang
     * ------------------------------------------------------------------ */
    P._sequenceReduced = function (ctl) {
        var self = this;
        var t = this.cfg.timing;
        var M = this._metrics();

        this._place({ x: M.vw - M.size - M.margin, y: -M.size * 0.15, r: 0 });
        this._set({ expression: 'happy', thrust: 'off', arms: 'rest', look: 'none', dizzy: 'off' });
        this.root.style.opacity = '0';
        this.root.classList.add('is-active');

        return this._animate(this.root, [{ opacity: 0 }, { opacity: 1 }],
                { duration: 400, fill: 'forwards' }, ctl,
                function () { self.root.style.opacity = '1'; })
            .then(function () {
                self._showBubble(self.cfg.greeting);
                return self._sleep(t.reducedHold, ctl);
            })
            .then(function () {
                self._hideBubble();
                return self._sleep(300, ctl);
            })
            .then(function () {
                return self._animate(self.root, [{ opacity: 1 }, { opacity: 0 }],
                    { duration: 400, fill: 'forwards' }, ctl,
                    function () { self.root.style.opacity = '0'; });
            });
    };

    /* ------------------------------------------------------------------
     * Bootstrap (singleton)
     * ------------------------------------------------------------------ */
    var instance = null;

    var api = {
        init: function () {
            if (instance && instance.root.isConnected) return instance;
            if (instance) { instance.destroy(); instance = null; }

            var root = document.querySelector('[data-flavory-robot]');
            if (!root || !root.animate) return null;   // komponen tidak ada / browser terlalu lama

            instance = new Mascot(root);
            instance.start();
            return instance;
        },
        replay: function () { if (instance) instance.replay(); },
        destroy: function () { if (instance) { instance.destroy(); instance = null; } }
    };

    window.FlavoryRobot = api;

    // Jalankan setelah halaman selesai dimuat
    if (document.readyState === 'complete') {
        setTimeout(api.init, 0);
    } else {
        window.addEventListener('load', function () { api.init(); }, { once: true });
    }

})(window, document);