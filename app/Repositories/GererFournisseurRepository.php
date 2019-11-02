<?php
namespace App\Repositories;

use App\Models\GererFournisseur;
use Illuminate\Support\Facades\DB;

class GererFournisseurRepository
{

    protected $obj;

    public function __construct(GererFournisseur $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererFournisseur $obj, Array $data)
	{
        $obj->admin_id =$data['admin_id'];
		$obj->fournisseur_id =$data['fournisseur_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_fournisseurs')
		->join('admins', 'gerer_fournisseurs.admin_id', '=', 'admins.id')
		->select('gerer_fournisseurs.*', 'admins.nom', 'admins.prenom')
		->where('gerer_fournisseurs.fournisseur_id', $id)
		->orderByDesc('gerer_fournisseurs.created_at')
		->get();
	}
    
    public function getPaginate($n)
	{
		return $this->obj->paginate($n);
	}

	public function store(Array $data)
	{
		$obj = new $this->obj;		
		$this->save($obj, $data);
		return $obj;
	}

	public function getById($id)
	{
		return $this->obj->findOrFail($id);
	}

	public function update($id, Array $inputs)
	{
		$this->save($this->getById($id), $inputs);
	}

	public function destroy($id)
	{
		$this->getById($id)->delete();
	}
}