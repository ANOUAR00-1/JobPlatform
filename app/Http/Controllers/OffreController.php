<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Offre;

class OffreController extends Controller
{
    public function index()
    {
        return response()->json(Offre::all());
    }
}
