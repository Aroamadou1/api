<?php
namespace App\Repositories;

use App\Models\Connexion;
use Illuminate\Support\Facades\Hash;

class ConnexionRepository
{

    protected $obj;

    public function __construct(Connexion $obj)
	{
		$this->obj = $obj;
	}

    private function save(Connexion $obj, Array $data)
	{
        $obj->fonction =$data['fonction'];
        $obj->user_id =$data['user_id'];
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

	public function login($id)
	{
		$this->getById($id)->delete();
	}

	public function logout($id)
	{
		$this->getById($id)->delete();
	}

	public function reset($id)
	{
		$this->getById($id)->delete();
	}



}