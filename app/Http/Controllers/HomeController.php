<?php

namespace App\Http\Controllers;
use Auth;
use Crypt;
use App\Models\Category;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        /// set the category id to session
        if(!Session::has('category')){
            $category = array();
            $list = Category::get_available_category_list();
            foreach ($list as $item) $category[str_replace(" ", "_", $item->name)] = $item->id;
            Session::put('category', $category);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //return Auth::user()->name;
        return view('home');
    }

    public function category(){
        $data['category'] = Category::get_available_category_list();
        return view('category',$data);
    }

    public function practice_problem_list($category_name = null){
        //if(!array_key_exists($category_name, Session::get('category'))) return abort(404);
        if(!array_key_exists($category_name, Session::get('category'))) return redirect(url('/practice'));

        $category_id = Session::get('category')[$category_name];
        $data['category_title'] = ucwords(str_replace('_', ' ',$category_name));
        $data['category_id'] = $category_name;
        $data['problems'] = Problem::get_problem_list_with_category_id($category_id, 1, 15);

        return view('problems',$data);

    }


    public function practice_problem($category_name = null, $id = null){
        $data['problem'] = Problem::get_full_problem_details($id);
        $data['my_submission'] = Submission::get_submission_list_of_a_problem(1, $id);
        //return $data['my_submission'];
        return view('problem',$data);
    }
}
