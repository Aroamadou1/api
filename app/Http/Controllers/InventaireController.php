<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InventaireRepository;

class InventaireController extends Controller
{
    protected $dataRepos;

    public function __construct(
        InventaireRepository $dataRepos
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
        $year = date("Y");
        $data = $this->dataRepos->getAll($year);
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
        if ($this->dataRepos->store($request->all())){
            $response = array("type" => "success", "message" =>"Ajouté avec success!");
        }
        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = array("type" => "danger", "message" =>"Echec de l'operation!");
        if ($this->dataRepos->update($request->input('id'), $request->all())){
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
            $response = array('produits' => $data, 'historiques' => $historiques);
            return response()->json($response);
        }
    }

    public function getByDate($date)
    {
        $data = $this->dataRepos->getAll($date);
        return  response()->json($data);
    }

}

