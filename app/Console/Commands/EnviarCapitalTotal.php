<?php

namespace App\Console\Commands;

use App\Models\Capital;
use App\Models\Prestamo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\CapitalTotalMail;

class EnviarCapitalTotal extends Command
{
    protected $signature = 'capital:enviar';

    protected $description = 'Envía el capital total actualizado al correo configurado';

    public function handle()
{
    // ============================
    // CAPITAL TOTAL EN SOLES (PEN)
    // ============================

    // 1. Último capital registrado en PEN
    $ultimoCapitalPEN = Capital::where('moneda', 'PEN')
        ->orderBy('id', 'desc')
        ->value('monto') ?? 0;

    // 2. Suma de todos los préstamos pendientes en PEN
    $totalPrestamosPendientesPEN = Prestamo::where('estado', 'no pagado')
        ->where('moneda', 'PEN')
        ->sum('monto');

    // 3. Capital total final
    $capitalTotalSoles = $ultimoCapitalPEN + $totalPrestamosPendientesPEN;

    // 4. Enviar correo
    Mail::to('ericabraham.lazoasencio@gmail.com')
        ->send(new CapitalTotalMail(
            $capitalTotalSoles,
            now()->format('Y-m-d H:i:s')
        ));

    $this->info('Correo enviado correctamente.');
}
}
