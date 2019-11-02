<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SortieRepository;

class SortieController extends Controller
{
    protected $dataRepos;

    public function __construct(
        SortieRepository $dataRepos
    )
    {
        $this->dataRepos = $dataRepos;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->dataRepos->getAll();
        return  response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        global $response;
        if ($request->input('id')) {
            if ($request->input('deleted_at')) {
                if($request->input('deleted_at') === 'new') {
                    if ($this->dataRepos->destroy($request->input('id'), $request->all())){
                        $response = array("type" => "success", "message" =>"Supprimé avec success!");
                    }
                } else {
                    if ($this->dataRepos->restore($request->input('id'), $request->all())){
                        $response = array("type" => "success", "message" =>"Restauré avec success!");
                    }
                }
            } else{
                if ($this->dataRepos->update($request->input('id'), $request->all())){
                    $response = array("type" => "success", "message" =>"Modifié avec success!");
                }
            }
        } else {
            $data =$this->dataRepos->store($request->all());
            if ($data){
                $response = array("data" => $data, "type" => "success", "message" =>"Ajouté avec success!");
            }
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type)
    {
        if ($type == 'archives') {
            $response = $this->dataRepos->getArchives();
            return response()->json($response);
        } else if (is_numeric($type)){
            $historiques = $this->dataRepos->getHistoriques($type);
            $data = $this->dataRepos->getproduits($type);
            $response = array('produits' => $data, 'historiques' => $historiques);
            return response()->json($response);
        }
    }

}
