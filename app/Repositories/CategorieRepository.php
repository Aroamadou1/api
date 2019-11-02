<?php
namespace App\Repositories;

use App\Models\Categorie;
use App\Repositories\GererCategorieRepository;
use App\Repositories\NotificationRepository;


class CategorieRepository
{

	protected $obj;
	protected $categorie;
	protected $notification;

    public function __construct(
		Categorie $obj,
		GererCategorieRepository $categorie,
		NotificationRepository $notification
		)
	{
		$this->obj = $obj;
		$this->notification = $notification;
		$this->categorie = $categorie;
	}

    private function save(Categorie $obj, Array $data)
	{
        $obj->nom =$data['nom'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->orderBy('nom', 'asc')->get();
    }
    
    public function getPaginate($n)
	{
		return $this->obj->paginate($n);
	}

	public function store(Array $data)
	{
		$obj = new $this->obj;		
		$this->save($obj, $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "categorie_id"=> $obj->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"categorie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'une categorie", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a ajouté la catégorie ".$obj->nom));
		return $obj;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function update($id, Array $data)
	{
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "categorie_id"=> $id, "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"categorie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"2", "titre"=>"Modification d'une categorie", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié la catégorie ".$data['nom']));
		return true;
	}

	public function destroy($id, $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "categorie_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"categorie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'une categorie", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé la catégorie ".$data['nom']));
		return true;
	}


	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "categorie_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"categorie", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'une categorie", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré la categorie ".$data['nom']));
		return true;
	}

	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	private function saveRegistrer(Array $data)
	{
		$this->categorie->store($data);
	}

	public function getHistoriques($id)
	{
		return $this->categorie->getHistoriques($id);
	}
}