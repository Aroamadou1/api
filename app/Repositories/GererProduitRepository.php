<?php
namespace App\Repositories;

use App\Models\GererProduit;
use Illuminate\Support\Facades\DB;

class GererProduitRepository
{

    protected $obj;

    public function __construct(GererProduit $obj)
	{
		$this->obj = $obj;
	}

    private function save(GererProduit $obj, Array $data)
	{
        $obj->admin_id =$data['admin_id'];
		$obj->produit_id =$data['produit_id'];
		$obj->codeOperation =$data['codeOperation'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
	}
	
	public function getHistoriques($id)
	{
		return DB::table('gerer_produits')
		->join('admins', 'gerer_produits.admin_id', '=', 'admins.id')
		->select('gerer_produits.*', 'admins.nom', 'admins.prenom')
		->where('gerer_produits.produit_id', $id)
		->orderByDesc('gerer_produits.created_at')
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