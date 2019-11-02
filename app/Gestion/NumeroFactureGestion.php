<?php 

namespace App\Gestion;
use Illuminate\Support\Facades\DB;

class NumeroFactureGestion
{

    public function generate()
	{
        $today = date("ymd");
        $date = date("Y-m-d");
        $n = DB::table('ventes')
        ->whereDate('created_at', $date)
        // ->whereBetween('created_at', [$date." 00:00:00",$date." 23:59:59"])
        ->count();
        $numero=$today."000";
        $n=$n+1;
        if($n<10) $numero = $today."00".$n;
        elseif($n>=10 && $n<100) $numero = $today."0".$n;
        elseif($n>=100) $numero = $today."".$n;
        return $numero;
	}

}