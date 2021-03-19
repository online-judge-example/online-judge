<?php

namespace App\Http\Controllers\Setter;

use App\Models\Category;
use App\Models\Testcase;
use App\Models\Problem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemController extends Controller

{
    /**
     * use to manage problem only. not category and testcase
     * only user as a problem setter (user_type == 1) can access all the function of this controller
     * practice user :  user_type = 0
     * problem setter : user_type = 1
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
     * return view, dashboard for setter
     */
    public function index(){

        return redirect(url('setter/problems'));
        //return view('setter.dashboard');
    }


    /** methods for return view */

    /**
     * @return View
     * problem list of the user(setter). only his/her problem list
     * use DataTable plugin to view the problem
     * first show the default(pass with view) problem list
     * then update the list with DataTable
     */
    public function problems(){
        $data['problems'] = Problem::get_my_problem_list(Auth::user()->id);
        return view('setter.problems', $data);
    }

    /**
     * @return View
     * only for the view for create problem
     */
    public function add_problem(){

        $data['action'] = url('/setter/problem/save');
        return view('setter.add_problem', $data);
    }

    /**
     * @param $problem_id
     * return view
     * set or update problem limit
     * time limit, memory limit, status, inputs, outputs
     * @return View
     */
    public function configure_problem($problem_id = 0){
        // check the problem owner
        if(!Problem::isProblemAccessible($problem_id, Auth::user()->id)) return abort(403);

        $data['problem'] = Problem::get_problem_details($problem_id);
        $data['categories'] = Category::get_all_category_list();
        $data['category_id'] = Category::get_categories_of_a_problem($problem_id);
        $data['testcase'] = Testcase::getAllTestcase($problem_id);

        return view('setter.limit',$data);
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





    /** methods for http/ajax Request */

    /**
     * @param Request $request (Problem details)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_problem(Request $request){
        // form validation
        $request->validate([
            'title' => 'required|string|min:3|max:50',
            'description' => 'required|string|min:20|max:1000',
            'input_format' => 'required|string|min:20|max:1000',
            'output_format' => 'required|string|min:20|max:1000',
            'sample_input' => 'required|string|min:1|max:100',
            'sample_output' => 'required|string|min:1|max:100',
        ]);

        // prepare the array for model to save in database
        $details = array(
            'setter_id' => Auth::user()->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'input_format' => $request->input('input_format'),
            'output_format' => $request->input('output_format'),
            'sample_input' => $request->input('sample_input'),
            'sample_output' => $request->input('sample_output'),
            'note' => $request->input('note'),
        );

        if(Problem::insert_problem($details)){
            return back()->with('success', 'problem save successfully');
        }

        return back()->with('error', 'An error occur. Please try later');
    }


    /**
     * @param Request $request (Ajax, DataTable Search)
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
        $order = 'id';      // default order column
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

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    /**
     * handle ajax request
     * update the problem visibility with get request
     */
    public function update_problem_status(){
        $id = $_GET['id'];      // get the problem id
        $status = $_GET['status'];  // get the current status(visibility) of the problem

        // json return format
        $data = array(
            'success' => 'false',
            'current' => $status
        );

        ($status) ? $status = 0 : $status = 1;  // reverse the status for update

        if(DB::table('problems')->where('id', $id)->update(['status' => $status])){
            // update status successfully
            $data['success'] = 'true';
            $data['status'] = $status;
        }
        return json_encode($data);
    }

    /**
     * @param Request $request (Http request)
     * @return \Illuminate\Http\RedirectResponse
     * update the problem description
     */

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


        // prepare the array for model to save in database
        $details = array(
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'input_format' => $request->input('input_format'),
            'output_format' => $request->input('output_format'),
            'sample_input' => $request->input('sample_input'),
            'sample_output' => $request->input('sample_output'),
            'note' => $request->input('note'),
        );

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

    public function update_problem_time_limit(Request $request){
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
    public function update_problem_memory_limit(Request $request){
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
}
