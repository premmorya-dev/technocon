<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('filter_first_name')) {
            $query->where('first_name', 'like', '%' . $request->input('filter_first_name') . '%');
        }
        if ($request->filled('filter_last_name')) {
            $query->where('last_name', 'like', '%' . $request->input('filter_last_name') . '%');
        }
        if ($request->filled('filter_email')) {
            $query->where('email', 'like', '%' . $request->input('filter_email') . '%');
        }
        if ($request->filled('filter_status')) {
            $query->where('status', '=',   $request->input('filter_status')  );
        }
        if ($request->filled('filter_user_name')) {
            $query->where('user_name', 'like', '%' . $request->input('filter_user_name') . '%');
        }
        if ($request->filled('filter_user_type')) {
            $query->where('user_type', '=',   $request->input('filter_user_type')  );
        }
       

    

    
        $users = $query->sortable()->paginate(setting('pagination_limit'));


      
        return view('pages/user.list', compact('users'));




        // $users = User::sortable()->paginate(5);
        // return view('pages/user.list', compact('users'));
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $countries = self::getCountryCode();
       
       return view('pages.user.add',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
      
        $request->validate([
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'nullable|regex:/^[\pL\s\-]+$/u|min:3|max:255',
            'user_name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],        
                

        ]);

        if($request->has('image')){
           $file =  $request->file('image');
           $extension =  $file->getClientOriginalExtension();
           $file_name = time().'_' . $extension;
           $file->move('uploads/user_profile_image/',$file_name);
        }

        $user = User::create([
            'user_name' => $request->user_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'profile_photo_path' => $file_name,
            'email' => $request->email,
           // 'date_added' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => $request->status,
            'password' => Hash::make($request->password),
            'country_code' => '91',
            'mobile_number' => $request->mobile_number,
            'last_login_ip' => $request->ip(),
            'image' => 'test',       
           
        ]);
      
        return redirect()->route('user.list')->with('success', 'User created successfully.');
        //$result = $insert->save();
        // Session::flash('success', 'User registered successfully');
        // return redirect()->route('user/list');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('pages/apps.user-management.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user_id)
    {
    //    dd($id);
       $user = User::find($user_id);    

       $countries = self::getCountryCode();

       $data = array_merge(compact('user'), ['countries' => $countries]);
    //    dd($data);
       return view('pages.user.edit', $data);
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$user_id)
    {
        // dd($request->all());
        
        $request->validate([
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'nullable|regex:/^[\pL\s\-]+$/u|min:3|max:255',
            'user_name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'user_type' => 'required',
            'status' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],  
            'country_code' => 'required|min:1|max:3',
            'mobile_number' => 'required|regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/',      
                

        ]);


        $user = User::findOrFail($user_id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->user_name = $request->input('user_name');
        $user->email = $request->input('email');
        $user->user_type = $request->input('user_type');
        $user->status = $request->input('status');
        $user->password = Hash::make($request->password);
        $user->country_code = $request->input('country_code');
        $user->mobile_number = $request->input('mobile_number');
     
        $user->save();

      return redirect()->route('user.list')->with('success', 'User updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $currentUserId = auth()->id();
       
       if($request->user_id){
        
            if ($request->user_id == $currentUserId) {
                session()->flash('error', 'You cannot delete the currently logged-in user.');
                return response()->json(['error'=>'1']); 
            }else{
                $user = User::find($request->user_id);

                if ($user) {
                    $user->delete();
                    session()->flash('success', 'User deleted successfully');
                    return response()->json(['error'=>'0']);
                }else{
                    session()->flash('success', 'User not found');
                    return response()->json(['error'=>'1']);
                }
            }

           
       }
      
        if($request->user_ids){  
           foreach($request->user_ids as $user_id){

                    if ($user_id == $currentUserId) {
                        session()->flash('error', 'You cannot delete the currently logged-in user.');
                        return response()->json(['error'=>'1']); 
                    }else{
                        $user = User::find($user_id);

                        if ($user) {
                            $user->delete();  
                            session()->flash('success', 'All user deleted successfully');                    
                        }
                    }
         
                   
           }
          
           return response()->json(['error'=>'0']);


        }


       
      

    }


    public static function getCountryCode()
    {
      $countries =   DB::table('technocon_country')->get()->toArray();

      foreach ($countries as $key=> $country) {
        $country->country_code = str_replace('+', '', $country->country_code);
      
     }

     return $countries;
  
 

    }
}
