<?php
namespace App\Repositories;

use App\Models\Vente;
use App\Models\VenteProduit;
use App\Repositories\GererVenteRepository;
use App\Repositories\GererSortieRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\DB;
use App\Gestion\NumeroFactureGestion;
use App\Gestion\StockGestion;
use App\Models\vente_porteur;

class VenteRepository
{
	protected $date;
	protected $obj;
	protected $vente;
	protected $sortie;
	protected $notification;
	protected $venteProd;
	protected $numeroFacture;
	protected $stock;
	protected $ventePorteur;

    public function __construct(
		Vente $obj,
		VenteProduit $venteProd,
		vente_Porteur $ventePorteur,
		GererVenteRepository $vente,
		GererSortieRepository $sortie,
		NotificationRepository $notification,
		NumeroFactureGestion $numeroFacture,
		StockGestion $stock
	)
	{
		$this->obj = $obj;
		$this->notification = $notification;
		$this->vente = $vente;
		$this->sortie = $sortie;
		$this->venteProd = $venteProd;
		$this->numeroFacture = $numeroFacture;
		$this->stock= $stock;
		$this->ventePorteur=$ventePorteur;
	}

	private function save(vente $obj, array $data)
	{
		$obj->numero = $this->numeroFacture->generate();
		$obj->nomClient = $data['nomClient'];
		$obj->contactClient = $data['contactClient'];
		$obj->quantiteTotale = $data['quantiteTotale'];
		$obj->save();
		$id = $obj->id;
		$prod = $data['data'];
		foreach ($prod as $item) {
			$table = new $this->venteProd;
			$this->saveProdvente($table, $data["registrer_id"], $id, $item);
		}
		$port = $data['porteurs'];
		foreach($port as $item) {
			$table = new $this->ventePorteur;
			$this->savePortVente($table, $item["porteur_id"], $id);
		}
		return $obj;
	}

	public function saveProdVente(venteProduit $table, $registrerId, $id, $item)
	{
		$table->vente_id = $id;
		$table->vendeur_id=$registrerId;
		$table->produit_id = $item["produit_id"];
		$table->quantite = $item["quantite"];
		$table->save();
		$this->stock->removeReel($item["produit_id"], $item["quantite"]);
	}

	public function removeProdVente(venteProduit $table, $id, $item)
	{
		$element = $table->findOrFail($id);
		$element->delete();
		$this->stock->addReel($item["produit_id"], $item["quantite"]);
	}

	public function savePortVente(vente_porteur $table, $porteurId, $id)
	{
		$table->vente_id = $id;
		$table->porteur_id=$porteurId;
		$table->save();
	}

	public function removePortVente(vente_porteur $table, $id)
	{
		$element = $table->findOrFail($id);
		$element->delete();
	}
	public function choosePortVente($porteurId, $id)
	{
		$obj = $this->ventePorteur->where('vente_id', $id)->where('porteur_id', $porteurId)->get();
		$obj->isChecked=true;
		$obj->save();
	}

	public function getAll($date)
	{
		$this->date=$date;
		return DB::table('ventes')
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
		return DB::table('ventes')
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
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "vente_id"=> $res->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"vente", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un vente", "message" => "Le vendeur ".$data['registrer_nom']." ".$data['registrer_prenom']." a enregistrer une  vente "));
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
		$editObj = $this->venteProd->where('vente_id', $id)->get();
		foreach($editObj as $item) {
			$obj = new $this->venteProd;
			$this->removeProdVente($obj, $item->id, $item);
		}
		$editPort = $this->ventePorteur->where('vente_id', $id)->get();
		foreach($editPort as $item) {
			$obj = new $this->ventePorteur;
			$this->removePortVente($obj, $item->id);
		}
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "vente_id"=> $data["id"], "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"vente", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"2", "titre"=>"Modification d'un vente", "message" => "Le vendeur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié une vente ".$data['numero']));
		$produits = $this->getProduits($id);
		$porteurs = $this->getPorteurs($id);
		$historiques = $this->getHistoriques($id);
		return Array("porteurs" => $porteurs, "produits "=>$produits, "historiques" => $historiques, "operation"=>$obj);
	}

	public function destroy($id, $data)
	{
		$delObj = $this->venteProd->where('vente_id', $id)->get();
		foreach($delObj as $item) {
			$obj = new $this->venteProd;
			$this->removeProdVente($obj, $item->id, $item);
		}
		$this->getById($id)->delete();
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "vente_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"vente", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'une vente", "message" => "Le vendeur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé la vente ".$data['numero']));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("vendeur_id"=>$data["registrer_id"], "vente_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"vendeur", "type"=>"vente", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'une vente", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré la vente ".$data['numero']));
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
			// $this->choosePortVente($data["porteur_id"], $id);
			$this->saveNotification(array("fonction"=>"magasinier", "type"=>"vente", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"3", "titre"=>"Validation d'une sortie", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a confirmé la sortie du bon ".$data['numero']));
		} else{
			$this->ajustReel($id);
			$this->saveNotification(array("fonction"=>"magasinier", "type"=>"vente", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Rejet d'une sortie", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a rejeté la sortie du bon ".$data['numero']));
		}
		$this->saveValider(array("magasinier_id"=>$data["registrer_id"], "vente_id"=> $id, "codeOperation"=>"3"));
		
		return true;
	}
	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	private function saveRegistrer(Array $data)
	{
		$this->vente->store($data);
	}

	private function saveValider(Array $data)
	{
		$this->sortie->store($data);
	}

	public function getHistoriques($id)
	{
		return $this->vente->getHistoriques($id);
	}

	public function getPorteurs($id)
	{
		return DB::table('vente_porteurs')
		->join('porteurs', 'vente_porteurs.porteur_id', '=', 'porteurs.id')
		->select('vente_porteurs.*', 'porteurs.nom AS porteurNom', 'porteurs.prenom AS porteurPrenom')
		->where('vente_porteurs.vente_id', $id)
		->get();
	}
	
	public function getMagasinier($id)
	{
		return $this->sortie->getMagasinier($id);
	}

	public function getProduits($id)
	{
		return DB::table('vente_produits')
		->join('produits', 'vente_produits.produit_id', '=', 'produits.id')
		->join('categories', 'produits.categorie_id', '=', 'categories.id')
		->select('vente_produits.*', 'produits.nom', 'produits.categorie_id', 'categories.nom AS categorie')
		->where('vente_produits.vente_id', $id)
		->where('vente_produits.deleted_at', '=', null)
		->get();
	}

	private function ajustStock($id){
		return DB::table('vente_produits')
		->select('vente_produits.produit_id', 'vente_produits.quantite')
		->where('vente_produits.vente_id', $id)
		->whereNull('vente_produits.deleted_at')
		->chunkById(100, function ($prods) {
			foreach ($prods as $prod) {
				$this->stock->removeStock($prod->produit_id, $prod->quantite);
			}
			return false;
		});	
	}

	private function ajustReel($id){
		return DB::table('vente_produits')
		->select('vente_produits.produit_id', 'vente_produits.quantite')
		->where('vente_produits.vente_id', $id)
		->whereNull('vente_produits.deleted_at')
		->chunkById(100, function ($prods) {
			foreach ($prods as $prod) {
				$this->stock->addReel($prod->produit_id, $prod->quantite);
			}
			return false;
		});	
	}
}
