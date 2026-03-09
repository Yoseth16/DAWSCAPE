<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreguntasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preguntas = [
            ['enunciado' => '¿En qué año se descubrió América?', 'respuesta' => '1492'],
            ['enunciado' => '¿Quién pinto la Mona Lisa?', 'respuesta' => 'Leonardo da Vinci'],
            ['enunciado' => '¿Cuál es el planeta más grande de nuestro sistema solar?', 'respuesta' => 'Júpiter'],
            ['enunciado' => '¿Cuál es el río más largo del mundo?', 'respuesta' => 'Río Amazonas'],
            ['enunciado' => '¿Dónde se encuentra la Torre Eiffel?', 'respuesta' => 'París'],
            ['enunciado' => '¿Cuál es el animal terrestre más rápido?', 'respuesta' => 'Guepardo'],
            ['enunciado' => '¿Quién escribió "El Quijote"?', 'respuesta' => 'Miguel de Cervantes'],
            ['enunciado' => '¿Cuál es el océano más grande?', 'respuesta' => 'Océano Pacífico'],
            ['enunciado' => '¿De qué color es la bandera de Japón?', 'respuesta' => 'Blanca y roja'],
            ['enunciado' => '¿Qué gas respiran las plantas para hacer fotosíntesis?', 'respuesta' => 'Dióxido de carbono'],
            ['enunciado' => '¿En qué continente está Egipto?', 'respuesta' => 'África'],
            ['enunciado' => '¿Cuál es la moneda del Reino Unido?', 'respuesta' => 'Libra esterlina'],
            ['enunciado' => '¿Qué instrumento tocaba Mozart principalmente?', 'respuesta' => 'Piano'],
            ['enunciado' => '¿Cuántos huesos tiene un adulto humano?', 'respuesta' => '206'],
            ['enunciado' => '¿En qué año llegó el hombre a la luna?', 'respuesta' => '1969'],
            ['enunciado' => '¿Cuál es el metal más caro del mundo?', 'respuesta' => 'Rodio'],
            ['enunciado' => '¿Cuántas patas tiene una araña?', 'respuesta' => '8'],
            ['enunciado' => '¿Qué idioma se habla en Brasil?', 'respuesta' => 'Portugués'],
            ['enunciado' => '¿Cuál es la montaña más alta del mundo?', 'respuesta' => 'Monte Everest'],
            ['enunciado' => '¿Quién descubrió la gravedad?', 'respuesta' => 'Isaac Newton'],
        ];

        foreach ($preguntas as $pregunta) {
            \App\Models\PreguntaDinamica::create([
                'enunciado' => $pregunta['enunciado'],
                'respuesta_correcta' => $pregunta['respuesta'],
                'activa' => true,
            ]);
        }
    }
}
