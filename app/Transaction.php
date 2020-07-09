<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
    	'quantity',
    	'buyer_id',
    	'product_id',
    ];

    public function buyer()
    {
    	//Una transaccion tiene un vendedor
    	return $this->belongsTo(Buyer::class);
    }

    public function product()
    {
    	//Una transaccion puede tener muchos productos
    	return $this->belongsTo(Product::class);
    }

}
