<?php

namespace App;

use App\Product;
use App\Scopes\SellerScope;

class Seller extends User
{
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new SellerScope);
	}

    public function products()
    {
    	//Un producto tiene muchas transacciones
    	return $this->hasMany(Product::class);
    }
}
