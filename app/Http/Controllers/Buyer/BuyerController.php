<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Obtener unicamente los compradores que tengan compras, osea los usuarios que tengan transacciones. Con el metodo has le pasamos la relacion que tiene el modelo, es como hacer una query que diga, traeme todos los usuarios que figuren en la tabla transactions.
        $compradores = Buyer::has('transactions')->get();
        return $this->showAll($compradores);
    }

 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //NO HACE FALTA CON INYECCION IMPLICITA
        //$comprador = Buyer::has('transactions')->findOrFail($id);

        return $this->showOne($buyer);
    }


}
