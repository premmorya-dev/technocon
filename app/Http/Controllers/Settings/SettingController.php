<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{

    public function index(Request $request)
    {            
            
        return view('pages.settings.list');
    }

       /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
    
    
        // $validator = Validator::make($request->all(), [
        //     'pagination_limit' => 'required|numeric',
        // ]);
        

            $request->validate([
                'pagination_limit' => 'required|numeric',               

            ]);
        
     
              $data = $request->all();
            if($data){    
                Setting::truncate();               
                    foreach($data as $key=>$value){                      
                        Setting::create([
                            'key' => $key ,
                            'value' =>$value,                         
                        ]);
                    }
            }
    

        return redirect()->route('settings.list')->with('success', 'User registered successfully');
    }



}
