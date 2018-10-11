<?php

namespace App\Http\Controllers;

use App\LegalForm;
use Illuminate\Http\Request;

class LegalFormController extends Controller
{
    public function getLegalForms()
    {
        $legalForms = LegalForm::all();

        return response()->json(['legalForms' => $legalForms]);
    }
}
