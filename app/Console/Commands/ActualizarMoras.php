<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cuota;
use Carbon\Carbon;

class ActualizarMoras extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuotas:actualizar-moras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la mora de las cuotas pendientes según reglas de año';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $hoy = Carbon::now()->startOfDay();

        // Obtener cuotas no pagadas y vencidas
        $cuotas = Cuota::where(function ($q) {
                $q->whereNull('monto_abono')
                  ->orWhereRaw('monto_abono < (monto + mora)');
            })
            ->whereDate('fecha', '<', $hoy) // solo cuotas vencidas
            ->get();

        foreach ($cuotas as $cuota) {

            $prestamo = $cuota->prestamo;

            if (!$prestamo) {
                continue;
            }

            // Seguridad extra: evitar cuotas futuras
            if ($cuota->fecha >= $hoy) {
                continue;
            }

            // Calcular días de atraso
            $diasAtraso = Carbon::parse($cuota->fecha)->diffInDays($hoy);

            // Regla según año del préstamo
            // Mora variable: 0.5% diario del monto de la cuota
            $nuevaMora = 5 * $diasAtraso;

            // Actualizar mora
            $cuota->mora = round($nuevaMora, 2);
            $cuota->save();

            // Log opcional para depuración
            $this->info("Cuota ID {$cuota->id} — Fecha: {$cuota->fecha->format('Y-m-d')} — Atraso: {$diasAtraso} días — Mora: {$cuota->mora}");
        }

        $this->info('Moras actualizadas correctamente.');
    }
}
