<?php
namespace App\Repositories;

use App\Models\Sortie;
use App\Models\SortieProduit;
use App\Repositories\GererSortieRepository;
use App\Repositories\ValiderSortieRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\DB;
use App\Gestion\NumeroFactureGestion;
use App\Gestion\StockGestion;
use App\Models\SortiePorteur;

class SortieRepository
{
	protected $date;
	protected $obj;
	protected $sortie;
	protected $validSortie;
	protected $notification;
	protected $sortieProd;
	protected $numeroFacture;
	protected $stock;
	protected $sortiePorteur;

    public function __construct(
		Sortie $obj,
		GererSortieRepository $sortie,
		ValiderSortieRepository $validSortie,
		SortieProduit $sortieProd,
		SortiePorteur $sortiePorteur,
		NotificationRepository $notification,
		NumeroFactureGestion $numeroFacture,
		StockGestion $stock
	)
	{
		$this->obj = $obj;
		$this->sortie = $sortie;
		$this->validSortie = $validSortie;
		$this->notification = $notification;
		$this->sortieProd = $sortieProd;
		$this->numeroFacture = $numeroFacture;
		$this->stock= $stock;
		$this->sortiePorteur=$sortiePorteur;
	}

	private function save(Sortie $obj, array $data)
	{
		$obj->numero = $this->numeroFacture->generate();
		$obj->nomClient = $data['nomClient'];
		$obj->contactClient = $data['contactClient'];
		$obj->quantiteTotale = $data['quantiteTotale'];
		$obj->save();
		$id = $obj->id;
		$prod = $data['data'];
		foreach ($prod as $item) {
			$table = new $this->sortieProd;
			$this->saveProdSortie($table, $data["registrer_id"], $id, $item);
		}
		$port = $data['porteurs'];
		foreach($port as $item) {
			$table = new $this->sortiePorteur;
			$this->savePortSortie($table, $item["porteur_id"], $id);
		}
		return $obj;
	}

	public function saveProdSortie(SortieProduit $table, $registrerId, $id, $item)
	{
		$table->sortie_id = $id;
		$table->vendeur_id=$registrerId;
		$table->produit_id = $item["produit_id"];
		$table->quantite = $item["quantite"];
		$table->save();
		$this->stock->removeReel($item["produit_id"], $item["quantite"]);
	}

	public function removeProdSortie(SortieProduit $table, $id, $item)
	{
		$element = $table->findOrFail($id);
		$element->delete();
		$this->stock->addReel($item["produit_id"], $item["quantite"]);
	}

	public function savePortSortie(SortiePorteur $table, $porteurId, $id)
	{
		$table->sortie_id = $id;
		$table->porteur_id=$porteurId;
		$table->save();
	}

	public function removePortSortie(SortiePorteur $table, $id)
	{
		$element = $table->findOrFail($id);
		$element->delete();
	}

	public function getAll($date)
	{
		$this->date=$date;
		return DB::table('sorties')
		->whereNull('deleted_at')
		->where(function ($query) {
			$query->whereDate('created_at', $this->date)
			->orWhere('sortie_at', null);
		})
		->orderBy('id', 'desc')
		->get();
	}

	public function getByDate($date)
	{
		$this->date=$date;
		return DB::table('sorties')
		->whereNull('deleted_at')
		->whereDate('created_at', $this->date)
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
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "sortie_id"=> $res->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"sortie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un sortie", "message" => "Le vendeur ".$data['registrer_nom']." ".$data['registrer_prenom']." a enregistrer une  sortie "));
		$produits = $this->getProduits($res->id);
		$porteurs = $this->getPorteurs($res->id);
		$historiques = $this->getHistoriques($res->id);
		return Array("porteurs" => $porteurs, "produits"=>$produits, "historiques" => $historiques, "operation"=>$obj);
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function update($id, Array $data)
	{
		$editObj = $this->sortieProd->where('sortie_id', $id)->get();
		foreach($editObj as $item) {
			$obj = new $this->sortieProd;
			$this->removeProdSortie($obj, $item->id, $item);
		}
		$editPort = $this->sortiePorteur->where('sortie_id', $id)->get();
		foreach($editPort as $item) {
			$obj = new $this->sortiePorteur;
			$this->removePortSortie($obj, $item->id);
		}
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "sortie_id"=> $data["id"], "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"sortie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"2", "titre"=>"Modification d'un sortie", "message" => "Le vendeur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié une sortie ".$data['numero']));
		$produits = $this->getProduits($id);
		$porteurs = $this->getPorteurs($id);
		$historiques = $this->getHistoriques($id);
		return Array("porteurs" => $porteurs, "produits "=>$produits, "historiques" => $historiques, "operation"=>$obj);
	}

	public function destroy($id, $data)
	{
		$delObj = $this->sortieProd->where('sortie_id', $id)->get();
		foreach($delObj as $item) {
			$obj = new $this->sortieProd;
			$this->removeProdSortie($obj, $item->id, $item);
		}
		$this->getById($id)->delete();
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "sortie_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"sortie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'une sortie", "message" => "Le vendeur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé la sortie ".$data['numero']));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "sortie_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"sortie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'une sortie", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré la sortie ".$data['numero']));
		return $id;
	}

	public function validation($id, Array $data)
	{	
		$obj = $this->getById($id);
		$obj->status=$data['response'];
		$obj->sortie_at = date("Y-m-d H:i:s");
		$obj->save();
		if($data['response']===1){
			$this->ajustStock($id);
			// $this->choosePortsortie($data["porteur_id"], $id);
			$this->saveNotification(array("fonction"=>"magasinier", "type"=>"sortie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"3", "titre"=>"Validation d'une sortie", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a confirmé la sortie du bon ".$data['numero']));
		} else{
			$this->ajustReel($id);
			$this->saveNotification(array("fonction"=>"magasinier", "type"=>"sortie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Rejet d'une sortie", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a rejeté la sortie du bon ".$data['numero']));
		}
		$this->saveValider(array("magasinier_id"=>$data["registrer_id"], "sortie_id"=> $id, "codeOperation"=>"3"));
		
		return true;
	}
	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	private function saveRegistrer(Array $data)
	{
		$this->sortie->store($data);
	}

	private function saveValider(Array $data)
	{
		$this->validSortie->store($data);
	}

	public function getHistoriques($id)
	{
		return $this->sortie->getHistoriques($id);
	}

	public function getPorteurs($id)
	{
		return DB::table('sortie_porteurs')
		->join('porteurs', 'sortie_porteurs.porteur_id', '=', 'porteurs.id')
		->select('sortie_porteurs.*', 'porteurs.nom AS porteurNom', 'porteurs.prenom AS porteurPrenom')
		->where('sortie_porteurs.sortie_id', $id)
		->get();
	}
	
	public function getMagasinier($id)
	{
		return $this->sortie->getMagasinier($id);
	}

	public function getProduits($id)
	{
		return DB::table('sortie_produits')
		->join('produits', 'sortie_produits.produit_id', '=', 'produits.id')
		->join('categories', 'produits.categorie_id', '=', 'categories.id')
		->select('sortie_produits.*', 'produits.nom', 'produits.categorie_id', 'categories.nom AS categorie')
		->where('sortie_produits.sortie_id', $id)
		->where('sortie_produits.deleted_at', '=', null)
		->get();
	}

	private function ajustStock($id){
		return DB::table('sortie_produits')
		->select('sortie_produits.produit_id', 'sortie_produits.quantite')
		->where('sortie_produits.sortie_id', $id)
		->whereNull('sortie_produits.deleted_at')
		->chunkById(100, function ($prods) {
			foreach ($prods as $prod) {
				$this->stock->removeStock($prod->produit_id, $prod->quantite);
			}
			return false;
		});	
	}

	private function ajustReel($id){
		return DB::table('sortie_produits')
		->select('sortie_produits.produit_id', 'sortie_produits.quantite')
		->where('sortie_produits.sortie_id', $id)
		->whereNull('sortie_produits.deleted_at')
		->chunkById(100, function ($prods) {
			foreach ($prods as $prod) {
				$this->stock->addReel($prod->produit_id, $prod->quantite);
			}
			return false;
		});	
	}
}
