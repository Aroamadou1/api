<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DataRepository;


class DataController extends Controller
{
    protected $dataRepos;

    public function __construct(
        DataRepository $dataRepos
    )
    {
        $this->dataRepos = $dataRepos;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all($fonction, $id)
    {
        $data = $this->dataRepos->getAll($fonction, $id);
        return  response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check($fonction, $userId, $notificationId)
    {
        $data = $this->dataRepos->check($fonction, $userId, $notificationId);
        return  response()->json($data);
    }

}
