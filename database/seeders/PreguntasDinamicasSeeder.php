<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreguntasDinamicasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preguntas = [
            // GEOGRAFÍA – ENIGMAS
            ['enunciado' => 'Muchos creen que mi ciudad más famosa es Sídney… pero la política vive en otra.', 'respuesta_correcta' => 'Canberra'],
            ['enunciado' => 'Nací en África y vi crecer civilizaciones a mis orillas. Algunos me disputan el título, pero sigo siendo el más largo.', 'respuesta_correcta' => 'Nilo'],
            ['enunciado' => 'Tierra de faraones, pirámides y el Nilo. Estoy en el continente donde nació la humanidad.', 'respuesta_correcta' => 'África'],
            ['enunciado' => 'Mi silueta parece un zapato elegante listo para pisar el Mediterráneo.', 'respuesta_correcta' => 'Italia'],
            ['enunciado' => 'Soy tan grande que abarco once husos horarios.', 'respuesta_correcta' => 'Rusia'],
            ['enunciado' => 'Separan dos países vecinos y forman frontera natural (España y Francia).', 'respuesta_correcta' => 'Pirineos'],
            ['enunciado' => 'Baña Nueva York y separa América de Europa.', 'respuesta_correcta' => 'Atlántico'],
            ['enunciado' => 'Mi nombre parece el de una antigua tribu indígena, pero soy capital del país del arce.', 'respuesta_correcta' => 'Ottawa'],
            ['enunciado' => 'Tengo chocolate, relojes y neutralidad… pero no euro.', 'respuesta_correcta' => 'Suiza'],
            ['enunciado' => 'Soy el mayor desierto cálido y me encuentro en la parte superior de un continente.', 'respuesta_correcta' => 'Norte de África'],
            ['enunciado' => 'Capital tecnológica y hogar del sol naciente.', 'respuesta_correcta' => 'Tokio'],
            ['enunciado' => 'Cruzo la ciudad del amor bajo torres de hierro.', 'respuesta_correcta' => 'Sena'],
            ['enunciado' => 'Capital del tango y del mate.', 'respuesta_correcta' => 'Buenos Aires'],
            ['enunciado' => 'El techo del mundo, en el Himalaya.', 'respuesta_correcta' => 'Everest'],
            ['enunciado' => 'Chichén Itzá y Tikal hablan de mí.', 'respuesta_correcta' => 'México'],

            // HISTORIA – ENIGMAS
            ['enunciado' => 'El año en que Europa volvió a arder tras la invasión de Polonia.', 'respuesta_correcta' => '1939'],
            ['enunciado' => 'Primer líder de la nación de las barras y estrellas.', 'respuesta_correcta' => 'George Washington'],
            ['enunciado' => 'Civilización que levantó las pirámides de Giza.', 'respuesta_correcta' => 'Egipcia'],
            ['enunciado' => 'Año en que tres carabelas cambiaron el mundo.', 'respuesta_correcta' => '1492'],
            ['enunciado' => 'El muro que dividía ideologías cayó en este año.', 'respuesta_correcta' => '1989'],
            ['enunciado' => 'Gobernó España con mano firme hasta 1975.', 'respuesta_correcta' => 'Francisco Franco'],
            ['enunciado' => 'Libertad, igualdad y fraternidad nacieron en…', 'respuesta_correcta' => 'Revolucion Francesa'], // Sin tilde para facilitar acierto crudo si el engine no normaliza 100% perfecto
            ['enunciado' => 'El tratado que castigó a Alemania tras la Gran Guerra.', 'respuesta_correcta' => 'Tratado de Versalles'],
            ['enunciado' => 'Imperio que dominaba Tenochtitlán.', 'respuesta_correcta' => 'Azteca'],
            ['enunciado' => 'Descubrí por accidente el primer antibiótico.', 'respuesta_correcta' => 'Alexander Fleming'],
            ['enunciado' => 'Carta Magna española aprobada tras la dictadura.', 'respuesta_correcta' => '1978'],
            ['enunciado' => 'Durante la II Guerra Mundial no reinaba Isabel II, sino…', 'respuesta_correcta' => 'Jorge VI'],
            ['enunciado' => 'Civilización andina que construyó Machu Picchu.', 'respuesta_correcta' => 'Inca'],
            ['enunciado' => 'Año en que España se dividió en guerra fratricida.', 'respuesta_correcta' => '1936'],
            ['enunciado' => 'El Imperio Romano de Occidente cayó en el año…', 'respuesta_correcta' => '476'],

            // CIENCIA – ENIGMAS
            ['enunciado' => 'Gigante gaseoso con gran mancha roja.', 'respuesta_correcta' => 'Jupiter'],
            ['enunciado' => 'Gas invisible que mantiene tu vida en cada respiración.', 'respuesta_correcta' => 'Oxigeno'],
            ['enunciado' => 'Metal precioso cuyo símbolo químico es Au.', 'respuesta_correcta' => 'Oro'],
            ['enunciado' => 'Número de huesos que sostiene tu cuerpo adulto.', 'respuesta_correcta' => '206'],
            ['enunciado' => 'Bombeo constante, sin descanso.', 'respuesta_correcta' => 'Corazon'],
            ['enunciado' => 'Proceso verde que transforma luz en vida.', 'respuesta_correcta' => 'Fotosintesis'],
            ['enunciado' => 'Revolucioné la física con la relatividad.', 'respuesta_correcta' => 'Albert Einstein'],
            ['enunciado' => 'La obtienes tomando el sol.', 'respuesta_correcta' => 'Vitamina D'],
            ['enunciado' => 'Detecto los movimientos y latidos de la Tierra.', 'respuesta_correcta' => 'Sismografo'],
            ['enunciado' => 'Soy la unidad mínima que tiene vida.', 'respuesta_correcta' => 'Celula'],
            ['enunciado' => 'Dos de hidrógeno y uno de oxígeno forman...', 'respuesta_correcta' => 'H2O'],
            ['enunciado' => 'El animal más veloz en tierra firme.', 'respuesta_correcta' => 'Guepardo'],
            ['enunciado' => 'Metal que es líquido a temperatura ambiente.', 'respuesta_correcta' => 'Mercurio'],
            ['enunciado' => 'Compañera nocturna y solitaria de la Tierra.', 'respuesta_correcta' => 'Luna'],
            ['enunciado' => 'Estoy en el mismísimo centro del átomo.', 'respuesta_correcta' => 'Nucleo'],

            // LITERATURA Y ARTE – ENIGMAS
            ['enunciado' => 'Caballero que luchó contra gigantes que en realidad eran molinos.', 'respuesta_correcta' => 'Miguel de Cervantes'],
            ['enunciado' => 'Sonrisa enigmática pintada por…', 'respuesta_correcta' => 'Leonardo da Vinci'],
            ['enunciado' => 'Amor trágico entre Montescos y Capuletos fue escrito por...', 'respuesta_correcta' => 'William Shakespeare'],
            ['enunciado' => 'Artista atormentado que perdió parte de su oreja.', 'respuesta_correcta' => 'Vincent van Gogh'],
            ['enunciado' => 'Movimiento artístico onírico de Salvador Dalí.', 'respuesta_correcta' => 'Surrealismo'],
            ['enunciado' => 'Realismo mágico en Macondo ideado por...', 'respuesta_correcta' => 'Gabriel Garcia Marquez'],
            ['enunciado' => 'Drama ambientado en una casa andaluza autor de…', 'respuesta_correcta' => 'Federico Garcia Lorca'],
            ['enunciado' => 'Museo parisino que guarda la Mona Lisa.', 'respuesta_correcta' => 'Museo del Louvre'],
            ['enunciado' => 'Compuso la Novena con el "Himno a la Alegría".', 'respuesta_correcta' => 'Ludwig van Beethoven'],
            ['enunciado' => 'Detective de Baker Street creado por…', 'respuesta_correcta' => 'Arthur Conan Doyle'],

            // CINE Y TELEVISIÓN – ENIGMAS
            ['enunciado' => 'Director que hundió el Titanic en la ficción de 1997.', 'respuesta_correcta' => 'James Cameron'],
            ['enunciado' => 'El niño que sobrevivió pertenece a la saga mágica de…', 'respuesta_correcta' => 'Harry Potter'],
            ['enunciado' => 'El rey de la sabana animada clásica.', 'respuesta_correcta' => 'Simba'],
            ['enunciado' => 'Profesor de química que se volvió criminal.', 'respuesta_correcta' => 'Breaking Bad'],
            ['enunciado' => 'Superhéroe neoyorquino mordido por una araña radiactiva.', 'respuesta_correcta' => 'Spider-Man'],

            // DEPORTES – ENIGMAS
            ['enunciado' => 'Número de guerreros en el campo por equipo en fútbol (profesional).', 'respuesta_correcta' => '11'],
            ['enunciado' => 'Cada cuántos años arde la antorcha olímpica.', 'respuesta_correcta' => '4'],
            ['enunciado' => 'Selección campeona del mundo en 2010.', 'respuesta_correcta' => 'Espana'],
            ['enunciado' => 'Deporte de tierra batida dominado por Rafa Nadal.', 'respuesta_correcta' => 'Tenis'],
            ['enunciado' => 'Valor máximo de un tiro lejano detrás de la línea en baloncesto.', 'respuesta_correcta' => '3'],

            // EXTRA DIFÍCILES
            ['enunciado' => 'Color Azul + Color Amarillo resultan en...', 'respuesta_correcta' => 'Verde'],
            ['enunciado' => 'Número romano con forma de ángulo recto que equivale a cincuenta.', 'respuesta_correcta' => 'L'],
            ['enunciado' => 'El país exótico del Taj Mahal.', 'respuesta_correcta' => 'India'],
            ['enunciado' => 'El instrumento que mide la temperatura corporal y ambiental.', 'respuesta_correcta' => 'Termometro'],
            ['enunciado' => 'El continente más pequeño y rodeado de agua de la Tierra.', 'respuesta_correcta' => 'Oceania']
        ];

        foreach ($preguntas as $pregunta) {
            \App\Models\PreguntaDinamica::firstOrCreate(
                ['enunciado' => $pregunta['enunciado']],
                ['respuesta_correcta' => $pregunta['respuesta_correcta'], 'activa' => true]
            );
        }
    }
}
