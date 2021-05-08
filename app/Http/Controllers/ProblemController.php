<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProblem;
use App\Models\Category;
use App\Models\Submission;
use App\Models\Testcase;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Http;

class ProblemController extends Controller

{
    /**
     * use to manage problem only. not category and testcase
     * only user as a problem setter (user_type == 1) can access all the function of this controller
     * practice user :  user_type = 0
     * problem setter : user_type = 1
     */

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
     * @return \Illuminate\Contracts\View\View
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
     * @return \Illuminate\Contracts\View\View
     * only for the view for create problem
     */
    public function create_problem(){
        $data['action'] = url('/setter/problem/save');
        return view('setter.add_problem', $data);
    }

    /**
     * @param $problem_id
     * return view
     * set or update problem limit
     * time limit, memory limit, status, inputs, outputs
     * @return \Illuminate\Contracts\View\View
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
     * @return \Illuminate\Contracts\View\View
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
    public function save_problem(CreateProblem $request){
        $validated = $request->validated();

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

        $totalData = Problem::where('setter_id', Auth::user()->id)->count();
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

    public function debug($id = null){


        $data['problem_id'] = -1;
        if($id != null){
            $row = Problem::select('id','title')
                ->where('setter_id', Auth::user()->id)
                ->where('id', $id)->first();
            $data['problem_title']  = $row->title;
            $data['problem_id'] = $row->id;
        }

        return view('setter.debug', $data);
    }

    public function getProblemSuggestion(Request $request){
        $data['status'] = "success";

        $problems = Problem::select('id', 'title')
            ->where('setter_id', Auth::user()->id)
            ->where('title', 'like', '%'.$request->get('text').'%')->paginate(10);

        $list = array();

        foreach ($problems as $problem){

            $list[] = '<p class="p-2 m-0 suggest_list"><a style="text-decoration: none" href="'.route('setter.debug', ['id'=>$problem->id]).'">'.$problem->title.'</a></p>';
        }

        $data['list'] = $list;
        return json_encode($data);

    }


    public function debug_submit(Request $request){
        /*
        $array1['verdict'] = 10;
        $array1['time_take'] = 10;
        $array1['memory_take'] = 10;
        $v[] = $array1;
        $array1['verdict'] = 15;
        $array1['time_take'] = 16;
        $array1['memory_take'] = 17;
        $v[] = $array1;

        return json_encode($v);
        */

        $problem_id = $request->get('problem');
        $code = $request->get('code');
        $language = $request->get('language');

        // gathering information
        $testcase = Testcase::getAllTestcase($problem_id);
        // get the problem limits
        $problem = Problem::get_problem_limit($problem_id);
        $data = array();

        foreach ($testcase as $tc){
            $response = Http::post('https://api.jdoodle.com/v1/execute', [
                'clientId' => '6f9e183fcfe6b78aa3981115e491432b',
                'clientSecret' => '6ba69d7123f3c3df6fef4d4798a6a79d2ecf851d00ba077e1583aeff3116139',
                'script' => $code,
                'stdin' => $tc->input,
                'language' => $language,
                'versionIndex' => config('app.language_index')[$language],
            ]);

            if ($response->successful()) {
                // api call success
                $details = array();

                $result = json_decode($response->body());

                if (property_exists($result, "error")) {
                    $details['verdict'] = config('app.verdict')[6];

                } else {

                    $server_tl = (float)$result->cpuTime;
                    $server_ml = (int)$result->memory;
                    $server_output = $output = preg_replace("/\r/", "", $result->output);
                    // get problem limit
                    $tl = (float)$problem->time_limit;
                    $ml = (int)$problem->memory_limit;
                    $epsilon = 0.0001;
                    $details['time_require'] = $tl;
                    $details['memory_require'] = $ml;
                    $details['time_take'] = $server_tl;
                    $details['memory_take'] = $server_ml;

                    if ($server_ml == 0) {
                        // compilation error

                        // update the verdict
                        $details['verdict'] = config('app.verdict')[5];

                    } else if (($tl + $epsilon) < $server_tl) {
                        // time limit
                        $details['verdict'] = config('app.verdict')[3];
                    } else if ($server_ml > $ml) {
                        // memory limit
                        $details['verdict'] = config('app.verdict')[4];
                    } else if (strcmp($server_output, $tc->output) != 0) {
                        // wrong answer
                        //var_dump(strcmp($server_output, trim($testcase[0]->output)));
                        $details['verdict'] = config('app.verdict')[2];
                    } else {
                        $details['verdict'] = config('app.verdict')[1];
                    }

                }  // end else

                $data[] = $details;

            } // end response success
        }  // end foreach

        return json_encode($data);

    }

}
