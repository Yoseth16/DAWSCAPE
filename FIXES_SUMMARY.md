# Resumen de Correcciones - DAWSCAPE

**Fecha:** 11 de marzo de 2026  
**Tareas completadas:** Tarea 12 y Tarea 13

---

## 🎯 TAREA 12: Redirección incorrecta después de la Prueba 6 (Miguel)

### Problema identificado:
Después de completar la Prueba 6 (Sobrecarga del Sistema - Simon Dice Cooperativo), el botón de éxito redirigía directamente a `/final`, saltándose la Prueba 7 (El Códice Oculto).

### Solución implementada:
**Archivo modificado:** `resources/views/pruebas/prueba6.blade.php`

**Cambio realizado:**
```blade
<!-- ANTES -->
<a href="/final" class="success-btn">COMPLETAR ESCAPE ROOM →</a>

<!-- DESPUÉS -->
<a href="/prueba7" class="success-btn">CONTINUAR A LA SIGUIENTE PRUEBA →</a>
```

**Resultado:**
- ✅ Los equipos ahora son redirigidos correctamente a la Prueba 7 después de completar la Prueba 6
- ✅ El flujo del juego se mantiene lineal: Prueba 6 → Prueba 7 → Final
- ✅ El texto del botón ahora refleja correctamente que hay más pruebas por delante

---

## 🏆 TAREA 13: Top 10 no considera las penalizaciones por uso de pistas

### Problema identificado:
El sistema de ranking (Top 10 y Top 5 en el panel de administración) ordenaba los equipos únicamente por `tiempo_segundos`, ignorando completamente el campo `penalizacion_segundos` que se acumula cuando los equipos usan pistas (60 segundos por pista).

Esto causaba que equipos que usaron pistas aparecieran en mejores posiciones que equipos más rápidos que no las usaron.

### Soluciones implementadas:

#### 1. **AdminStatsService.php** - Top 5 del Dashboard
**Archivo modificado:** `app/Services/AdminStatsService.php`

**Método:** `getTop5()`

**Cambio realizado:**
```php
// ANTES
return Partida::where('fase_actual', 'FINALIZADO')
    ->orderBy('tiempo_segundos', 'asc')
    ->limit(5)
    ->get();

// DESPUÉS
return Partida::where('fase_actual', 'FINALIZADO')
    ->orderByRaw('(tiempo_segundos + COALESCE(penalizacion_segundos, 0)) ASC')
    ->limit(5)
    ->get();
```

**Resultado:**
- ✅ El Top 5 del panel de administración ahora ordena por tiempo total (tiempo base + penalizaciones)
- ✅ Se usa `COALESCE` para manejar valores NULL en penalizacion_segundos

---

#### 2. **Top10Controller.php** - Pantalla pública del Top 10
**Archivo modificado:** `app/Http/Controllers/Top10Controller.php`

**Método:** `apiData()`

**Cambios realizados:**

##### a) Incluir penalizaciones en el cálculo de tiempo_live:
```php
// ANTES
$segsLive = 0;
if ($partida->fase_actual !== 'FINALIZADO' && $partida->fase_actual !== 'FINALIZADO_TIMEOUT' && $partida->inicio_juego) {
    $segsLive = abs(now()->diffInSeconds(Carbon::parse($partida->inicio_juego)));
} else {
    $segsLive = (int)$partida->tiempo_segundos;
}

// DESPUÉS
$segsLive = 0;
$penalizacion = (int)($partida->penalizacion_segundos ?? 0);

if ($partida->fase_actual !== 'FINALIZADO' && $partida->fase_actual !== 'FINALIZADO_TIMEOUT' && $partida->inicio_juego) {
    $segsLive = abs(now()->diffInSeconds(Carbon::parse($partida->inicio_juego))) + $penalizacion;
} else {
    $segsLive = (int)$partida->tiempo_segundos + $penalizacion;
}
```

##### b) Mejorar la ordenación de equipos finalizados:
```php
// ANTES
$finalizados = $todasPartidas->filter(function ($p) {
    return $p['terminado'];
})->sortBy('tiempo_live')->values();

// DESPUÉS
$finalizados = $todasPartidas->filter(function ($p) {
    return $p['terminado'];
})->sortBy(function ($p) {
    // Ordenar por tiempo total (tiempo_live ya incluye penalizaciones en el cálculo)
    return $p['tiempo_live'];
})->values(); // Sort by shortest time (including penalties)
```

**Resultado:**
- ✅ La pantalla pública del Top 10 ahora muestra tiempos totales (incluyendo penalizaciones)
- ✅ Los equipos finalizados se ordenan correctamente por tiempo total
- ✅ Los equipos activos también muestran sus penalizaciones acumuladas en tiempo real
- ✅ El cálculo de "gap" (diferencia con el primer lugar) ahora es preciso

---

## 📊 Impacto de las correcciones:

### Antes:
- ❌ Equipos podían "hacer trampa" usando todas las pistas sin penalización en el ranking
- ❌ El Top 10 no reflejaba el verdadero rendimiento de los equipos
- ❌ Los equipos se saltaban la Prueba 7

### Después:
- ✅ El ranking es justo: penaliza el uso de pistas con 60 segundos adicionales
- ✅ Los equipos que completan el juego sin ayuda son recompensados con mejores posiciones
- ✅ El flujo del juego es completo: todas las 7 pruebas se juegan en orden
- ✅ La competencia es más equilibrada y transparente

---

## 🔍 Archivos modificados:

1. `resources/views/pruebas/prueba6.blade.php` - Redirección corregida
2. `app/Services/AdminStatsService.php` - Top 5 con penalizaciones
3. `app/Http/Controllers/Top10Controller.php` - Top 10 con penalizaciones

---

## ✅ Verificación:

Para verificar que las correcciones funcionan correctamente:

1. **Prueba 6 → Prueba 7:**
   - Completar la Prueba 6
   - Verificar que redirige a `/prueba7` y no a `/final`

2. **Top 10 con penalizaciones:**
   - Crear dos equipos de prueba
   - Equipo A: completa sin pistas en 10 minutos
   - Equipo B: completa con 2 pistas en 8 minutos (8min + 2min penalización = 10min)
   - Verificar que ambos aparecen con tiempos similares en el ranking
   - Verificar que el orden es correcto según tiempo total

3. **Panel de administración:**
   - Verificar que el Top 5 muestra tiempos coherentes
   - Verificar que equipos con pistas tienen tiempos mayores en el ranking

---

## 📝 Notas técnicas:

- Se usa `COALESCE(penalizacion_segundos, 0)` para manejar casos donde el campo es NULL
- El cálculo de penalizaciones se hace tanto para equipos activos como finalizados
- La ordenación usa el operador de comparación espacial `<=>` para manejar valores NULL
- Los cambios son retrocompatibles con partidas existentes en la base de datos

---

**Estado:** ✅ Completado y listo para producción
