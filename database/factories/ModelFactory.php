<?php

use App\Buyer;
use App\Category;
use App\Product;
use App\Seller;
use App\Transaction;
use App\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Category::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Product::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]),
        'image' => $faker->randomElement(['img1.jpg', 'img2.png', 'img3.jpg', 'img4.jpg']),
        'seller_id' => User::inRandomOrder()->first()->id,
    ];
});


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Transaction::class, function (Faker\Generator $faker) {

	//Obtener todos los usuarios que tengan al menos un producto asociado. 
	$vendedor = Seller::has('products')->get()->random();
	//Obtengo todos los vendedores excepto el usuario donde el id sea similar al obtenido en el resultado anterior de vendedor, no queremos que un comprador se compre asi mismo.
	$comprador = User::all()->except($vendedor->id)->random();

    return [
        'quantity' => $faker->numberBetween(1, 10),
        'buyer_id' => $comprador->id,
        'porduct_id' => $vendedor->products->random()->id,
    ];
});


