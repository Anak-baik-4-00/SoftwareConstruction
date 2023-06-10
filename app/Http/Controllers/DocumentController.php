<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalDocument;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Dentist;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;

class DocumentController extends Controller
{
    //
    public function index1(){
        $documents = DigitalDocument::all();
        return view('DigitalDocument', ['documents' => $documents ]);
    } 

    public function patientDocument(){
        $currentuserid = Auth::user()->id;
        $gender = Patient::select('gender')->where('patientID', '=', $currentuserid)->get();
        $documents = DigitalDocument::join('patients', 'patients.patientID', '=', 'digital_documents.patientID')->join('dentists', 'dentists.dentistID', '=', 'digital_documents.dentistID')
        ->where('digital_documents.patientID','=', $currentuserid)
        ->get(['digital_documents.*', 'dentists.name as dentname']);
        return view('DigitalDocument', ['documents' => $documents], ['gender' => $gender]);
    }

    public function getdocument(Request $request, $id)
    {
        $file = DigitalDocument::find($id);

        $fileData = $file->file;
        $fileName = $file->name;

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '.pdf',
        ];

        return response($fileData, 200, $headers);
    }

    public function dentistDocument(){
        $currentuserid = Auth::user()->id;
        $gender = Dentist::select('gender')->where('dentistID', '=', $currentuserid)->get();
        $documents = DigitalDocument::join('patients', 'patients.patientID', '=', 'digital_documents.patientID')->join('dentists', 'dentists.dentistID', '=', 'digital_documents.dentistID')
        ->where('digital_documents.dentistID','=', $currentuserid)->orderby('patients.name', 'asc')
        ->get(['digital_documents.*', 'patients.name as patname', 'patients.patientID as patID', 'dentists.dentistID as dentID']);
        return view('dentistdocument', ['documents' => $documents], ['gender' => $gender]);
    }

    public function specificDocument($id){
        $currentuserid = Auth::user()->id;
        $gender = Dentist::select('gender')->where('dentistID', '=', $currentuserid)->get();
        $documents = DigitalDocument::join('patients', 'patients.patientID', '=', 'digital_documents.patientID')->join('dentists', 'dentists.dentistID', '=', 'digital_documents.dentistID')
        ->where('digital_documents.dentistID','=', $currentuserid)->where('digital_documents.patientID','=', $id)
        ->get(['digital_documents.*', 'patients.name as patname', 'patients.patientID as patID', 'dentists.dentistID as dentID']);
        return view('specificpatientdoc', ['documents' => $documents], ['gender' => $gender]);
    }

    public function deletedocument($id){
        DigitalDocument::where('id', $id)->delete();
        return redirect()->back();
    }

    public function redata(){
        if(Auth::user()->role == 0){
        $users = User::where('role','=','1')->get();
        return view('UploadDigitalDocument', ['users' => $users]);
        }
        else{
        $users = User::where('role','=','0')->get();
        return view('UploadDigitalDocument', ['users' => $users]);
        }
    }

    public function uploaddocument(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048', // Restrict to PDF files
        ]);


        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Read the file content
            $fileContents = file_get_contents($file->getRealPath());

            // Save the document to the database
            $document = new DigitalDocument();
            $document->dentistID = request('dentistID');
            $document->patientID = request('patientID');
            $document->type = request('type');
            $document->name = request('name');
            $document->file = $fileContents;
            $document->save();
        }
        return redirect()->route('upload.document.new');
    }
    

    // public function dentistDocument(){
    //     $patients = Patient::all();
    //     return view('dentistdocument', ['patients' => $patients]);
    // }
}

