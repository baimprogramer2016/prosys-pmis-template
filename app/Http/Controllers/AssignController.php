<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AssignController extends Controller
{
    
    public function index(Request $request){
        $status = $request->status;
      
        if($status == 'new'){
           $condition = 'checker';
        }
      
        elseif($status == 'check'){
           $condition = 'reviewer';
        }
      
        elseif($status == 'review'){
           $condition = 'approver';
        }

        $user = User::select('users.email','roles.name as role','users.name' )
        ->join('model_has_roles','model_has_roles.model_id','=','users.id')
        ->join('roles','model_has_roles.role_id','=','roles.id')
        ->where('roles.name','=',$condition)
        ->get();

        return $user;
    }
        
}
