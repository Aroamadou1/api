<?php
namespace App\Repositories;

use App\Models\Fournisseur;
use App\Repositories\GererFournisseurRepository;
use App\Repositories\NotificationRepository;

use Illuminate\Support\Facades\Hash;

class FournisseurRepository
{

	protected $obj;
	protected $fournisseur;
	protected $notification;

    public function __construct(Fournisseur $obj, GererFournisseurRepository $fournisseur, NotificationRepository $notification)
	{
		$this->obj = $obj;
		$this->fournisseur = $fournisseur;
		$this->notification = $notification;
	}

    private function save(Fournisseur $obj, Array $data)
	{
        $obj->nom =$data['nom'];
		$obj->contact =$data['contact'];
		$obj->save();
	}

	private function saveRegistrer(Array $data)
	{
		$this->fournisseur->store($data);
	}

	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	public function getAll()
	{
		return $this->obj->orderBy('nom', 'asc')->get();;
    }
    
    public function getArchives()
	{
		return $this->obj->onlyTrashed()->get();
	}

	public function store(Array $data)
	{
		$obj = new $this->obj;		
		$this->save($obj, $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "fournisseur_id"=> $obj->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"fournisseur", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un fournisseur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a ajouté le fournisseur ".$obj->nom." ".$obj->prenom));
		return true;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function getHistoriques($id)
	{
		return $this->fournisseur->getHistoriques($id);
	}

	public function getActivites($id)
	{
		return $this->notification->getActivites('fournisseur',$id);
	}


	public function update($id, Array $data)
	{
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "fournisseur_id"=> $id, "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"fournisseur","registrer_id"=>$data["registrer_id"],"codeOperation"=>"2", "titre"=>"Modification d'un fournisseur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié les informations de le fournisseur ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function destroy($id, Array $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "fournisseur_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"fournisseur", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'un fournisseur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé le fournisseur ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "fournisseur_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"fournisseur", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'un fournisseur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré le fournisseur ".$data['nom']." ".$data['prenom']));
		return true;
	}


}