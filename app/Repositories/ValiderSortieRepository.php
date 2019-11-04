<?php
namespace App\Repositories;

use App\Models\ValiderSortie;
use Illuminate\Support\Facades\DB;

class ValiderSortieRepository
{

    protected $obj;
	private $magasinierId;
    public function __construct(validerSortie $obj)
	{
		$this->obj = $obj;
	}

    private function save(validerSortie $obj, Array $data)
	{
		$obj->magasinier_id =$data['magasinier_id'];
		$obj->sortie_id =$data['sortie_id'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('valider_sorties')
		->join('magasiniers', 'valider_sorties.magasinier_id', '=', 'magasiniers.id')
		->select('valider_sorties.*', 'magasiniers.nom', 'magasiniers.prenom')
		->where('valider_sorties.sortie_id', $id)
		->orderByDesc('valider_sorties.created_at')
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