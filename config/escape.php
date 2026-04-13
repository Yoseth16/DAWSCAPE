<?php

return [
    'pistas' => [
        1 => [
            'creador' => 'Mario',
            'imagen' => 'mario.png',
            'imagen_habla' => 'mario2.png',
            'texto' => 'Primer acertijo: el número del aula está en la puerta. Segundo: contad los ordenadores de la sala. Tercero: cada ordenador tiene DOS pantallas, así que multiplicad por 2. Cuarto: contad las luces del techo. Quinto: el código secreto está grabado en la placa metálica del proyector que cuelga del techo, copiadlo EXACTO. Sexto: sumad TODO lo descubierto, y del código extraed cada dígito por separado para sumarlos también.',
        ],
        2 => [
            'creador' => 'Yoseth',
            'imagen' => 'yoseth.png',
            'imagen_habla' => 'yoseth2.png',
            'texto' => 'Buscad 5 letras repartidas por las máquinas del edificio (cafetería, vending, etc.). Cada máquina tiene una letra pegada o escondida. Cuando tengáis las 5 letras, reordenadlas para formar un nombre de mujer de 5 letras muy común en España. Pista extra: empieza por R.',
        ],
        3 => [
            'creador' => 'Ángel',
            'imagen' => 'angel.png',
            'imagen_habla' => 'angel2.png',
            'texto' => 'Tenéis que SUMAR tres cosas de la máquina de café: 1) Contad TODOS los botones (los de bebidas, Start, Stop, azúcar... ¡todos!). 2) Buscad el precio del Té al Limón y pasadlo a céntimos. 3) Mirad hasta qué número llega la barra de nivel de azúcar. Sumad esos tres números y tendréis la respuesta.',
        ],
        4 => [
            'creador' => 'Arturo',
            'imagen' => 'arturo.png',
            'imagen_habla' => 'arturo2.png',
            'texto' => 'Tenéis que SUMAR tres cosas de la máquina de snacks del Oasis: 1) Contad las filas horizontales (bandejas) que se ven dentro de la máquina. 2) En la máquina de al lado, buscad el código numérico asignado al café (es un número de 3 cifras). 3) Contad cuántos productos caben exactamente en el primer espiral de arriba a la izquierda (A1). Sumad los tres números.',
        ],
        5 => [
            'creador' => 'Eloy',
            'imagen' => 'eloy.png',
            'imagen_habla' => 'eloy2.png',
            'texto' => 'La tabla de la pantalla os dice qué letra corresponde a cada taquilla (Z=1, Y=2... A=26). Id a las taquillas en el orden que indica la secuencia (1, 26, 8, 18, 22, 15, 3) y buscad la letra que hay en cada una. Formad la palabra con esas letras en ESE orden. Pista extra: con el alfabeto invertido, taquilla 1=Z, taquilla 26=A, taquilla 8=S...',
        ],
        6 => [
            'creador' => 'Migue',
            'imagen' => 'miguel.png',
            'imagen_habla' => 'miguel2.png',
            'texto' => 'La memoria es frágil cuando trabaja sola. Dividid la carga, compartid el patrón. Cuatro cuadrantes, una mente colectiva. La sincronización es vuestra única salvación.',
        ],
        7 => [
            'creador' => 'Nacho',
            'imagen' => 'nacho.png',
            'imagen_habla' => 'nacho2.png',
            'texto' => 'Pulsad "Ver Ubicación" en cada dígito del candado para saber dónde está el QR. Id a esa ubicación, escaneadlo con el móvil y leed el acertijo: la respuesta siempre es un número del 0 al 9. Cada acertijo describe un número con pistas de ciencia, matemáticas o cultura general. Si no lo sacáis: buscad en Google la pista más concreta del acertijo y el número saldrá solo.',
        ],
    ],
    'penalizacion_pista' => 480, // 480 segundos (8 minutos) por pista

    'respuestas' => [
        'prueba1' => [
            ['115', 'aula 115'],
            ['16', 'dieciseis'],
            ['32', 'treinta y dos'],
            ['14', 'catorce'],
            ['5x0lh8bx100127t'],
            ['201', 'doscientos uno']
        ],
        // La prueba 2 la maneja el front-end con un anagrama, pero el back valida también
        'prueba2' => 'rocio',
        'prueba3' => 103, // Checksum: 18 botones + 80 cents + 5 azucar
        'prueba4' => 141, // Checksum Oasis: 6 filas + cod 128 cafe + 7 items A1
        'prueba5' => 'zasielx', // Palabra espejo
    ],
];
