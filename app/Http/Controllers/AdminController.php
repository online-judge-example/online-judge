<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

public function index(Request $request){
    if($request->has('search')){
        $data['users'] = User::where('user_type', '<', 2)
            ->where('name', 'like', '%'.$request->get('search').'%')
            ->orWhere('email', 'like', '%'.$request->get('search').'%')
            ->orWhere('username', 'like', '%'.$request->get('search').'%')
            ->orderBy('id', 'desc')
            ->paginate(config('app.standard_limit'));
    }else{
        $data['users'] = User::where('user_type', '<', 2)
            ->orderBy('id', 'desc')
            ->paginate(config('app.standard_limit'));
    }

    return view('admin.home', $data);
}

    public function coordinator(){
        $data['users'] = User::where('user_type', '=', 2)
            ->orderBy('id', 'desc')
            ->paginate(config('app.standard_limit'));
        return view('admin.coordinator', $data);
    }


    public function changeUserType($id = null){

        User::where('id', $id)->update(['user_type'=> 2]);

        return redirect()->route('admin.admin.coordinator');
    }
}
