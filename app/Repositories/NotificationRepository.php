<?php
namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationRepository
{

    protected $obj;

    public function __construct(Notification $obj)
	{
		$this->obj = $obj;
	}

    private function save(Notification $obj, Array $data)
	{
		$obj->fonction =$data['fonction'];
		$obj->type =$data['type'];
		$obj->registrer_id =$data['registrer_id'];
        $obj->codeOperation =$data['codeOperation'];
        $obj->titre =$data['titre'];
		$obj->message =$data['message'];
		// if($data['urgence']) $obj->urgence = $data['urgence'];	
		$obj->save();
	}

	public function getAll($date)
	{
		return DB::table('notifications')
		->whereDate('created_at', $date)
		->orderBy('id', 'desc')
		->get();
    }
	
	public function getActivites($fonction, $id)
	{
		return $this->obj->where('fonction', $fonction)->where('registrer_id', $id)->orderBy('created_at', 'desc')->get();
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