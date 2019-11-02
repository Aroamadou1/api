<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategorieRepository;


class CategorieController extends Controller
{
    protected $dataRepos;

    public function __construct(
        CategorieRepository $dataRepos
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

    public function show($id)
    {
        $today = date('Y-m-d');
        $historiques = $this->dataRepos->getHistoriques($id);
        // $activites = $this->dataRepos->getActivites($id, $today);
        $response = array( 'historiques' => $historiques);
        return response()->json($response);
    }

    public function activities($id, $date)
    {
        $historiques = $this->dataRepos->getHistoriques($id);
        // $activites = $this->dataRepos->getActivites($id, $date);
        $response = array('historiques' => $historiques);
        return response()->json($response);
    }

}
