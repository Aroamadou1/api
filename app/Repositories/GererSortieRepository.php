<?php
namespace App\Repositories;

use App\Models\GererSortie;
use Illuminate\Support\Facades\DB;

class GererSortieRepository
{

    protected $obj;

    public function __construct(GererSortie $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererSortie $obj, Array $data)
	{
        $obj->magasinier_id =$data['magasinier_id'];
		$obj->sortie_id =$data['vente_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getMagasinier($id)
	{
		return DB::table('gerer_sorties')
		->join('magasiniers', 'gerer_sorties.magasinier_id', '=', 'magasiniers.id')
		->select('gerer_sorties.*', 'magasiniers.nom', 'magasiniers.prenom')
		->where('gerer_sorties.sortie_id', $id)
		->get();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_sorties')
		->join('magasiniers', 'gerer_sorties.magasinier_id', '=', 'magasiniers.id')
		->select('gerer_sorties.*', 'magasiniers.nom', 'magasiniers.prenom')
		->where('gerer_sorties.sortie_id', $id)
		->orderByDesc('gerer_sorties.created_at')
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