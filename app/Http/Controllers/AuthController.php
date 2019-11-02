<?php

namespace App\Http\Controllers;
use App\Repositories\ConnexionRepository;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $dataRepos;

    public function __construct(
        ConnexionRepository $dataRepos
    )
    {
        $this->dataRepos = $dataRepos;
    }

    public function index(Request $request)
    {
        if(!$request->input('id')){
            $data = $this->dataRepos->login($request->all());
            return  response()->json($data);
        } else {
            $data = $this->dataRepos->logout($request->all());
            return  response()->json($data);
        }
    }
}
