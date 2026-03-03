<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAWSCAPE | Escape Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #050505; color: #00ff00; font-family: 'Courier New', Courier, monospace;
            height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0;
            background-image: radial-gradient(circle, #050505 0%, #001100 100%);
        }
        .terminal-box {
            border: 2px solid #00ff00; padding: 40px; box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
            background: rgba(0, 20, 0, 0.8); max-width: 500px; width: 100%;
        }
        h1 {
            font-size: 3.5rem; font-weight: bold; text-align: center; letter-spacing: 5px;
            text-shadow: 0 0 10px #00ff00; margin-bottom: 30px;
        }
        .form-control {
            background-color: transparent; border: 1px solid #00ff00; color: #00ff00;
            border-radius: 0; font-family: 'Courier New', Courier, monospace;
        }
        .form-control:focus {
            background-color: rgba(0, 255, 0, 0.1); border-color: #00ff00; color: #00ff00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        .form-control::placeholder { color: rgba(0, 255, 0, 0.5); }
        .btn-start {
            width: 100%; background: #00ff00; color: #000; border: none; padding: 15px;
            font-size: 1.5rem; font-weight: bold; letter-spacing: 2px; margin-top: 20px;
            transition: all 0.3s; cursor: pointer; text-transform: uppercase;
        }
        .btn-start:hover {
            background: #fff; color: #000; box-shadow: 0 0 20px #fff;
        }
        .glitch-text { animation: blink 2s infinite; }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    </style>
</head>
<body>

    <div class="terminal-box">
        <h1>DAWSCAPE</h1>
        <p class="text-center mb-4 glitch-text">>_ ACCESO RESTRINGIDO. IDENTIFÍQUESE.</p>

        <form action="/empezar" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">>_ CORREO INSTITUCIONAL</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="usuario@estudiante.xtart.com" 
                    required 
                    autocomplete="off"
                >
                <small class="text-secondary" style="font-size: 0.7rem;">* Solo se permite el dominio @estudiante.xtart.com</small>
            </div>

            <div class="mb-3">
                <label class="form-label">>_ NICKNAME / ALIAS</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Neo" required>
            </div>

            <div class="mb-4">
                <label class="form-label">>_ CLASE</label>
                <input type="text" name="clase" class="form-control" placeholder="Ej: 1DAW" required>
            </div>

            @if(session('error'))
                <div class="alert alert-danger" style="background: transparent; border: 1px solid red; color: red;">
                    >_ ERROR: {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 form-check text-start">
                <input type="checkbox" class="form-check-input" id="checkHonestidad" required>
                <label class="form-check-label text-white-50" for="checkHonestidad" style="font-size: 0.8rem;">
                    Certifico bajo honor que este es mi correo corporativo real (@estudiante.xtart.com). 
                    El uso de identidad falsa conllevará la expulsión del ranking.
                </label>
            </div>
            <button type="submit" class="btn-start">X TART</button>
        </form>
    </div>

</body>
</html>