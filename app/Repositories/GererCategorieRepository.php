<?php
namespace App\Repositories;

use App\Models\GererCategorie;
use Illuminate\Support\Facades\DB;

class GererCategorieRepository
{

    protected $obj;

    public function __construct(GererCategorie $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererCategorie $obj, Array $data)
	{
        $obj->admin_id =$data['admin_id'];
		$obj->categorie_id =$data['categorie_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_categories')
		->join('admins', 'gerer_categories.admin_id', '=', 'admins.id')
		->select('gerer_categories.*', 'admins.nom', 'admins.prenom')
		->where('gerer_categories.categorie_id', $id)
		->orderByDesc('gerer_categories.created_at')
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