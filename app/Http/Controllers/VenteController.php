<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VenteRepository;

class VenteController extends Controller
{
    protected $dataRepos;

    public function __construct(
        VenteRepository $dataRepos
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
        $date = date("Y-m-d");
        $data = $this->dataRepos->getAll($date);
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
        $response = array("type" => "success",  "message" =>"Echec de l'operation!");
        $data = $this->dataRepos->store($request->all());
        if ($data){
            $response = array("data" => $data, "type" => "success", "message" =>"Ajouté avec success!");
        }
        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = array("type" => "danger", "message" =>"Echec de l'operation!");
        $data = $this->dataRepos->store($request->all());
        if ($data){
            $response = array("type" => "success", "message" =>"Modifié avec success!");
        }
        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $response = array("type" => "success",  "message" =>"Echec de l'operation!");
        if ($this->dataRepos->destroy($request->input('id'), $request->all())){
            $response = array("type" => "success", "message" =>"Supprimé avec success!");
        }
        return response()->json($response);
    }

    public function restore(Request $request)
    {
        $response = array("type" => "success",  "message" =>"Echec de l'operation!");
        if ($this->dataRepos->restore($request->input('id'), $request->all())){
            $response = array("type" => "success", "message" =>"Restauré avec success!");
        }
        return response()->json($response);
    }

    public function validation(Request $request)
    {
        $response = array("type" => "danger", "message" =>"Echec de l'operation!");
        $data = $this->dataRepos->validation($request->input('id'), $request->all());
        if ($data){
            if($request->input('response')===1)  $response = array("type" => "success", "message" =>"Validé avec success!");
            else  $response = array("type" => "warning", "message" =>"Rejeté avec success!");
           
        }
        return response()->json($response);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function trash()
    {
        $response = $this->dataRepos->getArchives();
        return response()->json($response);
    }

    public function show($type)
    {
        if ($type == 'archives') {
            $response = $this->dataRepos->getArchives();
            return response()->json($response);
        } else if (is_numeric($type)){
            $historiques = $this->dataRepos->getHistoriques($type);
            $data = $this->dataRepos->getproduits($type);
            $porteurs = $this->dataRepos->getPorteurs($type);
            $magasinier = $this->dataRepos->getMagasinier($type);
            $response = array('magasinier'=>$magasinier, 'produits' => $data, 'porteurs'=> $porteurs, 'historiques' => $historiques);
            return response()->json($response);
        }
    }

    public function getByDate($date)
    {
        $data = $this->dataRepos->getAll($date);
        return  response()->json($data);
    }

}

