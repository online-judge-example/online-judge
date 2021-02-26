<?php

namespace App\Http\Controllers;
use App\Jobs\ProcessSubmission;
use Auth;
use Crypt;
use App\Models\Category;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Image;
use Storage;
use DB;
use Parsedown;

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
        $this->middleware('verified');
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
        return redirect(url('practice'));
        //return view('home');
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
        $data['problems'] = Problem::get_problem_list_with_category_id($category_id, Auth::user()->id);
        return view('problems',$data);

    }

    public function practice_problem($problem_id = null){
        if(!Problem::is_problem_exist_and_enable($problem_id)) return redirect(url('/'));
        $Parsedown = new Parsedown();  // init parsedown for parse markdown to html

        $data['problem_number'] = $problem_id;
        $data['problem'] = Problem::get_full_problem_details($problem_id);
        // parse the problem description,input,output,note
        $data['problem']->description = $Parsedown->text($data['problem']->description);
        $data['problem']->input_format = $Parsedown->text($data['problem']->input_format);
        $data['problem']->output_format = $Parsedown->text($data['problem']->output_format);
        $data['problem']->note = $Parsedown->text($data['problem']->note);

        // get submission history for this problem
        $data['my_submission'] = Submission::get_my_submission_list_of_a_problem(Auth::user()->id, $problem_id);

        return view('problem',$data);
    }

    public function submit(Request $request){

        $request->validate([
            'language' => 'required|in:c,cpp,class,python',
            'file' => 'required|file|max:512',
            //'problem_number' => 'required|numeric'
        ]);



       if(Problem::is_problem_exist_and_enable($request->get('problem_id'))){

            $data = array(
                'language_id' => $request->get('language'),
                'user_id'=> Auth::user()->id,
                'problem_id' => $request->get('problem_id'),
                'code'=> file_get_contents($request['file'])
            );
            $sub_id = Submission::submit_code($data);

            ProcessSubmission::dispatch($sub_id, $request->get('problem_id'))->delay(now()->addSeconds(5));

            //return back()->with('success', 'Invalid Problem');
           return redirect(url('submissions'));
        }

        return back()->with('error', 'Invalid Problem');
    }

    public function submissions(){
        $data['submissions'] = Submission::get_submission_list(Auth::user()->id);
        return view('submissions', $data);
    }

    public function submission_details($sub_id = null){
        $data = array();
        if(Auth::user()->user_type == 1 || Submission::isMySubmission(Auth::user()->id, $sub_id)){
            $data['details'] = Submission::get_submission_details($sub_id);
        }
        return view('submission_details', $data);
    }

    public function profile($username){

        $data['user'] = array();
        $flag = false;
        if($username == Auth::user()->username){
            $data['user'] = Auth::user(); $flag = true;
        }else if(Auth::user()->user_type > 0){
            $data['user'] = User::where('username', $username)->first();
            $flag = true;
        }
        if(empty($data['user'])) return abort(404);
        if(!$flag) return abort(404);

        $data['submissions'] = Submission::get_solved_problem_list($data['user']->id);
        $data['solve_count'] = count($data['submissions']);
        $data['sub_count'] = Submission::where('user_id', $data['user']->id)->count();

        return view('profile', $data);
    }

    public function upload_profile_pic(Request $request){
        $request->validate([
            'picture' => 'required|image|max:1024'
        ]);

        $name = substr($request->file('picture')->store('public/profile'), 15);

        Image::make(storage_path('app/public/profile/'.$name))
            ->resize(150, 150)
            ->save(storage_path('app/public/profile/'.$name));

        Storage::delete($name);

        $affected = DB::table('users')->where('id', Auth::user()->id)->update(['picture' => $name]);
        if($affected){
            return back()->with('success', 'Profile picture update successfully.');
        }

        return back()->with('error', 'An error occur. Please try later.');
    }

    public function update_institution(Request $request){
        $request->validate([
           'institution' => 'required|string|min:3|max:30'
        ]);

        $affected = DB::table('users')->where('id', Auth::user()->id)->update(['institution' => $request->input('institution')]);
        if($affected){
            return back()->with('success', 'Institution update successfully.');
        }

        return back()->with('error', 'An error occur. Please try later.');
    }

    public function update_country(Request $request){
        $request->validate([
           'country' => 'required|string|min:5|max:50'
        ]);

        $affected = DB::table('users')->where('id', Auth::user()->id)->update(['country' => $request->input('country')]);
        if($affected){
            return back()->with('success', 'Country update successfully.');
        }

        return back()->with('error', 'An error occur. Please try later.');
    }
}
