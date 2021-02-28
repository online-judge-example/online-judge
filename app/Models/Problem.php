<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
class Problem extends Model
{
    use HasFactory;
    protected $table = 'problems';

    protected $fillable = [
        'setter_id',
        'title' ,
        'description',
        'input_format',
        'output_format',
        'time_limit',
        'memory_limit',
        'sample_input',
        'sample_output',
        'update_at',
        'create_at',
    ];

    /**
     * Problem Model
     * setter is also a normal user
     * status column use for user only (preview)
     * (as a setter) can get preview  and update information only for his/her problem
     * normal user read and submit all the problem where status = 1
     */




    /**
     * @param $problem_number
     * @return bool
     * check problem exist and enable
     */
    public static function is_problem_exist_and_enable($problem_id){
        if(Problem::where('id', '=', $problem_id)->where('status', 1)->exists()) return true;
        return false;
    }


    public static function isProblemAccessible($problem_id, $user_id){
        // depend on user
        // check the problem owner
        if(Problem::where('id', '=', $problem_id)->where('setter_id', $user_id)->exists()) return true;
        return false;
    }

    /**
     * @param $problem_id (integer)
     * @return object (problem details)
     * for preview problem details, anyone can view when problem status is 1
     */
    public static function get_full_problem_details($problem_id){

        try{
            return DB::table('problems')
                ->select('problems.title','problems.description','problems.input_format','problems.output_format',
                    'problems.time_limit','problems.memory_limit','problems.sample_input', 'problems.sample_output', 'problems.note',
                    'users.name as setter_name','users.email')
                ->leftJoin('users', 'users.id', '=','problems.setter_id')
                ->where('problems.id',$problem_id)
                ->where('problems.status',1)->first();

        } catch (QueryException $ex){
            //dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }
    }

    /**
     * @param $category_id
     * @param $user_id
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function get_problem_list_with_category_id($category_id, $user_id){
        // get the array of problems id under the category id
        // return problem id and title with how many user tried and solve
        // also return  tried and solve for this user

        try{

            $problem_list = Category::get_problems_id($category_id);

            return DB::table('problems')
                ->select('problems.id', 'problems.title',
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id) as total_sub'),
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id AND verdict = 1) as total_solve'),
                    DB::raw("(SELECT MIN(submission.verdict)  FROM submission WHERE submission.user_id = '$user_id' AND submission.problem_id = problems.id LIMIT 1)as verdict")
                )->where('problems.status', 1)
                ->whereIn('problems.id', $problem_list)->paginate(config('app.standard_limit'));
        }catch (QueryException $ex){
            //dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }


        /*
         return DB::select('problems.id', 'problems.title',
                DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id) as total_sub'),
                DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id AND verdict = 1) as total_solve'),
                DB::raw('(SELECT MIN(submission.verdict)  FROM submission WHERE submission.user_id = :user AND submission.problem_id = problems.id LIMIT 1)as verdict'),
                array('user'=>$user_id)
            )->whereIn('problems.id', $category_id)->get();

        */

        /*
        return DB::select('SELECT problems.id, problems.title,
                (SELECT COUNT(submission.problem_id) FROM submission WHERE submission.problem_id = problems.id) as total_sub,
                (SELECT COUNT(submission.problem_id) FROM submission WHERE submission.problem_id = problems.id AND verdict = 1)as total_solve,
                (SELECT MIN(submission.verdict) FROM submission WHERE submission.user_id = ? AND submission.problem_id = problems.id LIMIT 1)as verdict
                FROM problems WHERE problems.id IN (?)',[$user_id, $category_id]);
         */

    }


    /**
     * @param $user_id
     * @param $limit
     * @return object(problem_list)
     */

    public static function get_my_problem_list($user_id){
        // only for setter
        // return problem list of a setter

        try{
            return DB::table('problems')
                ->select('problems.id', 'problems.title', 'problems.status',
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id) as total_sub'),
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id AND verdict = 1) as total_solve'),
                    DB::raw("(SELECT MIN(submission.verdict)  FROM submission WHERE submission.user_id = '$user_id' AND submission.problem_id = problems.id LIMIT 1)as verdict")
                )->where('problems.setter_id', $user_id)->paginate(config('app.problem_limit'));
        }catch (QueryException $ex){
            // return 0 means something wrong happen
            dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }

    }


    /**
     * @param array $problem_details
     * @return bool
     */
    public static function insert_problem($problem_details = array()){

        try{
            return DB::table('problems')->insert([
                'title'             =>      $problem_details['title'],
                'setter_id'         =>      $problem_details['setter_id'],
                'description'       =>      $problem_details['description'],
                'input_format'      =>      $problem_details['input_format'],
                'output_format'     =>      $problem_details['output_format'],
                'time_limit'        =>      1,
                'memory_limit'      =>      1024,
                'sample_input'      =>      $problem_details['sample_input'],
                'sample_output'     =>      $problem_details['sample_output'],
                //'execution_type'    =>      1,
                'note'              =>      $problem_details['note'],
            ]);
        }catch (QueryException $ex){
            dd($ex->getMessage());
            //die("An Error Occur. Please Try Later.");
        }
    }


    /**
     * @param null $problem_id
     * @param array $values (update values)
     * @return int
     */
    public static function update_problem($problem_id = null, $values = array()){
        try{
            return DB::table('problems')->where('id','=',$problem_id)->update([
                'title'             =>      $values['title'],
                //'setter_id'         =>      $values['setter_id'],
                'description'       =>      $values['description'],
                'input_format'      =>      $values['input_format'],
                'output_format'     =>      $values['output_format'],
                'sample_input'      =>      $values['sample_input'],
                'sample_output'     =>      $values['sample_output'],
                'note'              =>      $values['note']
            ]);

        }catch (QueryException $ex){
            $ex->getMessage();
            die("An Error Occur. Please Try Later.");
        }
    }

    /**
     * @param null $problem_id
     */
    public static function delete_problem($problem_id = null){
        try{
            DB::table('problems')->where('id','=', $problem_id)->delete();
        }catch (QueryException $ex){
            //$ex->getMessage();
            die("An Error Occur. Please Try Later.");
        }
    }

    /**
     * for setter only
     * if setter search his problem list
     * ajax request for DataTable
     * @param $user_id (current user)
     * @param $searchText (text want to search)
     * @param $start (offset)
     * @param $limit (limit)
     * @param $order (orderBy column name)
     * @param $dir  (order type, (asc, desc))
     * @return array of lenght 2
     *  index 0 is the collection object (results)
     *  index 1 is the number of row (count)
     */
    public static function search_problem($user_id, $searchText, $start, $limit, $order, $dir){
        //if(emptyString($text)){
            try{
                if(strlen($searchText)>0){
                    // search string not empty
                    // search the problem title using the $searchText string
                    $result =  DB::table('problems')
                        ->select('problems.id', 'problems.title', 'problems.status',
                            DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id) as total_sub'),
                            DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id AND verdict = 1) as total_solve'),
                            DB::raw("(SELECT MIN(submission.verdict)  FROM submission WHERE submission.user_id = '$user_id' AND submission.problem_id = problems.id LIMIT 1)as verdict")
                        )->where('problems.setter_id', $user_id)
                        ->where('problems.title', 'like', '%'.$searchText.'%')
                        ->offset($start)->limit($limit)->orderBy($order, $dir)
                        ->get();

                    $total_row =  DB::table('problems')
                        ->where('problems.setter_id', $user_id)
                        ->where('problems.title', 'like', '%'.$searchText.'%')
                        ->count();
                }else{
                    // search string is empty, so return everything
                    $result =  DB::table('problems')
                        ->select('problems.id', 'problems.title', 'problems.status',
                            DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id) as total_sub'),
                            DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id AND verdict = 1) as total_solve'),
                            DB::raw("(SELECT MIN(submission.verdict)  FROM submission WHERE submission.user_id = '$user_id' AND submission.problem_id = problems.id LIMIT 1)as verdict")
                        )->where('problems.setter_id', $user_id)
                        ->offset($start)->limit($limit)->orderBy($order, $dir)
                        ->get();

                    $total_row =  DB::table('problems')
                        ->where('problems.setter_id', $user_id)
                        ->count();
                }

                return array($result, $total_row);

            }catch (QueryException $ex){
                // return 0 means something wrong happen
                die("An Error Occur. Please Try Later. search");
            }
        //}

    }

    /**
     * @param $problem_id
     * @param $user_id
     * @return Collection $problem_title
     */
    public static function get_problem_title($problem_id){
        try{
            return DB::table('problems')->select('problems.id', 'problems.title')
                ->where('problems.id', $problem_id)
                //->where('problems.status', 1)
                ->first();
        }catch (QueryException $ex){
            //return $ex->getMessage();
            die("An Error Occur. Please Try Later.");
        }
    }

    /**
     * @param $problem_id (integer)
     * @return object (problem details)
     * this function return only problem description.
     *
     */
    public static function get_problem_details($problem_id){

        try{
            return DB::table('problems')
                ->select('problems.id','problems.title','problems.description','problems.input_format','problems.output_format',
                    'problems.time_limit','problems.memory_limit','problems.sample_input', 'problems.sample_output',
                    'problems.status','problems.note')
                ->where('problems.id',$problem_id)->first();

        } catch (QueryException $ex){
            //dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }
    }


    public static function get_problem_limit($problem_id){
        // return just time and memory limit
        try{
            return DB::table('problems')
                ->select('problems.time_limit','problems.memory_limit')
                ->where('problems.id',$problem_id)->first();
        } catch (QueryException $ex){
            //dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }
    }



    /** update */
    /**
     * @param $problem_id
     * @param $user_id
     * @param $time_limit
     * @return mixed
     */
    public static function update_time_limit($problem_id, $time_limit){
        try{
            return Problem::where('problems.id',$problem_id)
                ->update(['time_limit'=> $time_limit]);
        } catch (QueryException $ex){
            dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }
    }

    public static function update_memory_limit($problem_id, $memory_limit){
        try{
            return Problem::where('problems.id',$problem_id)
                ->update(['memory_limit'=> $memory_limit]);
        } catch (QueryException $ex){
            dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }
    }


}
