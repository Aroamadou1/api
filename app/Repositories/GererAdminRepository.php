<?php
namespace App\Repositories;

use App\Models\GererAdmin;
use Illuminate\Support\Facades\DB;

class GererAdminRepository
{

    protected $obj;

    public function __construct(GererAdmin $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererAdmin $obj, Array $data)
	{
        $obj->admin1_id =$data['admin1_id'];
		$obj->admin2_id =$data['admin2_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_admins')
		->join('admins', 'gerer_admins.admin1_id', '=', 'admins.id')
		->select('gerer_admins.*', 'admins.nom', 'admins.prenom')
		->where('gerer_admins.admin2_id', $id)
		->orderByDesc('gerer_admins.created_at')
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