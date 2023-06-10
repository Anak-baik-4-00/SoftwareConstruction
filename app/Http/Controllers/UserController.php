<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    //

    public function getUserList(){
        $users = User::all();
        return view('admin.UserVerification', ['users' => $users]);
    } 

    
    public function updateStatus(Request $request){
        $user = User::findOrFail($request->id);
        $user->userstatus = $request->input('changestatus');
        $user->save();
        return back();
    }

    public function getUserInfo(){
        $currentuserid = Auth::user()->id;
        $user = User::findOrFail($currentuserid);
        $patients = User::join('patients', 'patients.patientID', '=', 'users.id')
        ->where('users.id','=', $currentuserid)
        ->get(['users.*', 'patients.age as patage', 'patients.phoneNumber as patphone', 'patients.gender as patgen', 'patients.patientID as patID' ]);
        error_log($patients);
        $dentists = User::join('dentists', 'dentists.dentistID', '=', 'users.id')
        ->where('users.id','=', $currentuserid)
        ->get(['users.*', 'dentists.age as dentage', 'dentists.phoneNumber as dentphone', 'dentists.gender as dentgen']);

        // error_log($users);
        return view ('profile',['patients'=> $patients, 'dentists'=>$dentists, 'user' => $user]);
    }

    public function userdetailsinmanage($id){
        $user = User::findorfail($id);
        // $currentuserid = Auth::user()->id;
        $patients = User::join('patients', 'patients.patientID', '=', 'users.id')
        ->where('users.id','=', $id)
        ->get(['users.*', 'patients.age as patage', 'patients.phoneNumber as patphone', 'patients.gender as patgen' ]);
        // error_log($patients);

        $dentists = User::join('dentists', 'dentists.dentistID', '=', 'users.id')
        ->where('users.id','=', $id)
        ->get(['users.*', 'dentists.age as dentage', 'dentists.phoneNumber as dentphone', 'dentists.gender as dentgen']);

        // error_log($users);
        return view ('manageprofile',['patients'=> $patients, 'dentists'=>$dentists, 'user' => $user]);
    }


    public function UpdateUserInfo(Request $request, $id){
        // $currentuserid = User::find($id);
        $currentuserid = User::findorfail($id);
        // error_log($currentuserid);
        // Customer::where('customer_id', $request->customer_id)->firstOrFail();
        
        // $currentuserid = Auth::user()->id;
        // $patentUserID = Auth::user()->patientID;
        // $dentistUserID = Auth::user()->dentistID;
        // $user = User::findOrFail($currentuserid);
        // $patients = Patient::findOrFail($patentUserID);
        // $dentists = Dentist::findOrFail($dentistUserID);
        // $patients = User::join('patients', 'patients.patientID', '=', 'users.id')
        // ->where('users.id','=', $currentuserid)
        // ->get(['users.*', 'patients.age as patage', 'patients.phoneNumber as patphone', 'patients.gender as patgen' ]);

        // $dentists = User::join('dentists', 'dentists.dentistID', '=', 'users.id')
        // ->where('users.id','=', $currentuserid)
        // ->get(['users.*', 'dentists.age as dentage', 'dentists.phoneNumber as dentphone', 'dentists.gender as dentgen']);
        
        if ($currentuserid->role == 0){
            // $currentuserid = Auth::user()->id;
            $patient = Patient::where("patientID", $id)->firstOrFail();
            $user = User::where("id", $id)->firstOrFail();
            $user->name = $request->input('name');
            // $patient = Patient::findOrFail($id);
            $patient->name = $request->input('name');
            $patient->age = $request->input('age');
            $patient->phoneNumber = $request->input('phoneNumber');
            $patient->save();
            $user->save();
        }
        else {
            $dentist = Dentist::where("dentistID", $id)->firstOrFail();
            $user = User::where("id", $id)->firstOrFail();
            $user->name = $request->input('name');
            // $patient = Patient::findOrFail($id);
            $dentist->name = $request->input('name');
            $dentist->age = $request->input('age');
            $dentist->phoneNumber = $request->input('phoneNumber');
            $dentist->save();
            $user->save();
        }
        return redirect()->route('view.user.details');
    }
}
