<?php 

namespace App\Gestion;
// use Illuminate\Support\Facades\DB;
use App\Models\Produit;
use App\Models\VenteProduit;
class StockMaisonGestion
{
    protected $product;
    protected $venteProduit;
    public function __construct(
        Produit $product,
        VenteProduit $venteProduit
    ) 
    {
        $this->product = $product;
        $this->venteProduit = $venteProduit;
    }

    public function addStock($id, $q)
	{
        $obj = $this->product->findOrFail($id);
        $obj->quantiteStock = $obj->quantiteStock + $q;
        $obj->save();
    }
    
    public function removeStock($id, $q)
	{
        $obj = $this->product->findOrFail($id);
        $obj->quantiteStock = $obj->quantiteStock - $q;
        $obj->save();
    }

    public function updateStock($id, $q)
	{
        $obj = $this->product->findOrFail($id);
        $obj->quantiteStock = $q;
        $obj->save();
    }

    public function addReel($id, $q)
	{
        $obj = $this->product->findOrFail($id);
        $obj->quantiteReel = $obj->quantiteReel + $q;
        $obj->save();
    }
    
    public function removeReel($id, $q)
	{
        $obj = $this->product->findOrFail($id);
        $obj->quantiteReel = $obj->quantiteReel - $q;
        $obj->save();
    }

    public function updateReel($id, $q)
	{
        $obj = $this->product->findOrFail($id);
        $qVendu = $this->venteProduit->where('produit_id', $id)->where('sortie_at', null)->sum('quantite');
        $obj->quantiteReel = $q -$qVendu;
        $obj->save();
    }

}