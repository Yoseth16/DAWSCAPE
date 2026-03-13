<?php

return [
    'pistas' => [
        1 => [
            'creador' => 'Mario',
            'imagen' => 'mario.png',
            'imagen_habla' => 'mario2.png',
            'texto' => 'El guardián silencioso vigila desde lo alto. Su identidad está grabada en metal, visible para quien mire hacia arriba. Los pares se multiplican en este santuario del conocimiento.',
        ],
        2 => [
            'creador' => 'Yoseth',
            'imagen' => 'yoseth.png',
            'imagen_habla' => 'yoseth2.png',
            'texto' => 'Cinco fragmentos dispersos por el edificio. Cada máquina guarda una letra. El nombre de una mujer emerge del caos cuando ordenas el desorden.',
        ],
        3 => [
            'creador' => 'Ángel',
            'imagen' => 'angel.png',
            'imagen_habla' => 'angel2.png',
            'texto' => 'Tres elementos convergen en un único número: la interfaz táctil, el tributo monetario y la dulzura medida. La suma de estos tres revela el camino.',
        ],
        4 => [
            'creador' => 'Arturo',
            'imagen' => 'arturo.png',
            'imagen_habla' => 'arturo2.png',
            'texto' => 'La estructura vertical, el código del elixir oscuro y la primera columna del alfabeto. Tres valores que, unidos, desbloquean el siguiente nivel.',
        ],
        5 => [
            'creador' => 'Eloy',
            'imagen' => 'eloy.png',
            'imagen_habla' => 'eloy2.png',
            'texto' => 'El mundo al revés. Donde A se encuentra con Z, y la primera letra del alfabeto besa a la última. 64 nodos metálicos guardan el secreto invertido.',
        ],
        6 => [
            'creador' => 'Migue',
            'imagen' => 'migue.png',
            'imagen_habla' => 'miguel2.png',
            'texto' => 'La memoria es frágil cuando trabaja sola. Dividid la carga, compartid el patrón. Cuatro cuadrantes, una mente colectiva. La sincronización es vuestra única salvación.',
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
