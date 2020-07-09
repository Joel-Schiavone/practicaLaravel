<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', function () {
    return view('welcome');
});

/**
 * Buyer
 */
Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);
Route::resource('buyers.transactions', 'Buyer\BuyerTransactionController', ['only' => ['index']]);
Route::resource('buyers.products', 'Buyer\BuyerProductController', ['only' => ['index']]);
/**
 * Categories
 */
Route::resource('categories', 'Category\CategoryController', ['except' => ['create', 'edit']]);
/**
 * Products
 */
Route::resource('products', 'Product\ProductController', ['only' => ['index', 'show']]);
/**
 * Transactions
 */
Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show']]);
Route::resource('transactions.categories', 'Transaction\TransactionCategoryController', ['only' => ['index']]);
Route::resource('transactions.sellers', 'Transaction\TransactionSellerController', ['only' => ['index']]);
/**
 * Seller
 */
Route::resource('sellers', 'Seller\SellerController', ['only' => ['index', 'show']]);
/**
 * Users
 */
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);