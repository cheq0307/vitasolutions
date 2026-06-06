<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaSolutions — Acceso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; }

        .bg-mesh {
            background-color: #0a1628;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(15,110,86,0.35) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(15,110,86,0.18) 0%, transparent 55%),
                radial-gradient(ellipse 40% 40% at 60% 20%, rgba(30,41,59,0.8) 0%, transparent 50%);
        }

        .card-glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .input-dark {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.10);
            color: #f1f5f9;
            transition: border-color 0.2s, background 0.2s;
        }
        .input-dark::placeholder { color: rgba(148,163,184,0.5); }
        .input-dark:focus {
            outline: none;
            border-color: rgba(15,110,86,0.7);
            background: rgba(255,255,255,0.09);
            box-shadow: 0 0 0 3px rgba(15,110,86,0.15);
        }

        .btn-teal {
            background: linear-gradient(135deg, #0F6E56 0%, #0d9068 100%);
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
        }
        .btn-teal:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(15,110,86,0.4);
            opacity: 0.95;
        }
        .btn-teal:active { transform: translateY(0); }

        .dot-grid {
            background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        .serif { font-family: 'DM Serif Display', serif; }

        /* Línea decorativa izquierda */
        .accent-bar {
            width: 3px;
            background: linear-gradient(180deg, #0F6E56 0%, transparent 100%);
            border-radius: 99px;
        }
    </style>
</head>
<body class="bg-mesh dot-grid min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo / marca --}}
        <div class="text-center mb-10 fade-up">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="accent-bar h-8"></div>
                <span class="serif text-4xl text-white tracking-tight">VitaSolutions</span>
            </div>
            <p class="text-slate-400 text-sm tracking-widest uppercase font-medium">Sistema nutraceutico</p>
        </div>

        {{-- Card --}}
        <div class="card-glass rounded-2xl p-8 fade-up delay-1">

            <h1 class="text-xl font-semibold text-white mb-1">Bienvenido de vuelta</h1>
            <p class="text-slate-400 text-sm mb-8">Ingresa tus credenciales para continuar</p>

            {{-- Errores --}}
            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3 mb-6 fade-up">
                    <p class="text-red-400 text-sm font-medium">
                        {{ $errors->first() }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="fade-up delay-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        Correo electrónico
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           autocomplete="email" autofocus
                           class="input-dark w-full rounded-xl px-4 py-3 text-sm"
                           placeholder="correo@ejemplo.com">
                </div>

                {{-- Contraseña --}}
                <div class="fade-up delay-3">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="pwd"
                               autocomplete="current-password"
                               class="input-dark w-full rounded-xl px-4 py-3 text-sm pr-12"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePwd()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fade-up delay-4 pt-2">
                    <button type="submit"
                            class="btn-teal w-full text-white font-semibold text-sm py-3 rounded-xl">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-slate-600 text-xs mt-8 fade-up delay-4">
            © {{ date('Y') }} VitaSolutions · Todos los derechos reservados
        </p>

    </div>

    <script>
        function togglePwd() {
            const input = document.getElementById('pwd');
            const icon  = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>
</html>