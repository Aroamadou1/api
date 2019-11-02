<?php
namespace App\Repositories;

use App\Models\GererVendeur;
use Illuminate\Support\Facades\DB;

class GererVendeurRepository
{

    protected $obj;

    public function __construct(GererVendeur $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererVendeur $obj, Array $data)
	{
        $obj->admin_id =$data['admin_id'];
		$obj->vendeur_id =$data['vendeur_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_vendeurs')
		->join('admins', 'gerer_vendeurs.admin_id', '=', 'admins.id')
		->select('gerer_vendeurs.*', 'admins.nom', 'admins.prenom')
		->where('gerer_vendeurs.vendeur_id', $id)
		->orderByDesc('gerer_vendeurs.created_at')
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