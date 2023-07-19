<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    //
    public function viewAllTreatments(){

        $treatments = Treatment::simplePaginate(10);

        return view('admin.manageTreatments', ['treatments' => $treatments]);
    }

    public function addNewTreatment(){

        $treatment = new Treatment;

        $treatment->treatmentName = request('name');
        $treatment->price = request('price');

        $treatment->save();

        return redirect()->route('receptionist.treatment.viewAll');
    }

    public function viewEditTreatment($id)
    {

        $treatment = Treatment::find($id);

        return view('admin.editTreatment', ['treatment' => $treatment]);
    }

    public function editTreatment($id)
    {

        $treatment = Treatment::findOrFail($id);

        $treatment->treatmentName = request('name');
        $treatment->price = request('price');

        $treatment->save();

        return redirect()->route('receptionist.treatment.viewAll');
    }

    public function deleteTreatment($id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();

        return redirect()->route('receptionist.treatment.viewAll');
    }
    
}
