<?php
namespace App\Repositories;

use App\Models\ValiderEntree;
use Illuminate\Support\Facades\DB;

class ValiderEntreeRepository
{

    protected $obj;
	private $adminId;
    public function __construct(validerEntree $obj)
	{
		$this->obj = $obj;
	}

    private function save(validerEntree $obj, Array $data)
	{
		$obj->admin_id =$data['admin_id'];
		$obj->entree_id =$data['entree_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('validerEntrees')
		->join('admins', 'validerEntrees.admin_id', '=', 'admins.id')
		->select('validerEntrees.*', 'admins.nom', 'admins.prenom')
		->where('validerEntrees.entree_id', $id)
		->orderByDesc('validerEntrees.created_at')
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


	public function destroy($id)
	{
		$this->getById($id)->delete();
	}
}