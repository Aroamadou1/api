<?php
namespace App\Repositories;

use App\Models\Sortie;


class SortieRepository
{

    protected $obj;

    public function __construct(Sortie $obj)
	{
		$this->obj = $obj;
	}

    private function save(Sortie $obj, Array $data)
	{
        $obj->nomClient =$data['nomClient'];
        $obj->contactClient =$data['contactClient'];
        $obj->numero =$data['numero'];
        $obj->quantiteTotale =$data['quantiteTotale'];
		$obj->save();
	}

	public function getAll()
	{
		return $this->obj->all();
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