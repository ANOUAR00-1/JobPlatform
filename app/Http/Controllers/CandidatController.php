<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatController extends Controller
{
    // JNV-7 & JNV-8: Update Profile and Upload CV
    public function updateProfile(Request $request)
    {
        //( validation for CV)
        $request->validate([
            'telephone' => 'required|string',
            'experience' => 'required|string',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:2048', // 2 MB max  
            'ville_id' => 'required|exists:villes,id'
        ]);

        $user = Auth::user();
        $candidat = $user->candidat; 

        //  Upload CV 
        if ($request->hasFile('cv')) {
            // delete CV sur Storage
            if ($candidat->cv) {
                Storage::delete($candidat->cv);
            }
            // name dossier cvs 
            $path = $request->file('cv')->store('cvs', 'public');
            $candidat->cv = $path;
        }

        // update les autres champs
        $candidat->update([
            'telephone' => $request->telephone,
            'experience' => $request->experience,
            'ville_id' => $request->ville_id,
            'cv' => $candidat->cv 
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $candidat
        ]);
    }

    // JNV-23
    public function indexCandidatures()
    {
        $candidat = Auth::user()->candidat;

        
        $candidatures = $candidat->candidatures()->with('offreEmploi')->get();

        return response()->json($candidatures);
    }
}