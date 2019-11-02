<?php

namespace App\Repositories;

use App\Models\Entree;
use App\Models\EntreeProduit;
use App\Repositories\GererEntreeRepository;
use App\Repositories\ValiderEntreeRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\DB;
use App\Gestion\StockGestion;


class EntreeRepository
{

	protected $obj;
	protected $entree;
	protected $validerEntree;
	protected $notification;
	protected $entreeProd;
	protected $stock;
	public function __construct(
		Entree $obj,
		EntreeProduit $entreeProd,
		GererEntreeRepository $entree,
		ValiderEntreeRepository $validerEntree,
		NotificationRepository $notification,
		StockGestion $stock
	) {
		$this->obj = $obj;
		$this->notification = $notification;
		$this->entree = $entree;
		$this->validerEntree = $validerEntree;
		$this->entreeProd = $entreeProd;
		$this->stock= $stock;
	}

	private function save(Entree $obj, array $data)
	{
		$obj->fournisseur_id = $data['fournisseur_id'];
		$obj->numero = $data['numero'];
		$obj->quantiteTotale = $data['quantiteTotale'];
		$obj->save();
		$id = $obj->id;
		$prod = $data['data'];
		foreach ($prod as $item) {
			$table = new $this->entreeProd;
			$this->saveProdEntree($table, $id, $item);
		}
		return $obj;
	}

	public function saveProdEntree(EntreeProduit $table, $id, $item)
	{
		$table->entree_id = $id;
		$table->produit_id = $item["produit_id"];
		$table->quantite = $item["quantite"];
		$table->save();
	}

	public function removeProdEntree(EntreeProduit $table, $id)
	{
		$element = $table->findOrFail($id);
		$element->delete();
	}


	public function getAll($year)
	{
		return DB::table('entrees')
		->join('fournisseurs', 'entrees.fournisseur_id', '=', 'fournisseurs.id')
		->select('entrees.*', 'fournisseurs.nom')
		->whereNull('entrees.deleted_at')
		->whereYear('entrees.created_at', $year)
		->orWhere('status',2)
		->orderBy('id', 'desc')
		->get();
	}

	public function getAnnees(){
		// 
		return array("2019");
	}
	public function getPaginate($n)
	{
		return $this->obj->paginate($n);
	}

	public function store(Array $data)
	{
		$obj = new $this->obj;		
		$res = $this->save($obj, $data);
		$this->saveRegistrer(array("magasinier_id"=>$data["registrer_id"], "entree_id"=> $res->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"magasinier", "type"=>"entree", "registrer_id"=>$data["registrer_id"],"urgence"=>"1", "codeOperation"=>"1", "titre"=>"Ajout d'un entree", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a enregistré une  entree "));
		return $res;
	}

	public function validation($id, Array $data)
	{	
		$obj = $this->getById($id);
		$obj->status=$data['response'];
		$obj->save();
		if($data['response']===1){
			$this->ajustStock($id);
			$this->saveNotification(array("fonction"=>"admin", "type"=>"entree", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"3", "titre"=>"Validation d'une entree", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a confirmé l'entree des produits de".$data['fournisseur_nom']." ".$data['numero']));
			$this->saveNotification(array("fonction"=>"admin", "type"=>"produit", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"3", "titre"=>"Ajustement du stock", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a ajusté le stock suite à l'entree des produits de".$data['fournisseur_nom']." ".$data['numero']));
		} else{
			$this->saveNotification(array("fonction"=>"admin", "type"=>"entree", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"3", "titre"=>"Rejet d'une entree", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a rejeté l'entree des produits de".$data['fournisseur_nom']." ".$data['numero']));
		}
		$this->updateRegistrer(array("admin_id"=>$data["registrer_id"], "entree_id"=> $id, "codeOperation"=>"3"));
		return true;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function update($id, Array $data)
	{
		$editObj = $this->entreeProd->where('entree_id', $id)->get();
		foreach($editObj as $item) {
			$obj = new $this->entreeProd;
			$this->removeProdEntree($obj, $item->id);
		}
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("magasinier_id"=>$data["registrer_id"], "entree_id"=> $data["id"], "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"magasinier", "type"=>"entree", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"2", "titre"=>"Modification d'un entree", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié une entree ".$id));
		return true;
	}

	public function destroy($id, $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("magasinier_id"=>$data["registrer_id"], "entree_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"magasinier", "type"=>"entree", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'une entree", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé l'entree ".$id));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("magasinier_id"=>$data["registrer_id"], "entree_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"magasinier", "type"=>"entree", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'une entree", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré l'entree ".$id));
		return $id;
	}
	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	private function saveRegistrer(Array $data)
	{
		$this->entree->store($data);
	}

	private function updateRegistrer(Array $data)
	{
		$this->validerEntree->store($data);
	}
	
	public function getHistoriques($id)
	{
		return $this->entree->getHistoriques($id);
	}

	public function getProduits($id)
	{
		return DB::table('entree_produits')
		->join('produits', 'entree_produits.produit_id', '=', 'produits.id')
		->select('entree_produits.*', 'produits.nom', 'produits.categorie_id')
		->where('entree_produits.entree_id', $id)
		->where('entree_produits.deleted_at', '=', null)
		->get();
	}

	private function ajustStock($id){
		return DB::table('entree_produits')
		->select('entree_produits.quantite', 'entree_produits.produit_id')
		->where('entree_produits.entree_id', $id)
		->whereNull('entree_produits.deleted_at')
		->chunkById(100, function ($prods) {
			foreach ($prods as $prod) {
				$this->stock->addStock($prod->produit_id, $prod->quantite);
				$this->stock->addReel($prod->produit_id, $prod->quantite);
			}
			return false;
		});	
	}
}
