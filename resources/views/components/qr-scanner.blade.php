{{--
    Componente de Escáner QR Reutilizable
    Uso: @include('components.qr-scanner', ['route' => '/prueba3', 'successMessage' => 'QR escaneado correctamente'])
--}}

<div class="qr-scanner-container">
    <div class="qr-scanner-header">
        <h3>📱 Escanear Código QR</h3>
        <p>Apunta la cámara hacia el código QR</p>
    </div>

    <div id="qr-result" class="qr-result" style="display: none;">
        <div class="qr-success">
            <i class="ph-fill ph-check-circle"></i>
            <span id="qr-message">QR detectado</span>
        </div>
    </div>

    <div id="qr-error" class="qr-error" style="display: none;">
        <i class="ph-fill ph-warning-circle"></i>
        <span id="qr-error-message">Error al escanear</span>
    </div>

    <input type="file" accept="image/*" capture="environment" id="qr-input" style="display:none">
    <button id="btn-scan-native" class="btn-scanner" onclick="document.getElementById('qr-input').click()">
        <i class="ph ph-camera"></i> ESCANEAR
    </button>
    <div id="dummy-reader" style="visibility:hidden; width:1px; height:1px; position:absolute;"></div>
</div>

<style>
.qr-scanner-container {
    width: 100%;
    max-width: 500px;
    margin: 2rem auto;
    padding: 1.5rem;
    background: rgba(4, 26, 14, 0.8);
    border: 1px solid var(--border, #00ff8840);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 255, 136, 0.1);
}

.qr-scanner-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.qr-scanner-header h3 {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.5rem;
    color: var(--green, #00ff88);
    margin-bottom: 0.5rem;
    text-shadow: 0 0 10px var(--green, #00ff88);
}

.qr-scanner-header p {
    font-size: 0.9rem;
    color: var(--text, #c8ffdf);
    opacity: 0.8;
}

.qr-reader {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    background: #000;
    display: none;
    margin-bottom: 1rem;
}

.qr-reader.active {
    display: block;
}

#reader video {
    width: 100% !important;
    height: auto !important;
    border-radius: 8px;
}

#reader__scan_region {
    border-radius: 8px !important;
}

.qr-result, .qr-error {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 1rem;
    animation: slideIn 0.3s ease;
}

.qr-result {
    background: rgba(0, 255, 136, 0.1);
    border: 1px solid var(--green, #00ff88);
    color: var(--green, #00ff88);
}

.qr-error {
    background: rgba(255, 34, 68, 0.1);
    border: 1px solid var(--red, #ff2244);
    color: var(--red, #ff2244);
}

.qr-result i, .qr-error i {
    font-size: 1.5rem;
}

.qr-success {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.btn-scanner {
    width: 100%;
    padding: 1rem;
    font-family: 'Orbitron', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    border: 2px solid var(--green, #00ff88);
    background: rgba(0, 255, 136, 0.1);
    color: var(--green, #00ff88);
    border-radius: 8px;
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-scanner:hover {
    background: rgba(0, 255, 136, 0.2);
    box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
}

.btn-scanner:active {
    transform: scale(0.98);
}

.btn-scanner.active {
    background: var(--red, #ff2244);
    border-color: var(--red, #ff2244);
    color: #fff;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 600px) {
    .qr-scanner-container {
        padding: 1rem;
        margin: 1rem auto;
    }

    .qr-scanner-header h3 {
        font-size: 1.2rem;
    }
}
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const targetRoute = "{{ $route ?? '/qr' }}";
const successMessage = "{{ $successMessage ?? 'QR escaneado correctamente' }}";

document.getElementById('qr-input')?.addEventListener('change', async (e) => {
    if (e.target.files && e.target.files.length > 0) {
        const file = e.target.files[0];
        
        // UI Feedback
        document.getElementById('qr-result').style.display = 'flex';
        document.getElementById('qr-error').style.display = 'none';
        document.getElementById('qr-message').textContent = 'Procesando imagen...';
        document.getElementById('btn-scan-native').style.display = 'none';
        
        try {
            if (typeof Html5Qrcode === 'undefined') {
                showError('Error al cargar escáner. Recarga la página.');
                return;
            }
            const html5QrCode = new Html5Qrcode("dummy-reader");
            const decodedText = await html5QrCode.scanFile(file, true);
            
            // Éxito: vibrar y enviar
            if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
            sendQRToServer(decodedText);
            
        } catch (err) {
            console.error('Error procesando código QR desde imagen', err);
            showError('No se encontró un código QR válido en la imagen. Inténtalo de nuevo.');
            if (navigator.vibrate) navigator.vibrate(200);
            
            setTimeout(() => {
                document.getElementById('btn-scan-native').style.display = 'flex';
                document.getElementById('qr-result').style.display = 'none';
            }, 2500);
        }
        
        e.target.value = ''; // Reset input
    }
});

function sendQRToServer(qrData) {
    document.getElementById('qr-message').textContent = 'Validando en servidor...';

    fetch(targetRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ qr_data: qrData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('qr-message').textContent = data.message || successMessage;
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
        } else {
            showError(data.message || 'QR inválido o no reconocido');
            setTimeout(() => {
                document.getElementById('btn-scan-native').style.display = 'flex';
                document.getElementById('qr-result').style.display = 'none';
                document.getElementById('qr-error').style.display = 'none';
            }, 2000);
        }
    })
    .catch(error => {
        showError('Error de conexión con el servidor');
        setTimeout(() => {
            document.getElementById('btn-scan-native').style.display = 'flex';
            document.getElementById('qr-result').style.display = 'none';
            document.getElementById('qr-error').style.display = 'none';
        }, 2000);
    });
}

function showError(message) {
    document.getElementById('qr-error-message').textContent = message;
    document.getElementById('qr-error').style.display = 'flex';
    document.getElementById('qr-result').style.display = 'none';

    if (navigator.vibrate) {
        navigator.vibrate(200);
    }
}
</script>
