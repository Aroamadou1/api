<?php
namespace App\Repositories;

use App\Models\GererEntree;
use Illuminate\Support\Facades\DB;

class GererEntreeRepository
{

    protected $obj;
	private $adminId;
    public function __construct(GererEntree $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererEntree $obj, Array $data)
	{
		$obj->magasinier_id =$data['magasinier_id'];
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
		return DB::table('gerer_entrees')
		->join('magasiniers', 'gerer_entrees.magasinier_id', '=', 'magasiniers.id')
		->select('gerer_entrees.*', 'magasiniers.nom', 'magasiniers.prenom')
		->where('gerer_entrees.entree_id', $id)
		->orderByDesc('gerer_entrees.created_at')
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