<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
 
class UserController extends Controller
{
    public $successStatus = 200;
 /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            // $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
            $token = auth()->user()->createToken('token')->accessToken;
            // $token['userId'] = $user->id;
            return response()->json(['user' => $user]); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
 
 /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
                $input['password'] = bcrypt($request->password); 
                // var_dump($input);d
                // dd($input);
                $user = User::create($input); 
                // dd($user);
                // $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
                $token = $user->createToken('apitoken')->accessToken;
                // dd($token);
                // $token['name'] =  $user->name;
                $accessToken = $user->createToken('MyLaravelApp')->accessToken;
        return response()->json(['token'=> $token, 'user'=>$user]); 
    }
 
 /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function userDetails() 
    { 
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus); 
    }
}