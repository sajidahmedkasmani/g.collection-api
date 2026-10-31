<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} – Log in</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">

    <style>
        :root {
            --night: #1B1048;
            --night-deep: #0F0A2C;
            --violet: #7C5CFF;
            --ink: #14102B;
            --coral: #FF7A59;
            --sun: #FFD24A;
            --paper: #F7F5FF;
            --line: #D9D3F2;
            --muted: #6B6590;
            --danger: #D6285A;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }
        body {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            color: var(--ink);
            background: var(--paper);
            -webkit-font-smoothing: antialiased;
        }

        .page { min-height: 100%; display: grid; grid-template-columns: 1.05fr 1fr; }

        /* ---------- Left: animated stage ---------- */
        .stage-panel {
            position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 40px 48px;
            background: var(--night);
            color: #fff;
        }
        .aurora { position: absolute; inset: 0; pointer-events: none; }
        .aurora span {
            position: absolute; border-radius: 50%; filter: blur(70px); opacity: .55;
            animation: drift 16s ease-in-out infinite alternate;
        }
        .aurora span:nth-child(1) { width: 380px; height: 380px; background: var(--violet); top: -80px; left: -60px; }
        .aurora span:nth-child(2) { width: 300px; height: 300px; background: #FF5D8F; bottom: 10%; right: -80px; animation-duration: 20s; animation-delay: -6s; }
        .aurora span:nth-child(3) { width: 260px; height: 260px; background: #35D6B0; bottom: -90px; left: 25%; opacity: .35; animation-duration: 24s; animation-delay: -12s; }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(60px, 40px) scale(1.18); }
        }

        .brand { position: relative; font-weight: 800; font-size: 22px; letter-spacing: -.02em; display: flex; align-items: center; gap: 10px; }
        .brand i { width: 28px; height: 28px; border-radius: 9px; background: var(--sun); display: inline-block; transform: rotate(12deg); }

        .stage-wrap { position: relative; flex: 1; display: flex; align-items: flex-end; justify-content: center; min-height: 320px; }
        .stage { position: relative; width: 420px; height: 340px; transform-origin: bottom center; }

        .char { position: absolute; bottom: 0; transition: transform .4s cubic-bezier(.2,.8,.2,1); }
        .c-violet { left: 70px;  width: 180px; height: 340px; background: var(--violet); border-radius: 16px 16px 0 0; z-index: 1; }
        .c-ink    { left: 215px; width: 120px; height: 240px; background: var(--ink); border-radius: 14px 14px 0 0; z-index: 2; box-shadow: 0 0 0 2px rgba(255,255,255,.08); }
        .c-coral  { left: 0;     width: 240px; height: 120px; background: var(--coral); border-radius: 120px 120px 0 0; z-index: 3; }
        .c-sun    { left: 280px; width: 140px; height: 170px; background: var(--sun); border-radius: 70px 70px 0 0; z-index: 3; }

        .eyes { position: absolute; display: flex; gap: 18px; transition: transform .35s cubic-bezier(.2,.8,.2,1); }
        .c-violet .eyes { top: 52px; left: 40px; }
        .c-ink .eyes    { top: 36px; left: 26px; gap: 14px; }
        .c-coral .eyes  { top: 58px; left: 82px; gap: 26px; }
        .c-sun .eyes    { top: 54px; left: 38px; gap: 20px; }

        .eye { position: relative; width: 20px; height: 20px; border-radius: 50%; background: #fff; display: grid; place-items: center; overflow: hidden; transition: transform .18s ease; }
        .c-coral .eye, .c-sun .eye { width: 12px; height: 12px; background: var(--ink); }
        .pupil { width: 9px; height: 9px; border-radius: 50%; background: var(--ink); will-change: transform; }
        .c-coral .pupil, .c-sun .pupil { width: 100%; height: 100%; background: transparent; }

        .mouth { position: absolute; left: 44px; top: 96px; width: 40px; height: 14px; border-bottom: 4px solid var(--ink); border-radius: 0 0 40px 40px; transition: all .3s ease; }

        /* eyes closed while typing a hidden password */
        .stage.shy .eye { transform: scaleY(.08); }
        .stage.shy .eyes { transform: translateY(4px); }
        .stage.shy .mouth { width: 16px; height: 16px; border: 4px solid var(--ink); border-radius: 50%; left: 56px; }
        .stage.blink .eye { transform: scaleY(.08); }
        /* when password is visible: look away, shy peek */
        .stage.peek .c-violet { transform: skewX(-4deg); }
        .stage.peek .c-ink { transform: skewX(4deg) translateY(4px); }

        .tagline { position: relative; max-width: 420px; }
        .tagline h2 { margin: 0 0 6px; font-size: 30px; line-height: 1.1; letter-spacing: -.03em; font-weight: 700; }
        .tagline p { margin: 0; color: #C9C1F5; font-size: 16px; line-height: 1.5; }

        /* ---------- Right: form ---------- */
        .form-panel { display: flex; align-items: center; justify-content: center; padding: 40px 24px; }
        .card { width: 100%; max-width: 400px; }
        .card.shake { animation: shake .5s cubic-bezier(.36,.07,.19,.97); }
        @keyframes shake {
            10%, 90% { transform: translateX(-2px); }
            20%, 80% { transform: translateX(4px); }
            30%, 50%, 70% { transform: translateX(-8px); }
            40%, 60% { transform: translateX(8px); }
        }
        .card h1 { margin: 0 0 6px; font-size: 38px; line-height: 1.05; letter-spacing: -.035em; font-weight: 800; }
        .card .sub { margin: 0 0 28px; color: var(--muted); font-size: 16px; }

        .status { background: #E4F8F1; color: #0B6B50; border-radius: 12px; padding: 12px 14px; margin-bottom: 18px; font-size: 14px; }

        .field { margin-bottom: 18px; }
        .field label { display: block; font-weight: 500; font-size: 14px; margin-bottom: 6px; }
        .control { position: relative; }
        .control input[type=email], .control input[type=password], .control input[type=text] {
            width: 100%; height: 52px; padding: 0 16px; font: inherit; font-size: 16px; color: var(--ink);
            background: #fff; border: 2px solid var(--line); border-radius: 14px; outline: none;
            transition: border-color .2s, box-shadow .2s, transform .2s;
        }
        .control input:hover { border-color: #BDB3EE; }
        .control input:focus { border-color: var(--violet); box-shadow: 0 0 0 5px rgba(124,92,255,.18); }
        .control.has-toggle input { padding-right: 56px; }
        .toggle {
            position: absolute; right: 6px; top: 6px; width: 40px; height: 40px; border: 0; border-radius: 10px;
            background: transparent; color: var(--muted); cursor: pointer; display: grid; place-items: center;
            transition: background .2s, color .2s;
        }
        .toggle:hover { background: #EEEAFD; color: var(--violet); }
        .toggle:focus-visible, .btn:focus-visible, .forgot:focus-visible { outline: 3px solid var(--violet); outline-offset: 2px; }
        .toggle svg { width: 22px; height: 22px; }
        .toggle .off { display: none; }
        .toggle.on .off { display: block; } .toggle.on .on-eye { display: none; }

        .err { margin: 6px 0 0; color: var(--danger); font-size: 14px; }

        .row { display: flex; align-items: center; justify-content: space-between; margin: 4px 0 24px; font-size: 14px; gap: 12px; flex-wrap: wrap; }
        .check { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .check input { position: absolute; opacity: 0; }
        .box { width: 22px; height: 22px; border: 2px solid var(--line); border-radius: 7px; background: #fff; display: grid; place-items: center; transition: all .2s; }
        .box svg { width: 14px; height: 14px; stroke: #fff; stroke-width: 3; fill: none; stroke-dasharray: 20; stroke-dashoffset: 20; transition: stroke-dashoffset .25s ease .05s; }
        .check input:checked + .box { background: var(--violet); border-color: var(--violet); }
        .check input:checked + .box svg { stroke-dashoffset: 0; }
        .check input:focus-visible + .box { outline: 3px solid var(--violet); outline-offset: 2px; }
        .forgot { color: var(--violet); font-weight: 500; text-decoration: none; border-radius: 6px; }
        .forgot:hover { text-decoration: underline; text-underline-offset: 3px; }

        .btn {
            position: relative; overflow: hidden; width: 100%; height: 54px; border: 0; border-radius: 14px; cursor: pointer;
            font: inherit; font-size: 17px; font-weight: 700; color: #fff; letter-spacing: -.01em;
            background: linear-gradient(135deg, var(--violet), #5A3DE0);
            box-shadow: 0 10px 24px -8px rgba(124,92,255,.7);
            transition: transform .15s ease, box-shadow .2s ease;
        }
        .btn::after {
            content: ''; position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
            background: linear-gradient(100deg, transparent, rgba(255,255,255,.35), transparent);
            transform: skewX(-20deg); transition: left .6s ease;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 16px 30px -10px rgba(124,92,255,.8); }
        .btn:hover::after { left: 130%; }
        .btn:active { transform: translateY(0) scale(.99); }
        .btn .spin { display: none; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; margin: 0 auto; }
        .btn.loading .label { display: none; } .btn.loading .spin { display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ---------- Responsive ---------- */
        @media (max-width: 900px) {
            .page { grid-template-columns: 1fr; }
            .stage-panel { padding: 24px; min-height: 300px; }
            .tagline { display: none; }
            .stage { transform: scale(.62); }
            .stage-wrap { min-height: 220px; }
            .form-panel { padding: 32px 20px 48px; }
            .card h1 { font-size: 32px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- Left: animated characters -->
    <section class="stage-panel" aria-hidden="true">
        <div class="aurora"><span></span><span></span><span></span></div>

        <div class="brand"><i></i>{{ config('app.name', 'Laravel') }}</div>

        <div class="stage-wrap">
            <div class="stage" id="stage">
                <div class="char c-violet">
                    <div class="eyes"><div class="eye"><div class="pupil"></div></div><div class="eye"><div class="pupil"></div></div></div>
                </div>
                <div class="char c-ink">
                    <div class="eyes"><div class="eye"><div class="pupil"></div></div><div class="eye"><div class="pupil"></div></div></div>
                </div>
                <div class="char c-coral">
                    <div class="eyes"><div class="eye"><div class="pupil"></div></div><div class="eye"><div class="pupil"></div></div></div>
                </div>
                <div class="char c-sun">
                    <div class="eyes"><div class="eye"><div class="pupil"></div></div><div class="eye"><div class="pupil"></div></div></div>
                    <div class="mouth"></div>
                </div>
            </div>
        </div>

        <div class="tagline">
            <h2>Aapka kaam intezaar kar raha hai.</h2>
            <p>Log in karein aur wahin se shuru karein jahan chhora tha.</p>
        </div>
    </section>

    <!-- Right: form -->
    <main class="form-panel">
        <div class="card" id="card">
            <h1>Welcome back</h1>
            <p class="sub">Log in to continue to your account.</p>

            @if (session('status'))
                <div class="status" role="status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="field">
                    <label for="email">{{ __('Email') }}</label>
                    <div class="control">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                    </div>
                    @error('email') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="password">{{ __('Password') }}</label>
                    <div class="control has-toggle">
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        <button type="button" class="toggle" id="toggle" aria-label="Show password" aria-pressed="false">
                            <svg class="on-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.9 10.9 0 0 1 12 19C5 19 1 12 1 12a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A10.9 10.9 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="row">
                    <label class="check" for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span class="box"><svg viewBox="0 0 24 24"><path d="M4 12l5 5L20 6"/></svg></span>
                        {{ __('Remember me') }}
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    @endif
                </div>

                <button type="submit" class="btn" id="submitBtn">
                    <span class="label">{{ __('Log in') }}</span>
                    <span class="spin" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </main>
</div>

<script>
(function () {
    var stage = document.getElementById('stage');
    var pass = document.getElementById('password');
    var toggle = document.getElementById('toggle');
    var form = document.getElementById('loginForm');
    var btn = document.getElementById('submitBtn');
    var card = document.getElementById('card');
    var email = document.getElementById('email');
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var eyes = Array.prototype.slice.call(document.querySelectorAll('.eye'));

    // Pupils follow the cursor
    function look(x, y) {
        eyes.forEach(function (eye) {
            var r = eye.getBoundingClientRect();
            var cx = r.left + r.width / 2, cy = r.top + r.height / 2;
            var a = Math.atan2(y - cy, x - cx);
            var d = Math.min(5, Math.hypot(x - cx, y - cy) / 25);
            eye.firstElementChild.style.transform = 'translate(' + Math.cos(a) * d + 'px,' + Math.sin(a) * d + 'px)';
        });
    }
    if (!reduce) {
        document.addEventListener('mousemove', function (e) { look(e.clientX, e.clientY); });
        // When typing the email, look toward the field
        email.addEventListener('input', function () {
            var r = email.getBoundingClientRect();
            var p = Math.min(1, email.value.length / 24);
            look(r.left + r.width * p, r.top + r.height / 2);
        });
    }

    // Shy mode: close eyes while a hidden password is focused
    function sync() {
        var hidden = pass.type === 'password';
        stage.classList.toggle('shy', document.activeElement === pass && hidden);
        stage.classList.toggle('peek', !hidden);
    }
    pass.addEventListener('focus', sync);
    pass.addEventListener('blur', sync);

    toggle.addEventListener('click', function () {
        var show = pass.type === 'password';
        pass.type = show ? 'text' : 'password';
        toggle.classList.toggle('on', show);
        toggle.setAttribute('aria-pressed', String(show));
        toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        pass.focus();
        sync();
    });

    // Random blink
    if (!reduce) {
        (function blink() {
            setTimeout(function () {
                if (!stage.classList.contains('shy')) {
                    stage.classList.add('blink');
                    setTimeout(function () { stage.classList.remove('blink'); }, 150);
                }
                blink();
            }, 2500 + Math.random() * 3000);
        })();
    }

    // Loading state on submit
    form.addEventListener('submit', function () {
        btn.classList.add('loading');
        btn.setAttribute('aria-busy', 'true');
    });

    // Shake the card if the server returned errors
    @if ($errors->any())
        card.classList.add('shake');
    @endif
})();
</script>
</body>
</html>