<?php
namespace App\Repositories;

use App\Models\Porteur;

use App\Repositories\GererPorteurRepository;
use App\Repositories\NotificationRepository;


class PorteurRepository
{

	protected $obj;
	protected $porteur;
	protected $notification;

    public function __construct(Porteur $obj, GererPorteurRepository $porteur, NotificationRepository $notification)
	{
		$this->obj = $obj;
		$this->porteur = $porteur;
		$this->notification = $notification;
	}

    private function save(Porteur $obj, Array $data)
	{
        $obj->nom =$data['nom'];
        $obj->prenom =$data['prenom'];
		$obj->contact =$data['contact'];
		$obj->save();
	}

	private function saveRegistrer(Array $data)
	{
		$this->porteur->store($data);
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
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "porteur_id"=> $obj->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"porteur", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un porteur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a ajouté le porteur ".$obj->nom." ".$obj->prenom));
		return true;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function getHistoriques($id)
	{
		return $this->porteur->getHistoriques($id);
	}

	public function getActivites($id)
	{
		return $this->notification->getActivites('porteur',$id);
	}


	public function update($id, Array $data)
	{
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "porteur_id"=> $id, "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"porteur","registrer_id"=>$data["registrer_id"],"codeOperation"=>"2", "titre"=>"Modification d'un porteur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié les informations de le porteur ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function destroy($id, Array $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "porteur_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"porteur", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'un porteur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé le porteur ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "porteur_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"porteur", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'un porteur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré le porteur ".$data['nom']." ".$data['prenom']));
		return true;
	}


}