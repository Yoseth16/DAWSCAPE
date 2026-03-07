// Web Audio API Synth Simple
let audioCtx;

export function initAudio() {
    if (!audioCtx) {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        audioCtx = new AudioContext();
    }
    if (audioCtx.state === 'suspended') {
        audioCtx.resume();
    }
}

export function playTone(freq, type = 'sine', duration = 0.3) {
    if (!audioCtx) return;
    const osc = audioCtx.createOscillator();
    const gainNode = audioCtx.createGain();

    osc.type = type;
    osc.frequency.value = freq;

    // Envelope
    gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
    gainNode.gain.linearRampToValueAtTime(0.5, audioCtx.currentTime + 0.05);
    gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);

    osc.connect(gainNode);
    gainNode.connect(audioCtx.destination);

    osc.start();
    osc.stop(audioCtx.currentTime + duration);
}

// Frecuencias (Rojo, Verde, Azul, Amarillo, Error, Éxito)
export const FREQS = [329.63, 261.63, 220.00, 164.81]; // E4, C4, A3, E3
export const S_ERR = 110.00; // A2
export const S_OK = 880.00;  // A5

// ── LOGICA SIMON SAYS COOPERATIVO ──
export let sequence = [];
export let playerStep = 0;
export let roundLengths = [4, 6, 9, 12, 15]; // Rondas exigentes
export let currentRound = 0;
export let isPlaying = false;

// DOM refs (se inicializan dentro de buildSimonGame, cuando el DOM ya existe)
let statusTxt, rondaVal, pasosVal;

export function lightUp(sectorId, isPlayer = false) {
    const btn = document.getElementById('btn-' + sectorId);
    if (!btn) return;

    btn.classList.add('lit');
    playTone(FREQS[sectorId], isPlayer ? 'square' : 'sine', 0.4);

    setTimeout(() => {
        btn.classList.remove('lit');
    }, 450); // Mantiene destello poco mas de medio segundo
}

export function buildSimonGame({ initialSequence, onSuccess }) {
    // Inicializar refs del DOM aquí (el DOM ya existe cuando se llama a esta función)
    statusTxt = document.getElementById('sys-status');
    rondaVal = document.getElementById('ronda-val');
    pasosVal = document.getElementById('pasos-val');

    sequence = initialSequence;
    let maxSteps = sequence.length;

    // Desglosar rondas dinámicamente si la longitud es distinta, o dejar el default
    // Solo para asegurarse que el backend manda lo esperado (15 por defecto)
    if (maxSteps > 0) {
        roundLengths = [4, 6, 9, 12, maxSteps];
    }

    currentRound = 0;

    const board = document.getElementById('board');
    if (!board) return;

    // Attach local touch functions
    window.touchSector = (sectorId) => {
        // Evita doble triggering por eventos simultáneos de touch y click si fuera necesario,
        // o clics cuando no toca jugar.
        if (!isPlaying) return;

        // Efecto visual instantaneo
        lightUp(sectorId, true);

        // Comprobar logica
        let targetSteps = roundLengths[currentRound];

        if (sectorId === sequence[playerStep]) {
            // Correcto
            playerStep++;

            if (playerStep === targetSteps) {
                // Completó la ronda!
                isPlaying = false;
                board.classList.remove('active');
                if (statusTxt) {
                    statusTxt.innerText = "[OK] FASE ESTABILIZADA";
                    statusTxt.className = 'status-ok';
                }
                playTone(S_OK, 'triangle', 0.5);

                setTimeout(() => {
                    currentRound++;
                    if (currentRound >= roundLengths.length) {
                        if (onSuccess) onSuccess(sequence);
                    } else {
                        playRound();
                    }
                }, 1500);
            }
        } else {
            // Falló
            isPlaying = false;
            board.classList.remove('active');
            board.style.animation = 'shake 0.4s';

            if (statusTxt) {
                statusTxt.innerText = "[ERROR] DESINCRONIZACIÓN. REINICIANDO FASE...";
                statusTxt.className = 'status-info';
                statusTxt.style.color = 'var(--red)';
            }

            playTone(S_ERR, 'sawtooth', 0.8);

            setTimeout(() => {
                board.style.animation = '';
                // repite la misma ronda
                playRound();
            }, 2000);
        }
    }

    playRound();

    function playRound() {
        isPlaying = false;
        board.classList.remove('active'); // Bloquea clics
        playerStep = 0;

        if (rondaVal) rondaVal.innerText = (currentRound + 1) + "/" + roundLengths.length;
        let targetSteps = roundLengths[currentRound];
        if (pasosVal) pasosVal.innerText = targetSteps;

        if (statusTxt) {
            statusTxt.innerText = "// MEMORIZANDO PATRÓN...";
            statusTxt.className = 'status-info';
        }

        // Reproducir secuencia parcial hasta el límite de la ronda
        let i = 0;
        // Un pequeño delay inicial
        setTimeout(() => {
            let interval = setInterval(() => {
                lightUp(sequence[i]);
                i++;
                if (i >= targetSteps) {
                    clearInterval(interval);
                    // Dar control al jugador
                    setTimeout(() => {
                        if (statusTxt) {
                            statusTxt.innerText = "INTRODUCE LA SECUENCIA REQUERIDA";
                            statusTxt.className = '';
                        }
                        isPlaying = true;
                        board.classList.add('active');
                    }, 500);
                }
            }, 800); // 800ms de velocidad base.
        }, 1000);
    }
}
