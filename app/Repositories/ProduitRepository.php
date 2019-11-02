<?php
namespace App\Repositories;

use App\Models\Produit;
use App\Repositories\GererProduitRepository;
use App\Repositories\NotificationRepository;

class ProduitRepository
{

    protected $obj;
	protected $produit;
	protected $notification;

    public function __construct(
		Produit $obj,
		GererProduitRepository $produit,
		NotificationRepository $notification
		)
	{
		$this->obj = $obj;
		$this->notification = $notification;
		$this->produit = $produit;
	}

    private function save(Produit $obj, Array $data)
	{
        $obj->categorie_id =$data['categorie_id'];
        $obj->nom =$data['nom'];
        // $obj->quantite =$data['quantite'];
        $obj->quantiteCritique =$data['quantiteCritique'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->orderBy('nom', 'asc')->get();;
    }
    
    public function getPaginate($n)
	{
		return $this->obj->paginate($n);
	}

	public function store(Array $data)
	{
		$obj = new $this->obj;		
		$this->save($obj, $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "produit_id"=> $obj->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"produit", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un produit", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a ajouté le produit ".$data['nom']));
		return true;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function update($id, Array $data)
	{
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "produit_id"=> $id, "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"produit", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"2", "titre"=>"Modification d'un produit", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié le produit ".$data['nom']));
		return true;
	}

	public function destroy($id, $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "produit_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"produit", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'un produit", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé le produit ".$data['nom']));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "produit_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"produit", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'un produit", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré le produit ".$data['nom']));
		return true;
	}

	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	private function saveRegistrer(Array $data)
	{
		$this->produit->store($data);
	}

	public function getHistoriques($id)
	{
		return $this->produit->getHistoriques($id);
	}
}