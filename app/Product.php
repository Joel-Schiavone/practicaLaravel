<?php

namespace App;

use App\seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    const PRODUCTO_DISPONIBLE = 'disponible';
	const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id',
    ];

    public function estaDisponible()
    {
    	return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function seller()
    {
        //Un producto tiene un vendedor
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        //Un producto tiene muchas transacciones
        return $this->hasMany(Transaction::class);
    }
    
    public function categories()
    {
    	//muchos producto tienen muchas cagorias, relacion en ambos modelos
    	return $this->belongsToMany(Category::class);
    }




}
