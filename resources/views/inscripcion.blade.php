<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAWSCAPE | Inscripción de Equipos</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-200 font-sans antialiased">
    <div class="flex flex-col items-center justify-center min-h-screen p-4 sm:p-6">
        <div class="w-full max-w-2xl mx-auto bg-gray-800/50 backdrop-blur-sm border border-green-500/20 rounded-2xl shadow-lg shadow-green-500/5">

            <!-- Header -->
            <div class="border-b border-green-500/20 p-6 sm:p-8 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-green-400 font-orbitron tracking-wider">⟨DAWSCAPE⟩</h1>
                <p class="mt-2 text-lg text-green-300/80">Sistema de Inscripción de Equipos</p>
            </div>

            <div class="p-6 sm:p-8">
                <!-- Description Box -->
                <div class="mb-8 text-center bg-gray-900/60 p-6 rounded-lg border border-green-500/10">
                    <h2 class="text-xl font-bold text-green-400 mb-3">Bienvenido al Desafío</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Estás a punto de entrar en un <strong>Escape Room digital</strong> lleno de misterios. Resuelve acertijos, descifra códigos y trabaja en equipo para escapar a tiempo. ¿Aceptas el reto?
                    </p>
                </div>

                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg text-center" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg" role="alert">
                        <p class="font-bold mb-2">Se encontraron los siguientes errores:</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('inscripcion.guardar') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="nombre_equipo" class="block text-sm font-medium text-green-400 mb-2">Nombre del Equipo</label>
                        <input type="text" id="nombre_equipo" name="nombre_equipo"
                               class="w-full bg-gray-900/50 border border-green-500/30 rounded-lg py-3 px-4 text-gray-200 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"
                               placeholder="Ej: Los Detectives Digitales" value="{{ old('nombre_equipo') }}" required maxlength="60">
                    </div>

                    <div>
                        <label for="correo" class="block text-sm font-medium text-green-400 mb-2">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo"
                               class="w-full bg-gray-900/50 border border-green-500/30 rounded-lg py-3 px-4 text-gray-200 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"
                               placeholder="equipo@ejemplo.com" value="{{ old('correo') }}" required maxlength="120">
                    </div>

                    <div>
                        <label for="clase" class="block text-sm font-medium text-green-400 mb-2">Clase / Grupo</label>
                        <input type="text" id="clase" name="clase"
                               class="w-full bg-gray-900/50 border border-green-500/30 rounded-lg py-3 px-4 text-gray-200 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"
                               placeholder="Ej: 2º DAW" value="{{ old('clase') }}" required maxlength="20">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-600 text-gray-900 font-bold py-3 px-4 rounded-lg transition-transform transform hover:scale-105">
                            Registrar Equipo
                        </button>
                        <a href="{{ route('inicio') }}"
                           class="w-full text-center bg-gray-700 hover:bg-gray-600 text-gray-200 font-bold py-3 px-4 rounded-lg transition-transform transform hover:scale-105">
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
