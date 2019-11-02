<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BilanRepository;

class BilanController extends Controller
{
    protected $dataRepos;

    public function __construct(
       BilanRepository $dataRepos
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

    public function getByDate($date)
    {
        $data = $this->dataRepos->getAll($date);
        return  response()->json($data);
    }

    public function store(Request $request)
    {
        if($this->dataRepos->store()){
            return response()->json(array("type" => "success", "message" =>"Bilan généré avec success!"));
        }
        
    }

}
