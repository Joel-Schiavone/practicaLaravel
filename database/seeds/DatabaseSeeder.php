<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Reinicia a cero las claves foraneas
    	DB::statement('SET FOREIGN_KEY_CHECKS = 0');

    	//Vacia el contenido de todas las tablas a través del modelo
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        //Como category_product no tiene un modelo definido utilizamos para el db de facades para acceder a la tabla por su nombre sin buscarla por el modelo
        DB::table('category_product')->truncate();

        //Cargo las cantidades de registros para las tablas
        $cantidadUsurios = 10;
        $cantidadCategories = 10;
        $cantidadProductos = 10;
        $cantidadTransacciones = 10;

        //Llamamos los factories
        factory(User::class, $cantidadUsurios)->create();
        factory(Category::class, $cantidadCategories)->create();

        // Al momento de crear un producto debemos asociarlo con las categorias que pertenecera. 

        //Para cada una de las instancias creada se debe ejecutar la funcion dentro
        factory(Product::class, $cantidadProductos)->create()->each(
        	//recibe cada producto uno a uno
        	function($product){
        		//Generamos las categorias de orden aleatorio y en diferente cantidades, por ejemplo 1 a 5 categorias y solo el id de la categoria.
        		//Category::all() : todas las categorias
        		//->random(mt_rand(1, 5)) : de manera aleatoria y entre 1 y 5 resultados
        		//->pluck('id') : De toda la coleccion de datos solo requiero el id
        		$categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');

        		//Agregamos las categorias al producto 
        		$product->categories()->attach($categorias);
        	}
        );

        factory(Transaction::class, $cantidadTransacciones)->create();


    }
}
