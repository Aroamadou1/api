<?php
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use \stdClass;
// use App\Models\Vente;
// use App\Models\VenteProduit;
// use App\Models\Entree;
// use App\Models\EntreeProduit;


class StatRepository
{

    // protected $vente;
    // protected $venteProduit;
    // protected $entree;
    // protected $entreeProduit;

    public function __construct()
	{
		// $this->obj = $obj;
	}

	public function getAll()
	{
		// return $this->obj->all();
    }

    
	// public function getBestSell()
	// {
    //     return DB::table('vente_produits')
	// 	->join('produits', 'vente_produits.produit_id', '=', 'produits.id')
	// 	->join('categories', 'produits.categorie_id', '=', 'categories.id')
	// 	->select('vente_produits.*', 'produits.nom', 'produits.categorie_id', 'categories.nom AS categorie')
	// 	->where('vente_produits.vente_id', $id)
	// 	->where('vente_produits.deleted_at', '=', null)
	// 	->get();
    // }

    public function getSell($date)
	{
       $obj = DB::table('produits')
       	->join('categories', 'produits.categorie_id', '=', 'categories.id')
       	->select('produits.*', 'categories.nom AS categorie')
       	->where('produits.deleted_at', '=', null)
       	->get();
		   $data =[];
		for ($x = 0; $x <count($obj); $x++) {
			$res = DB::table('vente_produits')
			->where('produit_id', $obj[$x]->id)
			->where('deleted_at', '=', null)
			->whereDate('created_at', $date)
			->sum('quantite');
			array_push($data, (object)[
				"nom" =>$obj[$x]->nom,
				"categorie" => $obj[$x]->categorie,
				"quantite" => $res
			]);
		}
      return $data;
    }

    public function getWorstSell()
	{
		
    }

    public function getUsersOnlineSell()
	{
		
    }
    
    public function getPaginate($n)
	{
		return $this->obj->paginate($n);
	}


	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

}