<?php
namespace App\Repositories;

use App\Models\GererInventaire;
use Illuminate\Support\Facades\DB;

class GererInventaireRepository
{

    protected $obj;

    public function __construct(GererInventaire $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererInventaire $obj, Array $data)
	{
        $obj->admin_id =$data['admin_id'];
		$obj->inventaire_id =$data['inventaire_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
    }
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_inventaires')
		->join('admins', 'gerer_inventaires.admin_id', '=', 'admins.id')
		->select('gerer_inventaires.*', 'admins.nom', 'admins.prenom')
		->where('gerer_inventaires.inventaire_id', $id)
		->orderByDesc('gerer_inventaires.created_at')
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