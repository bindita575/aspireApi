<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //validation
        $validator = validator::make($request->all(),
        [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,id',
            'password' => 'required|min:8',
            'confirm_password'=>'required|same:password'
        ]);
        
        if($validator->fails())
        {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];

            return response()->json($response, 400);
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role_id'] = 2;
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User Register Successfully' 
        ];

         return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        //validation
        $validator = validator::make($request->all(),
        [
            'email' => 'required|email',
            'password' => 'required|min:8',
            
        ]);
        
        if($validator->fails())
        {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            
            return response()->json($response, 400);
        }

        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login Successfully' 
            ];
            return response()->json($response, 200);
        }
        else{
            $response= [
                'success' => false,
                'message' => 'Unauthorised'
            ];

            return response()->json($response);
        }
    }
}
