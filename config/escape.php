<?php

return [
    'pistas' => [
        'Prueba 1 Mario' => 'Observa bien los números de las puertas y cuenta los elementos pares del aula. La suma final es la clave.',
        'Prueba 2 Yoseth' => 'El anagrama ROCIO se forma con las letras dispersas. Rebusca en el código de las máquinas.',
        'Prueba 3 Angel' => 'Suma los botones (19), el precio del café (110) y el nivel de azúcar (5).',
        'Prueba 4 Arturo' => 'El checksum de la Oasis es la suma de las filas, el código del café y los items de la primera columna.',
        'Prueba 5 Eloy' => 'A=Z, B=Y... Invierte el abecedario para leer el mensaje oculto en las taquillas.',
        'Prueba 6 Migue' => 'Presta atención a la secuencia de luces y sonidos. La cooperación entre el equipo es vital.',
    ],
    'penalizacion_pista' => 180, // 3 minutos por pista

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
        'prueba3' => 124, // Checksum: 19 botones + 110 cents + 5 azucar
        'prueba4' => 141, // Checksum Oasis: 6 filas + cod 128 cafe + 7 items A1
        'prueba5' => 'zasielx', // Palabra espejo
    ],
];
