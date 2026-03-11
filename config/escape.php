<?php

return [
    'pistas' => [
        1 => [
            'creador' => 'Mario',
            'imagen' => 'mario.png',
            'texto' => 'Observa bien los números de las puertas y cuenta los elementos pares del aula. La suma final es la clave.',
        ],
        2 => [
            'creador' => 'Yoseth',
            'imagen' => 'yoseth.png',
            'texto' => 'El anagrama ROCIO se forma con las letras dispersas. Rebusca en el código de las máquinas.',
        ],
        3 => [
            'creador' => 'Ángel',
            'imagen' => 'angel.png',
            'texto' => 'Suma los botones (18), el precio del café (80) y el nivel de azúcar (5).',
        ],
        4 => [
            'creador' => 'Arturo',
            'imagen' => 'arturo.png',
            'texto' => 'El checksum de la Oasis es la suma de las filas, el código del café y los items de la primera columna.',
        ],
        5 => [
            'creador' => 'Eloy',
            'imagen' => 'eloy.png',
            'texto' => 'A=Z, B=Y... Invierte el abecedario para leer el mensaje oculto en las taquillas.',
        ],
        6 => [
            'creador' => 'Migue',
            'imagen' => 'migue.png',
            'texto' => 'Presta atención a la secuencia de luces y sonidos. La cooperación entre el equipo es vital.',
        ],
    ],
    'penalizacion_pista' => 480, // 8 minutos por pista

    'respuestas' => [
        'prueba1' => [
            ['115', 'aula 115'],
            ['16', 'dieciseis'],
            ['32', 'treinta y dos'],
            ['14', 'catorce'],
            ['5x0lh8bx100127t'],
            ['201', 'doscientos uno']
        ],
        // La prueba 2 la maneja el front-end con un anagrama "ROCIO"
        'prueba3' => 103, // Checksum: 18 botones + 80 cents + 5 azucar
        'prueba4' => 141, // Checksum Oasis: 6 filas + cod 128 cafe + 7 items A1
        'prueba5' => 'zasielx', // Palabra espejo
    ],
];
