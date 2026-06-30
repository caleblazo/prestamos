<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClientesAdjunto;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\Referente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClienteController extends Controller
{
    public function create()
    {
        return view('clientes.registrar-cliente', [
            'departamentos' => Departamento::all(),
            'provincias' => Provincia::all(),
            'distritos' => Distrito::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:F,M',
            'dni' => 'required|digits:8|unique:clientes,dni',
            'departamento_id' => 'required|exists:departamentos,id',
            'provincia_id' => 'required|exists:provincias,id',
            'distrito_id' => 'required|exists:distritos,id',
            'direccion' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'celular' => 'required|digits:9',
            'correo' => 'required|email|unique:clientes,correo',
            'referente_nombre' => 'required|string|max:255',
            'referente_celular' => 'required|digits:9',
        ]);

        $cliente = Cliente::create($validated);

        Referente::create([
            'cliente_id' => $cliente->id,
            'nombre' => $validated['referente_nombre'],
            'celular' => $validated['referente_celular'],
        ]);

        return redirect()->back()->with('success', 'Cliente registrado correctamente.');
    }

    public function index(Request $request)
    {
        $query = Cliente::where('estado', 'activo'); // 👈 Filtra solo activos

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%");
            });
        }

        $clientes = $query->paginate(10);

        return view('clientes.index', compact('clientes'));
    }

    public function inactivar(Cliente $cliente)
    {
        $cliente->estado = 'inactivo';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente marcado como inactivo.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', [
            'cliente' => $cliente->load('referente'),
            'departamentos' => Departamento::all(),
            'provincias' => Provincia::all(),
            'distritos' => Distrito::all(),
        ]);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:F,M',
            'departamento_id' => 'required|exists:departamentos,id',
            'provincia_id' => 'required|exists:provincias,id',
            'distrito_id' => 'required|exists:distritos,id',
            'direccion' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'celular' => 'required|digits:9',
            'correo' => 'required|email|unique:clientes,correo,'.$cliente->id,
            'estado' => 'required|in:activo,inactivo',
            'referente_nombre' => 'required|string|max:255',
            'referente_celular' => 'required|digits:9',
        ]);

        // Actualizar cliente
        $cliente->update([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'sexo' => $validated['sexo'],
            'departamento_id' => $validated['departamento_id'],
            'provincia_id' => $validated['provincia_id'],
            'distrito_id' => $validated['distrito_id'],
            'direccion' => $validated['direccion'],
            'referencia' => $validated['referencia'] ?? null,
            'celular' => $validated['celular'],
            'correo' => $validated['correo'],
        ]);

        // Actualizar o crear referente
        if ($cliente->referente) {
            $cliente->referente->update([
                'nombre' => $validated['referente_nombre'],
                'celular' => $validated['referente_celular'],
            ]);
        } else {
            Referente::create([
                'cliente_id' => $cliente->id,
                'nombre' => $validated['referente_nombre'],
                'celular' => $validated['referente_celular'],
            ]);
        }

        return redirect()
            ->route('clientes.edit', $cliente->id)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function archivos(Cliente $cliente)
    {
        return view('clientes.archivos', compact('cliente'));
    }

    public function archivosStore(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'dni' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'recibo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Guardar usando el disco personalizado "clientes"
        $dniPath = $request->file('dni')->store('cliente', 'public');
        $reciboPath = $request->file('recibo')->store('cliente', 'public');

        ClientesAdjunto::create([
            'cliente_id' => $cliente->id,
            'dni_ruta' => $dniPath,        // ejemplo: cliente/archivo.jpeg
            'dni_nombre' => basename($dniPath),
            'recibo_ruta' => $reciboPath,
            'recibo_nombre' => basename($reciboPath),
        ]);

        return redirect()->route('clientes.archivos', $cliente->id)
            ->with('success', 'Archivos subidos correctamente.');
    }

    public function show(Cliente $cliente)
    {
        // Cargar relaciones: prestamos y cuotas
        $cliente->load([
            'departamento',
            'provincia',
            'distrito',
            'adjuntos',
            'prestamos.cuotas',
        ]);

        return view('clientes.show', compact('cliente'));
    }
}
