<?php
namespace App\Repositories;

use App\Models\GererVente;
use Illuminate\Support\Facades\DB;

class GererVenteRepository
{

    protected $obj;

    public function __construct(GererVente $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererVente $obj, Array $data)
	{
        $obj->vendeur_id =$data['vendeur_id'];
		$obj->vente_id =$data['vente_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	
	// public function validate($id, Array $data)
	// {
	// 	$obj = $this->getById($id);
	// 	$obj->magasinier_id =$data['magasinier_id'];
	// 	$obj->confirmed_at = date("Y-m-d H:i:s");
	// 	$obj->save();
	// }

	
	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_ventes')
		->join('vendeurs', 'gerer_ventes.vendeur_id', '=', 'vendeurs.id')
		->select('gerer_ventes.*', 'vendeurs.nom', 'vendeurs.prenom')
		->where('gerer_ventes.vente_id', $id)
		->orderByDesc('gerer_ventes.created_at')
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