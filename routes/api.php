<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('hello', 'HelloController');
// ADMIN
Route::get('admin', 'AdminController@index');
Route::get('adminTrash', 'AdminController@trash');
Route::get('admin/{id}', 'AdminController@show');
Route::get('admin/{id}/{date}', 'AdminController@activities');
Route::post('adminAdd', 'AdminController@store');
Route::post('adminUpdate', 'AdminController@update');
Route::post('adminDestroy', 'AdminController@destroy');
Route::post('adminRestore', 'AdminController@restore');
Route::post('adminLogin', 'AdminController@login');
Route::post('adminLogout', 'AdminController@logout');
Route::post('adminChangePassword', 'AdminController@changePassword');
Route::post('adminResetPassword', 'AdminController@resetPassword');
Route::post('adminChangeIdentifiant', 'AdminController@changeIdentifiant');
//VENDEUR
Route::get('vendeur', 'VendeurController@index');
Route::get('vendeurTrash', 'VendeurController@trash');
Route::get('vendeur/{id}', 'VendeurController@show');
Route::get('vendeur/{id}/{date}', 'VendeurController@activities');
Route::post('vendeurAdd', 'VendeurController@store');
Route::post('vendeurUpdate', 'VendeurController@update');
Route::post('vendeurDestroy', 'VendeurController@destroy');
Route::post('vendeurRestore', 'VendeurController@restore');
Route::post('vendeurLogin', 'VendeurController@login');
Route::post('vendeurLogout', 'VendeurController@logout');
Route::post('vendeurChangePassword', 'VendeurController@changePassword');
Route::post('vendeurChangeIdentifiant', 'VendeurController@changeIdentifiant');
//MAGASINIER
Route::get('magasinier', 'MagasinierController@index');
Route::get('magasinierTrash', 'MagasinierController@trash');
Route::get('magasinier/{id}', 'MagasinierController@show');
Route::get('magasinier/{id}/{date}', 'MagasinierController@activities');
Route::post('magasinierAdd', 'MagasinierController@store');
Route::post('magasinierUpdate', 'MagasinierController@update');
Route::post('magasinierDestroy', 'MagasinierController@destroy');
Route::post('magasinierRestore', 'MagasinierController@restore');
Route::post('magasinierLogin', 'MagasinierController@login');
Route::post('magasinierLogout', 'sMagasinierController@logout');
Route::post('magasinierChangePassword', 'MagasinierController@changePassword');
Route::post('magasinierChangeIdentifiant', 'MagasinierController@changeIdentifiant');
//PORTEUR
Route::get('porteur', 'PorteurController@index');
Route::get('porteurTrash', 'PorteurController@trash');
Route::get('porteur/{id}', 'PorteurController@show');
Route::get('porteur/{id}/{date}', 'PorteurController@activities');
Route::post('porteurAdd', 'PorteurController@store');
Route::post('porteurUpdate', 'PorteurController@update');
Route::post('porteurDestroy', 'PorteurController@destroy');
Route::post('porteurRestore', 'PorteurController@restore');
Route::post('porteurLogin', 'PorteurController@login');
Route::post('porteurLogout', 'sPorteurController@logout');
Route::post('porteurChangePassword', 'PorteurController@changePassword');
// Route::post('porteurChangeIdentifiant', 'PorteurController@changeIdentifiant');
//FOURNISSEUR
Route::get('fournisseur', 'FournisseurController@index');
Route::get('fournisseurTrash', 'FournisseurController@trash');
Route::get('fournisseur/{id}', 'FournisseurController@show');
Route::get('fournisseur/{id}/{date}', 'FournisseurController@activities');
Route::post('fournisseurAdd', 'FournisseurController@store');
Route::post('fournisseurUpdate', 'FournisseurController@update');
Route::post('fournisseurDestroy', 'FournisseurController@destroy');
Route::post('fournisseurRestore', 'FournisseurController@restore');
Route::post('fournisseurLogin', 'FournisseurController@login');
Route::post('fournisseurLogout', 'sFournisseurController@logout');
Route::post('fournisseurChangePassword', 'FournisseurController@changePassword');
// Route::post('fournisseurChangeIdentifiant', 'FournisseurController@changeIdentifiant');
//CATEGORIE
Route::get('categorie', 'CategorieController@index');
Route::get('categorieTrash', 'CategorieController@trash');
Route::get('categorie/{id}', 'CategorieController@show');
Route::post('categorieAdd', 'CategorieController@store');
Route::post('categorieUpdate', 'CategorieController@update');
Route::post('categorieDestroy', 'CategorieController@destroy');
Route::post('categorieRestore', 'CategorieController@restore');
//PRODUIT
Route::get('produit', 'ProduitController@index');
Route::get('produitTrash', 'ProduitController@trash');
Route::get('produit/{id}', 'ProduitController@show');
Route::post('produitAdd', 'ProduitController@store');
Route::post('produitUpdate', 'ProduitController@update');
Route::post('produitDestroy', 'ProduitController@destroy');
Route::post('produitRestore', 'ProduitController@restore');
//INVENTAIRE
Route::get('inventaire', 'InventaireController@index');
Route::get('inventaireTrash', 'InventaireController@trash');
Route::get('inventaire/{type}', 'InventaireController@show');
Route::post('inventaireAdd', 'InventaireController@store');
Route::post('inventaireUpdate', 'InventaireController@update');
Route::post('inventaireDestroy', 'InventaireController@destroy');
Route::post('inventaireRestore', 'InventaireController@restore');
//ENTREE
Route::get('entree', 'EntreeController@index');
Route::get('entreeTrash', 'EntreeController@trash');
Route::get('entree/{type}', 'EntreeController@show');
Route::post('entreeAdd', 'EntreeController@store');
Route::post('entreeUpdate', 'EntreeController@update');
Route::post('entreeDestroy', 'EntreeController@destroy');
Route::post('entreeRestore', 'EntreeController@restore');
Route::post('entreeValidate', 'EntreeController@validation');
//VENTE
Route::get('sortie', 'VenteController@index');
Route::get('sortieTrash', 'VenteController@trash');
Route::get('sortieSearch/{date}', 'VenteController@getByDate');
Route::get('sortie/{type}', 'VenteController@show');
Route::post('sortieAdd', 'VenteController@store');
Route::post('sortieUpdate', 'VenteController@update');
Route::post('sortieDestroy', 'VenteController@destroy');
Route::post('sortieRestore', 'VenteController@restore');
Route::post('sortieValidate', 'VenteController@validation');


//BILAN
Route::get('bilan', 'BilanController@index');
Route::get('bilanTrash', 'BilanController@trash');
Route::get('bilan/{type}', 'BilanController@getByDate');
Route::post('bilanAdd', 'BilanController@store');
Route::post('bilanUpdate', 'BilanController@update');
Route::post('bilanDestroy', 'BilanController@destroy');
Route::post('bilanRestore', 'BilanCon   troller@restore');
//STATS
Route::get('stat', 'StatController@index');
Route::get('statTrash', 'StatController@trash');
Route::get('stat/{date}', 'StatController@getByDate');
Route::post('statAdd', 'StatController@store');
Route::post('statUpdate', 'StatController@update');
Route::post('statDestroy', 'StatController@destroy');
Route::post('statRestore', 'StatController@restore');
//NOTIFICATIONS
Route::get('notification', 'NotificationController@index');
Route::get('notification/{date}', 'NotificationController@getByDate');
//DATA
Route::get('data/{fonction}/{userId}/{notificationId}', 'DataController@check');
Route::get('data/{fonction}/{id}', 'DataController@all');




// Route::resource('vendeur', 'VendeurController');
// Route::post('vendeurlogin', 'VendeurController@auth');
// Route::resource('magasinier', 'MagasinierController');
// Route::post('magasinierlogin', 'MagasinierController@auth');
// Route::resource('porteur', 'PorteurController');
// Route::resource('fournisseur', 'FournisseurController');
// Route::resource('categorie', 'CategorieController');
// Route::resource('produit', 'ProduitController');
// Route::resource('inventaire', 'InventaireController');
// Route::post('editInventaire', 'VenteController@edit');
// Route::resource('vente', 'VenteController');
// Route::post('editvente', 'VenteController@edit');
// Route::resource('sortie', 'VenteController');
// Route::post('validateSortie', 'VenteController@validation');
// Route::get('sortieSearch/{date}', 'VenteController@getByDate');

// Route::resource('entree', 'EntreeController');
// Route::post('editEntree', 'EntreeController@edit');
// Route::get('entreeSearch/{date}', 'EntreeController@getByDate');
// Route::post('validateEntree', 'EntreeController@validation');
// Route::get('auth', 'AuthController@index');
// Route::get('stats', 'StatsController@index');
// Route::get('notification', 'NotificationController@index');
// Route::get('notificationSearch/{date}', 'NotificationController@getByDate');
// Route::get('notification/{fonction}', 'NotificationController@getByFonction');
// Route::get('bilan/{date}', 'BilanController@getByDate');
// Route::post('bilan', 'BilanController@store');
// Route::get('bilan', 'BilanController@index');
// Route::get('stat', 'StatController@index');
// Route::get('searchStat/{date}', 'StatController@getByDate');
