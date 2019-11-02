<?php
namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\GererAdminRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ConnexionRepository;

use Illuminate\Support\Facades\Hash;

class AdminRepository
{

	protected $obj;
	protected $admin;
	protected $notification;
	protected $connexion;

    public function __construct(
		Admin $obj,
		GererAdminRepository $admin,
		NotificationRepository $notification,
		ConnexionRepository $connexion
		)
	{
		$this->obj = $obj;
		$this->admin = $admin;
		$this->notification = $notification;
		$this->connexion = $connexion;
	}

    private function save(Admin $obj, Array $data)
	{
        $obj->nom =$data['nom'];
        $obj->prenom =$data['prenom'];
        $obj->identifiant =$data['identifiant'];
        $obj->password = Hash::make('vonamawu2019');
		$obj->contact =$data['contact'];
		$obj->save();
	}

	private function saveRegistrer(Array $data)
	{
		$this->admin->store($data);
	}

	private function saveNotification(Array $data)
	{
		$this->notification->store($data);
	}

	private function saveConnexion(Array $data) {
		$this->connexion->store($data);
	}

	public function getAll()
	{
		return $this->obj->orderBy('nom', 'asc')->get();
    }
    
    public function getArchives()
	{
		return $this->obj->onlyTrashed()->get();
	}

	public function store(Array $data)
	{
		$obj = new $this->obj;		
		$this->save($obj, $data);
		$this->saveRegistrer(array("admin1_id"=>$data["registrer_id"], "admin2_id"=> $obj->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"admin","type"=>"admin", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un administrateur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a ajouté l'admnistrateur ".$obj->nom." ".$obj->prenom));
		return true;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function getHistoriques($id)
	{
		return $this->admin->getHistoriques($id);
	}

	public function getActivites($id)
	{
		return $this->notification->getActivites('admin',$id);
	}


	public function update($id, Array $data)
	{
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("admin1_id"=>$data["registrer_id"], "admin2_id"=> $id, "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"admin","type"=>"admin","registrer_id"=>$data["registrer_id"],"codeOperation"=>"2", "titre"=>"Modification d'un administrateur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié les informations de l'admnistrateur ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function destroy($id, Array $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("admin1_id"=>$data["registrer_id"], "admin2_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"admin","type"=>"admin", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'un administrateur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé l'admnistrateur ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("admin1_id"=>$data["registrer_id"], "admin2_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"admin","type"=>"admin", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'un administrateur", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré l'admnistrateur ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function login(Array $data)
	{
		global $response;
		$user = $this->obj->where('identifiant', $data["identifiant"])->first();
		if($user){
			if (Hash::check($data["password"], $user->password)) {
				$response = array('user'=>$user, 'isAuth'=>true, "type"=>"success", 'message'=>'vous etes connecté!');
				$user->isOnline=true;
				$user->save();
				$this->saveConnexion(array("fonction"=>"admin", "user_id"=>$user->id));
				$this->saveNotification(array("fonction"=>"admin","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"1", "titre"=>"Connexion d'un administrateur", "message" => "L'admnistrateur ".$user->nom." ".$user->prenom." s'est connecté"));
			} else {
				$response = array('isAuth'=>false,"type"=>"danger", 'message'=>'Mot de passe incorrect!');
			}
		} else {
			$response = array('isAuth'=>false, "type"=>"danger", 'message'=>'Identifiant inconnu!');
		}
		return $response;
	}

	public function logout($id) {
		$user = $this->obj->where('id', $id)->first();
		$user->isOnline=false;
		$user->save();
		$this->saveConnexion(array("fonction"=>"admin", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"admin","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"0", "titre"=>"Deconnexion", "message" => "L'admnistrateur ".$user->nom." ".$user->prenom." s'est déconnecté"));
		return array('isAuth'=>true,"type"=>"success", 'message'=>'Vous etes déconnecté!');
	}

	public function changePassword($data) {
		$user = $this->getById($data['id']);
		$user->password = Hash::make($data['password']);
		$user->save();
		$this->saveConnexion(array("fonction"=>"admin", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"admin","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"2", "titre"=>"Changement de mode passe", "message" => "L'admnistrateur ".$user->nom." ".$user->prenom." a changé son mot de passe"));
		return array('isAuth' => true, 'type'=>'success', 'message'=> 'Mot de passe changé avec succes!');
	}

	public function resetPassword($data) {
		$user = $this->getById($data['id']);
		$user->password = Hash::make('vonamawu2019');
		$user->save();
		$this->saveConnexion(array("fonction"=>"admin", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"admin","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"2", "titre"=>"Renitialisation du mode passe", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a renitialisé le mot de passe de ladministrateur ".$user->nom." ".$user->prenom." a changé son mot de passe"));
		return array('isAuth' => true, 'type'=>'success', 'message'=> 'Mot de passe changé avec succes!');
	}

	public function changeIdentifiant($data) {
		$user = $this->getById($data['id']);
		$user->identifiant = $data['identifiant'];
		$user->save();
		$this->saveConnexion(array("fonction"=>"admin", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"admin","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"2", "titre"=>"changement de lidentifiant", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a changé son identifiant".$user->nom." ".$user->prenom." a changé son mot de passe"));
		return array('isAuth' => true, 'type'=>'success', 'message'=> 'Mot de passe changé avec succes!');
	}

}