<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SortieMaisonProduit extends Model
{
    //
    use SoftDeletes;
    public $timestamps = false;
}
