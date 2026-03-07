@extends('layouts.app')

@section('title', 'SOBRECARGA DEL SISTEMA | DAWSCAPE')

@push('styles')
    @vite(['resources/css/prueba6.css'])
@endpush

@section('content')

<!-- HEADER -->
<div class="game-header">
  <div class="team-info">
    <strong>Equipo:</strong> {{ session('nombre_equipo', 'Sin equipo') }}<br>
    <strong>Clase:</strong> {{ session('clase', 'N/A') }}
  </div>
  <div class="header-timer" id="cronometro">--:--</div>
</div>

<div class="screen" id="screen-game">
    <div class="title-glitch">RESTAURACIÓN DE ENERGÍA</div>
    
    <div class="lore-box">
        <strong>FALLO CASCADA DETECTADO.</strong><br>
        El sistema requiere la reintroducción manual de las secuencias de encendido. 
        <br><br>Repartíos el esfuerzo mental: la frecuencia sobrecargará la memoria de un solo individuo.
    </div>

    <!-- Indicador de estado -->
    <div id="sys-status">SISTEMA A LA ESPERA</div>

    <button id="btn-start" class="btn-start" onclick="startGame()">▶ INICIAR SECUENCIA</button>

    <div class="hud" style="display:none;" id="hud-container">
        <div>FASE: <span id="ronda-val" style="color:var(--green);font-weight:bold;">1/5</span></div>
        <div>PASOS: <span id="pasos-val" style="color:#00aaff;font-weight:bold;">0</span></div>
    </div>

    <!-- Tablero 2x2 -->
    <div class="simon-board" id="board">
        <div class="sector" id="btn-0" onmousedown="touchSector(0)" ontouchstart="touchSector(0)"></div>
        <div class="sector" id="btn-1" onmousedown="touchSector(1)" ontouchstart="touchSector(1)"></div>
        <div class="sector" id="btn-2" onmousedown="touchSector(2)" ontouchstart="touchSector(2)"></div>
        <div class="sector" id="btn-3" onmousedown="touchSector(3)" ontouchstart="touchSector(3)"></div>
    </div>

</div>

<!-- PANTALLA ÉXITO -->
<div class="screen" id="screen-success">
    <div style="font-size: 5rem; margin-bottom: 1rem; filter: drop-shadow(0 0 20px #00ff88);">⚡</div>
    <div class="title-glitch" style="margin-bottom:0.5rem">SISTEMA RESTAURADO</div>
    <div style="color:#88ccaa; font-size:1.1rem; margin-bottom: 2rem;">"Enrutamiento de energía cooperativo completado al 100%."</div>
    <a href="/final" class="success-btn">COMPLETAR ESCAPE ROOM →</a>
</div>

@push('scripts')
@vite(['resources/js/prueba6.js'])
<script type="module">
import { initAudio, buildSimonGame, lightUp } from '{{ Vite::asset('resources/js/prueba6.js') }}';

// ── TIMER (Mismo de siempre) ──
let tiempo = parseInt("{{ $tiempo_transcurrido ?? 0 }}");
const timerDisplay = document.getElementById('cronometro');
const MAX_MINUTOS = 90;

function actualizarTimer() {
  let minutos = parseInt(tiempo / 60, 10);
  let segundos = parseInt(tiempo % 60, 10);
  let m = minutos  < 10 ? "0" + minutos  : minutos;
  let s = segundos < 10 ? "0" + segundos : segundos;
  timerDisplay.textContent = m + ":" + s;
  if (minutos >= MAX_MINUTOS) { window.location.href = '/final'; return; }
  tiempo++;
}
actualizarTimer();
setInterval(actualizarTimer, 1000);

window.startGame = function() {
    initAudio(); // Inicializamos el audio al primer click del usuario
    document.getElementById('btn-start').style.display = 'none';
    document.getElementById('hud-container').style.display = 'flex';
    
    // Obtenemos la secuencia segura generada por el backend almacenada en la sesión
    const initialSequence = @json(session('prueba6_sequence', []));

    buildSimonGame({
        initialSequence: initialSequence,
        onSuccess: function(playedSequence) {
             winGame(playedSequence);
        }
    });
}

function winGame(sequencePlayed) {
    document.getElementById('screen-game').style.display = 'none';
    document.getElementById('screen-success').style.display = 'flex';

    fetch("/prueba6migue", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        // Enviamos la secuencia para validarla en servidor
        body: JSON.stringify({ completed: true, expected_sequence: sequencePlayed })
    }).then(r => r.json()).then(res => {
        if(res.success) {
            console.log(res.mensaje);
        }
    });
}
</script>
@endpush
@endsection
