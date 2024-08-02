<?php

namespace App\Http\Controllers\DropDown;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DropDown\RegistrationStatusModel;
class RegistrationStatusController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RegistrationStatusModel::query();

        // dd($query);

        // Apply filters
        if ($request->filled('filter_registration_status')) {
            $query->where('registration_status', 'like','%'. $request->input('filter_registration_status') .'%');
        }
        if ($request->filled('filter_bootstrap_class')) {
            $query->where('bootstrap_class', 'like', '%' . $request->input('filter_bootstrap_class') . '%');
        }
        if ($request->filled('filter_registration_status_backend')) {
            $query->where('registration_status_backend', 'like', '%' . $request->input('filter_registration_status_backend') . '%');
        }
        if ($request->filled('filter_status_description')) {
            $query->where('status_description', 'like', '%' . $request->input('filter_status_description') . '%');
        }


        $registration_statuses = $query->sortable()->paginate(setting('pagination_limit'));
        // print_r($sd_selections);die;
        // if($sd_selections){
        //     foreach($sd_selections as $key => $value){
        //         $sd_selections[$key]->selection_status_description = substr($value->selection_status_description, 0, 100) . '...';              

        //     }

        // }

      
        return view('pages/dropdown.registration_status.list', compact('registration_statuses'));

       
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     return view('pages/dropdown.student_selection.add');
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request): RedirectResponse
    // {

        
    //     $request->validate([
    //         'selection_status' => 'required',
    //         'bootstrap_class' => 'required',
    //         'selection_status_backend' => 'required',
    //         'selection_status_description' => 'required',


    //     ]);



    //     $user = StudentSelectionModel::create([
    //         'selection_status' => $request->selection_status,
    //         'bootstrap_class' => $request->bootstrap_class,
    //         'selection_status_backend' => $request->selection_status_backend,
    //         'selection_status_description' => $request->selection_status_description,   

    //     ]);

    //     return redirect()->route('student_selection.list')->with('success', 'Student selection status created successfully.');
    // }

   

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit($sd_selection_id)
    // {
       
    //    $sd_selection = StudentSelectionModel::find($sd_selection_id);
    //    return view('pages/dropdown.student_selection.edit', compact('sd_selection'));

    // }

    // // /**
    // //  * Update the specified resource in storage.
    // //  */
    // public function update(Request $request,$sd_selection_id)
    // {
    //     $request->validate([
    //         'selection_status' => 'required',
    //         'bootstrap_class' => 'required',
    //         'selection_status_backend' => 'required',
    //         'selection_status_description' => 'required',
    //     ]);



    //     $student_selection = StudentSelectionModel::findOrFail($sd_selection_id);
    //     $student_selection->selection_status = $request->selection_status;
    //     $student_selection->bootstrap_class = $request->bootstrap_class;
    //     $student_selection->selection_status_backend = $request->selection_status_backend;
    //     $student_selection->selection_status_description = $request->selection_status_description;
      

    //     $student_selection->save();

    //     return redirect()->route('student_selection.list')->with('success', 'Student selection status updated successfully.');

    // }

    // // /**
    // //  * Remove the specified resource from storage.
    // //  */
    // public function destroy(Request $request)
    // {
        

    //     if ($request->sd_selection_id) {


    //         $sd_selection = StudentSelectionModel::find($request->sd_selection_id);

    //         if ($sd_selection) {
    //             $sd_selection->delete();
    //             session()->flash('success', 'Student selection status deleted successfully');
    //             return response()->json(['error' => '0']);
    //         } else {
    //             session()->flash('success', 'Student selection status not found');
    //             return response()->json(['error' => '1']);
    //         }
    //     }

    //     if ($request->sd_selections_ids) {
    //         foreach ($request->sd_selections_ids as $sd_selection_id) { 
    //             $sd_selection = StudentSelectionModel::find($sd_selection_id);
    //             if ($sd_selection) {
    //                 $sd_selection->delete();
    //                 session()->flash('success', 'All Student selection status deleted successfully');
    //             }
    //         }

    //         return response()->json(['error' => '0']);
    //     }
    // }






}
