<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VICTORIA | DAWSCAPE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap');

      :root {
        --green: #00ff88;
        --red: #ff2244;
        --bg: #020c06;
        --panel: #041a0e;
        --border: #00ff8840;
        --text: #c8ffdf;
      }

      * { margin: 0; padding: 0; box-sizing: border-box; }

      body {
        background: var(--bg);
        color: var(--text);
        font-family: 'Share Tech Mono', monospace;
        min-height: 100vh;
        overflow-x: hidden;
      }

      body::before {
        content: '';
        position: fixed;
        inset: 0;
        background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,136,0.013) 2px, rgba(0,255,136,0.013) 4px);
        pointer-events: none;
        z-index: 1000;
      }

      .screen {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 1.5rem;
        position: relative;
        z-index: 10;
        animation: fadeIn 1s ease;
      }

      .victory-box {
        width: 100%;
        max-width: 680px;
        border: 1px solid var(--border);
        background: var(--panel);
        padding: clamp(1.5rem, 5vw, 3rem) clamp(1rem, 4vw, 3rem);
        position: relative;
        text-align: center;
        box-shadow: 0 0 40px rgba(0, 255, 136, 0.1);
      }

      .victory-box::before {
        content: '// REPORTE DEL SISTEMA';
        position: absolute;
        top: -0.7rem; left: 1rem;
        background: var(--bg);
        padding: 0 0.5rem;
        color: var(--green);
        font-size: 0.7rem;
        letter-spacing: 0.2em;
      }

      .victory-box.timeout {
        border-color: var(--red);
        box-shadow: 0 0 40px rgba(255, 34, 68, 0.15);
      }
      .victory-box.timeout::before {
        color: var(--red);
      }

      .title {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        letter-spacing: 0.1em;
        margin-bottom: 1.5rem;
      }

      .title.success {
        color: var(--green);
        text-shadow: 0 0 20px var(--green);
        animation: pulse-glow 2s infinite;
      }

      .title.error {
        color: var(--red);
        text-shadow: 0 0 20px var(--red);
        animation: glitch-blink 2s infinite;
      }

      /* MEJORADO: Subtitle text con mejor contraste */
      .subtitle-txt {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #b0e0bf;
        margin-bottom: 2rem;
        text-shadow: 0 0 6px rgba(200, 255, 223, 0.15);
      }
      .victory-box.timeout .subtitle-txt {
        color: #ff9999;
        text-shadow: 0 0 6px rgba(255, 153, 153, 0.2);
      }

      .highlight { color: var(--green); font-weight: bold; }
      .victory-box.timeout .highlight { color: var(--red); }

      .time-display {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(2rem, 10vw, 3.5rem);
        color: var(--green);
        text-shadow: 0 0 20px var(--green);
        margin: 1.5rem 0;
        letter-spacing: 0.1em;
      }

      .ranking-panel {
        text-align: left;
        background: rgba(0, 0, 0, 0.4);
        border: 1px dotted var(--border);
        padding: clamp(1rem, 4vw, 1.5rem);
        margin-top: 1.5rem;
      }

      /* MEJORADO: Ranking panel header con mejor contraste */
      .ranking-panel h4 {
        color: #00ff88;
        font-size: 0.85rem;
        letter-spacing: 0.2em;
        margin-bottom: 1rem;
        text-transform: uppercase;
        text-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
      }

      .breakdown-panel {
          margin-top: 1rem;
          margin-bottom: 2rem;
          background: rgba(0, 255, 136, 0.05);
          border-left: 3px solid var(--green);
          padding: 1.5rem;
          text-align: left;
      }

      .breakdown-panel h4 {
        color: var(--green);
        font-size: 0.85rem;
        letter-spacing: 0.2em;
        margin-bottom: 1rem;
        text-transform: uppercase;
      }

      /* MEJORADO: Breakdown list con mejor contraste */
      .breakdown-list {
          list-style: none;
          padding: 0;
          font-size: 0.95rem;
          line-height: 2;
          color: #b0e0bf;
          text-shadow: 0 0 5px rgba(200, 255, 223, 0.1);
      }

      .breakdown-list li {
          display: flex;
          justify-content: space-between;
          border-bottom: 1px solid rgba(0, 255, 136, 0.1);
          padding-bottom: 0.3rem;
          margin-bottom: 0.3rem;
      }

      /* MEJORADO: Ranking list con mejor contraste */
      .ranking-list {
        list-style-type: decimal;
        padding-left: 1.5rem;
        color: #88ccaa;
        line-height: 1.8;
        text-shadow: 0 0 4px rgba(136, 204, 170, 0.15);
      }

      .ranking-list li { margin-bottom: 0.5rem; }
      .ranking-list li.current-team {
        color: var(--green);
        font-weight: bold;
        text-shadow: 0 0 5px var(--green);
      }

      /* DIPLOMA FINAL */
      /* =================== DIPLOMA REDESIGN =================== */
      .diploma-section {
        margin-top: 1.5rem;
        padding: clamp(0.4rem, 2vw, 0.75rem);
        width: 100%;
      }
      .diploma-card {
        position: relative;
        background: #050505;
        border: 3px solid #00ff88;
        box-shadow: 0 0 40px rgba(0,255,136,0.25), 0 0 80px rgba(0,255,136,0.08), inset 0 0 50px rgba(0,255,136,0.02);
        padding: clamp(1.5rem, 4vw, 3rem) clamp(1.5rem, 4vw, 3.5rem);
        overflow: hidden;
        font-family: 'Share Tech Mono', monospace;
        color: #c8ffdf;
      }
      .diploma-card::before {
        content: '';
        position: absolute;
        top: 14px; left: 14px; right: 14px; bottom: 14px;
        border: 1px solid rgba(0,255,136,0.25);
        pointer-events: none;
        z-index: 0;
      }
      .diplo-scanlines {
        position: absolute;
        inset: 0;
        background-image: repeating-linear-gradient(0deg, transparent, transparent 3px, rgba(0,0,0,0.18) 3px, rgba(0,0,0,0.18) 4px);
        pointer-events: none;
        z-index: 1;
      }
      .diplo-watermark {
        position: absolute;
        inset: 0;
        overflow: hidden;
        font-size: clamp(2rem, 5vw, 4rem);
        font-family: 'Share Tech Mono', monospace;
        color: rgba(0,255,136,0.04);
        line-height: 1.4;
        word-break: break-all;
        pointer-events: none;
        z-index: 0;
        padding: 1rem;
        user-select: none;
      }
      .diplo-corner {
        position: absolute;
        font-family: 'Share Tech Mono', monospace;
        font-size: clamp(1.2rem, 3.5vw, 2.2rem);
        color: #00ff88;
        text-shadow: 0 0 12px rgba(0,255,136,0.9);
        z-index: 3;
        line-height: 1;
      }
      .diplo-tl { top: 14px; left: 16px; }
      .diplo-tr { top: 14px; right: 16px; }
      .diplo-bl { bottom: 14px; left: 16px; }
      .diplo-br { bottom: 14px; right: 16px; }
      .diplo-content { position: relative; z-index: 2; text-align: center; }
      .diplo-system-tag {
        font-size: clamp(0.5rem, 1.4vw, 0.75rem);
        color: rgba(0,255,136,0.65);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
      }
      @keyframes diplomaGlitch {
        0%,89%,100% { text-shadow: 0 0 20px rgba(0,255,136,0.5); transform: translate(0,0); }
        90% { transform: translate(-3px,0); text-shadow: -3px 0 0 rgba(255,0,60,0.7), 3px 0 0 rgba(0,240,255,0.7); }
        92% { transform: translate(3px,0); }
        94% { transform: translate(-1px,0); }
        96% { transform: translate(0,0); }
      }
      .diplo-title {
        font-size: clamp(1.3rem, 4.5vw, 2.8rem);
        font-weight: 900;
        color: #ffffff;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        text-shadow: 0 0 20px rgba(0,255,136,0.5);
        animation: diplomaGlitch 7s infinite;
        margin: 0.2rem 0;
        font-family: 'Share Tech Mono', monospace;
      }
      .diplo-sep {
        color: rgba(0,255,136,0.45);
        font-size: clamp(0.45rem, 1.2vw, 0.75rem);
        margin: 0.45rem 0;
        overflow: hidden;
        white-space: nowrap;
        letter-spacing: -0.02em;
      }
      .diplo-certify-text { font-size: clamp(0.7rem, 1.8vw, 0.95rem); color: #888; margin: 0.2rem 0; }
      @keyframes teamNamePulse {
        0%,100% { text-shadow: 0 0 10px rgba(0,255,136,0.7), 0 0 30px rgba(0,255,136,0.3); }
        50%      { text-shadow: 0 0 20px #00ff88, 0 0 60px rgba(0,255,136,0.5), 0 0 100px rgba(0,255,136,0.15); }
      }
      .diplo-team-name {
        font-size: clamp(1.4rem, 4.5vw, 2.8rem);
        font-weight: 700;
        color: #00ff88;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        animation: teamNamePulse 2.5s ease-in-out infinite;
        margin: 0.4rem 0 0.2rem;
        font-family: 'Share Tech Mono', monospace;
        word-break: break-word;
      }
      .diplo-award-label {
        font-size: clamp(0.55rem, 1.3vw, 0.78rem);
        color: rgba(0,255,136,0.55);
        letter-spacing: 0.18em;
        margin: 0.25rem 0;
      }
      .diploma-award-emoji { font-size: clamp(1.8rem, 5vw, 3.5rem); margin: 0.15rem 0; }
      .diploma-award-title {
        font-size: clamp(0.9rem, 2.5vw, 1.4rem);
        font-weight: 700;
        color: #ffd700;
        text-shadow: 0 0 15px rgba(255,215,0,0.6);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 0.2rem;
        font-family: 'Share Tech Mono', monospace;
      }
      .diplo-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.35rem 1.5rem;
        margin: 0.4rem auto;
        max-width: 680px;
        text-align: left;
      }
      .diplo-stat {
        font-size: clamp(0.6rem, 1.6vw, 0.88rem);
        color: #c8ffdf;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
      }
      .diplo-stat-label { color: #777; }
      .diplo-stat-val { color: #00ff88; font-weight: bold; }
      .diplo-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 0.5rem;
        gap: 0.75rem;
      }
      .diplo-footer-left { text-align: left; flex: 1; min-width: 0; }
      .diplo-cert-code {
        font-size: clamp(0.5rem, 1.3vw, 0.72rem);
        color: rgba(0,255,136,0.45);
        letter-spacing: 0.12em;
        margin-bottom: 0.2rem;
      }
      .diplo-footer-text { font-size: clamp(0.45rem, 1.1vw, 0.65rem); color: #555; }
      .diplo-seal {
        width: clamp(70px, 10vw, 130px);
        height: clamp(70px, 10vw, 130px);
        flex-shrink: 0;
        filter: drop-shadow(0 0 8px rgba(0,255,136,0.5));
      }
      .diploma-actions {
        margin-top: 0.75rem;
        display: flex;
        justify-content: center;
        width: 100%;
      }
      .diploma-download-btn {
        display: inline-block;
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(0.6rem, 2vw, 0.75rem);
        letter-spacing: 0.18em;
        padding: 0.9rem 1.6rem;
        border: 1px solid var(--green);
        color: var(--green);
        background: transparent;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        max-width: 100%;
        text-align: center;
        -webkit-tap-highlight-color: transparent;
        min-height: 48px;
      }
      .diploma-download-btn:hover {
        background: var(--green);
        color: #00110a;
        box-shadow: 0 0 14px rgba(0,255,136,0.4);
      }
      @media print {
        body * { visibility: hidden; }
        .diploma-card, .diploma-card * { visibility: visible; }
        .diploma-card {
          position: fixed; top: 0; left: 0;
          width: 100%; height: 100%;
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
          border: 3px solid #00ff88 !important;
          box-shadow: none;
          padding: 1.5cm 2cm;
        }
        .diplo-scanlines { display: none; }
        @page { size: A4 landscape; margin: 0; }
      }

      .btn {
        display: inline-block;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.9rem;
        letter-spacing: 0.2em;
        padding: 1.2rem 2.5rem;
        border: 2px solid var(--green);
        background: transparent;
        color: var(--green);
        text-decoration: none;
        cursor: pointer;
        text-transform: uppercase;
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
        margin-top: 2rem;
        font-weight: 700;
        width: 100%;
      }

      .btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: var(--green);
        transition: left 0.3s;
        z-index: -1;
      }

      .btn:hover::before { left: 0; }
      .btn:hover { color: var(--bg); box-shadow: 0 0 20px var(--green); }

      @keyframes pulse-glow {
        0%, 100% { text-shadow: 0 0 20px var(--green); }
        50% { text-shadow: 0 0 40px var(--green), 0 0 60px #00ff8840; }
      }

      @keyframes glitch-blink {
        0%, 100% { opacity: 1; text-shadow: 0 0 20px var(--red); }
        50% { opacity: 0.8; text-shadow: 0 0 40px var(--red); }
      }

      @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

      /* Particles */
      .particles { position: fixed; inset: 0; pointer-events: none; z-index: 1; }
      .particle {
        position: absolute;
        width: 2px; height: 2px;
        background: var(--green);
        border-radius: 50%;
        opacity: 0;
        animation: float linear infinite;
      }
      @keyframes float {
        0% { opacity: 0; transform: translateY(100vh); }
        10% { opacity: 0.5; }
        90% { opacity: 0.3; }
        100% { opacity: 0; transform: translateY(-10vh); }
      }

      /* MOBILE */
      .btn { min-height: 52px; -webkit-tap-highlight-color: transparent; }
      @media (max-width: 600px) {
        .screen { padding: 0.75rem; }
        .victory-box { padding: 1rem 0.75rem; }
        .victory-box::before { font-size: 0.6rem; left: 0.5rem; }
        .title { font-size: clamp(1.5rem, 7vw, 2.5rem); margin-bottom: 1rem; }
        .breakdown-list { font-size: 0.85rem; }
        .breakdown-panel { padding: 1rem; }
        .subtitle-txt { font-size: 0.95rem; line-height: 1.6; }
        .ranking-list { font-size: 0.85rem; padding-left: 1.2rem; }
        .ranking-list li { flex-wrap: wrap; gap: 0.2rem; }
        .time-display { font-size: clamp(1.5rem, 8vw, 2.5rem); }
        .diploma-section { padding: 0.4rem; }
        .diplo-stats { grid-template-columns: 1fr; gap: 0.3rem; max-width: 100%; }
        .diplo-stat { white-space: normal; }
        .diplo-team-name { font-size: 1.4rem; }
        .diploma-award-emoji { font-size: 2rem; }
        .diplo-footer { flex-direction: column; align-items: center; text-align: center; }
        .diplo-footer-left { text-align: center; }
        .diplo-seal { width: 90px; height: 90px; }
        .diploma-download-btn { padding: 0.75rem 1rem; width: 100%; }
        .btn { padding: 1rem 1.5rem; font-size: 0.8rem; }
      }

      @media (max-width: 380px) {
        .screen { padding: 0.5rem; }
        .victory-box { padding: 0.75rem 0.5rem; }
        .title { font-size: 1.4rem; }
        .subtitle-txt { font-size: 0.85rem; }
        .ranking-list { font-size: 0.8rem; }
      }
    </style>
</head>
<body>

@include('components.game-audio-setup', ['trackKey' => 'final'])

<div class="particles" id="particles"></div>

<div class="screen">
  @if($partida->fase_actual === 'FINALIZADO_TIMEOUT' || session('timeout'))
    <div class="victory-box timeout">
      <div style="font-size:3rem; margin-bottom:1rem">🚫</div>
      <h1 class="title error">TIEMPO AGOTADO</h1>

      <div class="subtitle-txt">
        Misión fallida, equipo <span class="highlight">{{ $partida->nombre_equipo }}</span> ({{ $partida->clase }}).<br><br>
        El sistema de seguridad ha bloqueado la salida y se acabó vuestro tiempo.
      </div>

      <div class="ranking-panel" style="border-color: #ffaa0040;">
        <h4 style="color:#ffaa00">>> Progreso antes del cierre_</h4>
        <div style="color:#ffddaa; font-size:1.1rem; margin-top:0.5rem">
          @if(session()->has('prueba1mario_superada'))
            Prueba 1 Mario completada (fallo en validación final).
          @else
            No habéis conseguido escapar a tiempo.
          @endif
        </div>
      </div>
  @else
    <div class="victory-box">
      <div style="font-size:3.5rem; margin-bottom:1rem">🔓</div>
      <h1 class="title success">SISTEMA HACKEADO</h1>

      <div class="subtitle-txt">
        Enhorabuena, equipo <span class="highlight">{{ $partida->nombre_equipo }}</span> ({{ $partida->clase }}).<br><br>
        Habéis conseguido escapar del edificio a tiempo cruzando el Firewall.
      </div>

      <div style="color:#007744; font-size:0.8rem; letter-spacing:0.2em; text-transform:uppercase">// TIEMPO TOTAL DE OPERACIÓN</div>
      <div class="time-display">
        {{ floor($partida->tiempo_segundos / 60) }}m {{ $partida->tiempo_segundos % 60 }}s
      </div>
      @if($partida->penalizacion_segundos > 0)
        <div style="color: #f59e0b; font-size: 0.9rem; margin-top: -10px; margin-bottom: 20px;">
          (Incluye {{ floor($partida->penalizacion_segundos / 60) }}m de penalización por pistas)
        </div>
      @endif

      @if(isset($hitos) && count($hitos) > 0)
          <div class="breakdown-panel">
            <h4>>> INFORME DE RENDIMIENTO (POR FASE)_</h4>
            <ul class="breakdown-list">
              @foreach($hitos as $hito)
                  <li>
                      <span>{{ $hito['fase'] }}</span>
                      <span style="color:var(--green); font-family: 'Orbitron', sans-serif;">
                          {{ floor($hito['segundos_invertidos'] / 60) }}m {{ $hito['segundos_invertidos'] % 60 }}s
                      </span>
                  </li>
              @endforeach
            </ul>
          </div>
      @endif
  @endif

      <div class="ranking-panel">
        <h4>>> TOP 3 EQUIPOS_</h4>
        @if($top3->count() > 0)
          <ol class="ranking-list">
              @foreach($top3 as $equipo)
                  <li class="{{ $equipo->email === $partida->email ? 'current-team' : '' }}" style="display:flex; justify-content:space-between; align-items:baseline; gap:0.5rem">
                      <span>{{ $equipo->nombre_equipo }} ({{ $equipo->clase }})</span> <span style="font-family:'Orbitron', sans-serif; white-space:nowrap">[{{ floor($equipo->tiempo_segundos / 60) }}m {{ $equipo->tiempo_segundos % 60 }}s]</span>
                  </li>
              @endforeach
          </ol>
        @else
            <p style="color:#445544; font-size:0.9rem">Aún no hay datos de ranking.</p>
        @endif
      </div>

      @if($partida->fase_actual === 'FINALIZADO')
      <div class="diploma-section" id="diploma-section">
        <div class="diploma-card">
          <!-- Corner decorations -->
          <div class="diplo-corner diplo-tl">╔</div>
          <div class="diplo-corner diplo-tr">╗</div>
          <div class="diplo-corner diplo-bl">╚</div>
          <div class="diplo-corner diplo-br">╝</div>
          <!-- Scanlines overlay -->
          <div class="diplo-scanlines"></div>
          <!-- Watermark (populated by JS) -->
          <div class="diplo-watermark" id="diplo-watermark"></div>

          <div class="diplo-content">
            <div class="diplo-system-tag">[ DAWSCAPE // SISTEMA DE CERTIFICACIÓN DIGITAL ]</div>
            <h2 class="diplo-title">CERTIFICADO DE AVENTURA</h2>
            <div class="diplo-sep">════════════════════════════════════════════════════════════════════</div>

            <p class="diplo-certify-text">Este diploma certifica que el equipo</p>
            <div class="diplo-team-name">{{ $partida->nombre_equipo }}</div>
            <p class="diplo-certify-text">ha completado con éxito el desafío DAWSCAPE</p>

            <div class="diplo-sep">════════════════════════════════════════════════════════════════════</div>
            <div class="diplo-award-label">— ◆ PREMIO OBTENIDO ◆ —</div>
            <div class="diploma-award-emoji" id="diploma-award-emoji">🏆</div>
            <div class="diploma-award-title" id="diploma-award-title">Cargando premio...</div>

            <div class="diplo-sep">════════════════════════════════════════════════════════════════════</div>
            <div class="diplo-stats">
              <div class="diplo-stat">
                <span>⏱</span>
                <span class="diplo-stat-label">Tiempo total:</span>
                <span class="diplo-stat-val" id="diplo-time">--</span>
              </div>
              <div class="diplo-stat">
                <span>📅</span>
                <span class="diplo-stat-label">Fecha del reto:</span>
                <span class="diplo-stat-val" id="diplo-date">--</span>
              </div>
              <div class="diplo-stat">
                <span>🏆</span>
                <span class="diplo-stat-label">Posición ranking:</span>
                <span class="diplo-stat-val" id="diplo-pos">#--</span>
              </div>
              <div class="diplo-stat">
                <span>🔓</span>
                <span class="diplo-stat-label">Retos resueltos:</span>
                <span class="diplo-stat-val" id="diplo-retos">-/9</span>
              </div>
            </div>

            <div class="diplo-sep">════════════════════════════════════════════════════════════════════</div>
            <div class="diplo-footer">
              <div class="diplo-footer-left">
                <div class="diplo-cert-code" id="diplo-cert">[ CERT-00000000 ]</div>
                <div class="diplo-footer-text">DAWSCAPE — Escape Room Digital &middot; XTART &middot; 2026</div>
              </div>
              <div class="diplo-seal">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" overflow="visible">
                  <defs><path id="diplo-circle-path" d="M 100,12 A 88,88 0 1 1 99.9,12"/></defs>
                  <circle cx="100" cy="100" r="90" fill="none" stroke="#00ff88" stroke-width="3"/>
                  <circle cx="100" cy="100" r="74" fill="none" stroke="rgba(0,255,136,0.4)" stroke-width="1.5"/>
                  <circle cx="100" cy="100" r="70" fill="rgba(0,255,136,0.06)"/>
                  <text fill="#00ff88" font-family="Share Tech Mono,monospace" font-size="12.5">
                    <textPath href="#diplo-circle-path"> · DAWSCAPE · XTART · 2026 · VERIFIED ·</textPath>
                  </text>
                  <text x="100" y="94" text-anchor="middle" fill="#00ff88" font-family="Share Tech Mono,monospace" font-size="19" font-weight="bold">DAWS</text>
                  <text x="100" y="116" text-anchor="middle" fill="#00ff88" font-family="Share Tech Mono,monospace" font-size="19" font-weight="bold">CAPE</text>
                </svg>
              </div>
            </div>
          </div>
        </div>
        <div class="diploma-actions">
          <button type="button" class="diploma-download-btn" id="download-diploma">
            IMPRIMIR / DESCARGAR DIPLOMA
          </button>
        </div>
      </div>
      @endif

      <a href="/" class="btn">FINALIZAR CONEXIÓN →</a>
    </div>
</div>

<script>
// Partículas
(function() {
  const c = document.getElementById('particles');
  for (let i = 0; i < 25; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + '%';
    p.style.animationDuration = (10 + Math.random() * 14) + 's';
    p.style.animationDelay = (Math.random() * 12) + 's';
    c.appendChild(p);
  }
})();

// =========================
// SISTEMA DE DIPLOMA FINAL
// =========================
(function() {
  const section = document.getElementById('diploma-section');
  if (!section) return; // solo cuando el equipo ha finalizado

  const stats = {
    teamName: @json($partida->nombre_equipo),
    totalTime: {{ (int)($partida->tiempo_segundos ?? 0) }},
    timePerChallenge: @json(isset($hitos) ? collect($hitos)->pluck('segundos_invertidos')->values() : []),
    hintsUsed: 0,
    maxHints: 10,
    firstTrySuccessCount: 0,
    totalAttempts: 0,
    position: 0,
    totalChallenges: {{ isset($hitos) ? count($hitos) : 0 }},
  };

  // Helpers
  const minutes = (s) => s / 60;
  const ratio = (n, d) => (d === 0 ? 0 : n / d);
  const fastestChallengeTime = (st) =>
    st.timePerChallenge.length ? Math.min.apply(null, st.timePerChallenge) : Infinity;

  // Definición de premios (subset principal)
  const AWARDS = [
    {
      id: 'first_finish',
      label: 'Primer equipo en terminar',
      emoji: '🏆',
      weight: 1000,
      score: (s) => (s.position === 1 ? 100 : 0),
    },
    {
      id: 'velocista_digital',
      label: 'Velocista Digital',
      emoji: '⚡',
      weight: 850,
      score: (s) => {
        const m = minutes(s.totalTime);
        if (m > 60) return 0;
        if (m <= 30) return 90 + (30 - m);
        return 70 + (60 - m) * 0.5;
      },
    },
    {
      id: 'sin_pistas',
      label: 'Sin Pistas',
      emoji: '🧠',
      weight: 900,
      score: (s) => (s.hintsUsed === 0 ? 95 : 0),
    },
    {
      id: 'tortuga_ganadora',
      label: 'La Tortuga Ganadora',
      emoji: '🐢',
      weight: 740,
      score: (s) => {
        const m = minutes(s.totalTime);
        if (m < 60) return 0;
        return 60 + (m - 60) * 0.3;
      },
    },
    {
      id: 'eureka',
      label: 'Eureka',
      emoji: '💡',
      weight: 730,
      score: (s) => {
        const f = minutes(fastestChallengeTime(s));
        if (f === Infinity || f > 3) return 0;
        return 88 + (3 - f) * 4;
      },
    },
    {
      id: 'sobrevivientes_desafio',
      label: 'Sobrevivientes del Desafío',
      emoji: '🏅',
      weight: 650,
      score: (s) => 60 + minutes(s.totalTime) * 0.3,
    },
  ];

  function pickAward(st) {
    const candidates = AWARDS
      .map((cfg) => ({ cfg, value: cfg.score(st) }))
      .filter((c) => c.value > 0);
    if (!candidates.length) {
      const fallback = AWARDS.find((a) => a.id === 'sobrevivientes_desafio');
      return fallback;
    }
    candidates.sort((a, b) =>
      b.value !== a.value ? b.value - a.value : b.cfg.weight - a.cfg.weight
    );
    return candidates[0].cfg;
  }

  function formatDuration(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}m ${String(s).padStart(2, '0')}s`;
  }

  const award = pickAward(stats);
  const emojiEl = document.getElementById('diploma-award-emoji');
  const titleEl = document.getElementById('diploma-award-title');
  const btn = document.getElementById('download-diploma');

  // Populate award info
  if (emojiEl) emojiEl.textContent = award.emoji;
  if (titleEl) titleEl.textContent = award.label;

  // Populate dynamic stats
  const dipTime = document.getElementById('diplo-time');
  const dipDate = document.getElementById('diplo-date');
  const dipPos  = document.getElementById('diplo-pos');
  const dipRetos = document.getElementById('diplo-retos');
  const dipCert  = document.getElementById('diplo-cert');
  if (dipTime)  dipTime.textContent  = formatDuration(stats.totalTime);
  if (dipDate)  dipDate.textContent  = new Date().toLocaleDateString('es-ES');
  if (dipPos)   dipPos.textContent   = '#' + (stats.position || '-');
  if (dipRetos) dipRetos.textContent = stats.totalChallenges + '/9';
  if (dipCert) {
    const certCode = 'CERT-' + String({{ $partida->id ?? 0 }}).padStart(8, '0');
    dipCert.textContent = '[ ' + certCode + ' ]';
  }

  // Populate watermark
  const wm = document.getElementById('diplo-watermark');
  if (wm) { let t = ''; for (let i = 0; i < 300; i++) t += '{ DAWSCAPE } '; wm.textContent = t; }

  // Print button
  if (btn) btn.addEventListener('click', function() { window.print(); });
})();
</script>

<!-- Firma discreta -->
<div style="position: fixed; bottom: 5px; right: 10px; font-family: monospace; font-size: 0.65rem; color: rgba(255, 255, 255, 0.3); z-index: 9999; text-align: right;">
    DAWSCAPE SYSTEM // DAW <br>
    <a href="{{ route('legal') }}" style="color: rgba(255, 255, 255, 0.3); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#00ff88'" onmouseout="this.style.color='rgba(255, 255, 255, 0.3)'">AVISO LEGAL Y RGPD</a>
</div>

<!-- MODAL VALORACION -->
<style>
.val-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.85); backdrop-filter: blur(5px);
    z-index: 10000; display: flex; justify-content: center; align-items: center;
    opacity: 0; pointer-events: none; transition: opacity 0.5s ease;
}
.val-modal-overlay.active { opacity: 1; pointer-events: auto; }
.val-modal {
    background: #041a0e; border: 2px solid #00ff88; border-radius: 0;
    padding: 2rem; width: 90%; max-width: 400px;
    box-shadow: 0 0 30px rgba(0, 255, 136, 0.25), inset 0 0 40px rgba(0, 255, 136, 0.03);
    text-align: center; color: #c8ffdf; font-family: 'Share Tech Mono', monospace;
    transform: translateY(20px); transition: transform 0.5s ease;
    position: relative;
}
.val-modal::before {
    content: '// VALORACIÓN DEL SISTEMA';
    position: absolute; top: -0.65rem; left: 1rem;
    background: #041a0e; padding: 0 0.5rem;
    color: #00ff88; font-size: 0.65rem; letter-spacing: 0.2em;
}
.val-modal-overlay.active .val-modal { transform: translateY(0); }
.val-title { color: #00ff88; font-size: 1.3rem; margin-bottom: 1rem; text-shadow: 0 0 12px rgba(0, 255, 136, 0.7); letter-spacing: 0.1em; text-transform: uppercase; }
.val-stars { display: flex; justify-content: center; gap: 10px; margin-bottom: 1.5rem; flex-direction: row-reverse; }
.val-stars input { display: none; }
.val-stars label { font-size: 2.5rem; color: #1a4a2e; cursor: pointer; transition: color 0.2s, text-shadow 0.2s; }
.val-stars label:hover, .val-stars label:hover ~ label, .val-stars input:checked ~ label { color: #00ff88; text-shadow: 0 0 15px rgba(0, 255, 136, 0.9); }
.val-textarea { width: 100%; background: #020c06; border: 1px solid #00ff8840; border-radius: 0; color: #c8ffdf; padding: 0.75rem; font-family: inherit; resize: none; margin-bottom: 1.5rem; margin-top: 1rem; }
.val-textarea:focus { outline: none; border-color: #00ff88; box-shadow: 0 0 8px rgba(0, 255, 136, 0.3); }
.val-textarea::placeholder { color: #445544; }
.val-buttons { display: flex; gap: 1rem; }
.val-btn { flex: 1; padding: 0.75rem; border-radius: 0; font-weight: bold; font-family: inherit; cursor: pointer; text-transform: uppercase; border: none; transition: all 0.2s; letter-spacing: 0.1em; }
.val-btn-submit { background: transparent; color: #00ff88; border: 2px solid #00ff88; position: relative; overflow: hidden; }
.val-btn-submit::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: #00ff88; transition: left 0.3s; z-index: 0; }
.val-btn-submit span { position: relative; z-index: 1; }
.val-btn-submit:not(.disabled):hover::before { left: 0; }
.val-btn-submit:not(.disabled):hover { color: #020c06; box-shadow: 0 0 18px rgba(0, 255, 136, 0.5); }
.val-btn-submit.disabled { opacity: 0.35; cursor: not-allowed; border-color: #00ff8860; color: #00ff8860; }
.val-btn-skip { background: transparent; border: 1px solid #2a4a35; color: #88ccaa; }
.val-btn-skip:hover { background: #0a1f10; border-color: #00ff8860; color: #c8ffdf; }
.val-msg { display: none; margin-top: 1rem; color: #00ff88; font-weight: bold; text-shadow: 0 0 8px rgba(0, 255, 136, 0.5); letter-spacing: 0.05em; }
</style>

<div class="val-modal-overlay" id="valModalOverlay">
    <div class="val-modal">
        <div class="val-title">¿Qué te ha parecido la actividad?</div>
        
        <div class="val-stars">
            <input type="radio" name="estrellas" id="star5" value="5"><label for="star5">★</label>
            <input type="radio" name="estrellas" id="star4" value="4"><label for="star4">★</label>
            <input type="radio" name="estrellas" id="star3" value="3"><label for="star3">★</label>
            <input type="radio" name="estrellas" id="star2" value="2"><label for="star2">★</label>
            <input type="radio" name="estrellas" id="star1" value="1"><label for="star1">★</label>
        </div>
        
        <textarea id="valComentario" class="val-textarea" rows="3" placeholder="Déjanos un comentario (opcional)..."></textarea>
        
        <div class="val-buttons" id="valButtons">
            <button class="val-btn val-btn-skip" id="btnSkipVal">Omitir</button>
            <button class="val-btn val-btn-submit disabled" id="btnSubmitVal" disabled><span>Enviar valoración</span></button>
        </div>
        
        <div class="val-msg" id="valMsg">[OK] Valoración registrada.<br>¡Gracias por tu feedback!</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Verificar si ya se mostro para esta partida
    if (localStorage.getItem('dawscape_valorado_{{ $partida->id ?? '' }}')) return;

    const overlay = document.getElementById('valModalOverlay');
    const btnSubmit = document.getElementById('btnSubmitVal');
    const btnSkip = document.getElementById('btnSkipVal');
    const msg = document.getElementById('valMsg');
    const buttonsContainer = document.getElementById('valButtons');
    const stars = document.querySelectorAll('input[name="estrellas"]');
    
    // Habilitar boton cuando se selecciona una estrella
    stars.forEach(star => {
        star.addEventListener('change', () => {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('disabled');
        });
    });

    // Mostrar modal despues de 5s
    setTimeout(() => {
        overlay.classList.add('active');
    }, 5000);

    // Omitir
    btnSkip.addEventListener('click', () => {
        localStorage.setItem('dawscape_valorado_{{ $partida->id ?? '' }}', 'true');
        overlay.classList.remove('active');
    });

    // Enviar
    btnSubmit.addEventListener('click', () => {
        if(btnSubmit.disabled) return;
        
        const checkedStar = document.querySelector('input[name="estrellas"]:checked');
        if(!checkedStar) return;
        
        const starsVal = checkedStar.value;
        const comment = document.getElementById('valComentario').value;
        const partidaId = '{{ $partida->id ?? '' }}';
        
        let csrfToken = '';
        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
        if(metaCsrf) {
            csrfToken = metaCsrf.getAttribute('content');
        } else {
            // Si la vista no tiene meta csrf, lo buscamos en campos hidden o añadimos un tag global en algun lado. Pero laravel permite headers o inputs.
            csrfToken = '{{ csrf_token() }}';
        }

        // Deshabilitar boton
        btnSubmit.disabled = true;
        btnSubmit.querySelector('span').textContent = 'Enviando...';

        fetch('/valoraciones', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                partida_id: partidaId,
                estrellas: starsVal,
                comentario: comment
            })
        }).then(res => res.json())
          .then(data => {
              if(data.success || data.message === 'Ya valorado') {
                  localStorage.setItem('dawscape_valorado_{{ $partida->id ?? '' }}', 'true');
                  buttonsContainer.style.display = 'none';
                  msg.style.display = 'block';
                  setTimeout(() => {
                      overlay.classList.remove('active');
                  }, 2000);
              } else {
                  console.error(data);
                  btnSubmit.disabled = false;
                  btnSubmit.querySelector('span').textContent = 'Enviar valoración';
              }
          }).catch(err => {
              console.error('Error', err);
              btnSubmit.disabled = false;
              btnSubmit.querySelector('span').textContent = 'Enviar valoración';
          });
    });
});
</script>
</body>
</html>
