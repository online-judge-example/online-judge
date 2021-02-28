<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\Testcase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;
use Parsedown;

class ProblemSetter extends Controller
{
    /**
     * Problem Setter is a Controller which control all the action of a problem setter
     * only user as a problem setter (user_type == 0) can access all the function of this controller
     * practice user :  user_type = 0
     * problem setter : user_type == 1
     */


    public function __construct()
    {
        // check the authentication
        $this->middleware('auth');
        // check the user_type
        $this->middleware('setter');
    }

    /**
     * function index, no parameter
     * return view
     */
    public function index(){

        return redirect(url('setter/problems'));
        //return view('setter.home');
    }

    public function add_problem(){
        $data['action'] = url('/setter/problem/save');
        return view('setter.add_problem', $data);
    }

    public function save_problem(Request $request){
        $request->validate([
            'title' => 'required|string|min:3|max:50',
            'description' => 'required|string|min:20|max:1000',
            'input_format' => 'required|string|min:20|max:1000',
            'output_format' => 'required|string|min:20|max:1000',
            'sample_input' => 'required|string|min:1|max:50',
            'sample_output' => 'required|string|min:1|max:50',
        ]);


        $details['setter_id'] = Auth::user()->id;
        $details['title'] = $request->input('title');
        $details['description'] = $request->input('description');
        $details['input_format'] = $request->input('input_format');
        $details['output_format'] = $request->input('output_format');
        $details['sample_input'] = $request->input('sample_input');
        $details['sample_output'] = $request->input('sample_output');
        $details['note'] = $request->input('note');

        if(Problem::insert_problem($details)){
            return back()->with('success', 'problem save successfully');
        }

        return back()->with('error', 'An error occur. Please try later');
    }

    public function problems(){
        $data['problems'] = Problem::get_my_problem_list(Auth::user()->id);
        return view('setter.problems', $data);
    }

    /**
     * @param Request $request
     * for DataTable search only
     */
    public function search_problem(Request $request){

        $columns = array(
            0 => 'title',
            1 => 'status',
            //2 => 'solve/tried',
            //3 => 'edit',
        );

        $totalData = DB::table('problems')->where('setter_id', Auth::user()->id)->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $request->input('order.0.column');
        $order = 'id';
        $dir = $request->input('order.0.dir');
        //$dir = 'asc';
        $searchText = $request->input('search.value');

        // search problem return an array, index 0 is the query collection object, index 1 is number of row(count)
        $result = Problem::search_problem(Auth::user()->id, $searchText, $start, $limit, $order, $dir);
        if(!empty($searchText)) $totalFiltered = $result[1];


        $data = array();
        if(!empty($result[0])){
            foreach ($result[0] as $row) {

                $nestedData['title'] = '<a href='.url('problem/'.$row->id).' target="_blank">'.$row->title.'</a>';
                if($row->status == 1) {
                    $nestedData['status'] = '<i class="fa fa-eye" data-id="'.$row->id.'" data-current="'.$row->status.'"></i>';
                } else {
                    $nestedData['status'] = '<i class="fa fa-eye-slash" data-id="'.$row->id.'" data-current="'.$row->status.'"></i>';
                }

                $nestedData['solve/tried'] = '<a href="'.url('/setter/submissions/'.$row->id).'" target="_blank">'.$row->total_solve.'/'.$row->total_sub.'</a>';
                $nestedData['edit'] = '<a href="'.url('/setter/configure/'.$row->id).'" ><i class="fa fa-edit"></i></a>';

                $data[] = $nestedData;
            }
        }

        //$data = array();
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    public function problem_images(){
        $data['images'] = Storage::disk('public')->files('/problem_images');
        //return $data['images'];
        return view('setter.images', $data);
    }

    /**
     * handle ajax request
     * update the problem visibility with get request
     */
    public function update_problem_status(){
        $id = $_GET['id'];      // get the problem id
        $status = $_GET['status'];  // get the current status(visibility) of the problem

        if($status) $status = 0; else $status = 1;  // reverse the status for update

        // json return format
        $data = array(
            'success' => 'false',
            'current' => $status
        );

        if(DB::table('problems')->where('id', $id)->update(['status' => $status])){
            // update status successfully
            $data['success'] = 'true';
        }
        return json_encode($data);
    }

    /**
     * @param $problem_id
     * @return view
     * all the submission of the problem with username
     */
    public function problem_submissions($problem_id){
        // check the problem belongs to current user
        $data['problem'] = Problem::get_problem_title($problem_id);
        //return $data['problem'];
        // get the submission list
        if($data['problem'] == null) return abort(404);
        $data['submissions'] = Submission::get_submissions_of_a_problem($problem_id);

        return view('setter.problem_submissions', $data);
    }

    public function submissions(){
        $data['submission'] = Submission::get_all_submission();

        return view('setter.submissions', $data);
    }

    public function getSubmissionsAjax(){
        $sub = Submission::get_all_submission();
        $sub->withPath('submissions');
        $data = array(
            'success' => 'true',
            'links' => ''.$sub->links(),
        );


        $arrayTemp = array();
        foreach($sub as $s){
            $sub_url = url('/submission/'.$s->sub_id);
            $pro_url = url('problem/'.$s->problem_id);  // problem id
            $user_url = url('profile/'.$s->username);
            $temp = '<tr>';
            $temp .= '<td><a href="'.$sub_url.'" target="_blank">'.$s->sub_id.'</a></td>';
            $temp .= '<td><a href="'.$pro_url.'" target="_blank">'.substr($s->title, 0, 15).'...</td>';
            $temp .= '<td><a href="'.$user_url.'" target="_blank">'.$s->username.'</td>';
            $temp .= '<td>'.\Carbon\Carbon::parse($s->created_at)->format('d M Y-h:m:s').'</td>';
            $temp .= '<td>'.config('app.language')[$s->language_id].'</td>';
            $temp .= '<td>'.config('app.verdict')[$s->verdict].'</td>';
            $temp .= '</tr>';

            $arrayTemp[] = $temp;
        }
        $data['table'] = $arrayTemp;

        return json_encode($data);
    }


    /**
     * @param null $problem_id
     * return view
     * set or update problem limit
     *  time limit, memory limit, status, inputs, outputs,
     * @return View
     */
    public function configure_problem($problem_id = null){
        // check the problem owner
        if(!Problem::isProblemAccessible($problem_id, Auth::user()->id)) return abort(403);

        $data['problem'] = Problem::get_problem_details($problem_id);
        $data['categories'] = Category::get_all_category_list();
        $data['category_id'] = Category::get_categories_of_a_problem($problem_id);
        $data['testcase'] = Testcase::getAllTestcase($problem_id);

        return view('setter.limit',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * delete all previous categories
     * assign new categories
     * post request
     */
    public function update_problem_category(Request $request){
        $request->validate([
            'category' => 'required|min:1|max:100',
            'problem_id' => 'required|numeric|integer|min:1000|max:100000'
        ]);


        if(Problem::isProblemAccessible($request->input('problem_id'), Auth::user()->id)){
            // problem belongs to this user
            Category::delete_problem_category($request->get('problem_id')); // delete previous categories
            Category::add_problem_category($request->input('category'), $request->get('problem_id')); // assign new categories

            return back();
        }

        return back()->with('error', 'Problem is not accessible');
    }

    /**
     * @param $problem_id
     * @return View
     * use for update problem description
     */

    public function update_problem_description($problem_id){
        if(!Problem::isProblemAccessible($problem_id, Auth::user()->id)) abort(403);
        $data['problem'] = Problem::get_problem_details($problem_id);
        $data['action'] = url('/setter/problem/update');
        return view('setter.add_problem', $data);
    }

    public function update_description(Request $request){

        $request->validate([
            'title' => 'required|string|min:3|max:50',
            'description' => 'required|string|min:20|max:1000',
            'input_format' => 'required|string|min:20|max:1000',
            'output_format' => 'required|string|min:20|max:1000',
            'sample_input' => 'required|string|min:1|max:50',
            'sample_output' => 'required|string|min:1|max:50',
            'problem_id' => 'required|numeric'
        ]);

        if(!Problem::isProblemAccessible($request->input('problem_id'), Auth::user()->id)) abort(403);

        //$details['setter_id'] = Auth::user()->id;
        $details['title'] = $request->input('title');
        $details['description'] = $request->input('description');
        $details['input_format'] = $request->input('input_format');
        $details['output_format'] = $request->input('output_format');
        $details['sample_input'] = $request->input('sample_input');
        $details['sample_output'] = $request->input('sample_output');
        $details['note'] = $request->input('note');

        if(Problem::update_problem($request->input('problem_id'), $details)){
            return back()->with('success', 'problem save successfully.');
        }

        return back()->with('error', 'An error occur. Please try later');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * update the time limit of a problem
     */

    public function update_problem_timelimit(Request $request){
        $request->validate([
           'problem_id'=>'required|numeric',
           'time_limit'=>'required|numeric|min:1|max:10',
        ]);

        if(Problem::isProblemAccessible($request->input('problem_id'), Auth::user()->id)){
            Problem::update_time_limit($request->input('problem_id'), $request->input('time_limit'));
            return back()->with('success', 'Time Limit update successfully.');
        }
        return back()->with('error', 'Problem is not accessible');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * update the memory limit of a problem
     */
    public function update_problem_memorylimit(Request $request){
        $request->validate([
           'problem_id'=>'required|numeric',
           'memory_limit'=>'required|numeric|integer|min:1024|max:2048',
        ]);

        if(Problem::isProblemAccessible($request->input('problem_id'), Auth::user()->id)){
            Problem::update_memory_limit($request->input('problem_id'), $request->input('memory_limit'));
            return back();
        }
        return back()->with('error', 'Problem is not accessible');
    }

    /**
     * @return false|string
     * remove a single category if a problem
     * get request, using ajax
     */
    public function delete_problem_category(){
        $category_id = $_GET['category_id'];      // get the category id
        $problem_id = $_GET['problem_id'];  // get the problem id

        // json return format
        $data = array(
            'success' => 'false',
        );

        if(Problem::isProblemAccessible($problem_id, Auth::user()->id)){
            if(DB::table('problem_category_linker')->where('category_id', $category_id)->where('problem_id', $problem_id)->delete()){
                // update status successfully
                $data['success'] = 'true';
            }
        }

        return json_encode($data);
    }

    public function delete_testcase(){
        $testcase_id = $_GET['testcase_id'];      // get the testcase id
        $problem_id = $_GET['problem_id'];  // get the problem id
        // json return format
        $data = array(
            'success' => 'false',
        );
        if(Problem::isProblemAccessible($problem_id, Auth::user()->id)){
            Testcase::deleteTestCase($testcase_id, $problem_id);
            $data['success'] = 'true';
        }
        return json_encode($data);
    }

    public function upload_testcase(Request $request){
        $request->validate([
            'problem_id' => 'required|numeric',
            'input' => 'required|file|mimetypes:text/plain|max:5120',
            'output' => 'required|file|mimetypes:text/plain|max:5120',
        ]);
        $problem_id = $request->input('problem_id');
        if(Problem::isProblemAccessible($problem_id, Auth::user()->id)){

            $input = preg_replace("/\r/", "", file_get_contents($request['input']));
            $output = preg_replace("/\r/", "", file_get_contents($request['output']));

            Testcase::insertTestcase($problem_id, $input, $output);
            return back();
        }

        return back()->with('error', 'problem is not accessible');
    }

    public function download_testcase($tc_id, $case_no, $problem_id){

        if(Problem::isProblemAccessible($problem_id, Auth::user()->id)){
            $tc = Testcase::getOneTestCase($tc_id, $problem_id);

            $folder = "testcase";
            // create folder and file
            Storage::put($folder.'/input.txt', $tc->input);
            Storage::put($folder.'/output.txt', $tc->output);

            // make zip file
            $zip_file = "testcase_".$case_no.".zip";
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            // add the folder to the zip
            $zip->addFile(storage_path('app/'.$folder.'/input.txt'),'input.txt');
            $zip->addFile(storage_path('app/'.$folder.'/output.txt'),'output.txt');

            $zip->close();
            // delete the folder
            Storage::deleteDirectory('testcase');
            return response()->download($zip_file);
        }
    }

    public function users(){
        $data['user'] = User::get_all_user();
        return view('setter.users', $data);
    }

    public function parse_markdown(Request $request){

        $data = $request->get('text');
        $Parsedown = new Parsedown();

        return $Parsedown->text($data);
    }

    public function category(){
        $data['categories'] = Category::get_all_category_list();
        return view('setter.category', $data);
    }

    public function create_category(Request $request){
        $request->validate([
           'category' => 'required|string|min:3|max:30'
        ]);
        // convert to lower case
        $cagetory = strtolower($request->input('category'));

        if(!Category::create_category($cagetory)){
            return back()->with('error', 'An error occur. Please try later.');
        }

        return back()->with('success', 'Category Create Successfully');
    }

    public function update_category(Request $request){
        $request->validate([
            'category' => 'required|string|min:3|max:30',
            'position' => 'required|numeric|min:1|max:100',
        ]);

        $info['id'] = $request->input('category_id');
        $info['name'] = strtolower($request->input('category'));
        $info['position'] = $request->input('position');

        if(Category::update_category($info))

        return back()->with('success', 'Category Update Successfully');

    }

    public function upload_image(Request $request){
        $request->validate([
            'picture' => 'required|image|max:1024'
        ]);

        //$name = substr($request->file('picture')->store('public/problem_image'), 20);

        if($request->file('picture')->store('public/problem_images')) return back()->with('success', 'Image Upload Successfully.');
        else return back()->with('error', 'An error occur. Please try later.');
    }

    /**
     * @param $id category id
     * @param $visibility (change to this visibility)
     * @return Redirector
     */
    public function change_category_visibility($id, $visibility){
        // if visibility is 1 then parameter $visibility is 0
        Category::where('id', $id)->update(['visibility' => $visibility]);
        return redirect(url('/setter/category'));
    }

    public function delete_category($id){
        Category::where('id', $id)->delete();
        return redirect(url('/setter/category'));
    }

}
