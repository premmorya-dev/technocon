<?php

namespace App\Http\Controllers\StudentRegistration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentRegistrationModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
class StudentRegistrationController extends Controller
{
           /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StudentRegistrationModel::query();
        $query->leftJoin('payment_status', 'student_registration_2024.payment_status_id', '=', 'payment_status.payment_status_id')
        ->select('student_registration_2024.*', 'payment_status.*') ;

        // Apply filters
        if ($request->filled('filter_registration_number')) {
            $query->where('student_registration_2024.registration_number', 'like','%'. $request->input('filter_registration_number') .'%');
        }
        if ($request->filled('filter_first_name')) {
            $query->where('student_registration_2024.first_name', 'like','%'. $request->input('filter_first_name') .'%');
        }
        if ($request->filled('filter_last_name')) {
            $query->where('student_registration_2024.last_name', 'like','%'. $request->input('filter_last_name') .'%');
        }
        if ($request->filled('filter_registered_email')) {
            $query->where('student_registration_2024.registered_email', 'like','%'. $request->input('filter_registered_email') .'%');
        }
        if ($request->filled('filter_payment_status_id')) {
            $query->where('student_registration_2024.payment_status_id', 'like', $request->input('filter_payment_status_id') );
        }
   

        $data['student_registrations'] = $query->sortable()->paginate(setting('pagination_limit'));

        $data['payment_statuses'] = DB::table('payment_status')->get();    
          
        return view('pages/student_registration.list', compact('data'));
      

       
    }

    public function addStudentRegistration(Request $request)
    {
      dd($request);
    }

}
