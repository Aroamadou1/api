<?php
namespace App\Repositories;

use App\Models\Produit;
use App\Models\Bilan;
use Illuminate\Support\Facades\DB;

class BilanRepository
{

    protected $produit;
    protected $obj;
   

    public function __construct(Bilan $obj, Produit $produit)
	{
        $this->obj = $obj;
        $this->produit = $produit;
	}

	public function getAll($date)
	{
		return DB::table('bilans')
		->whereDate('created_at', $date)
		->orderBy('nom', 'asc')
		->get();
    }
    
   public function store() {
    DB::table('produits')->orderBy('id')->chunk(100, function ($produits) {
        foreach ($produits as $produit) {
            $obj = new $this->obj;
            $this->save($obj, $produit);
        }
        return false;
    });
   return true;
   }

   private function save(Bilan $obj, $data) {
    $obj->nom = $data->nom;
    $obj->categorie_id = $data->categorie_id;
    $obj->produit_id = $data->id;
    $obj->quantiteStock = $data->quantiteStock;
    $obj->quantiteReel = $data->quantiteReel;
    $obj->save();
   }


	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

}