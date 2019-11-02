<?php
namespace App\Repositories;

use App\Models\GererMagasinier;
use Illuminate\Support\Facades\DB;

class GererMagasinierRepository
{

    protected $obj;

    public function __construct(GererMagasinier $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererMagasinier $obj, Array $data)
	{
        $obj->admin_id =$data['admin_id'];
		$obj->magasinier_id =$data['magasinier_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
    }
    public function getHistoriques($id)
	{
		return DB::table('gerer_magasiniers')
		->join('admins', 'gerer_magasiniers.admin_id', '=', 'admins.id')
		->select('gerer_magasiniers.*', 'admins.nom', 'admins.prenom')
		->where('gerer_magasiniers.magasinier_id', $id)
		->orderByDesc('gerer_magasiniers.created_at')
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