<?php
namespace App\Repositories;

use App\Models\GererPorteur;
use Illuminate\Support\Facades\DB;

class GererPorteurRepository
{

    protected $obj;

    public function __construct(GererPorteur $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererPorteur $obj, Array $data)
	{
        $obj->admin_id =$data['admin_id'];
		$obj->porteur_id =$data['porteur_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_porteurs')
		->join('admins', 'gerer_porteurs.admin_id', '=', 'admins.id')
		->select('gerer_porteurs.*', 'admins.nom', 'admins.prenom')
		->where('gerer_porteurs.porteur_id', $id)
		->orderByDesc('gerer_porteurs.created_at')
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