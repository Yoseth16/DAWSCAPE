<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;
use App\Models\Inscripcion;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    protected $statsService;

    public function __construct(\App\Services\AdminStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function adminPanel()
    {
        // 1. Obtener Top 5
        $top5 = $this->statsService->getTop5();

        // 2. Obtener la lista general
        $partidas = $this->statsService->getPartidasOrdered();

        // 3. Obtener Métricas (Ahora optimizadas desde BBDD)
        $metrics = $this->statsService->getMetrics();
        $totalEquipos =      $metrics['total'];
        $equiposFinalizados =$metrics['finalizados'];
        $equiposActivos =    $metrics['activos'];
        $equiposTimeout =    $metrics['timeout'];
        $promedioSegundos =  $metrics['promedio_sec'];

        // 4. Obtener Chart Data
        $chartData = $this->statsService->getChartData();

        // 5. NUEVO: Obtener Inscripciones (equipos que se registraron pero no han comenzado)
        $emailsConPartida = Partida::pluck('email')->toArray();
        $inscripcionesPendientes = Inscripcion::whereNotIn('correo', $emailsConPartida)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInscritos = Inscripcion::count();
        $inscritosNoIniciados = $inscripcionesPendientes->count();

        // 6. Autodescubrimiento de rutas de pruebas para el menú Admin
        $rutasPruebas = collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                return str_starts_with($route->getName(), 'admin.prueba.');
            })
            ->map(function ($route) {
                $nombreSlug = str_replace('admin.prueba.', '', $route->getName());
                $nombreLegible = ucfirst(preg_replace('/([a-zA-Z]+)(\d+)(.*)/', '$1 $2 $3', $nombreSlug));
                return [
                    'url' => url('/') . '/' . ltrim($route->uri(), '/'),
                    'nombre' => 'Test: ' . $nombreLegible
                ];
            })
            ->values();

        return view('admin.dashboard', compact(
            'partidas', 'top5', 'rutasPruebas',
            'totalEquipos', 'equiposFinalizados', 'equiposActivos', 'equiposTimeout', 'chartData',
            'inscripcionesPendientes', 'totalInscritos', 'inscritosNoIniciados'
        ))->with('tiempoMedioSegundos', $promedioSegundos);
    }

    public function eliminarEquipo($uuid)
    {
        $partida = Partida::where('uuid', $uuid)->first();
        if ($partida) {
            $partida->delete();
            return back()->with('success', 'Equipo eliminado correctamente del sistema.');
        }
        return back()->with('error', 'El equipo no existe.');
    }

    public function mostrarLogs($uuid)
    {
        $partida = Partida::with(['logs' => function($query) {
            $query->orderBy('created_at', 'asc');
        }])->where('uuid', $uuid)->firstOrFail();

        return view('admin.logs', compact('partida'));
    }

    public function exportarTop20PDF()
    {
        $top20 = Partida::where('fase_actual', config('fases.fases.finalizado.db'))
            ->orderBy('tiempo_segundos', 'asc')
            ->limit(20)
            ->get();

        $pdf = Pdf::loadView('ranking-pdf', compact('top20'));
        return $pdf->download('Ranking_Top_20_DAWSCAPE.pdf');
    }




    public function apiStats()
    {
        $partidas = $this->statsService->getPartidasOrdered();
        $metrics = $this->statsService->getMetrics();
        $chartData = $this->statsService->getChartData();
        $bottlenecks = $this->statsService->getBottlenecks();

        // Limpieza drástica del método, delegando a la capa Controller solo el Response JSON.
        return response()->json([
            'metrics' => [
                'total' =>       $metrics['total'],
                'finalizados' => $metrics['finalizados'],
                'activos' =>     $metrics['activos'],
                'promedio' =>    $metrics['promedio']
            ],
            'chart' => $chartData,
            'bottlenecks' => $bottlenecks,
            'partidas' => $partidas->map(function($p) {
                $segsLive = 0;
                if ($p->fase_actual !== config('fases.fases.finalizado.db') && $p->fase_actual !== config('fases.fases.timeout.db') && $p->inicio_juego) {
                    $segsLive = abs(now()->diffInSeconds(\Carbon\Carbon::parse($p->inicio_juego)));
                }
                $pistasCount = \App\Models\PistaUsada::where('partida_id', $p->id)->count();
                return [
                    'uuid' => $p->uuid,
                    'id' => str_pad($p->id, 3, '0', STR_PAD_LEFT),
                    'nombre_equipo' => $p->nombre_equipo,
                    'email' => $p->email,
                    'clase' => $p->clase,
                    'fase_actual' => $p->fase_actual,
                    'tiempo_segundos' => (int)$p->tiempo_segundos,
                    'tiempo_live' => $segsLive,
                    'color_hex' => $p->color_hex,
                    'icono' => $p->icono,
                    'logros' => $p->logros ?? [],
                    'avatar' => strtoupper(substr($p->nombre_equipo, 0, 1)),
                    'pistas_count' => $pistasCount
                ];
            })
        ]);
    }

    public function apiInscripciones()
    {
        $emailsConPartida = Partida::pluck('email')->toArray();
        $inscripcionesPendientes = Inscripcion::whereNotIn('correo', $emailsConPartida)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInscritos = Inscripcion::count();
        $inscritosNoIniciados = $inscripcionesPendientes->count();

        return response()->json([
            'total_inscritos' => $totalInscritos,
            'no_iniciados' => $inscritosNoIniciados,
            'iniciados' => $totalInscritos - $inscritosNoIniciados,
            'inscripciones_pendientes' => $inscripcionesPendientes->map(function($insc) {
                return [
                    'id' => $insc->id,
                    'nombre_equipo' => $insc->nombre_equipo,
                    'correo' => $insc->correo,
                    'clase' => $insc->clase,
                    'avatar' => strtoupper(substr($insc->nombre_equipo, 0, 1)),
                    'tiempo_inscripcion' => $insc->created_at->diffForHumans(),
                    'created_at' => $insc->created_at
                ];
            })
        ]);
    }

    // ==========================================
    // MÓDULO: CREADOR DINÁMICO DE PRUEBAS
    // ==========================================
    public function eliminarInscripcion($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $nombreEquipo = $inscripcion->nombre_equipo;
        $inscripcion->delete();

        return back()->with('success', "Inscripción de '" . $nombreEquipo . "' eliminada correctamente.");
    }

    // ==========================================
    // MÓDULO: CREADOR DINÁMICO DE PRUEBAS
    // ==========================================
    public function gestionarPreguntas()
    {
        $preguntas = \App\Models\PreguntaDinamica::orderBy('created_at', 'desc')->get();
        return view('admin.gestionar-preguntas', compact('preguntas'));
    }

    public function guardarPregunta(Request $request)
    {
        $request->validate([
            'enunciado' => 'required|string',
            'respuesta_correcta' => 'required|string|max:255'
        ]);

        \App\Models\PreguntaDinamica::create([
            'enunciado' => $request->enunciado,
            'respuesta_correcta' => $request->respuesta_correcta,
            'activa' => true
        ]);

        return back()->with('success', 'Pregunta guardada correctamente.');
    }

    public function togglePregunta($id)
    {
        $pregunta = \App\Models\PreguntaDinamica::findOrFail($id);
        $pregunta->activa = !$pregunta->activa;
        $pregunta->save();

        $estado = $pregunta->activa ? 'activada' : 'desactivada';
        return back()->with('success', "Pregunta $estado exitosamente.");
    }

    public function eliminarPregunta($id)
    {
        $pregunta = \App\Models\PreguntaDinamica::findOrFail($id);
        $pregunta->delete();

        return back()->with('success', 'Pregunta eliminada del sistema.');
    }
}
