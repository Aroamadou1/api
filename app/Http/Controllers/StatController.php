<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StatRepository;

class StatController extends Controller
{
    protected $dataRepos;

    public function __construct(
        StatRepository $dataRepos
    )
    {
        $this->dataRepos = $dataRepos;
    }

    public function index()
    {
       
        $date = date("Y-m-d");
        // $data = $this->dataRepos->getAll($date);
        $data = $this->dataRepos-> getSell($date);
        return  response()->json($data);
    }

    public function getByDate($date)
    {
        $data = $this->dataRepos->getSell($date);
        return  response()->json($data);
    }
}
