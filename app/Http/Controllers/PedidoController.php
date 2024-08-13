<?php

namespace App\Http\Controllers;

use App\Http\Resources\PedidoCollection;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // With trae los registros de user por su relacion unica
        return new PedidoCollection(Pedido::with('user')->with('productos')->where('estado', 0)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Almacenar una orden
        $pedido = new Pedido();
        $pedido->user_id = Auth::user()->id; // Podemos obtener la id porque nuestra peticions es autenticada
        $pedido->total = $request->total;
        $pedido->save(); // Automaticamente genera el create_at y el updated_at

        // Obtener el id del pediddo
        $id = $pedido->id;

        // Obtener los producto
        $productos = $request->productos;

        // Formatear el arreglo
        $pedido_producto = [];

        foreach($productos as $producto) {
            $pedido_producto[] = [
                'pedido_id' => $id,
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Almacenar en la DB
        PedidoProducto::insert($pedido_producto);

        return [
            'message' => 'Pedido Realizado Correctamente, estará listo en unos minutos'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
