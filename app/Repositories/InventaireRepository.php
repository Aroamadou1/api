<?php
namespace App\Repositories;

use App\Models\Inventaire;
use App\Models\InventaireProduit;
use App\Repositories\GererInventaireRepository;
use App\Repositories\NotificationRepository;
use App\Gestion\StockGestion;
use Illuminate\Support\Facades\DB;


class InventaireRepository
{

	protected $obj;
	protected $inventaire;
	protected $notification;
	protected $invProd;
	protected $stock;

    public function __construct(
		Inventaire $obj,
		InventaireProduit $invProd,
		GererInventaireRepository $inventaire,
		NotificationRepository $notification,
		StockGestion $stock
		)
	{
		$this->obj = $obj;
		$this->notification = $notification;
		$this->inventaire = $inventaire;
		$this->invProd= $invProd;
		$this->stock= $stock;
	}

    private function save(Inventaire $obj, Array $data)
	{
		$obj->motif = $data['motif'];
		$obj->save();
		$id = $obj->id;
		$prod=$data['data'];
		foreach($prod as $item) {
			$table = new $this->invProd;	
			$this->saveProdInv( $table, $id, $item,$data["registrer_id"], $data['registrer_nom'], $data['registrer_prenom']);
		}
		return $obj;
	}

	public function saveProdInv(InventaireProduit $table, $id, $item, $regId, $regNom, $regPrenom){
			$table->inventaire_id=$id;
			$table->produit_id=$item["id"];
			$table->quantiteCompte=$item["quantiteCompte"];
			$table->quantiteStock=$item["quantiteStock"];
			$table->quantiteReel=$item["quantiteReel"];
			$table->ajust=$item['strategie']; 
			$table->save();
			if($item['strategie']) {
				$this->stock->updateStock($item["id"], $item["quantiteCompte"]);
				$this->stock->updateReel($item["id"], $item["quantiteCompte"]);
				$this->saveNotification(array("fonction"=>"admin", "type"=>"produit","registrer_id"=>$regId , "codeOperation"=>"1", "titre"=>"Ajustement de stock", "message" => "L'admnistrateur ".$regNom." ".$regPrenom." a ajuster le stock du produit ".$item["id"]));
			}
	}

	public function getAll($year)
	{
		return DB::table('inventaires')
		->whereNull('deleted_at')
		->whereYear('created_at', $year)
		->orderBy('id', 'desc')
		->get();
    }
    
    public function getPaginate($n)
	{
		return $this->obj->paginate($n);
	}

	public function store(Array $data)
	{
		$obj = new $this->obj;		
		$res = $this->save($obj, $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "inventaire_id"=> $res->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"inventaire", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un inventaire", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a fait l'inventaire numero ".$res->id));
		return $res;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function update($id, Array $data)
	{
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "inventaire_id"=> $id, "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"inventaire", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"2", "titre"=>"Modification d'un inventaire", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié l'inventaire ".$id));
		return true;
	}

	public function destroy($id, $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "inventaire_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"inventaire", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'un inventaire", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé l'inventaire ".$id));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "inventaire_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"inventaire", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'un inventaire", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré l'inventaire ".$id));
		return $id;
	}

	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	private function saveRegistrer(Array $data)
	{
		$this->inventaire->store($data);
	}

	public function getHistoriques($id)
	{
		return $this->inventaire->getHistoriques($id);
	}

	public function getProduits($id)
	{
		return DB::table('inventaire_produits')
		->join('produits', 'inventaire_produits.produit_id', '=', 'produits.id')
		->select('inventaire_produits.*', 'produits.nom', 'produits.categorie_id')
		->where('inventaire_produits.inventaire_id', $id)
		->get();
	}
}