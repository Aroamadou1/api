<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationRepository;

class NotificationController extends Controller

{
    //
    protected $dataRepos;

    public function __construct(
        NotificationRepository $dataRepos
    )
    {
        $this->dataRepos = $dataRepos;
    }
    public function index()
    {   
        $date = date("Y-m-d");
        $data = $this->dataRepos->getAll($date);
        return  response()->json($data);
    }

    public function getByFonction($fonction)
    {
        $data = $this->dataRepos->getPaginate($fonction);
        return  response()->json($data);
    }

    public function getByDate($date)
    {
        $data = $this->dataRepos->getAll($date);
        return  response()->json($data);
    }
}
