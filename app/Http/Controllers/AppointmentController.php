<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\User;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function makeAppointment(){
        $appointment = new Appointment();
        // $appointment->appointmentID = $appointment->appointmentID+1;
        $appointment->dentistID = request('dentistID');
        $appointment->patientID = request('patientID');
        $appointment->treatmentID = request('treatmentID');
        $appointment->date = request('date');
        $appointment->time = request('time');
        $appointment->status = request('status');

        $appointment->save();
        return redirect()->route('view.all.appointment');
    }
    
    public function reqalldata(){
        $users = User::where('role','=','1')->get();
        $treatments = Treatment::all();
        $appointments = Appointment::all();
        return view('makeappointment', ['users' => $users], ['appointments' => $appointments, 'treatments' => $treatments]);
    }
    // public function index1(){
    //     $treatments = Treatment::all();
    //     return view('makeappointment', ['treatments' => $treatments]);
    // }
    
    public function allappointment(){
        $currentuserid = Auth::user()->id;
        // $appointments = Appointment::join('dentists', 'appointments.dentistID', '=', 'dentists.dentistID')
        // ->where('appointments.dentistID','=', $currentuserid)->get(['appointments.*', 'dentists.name as name']);

        $appointments = Appointment::join('dentists', 'dentists.dentistID', '=', 'appointments.dentistID')->join('patients', 'patients.patientID', '=', 'appointments.patientID')
        ->where('appointments.dentistID','=', $currentuserid)->orderby('date','asc')
        ->get(['appointments.*', 'dentists.name as dentname', 'patients.name as patname']);
        return view('approveappointment', ['appointments' => $appointments]);
    }


    public function updatestatus(Request $request){
        $appointment = Appointment::findOrFail($request->appointmentID);
        $appointment->status = $request->input('changestatus');
        $appointment->save();
        return back();
    }

    public function allappointment1(){
        $currentuserid = Auth::user()->id;
        $appointments = Appointment::join('dentists', 'dentists.dentistID', '=', 'appointments.dentistID')->join('patients', 'patients.patientID', '=', 'appointments.patientID')
        ->where('appointments.patientID','=', $currentuserid)->orderby('date','asc')
        ->get(['appointments.*', 'dentists.name as name']);
        return view('cancelappointment', ['appointments' => $appointments]);
    }


    public function updatestatus1(Request $request){
        $appointment = Appointment::findOrFail($request->appointmentID);
        $appointment->status = $request->input('changestatus');
        $appointment->save();
        return back();
    }

    public function allappointment2(){
        // $appointments = Appointment::all();
        $currentuserid = Auth::user()->id;
        $gender = Patient::select('gender')->where('patientID', '=', $currentuserid)->get();
        $appointments = Appointment::join('dentists', 'dentists.dentistID', '=', 'appointments.dentistID')->join('patients', 'patients.patientID', '=', 'appointments.patientID')
        ->where('appointments.patientID','=', $currentuserid)->orwhere('appointments.dentistID','=', $currentuserid)
        ->orderby('date','asc')
        ->get(['appointments.*', 'dentists.name as dentname', 'patients.name as patname']);
        return view('viewappointment', ['appointments' => $appointments, 'gender' => $gender]);
    }

    public function reschedule($id){
        $appointment = Appointment::find($id);
        return view ('rescheduleappointment', ['appointment' => $appointment]);
    }

    public function savereschedule(Request $request, $id){
        $appointment = Appointment::findorfail($id);
        // $appdate = $appointment->date = request('date');
        $appointment->time = request('time');
        if($request->filled('date')){
            $appdate = $appointment->date = request('date');
        }

        // error_log(request('time'));
        // error_log(request('date'));

        // if ($appointment->date == null){
        //     old('date', $appdate);
        // }
        // else

        $appointment-> save();
        return redirect()-> route('view.all.appointment');
    }


    public function notification(){
        $currentuserid = Auth::user()->id;
        $gender = Patient::select('gender')->where('patientID', '=', $currentuserid)->get();
        $appointments = Appointment::join('dentists', 'dentists.dentistID', '=', 'appointments.dentistID')->join('patients', 'patients.patientID', '=', 'appointments.patientID')
        ->where('appointments.patientID','=', $currentuserid)->orwhere('appointments.dentistID','=', $currentuserid)
        ->orderby('updated_at','asc')
        ->get(['appointments.*', 'dentists.name as dentname', 'patients.name as patname']);
        return view('viewnotification', ['appointments' => $appointments, 'gender' => $gender]);
    }
}
