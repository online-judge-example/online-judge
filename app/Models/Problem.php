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
        'execution_type',
        'update_at',
        'create_at',
    ];


    /**
     * @param $problem_id (integer)
     * @return object (problem details)
     */
    public static function get_full_problem_details($problem_id){

        try{
            return DB::table('problems')
                ->select('problems.title','problems.description','problems.input_format','problems.output_format',
                    'problems.time_limit','problems.memory_limit','problems.sample_input', 'problems.sample_output',
                    'problems.execution_type', 'users.name as setter_name','users.email')
                ->leftJoin('users', 'users.id', '=','problems.setter_id')
                ->where('problems.id',$problem_id)->get();

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
    public static function get_problem_list_with_category_id($category_id, $user_id, $limit){
        // get the array of problem id under the category id
        // return problem id and title with how many user tried and solve
        // also return  tried and solve for this user

        try{

            $problem_list = Category::get_problems_id($category_id);

            return DB::table('problems')
                ->select('problems.id', 'problems.title',
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id) as total_sub'),
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id AND verdict = 1) as total_solve'),
                    DB::raw("(SELECT MIN(submission.verdict)  FROM submission WHERE submission.user_id = '$user_id' AND submission.problem_id = problems.id LIMIT 1)as verdict")
                )->whereIn('problems.id', $problem_list)->paginate($limit);
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

    public static function get_my_problem_list($user_id, $limit){
        // only for setter
        // return problem list of a setter

        try{
            return DB::table('problems')
                ->select('problems.id', 'problems.title',
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id) as total_sub'),
                    DB::raw('(SELECT COUNT(submission.problem_id)  FROM submission WHERE submission.problem_id = problems.id AND verdict = 1) as total_solve'),
                    DB::raw("(SELECT MIN(submission.verdict)  FROM submission WHERE submission.user_id = '$user_id' AND submission.problem_id = problems.id LIMIT 1)as verdict")
                )->where('problems.setter_id', $user_id)->paginate($limit);
        }catch (QueryException $ex){
            // return 0 means something wrong happen
            die("An Error Occur. Please Try Later.");
        }

    }


    /**
     * @param array $problem_details
     */
    public static function insert_problem($problem_details = array()){

        try{
            DB::table('problems')->insert([
                'title'             =>      $problem_details['title'],
                'setter_id'         =>      $problem_details['setter_id'],
                'description'       =>      $problem_details['description'],
                'input_format'      =>      $problem_details['input_format'],
                'output_format'     =>      $problem_details['output_format'],
                'time_limit'        =>      $problem_details['time_limit'],
                'memory_limit'      =>      $problem_details['memory_limit'],
                'sample_input'      =>      $problem_details['sample_input'],
                'sample_output'     =>      $problem_details['sample_output'],
                'execution_type'    =>      $problem_details['execution_type']
            ]);
        }catch (QueryException $ex){
            $ex->getMessage();
            die("An Error Occur. Please Try Later.");
        }
    }


    /**
     * @param null $problem_id
     * @param array $values(update values)
     */
    public static function update_problem($problem_id = null, $values = array()){
        try{
            DB::table('problems')->where('id','=',$problem_id)->update([
                'title'             =>      $values['title'],
                'setter_id'         =>      $values['setter_id'],
                'description'       =>      $values['description'],
                'input_format'      =>      $values['input_format'],
                'output_format'     =>      $values['output_format'],
                'time_limit'        =>      $values['time_limit'],
                'memory_limit'      =>      $values['memory_limit'],
                'sample_input'      =>      $values['sample_input'],
                'sample_output'     =>      $values['sample_output'],
                'execution_type'    =>      $values['execution_type']
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
            $ex->getMessage();
            die("An Error Occur. Please Try Later.");
        }
    }
}
