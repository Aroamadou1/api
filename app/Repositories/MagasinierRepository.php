<?php
namespace App\Repositories;

use App\Models\Magasinier;
use App\Repositories\GererMagasinierRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ConnexionRepository;
use Illuminate\Support\Facades\Hash;

class MagasinierRepository
{

	protected $obj;
	protected $magasinier;
	protected $notification;
	protected $connexion;

    public function __construct(
		Magasinier $obj,
		GererMagasinierRepository $magasinier,
		NotificationRepository $notification,
		ConnexionRepository $connexion
		)
	{
		$this->obj = $obj;
		$this->magasinier = $magasinier;
		$this->notification = $notification;
		$this->connexion = $connexion;
	}

    private function save(Magasinier $obj, Array $data)
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
		$this->magasinier->store($data);
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
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "magasinier_id"=> $obj->id, "codeOperation"=>"1"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"magasinier", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"1", "titre"=>"Ajout d'un magasinier", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a ajouté le magasinier ".$obj->nom." ".$obj->prenom));
		return true;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function getHistoriques($id)
	{
		return $this->magasinier->getHistoriques($id);
	}

	public function getActivites($id)
	{
		return $this->notification->getActivites('magasinier',$id);
	}


	public function update($id, Array $data)
	{
		$this->save($this->getById($id), $data);
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "magasinier_id"=> $id, "codeOperation"=>"2"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"magasinier","registrer_id"=>$data["registrer_id"],"codeOperation"=>"2", "titre"=>"Modification d'un magasinier", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a modifié les informations de le magasinier ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function destroy($id, Array $data)
	{
		$this->getById($id)->delete();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "magasinier_id"=> $id, "codeOperation"=>"0"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"magasinier", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"0", "titre"=>"Suppression d'un magasinier", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a supprimé le magasinier ".$data['nom']." ".$data['prenom']));
		return true;
	}

	public function restore($id, Array $data)
	{
		$this->obj->withTrashed()->where('id', $id)->restore();
		$this->saveRegistrer(array("admin_id"=>$data["registrer_id"], "magasinier_id"=> $id, "codeOperation"=>"4"));
		$this->saveNotification(array("fonction"=>"admin", "type"=>"magasinier", "registrer_id"=>$data["registrer_id"], "codeOperation"=>"4", "titre"=>"Restauration d'un magasinier", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a restauré le magasinier ".$data['nom']." ".$data['prenom']));
		return true;
	}

	private function saveConnexion(Array $data) {
		$this->connexion->store($data);
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
				$this->saveConnexion(array("fonction"=>"magasinier", "user_id"=>$user->id));
				$this->saveNotification(array("fonction"=>"magasinier","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"1", "titre"=>"Connexion d'un magasinier", "message" => "Le magasinier ".$user->nom." ".$user->prenom." s'est connecté"));
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
		$this->saveConnexion(array("fonction"=>"magasinier", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"magasinier","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"0", "titre"=>"Deconnexion d'un magasinier", "message" => "Le magasinier ".$user->nom." ".$user->prenom." s'est déconnecté"));
		return array('isAuth'=>true,"type"=>"success", 'message'=>'Vous etes déconnecté!');
	}

	public function changePassword($data) {
		$user = $this->getById($data['id']);
		$user->password = Hash::make($data['password']);
		$user->save();
		$this->saveConnexion(array("fonction"=>"magasinier", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"magasinier","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"2", "titre"=>"Changement de mode passe d'un magasinier", "message" => "Le magasinier ".$user->nom." ".$user->prenom." a changé son mot de passe"));
		return array('isAuth' => true, 'type'=>'success', 'message'=> 'Mot de passe changé avec succes!');
	}

	public function resetPassword($data) {
		$user = $this->getById($data['id']);
		$user->password = Hash::make('vonamawu2019');
		$user->save();
		$this->saveConnexion(array("fonction"=>"magasinier", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"admin","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"2", "titre"=>"Renitialisation du mode passe", "message" => "L'admnistrateur ".$data['registrer_nom']." ".$data['registrer_prenom']." a renitialisé le mot de passe du magasinier ".$user->nom." ".$user->prenom." a changé son mot de passe"));
		return array('isAuth' => true, 'type'=>'success', 'message'=> 'Mot de passe changé avec succes!');
	}

	public function changeIdentifiant($data) {
		$user = $this->getById($data['id']);
		$user->identifiant = $data['identifiant'];
		$user->save();
		$this->saveConnexion(array("fonction"=>"magasinier", "user_id"=>$user->id));
		$this->saveNotification(array("fonction"=>"magasinier","type"=>"connexion", "registrer_id"=>$user->id, "codeOperation"=>"2", "titre"=>"changement de lidentifiant", "message" => "Le magasinier ".$data['registrer_nom']." ".$data['registrer_prenom']." a changé son identifiant".$user->nom." ".$user->prenom." a changé son mot de passe"));
		return array('isAuth' => true, 'type'=>'success', 'message'=> 'Mot de passe changé avec succes!');
	}

}