<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use DB;
class Submission extends Model
{
    use HasFactory;
    protected $table = "submission";

    public static function isMySubmission($user_id, $sub_id){
        return Submission::where('sub_id', $sub_id)->where('user_id', $user_id)->exists();
    }

    public static function get_all_submission(){
        // return all the latest submissions
        try{
            return DB::table('submission')->select('submission.sub_id', 'problems.id as problem_id', 'problems.title', 'submission.language_id', 'submission.cpu',
                'submission.memory', 'submission.verdict', 'submission.created_at', 'users.id as user_id' ,'users.username')
                ->leftJoin('problems','submission.problem_id', '=', 'problems.id')
                ->leftJoin('users','submission.user_id', '=', 'users.id')
                ->orderBy('sub_id','desc')->paginate(config('app.standard_limit'));
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later.");
            //dd($ex->getMessage());
        }
    }

    public static function get_submission_list($user_id){
        // return all the submission of a user
        try{
            return DB::table('submission')
                ->select('submission.sub_id', 'problems.id', 'problems.title', 'submission.language_id', 'submission.cpu', 'submission.memory', 'submission.verdict', 'submission.created_at')
                ->leftJoin('problems','submission.problem_id', '=', 'problems.id')
                ->where('submission.user_id', $user_id)
                ->orderBy('sub_id','desc')->paginate(10);
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later.");
            //dd($ex->getMessage());
        }
    }

    public static function get_solved_problem_list($user_id){
        // return all the Accepted problem id of a user
        //verdict 1 means accepted
        try{
            return DB::table('submission')
                ->select('submission.problem_id')
                ->where('submission.user_id', $user_id)
                ->where('submission.verdict', '=', 1)
                ->orderBy('submission.sub_id','desc')
                ->groupBy('submission.problem_id')->get();
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later.");
            //dd($ex->getMessage());
        }
    }

    public static function get_submission_details($sub_id){

        try{
            return DB::table('submission')
                ->select('submission.sub_id', 'problems.id', 'problems.title', 'submission.language_id',
                'submission.cpu', 'submission.memory', 'submission.verdict', 'submission.created_at',
                'submission.updated_at', 'submission.code', 'users.id as user_id', 'users.username')
                ->leftJoin('problems','submission.problem_id', '=', 'problems.id')
                ->leftJoin('users','submission.user_id', '=', 'users.id')
                ->where('submission.sub_id', $sub_id)->first();
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later.");
            //dd($ex->getMessage());
        }
    }

    public static function get_my_submission_list_of_a_problem($user_id, $problem_id){
        try{
            return DB::table('submission')
                ->select('submission.sub_id', 'submission.verdict', 'submission.created_at')
                ->orderBy('submission.sub_id', 'desc')
                ->where('submission.user_id', $user_id)
                ->where('submission.problem_id', $problem_id)
                ->paginate(5);
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later.");
            //dd($ex->getMessage());
        }
    }


    public static function submit_code($code){
        try{
            return DB::table('submission')->insertGetId([
                'language_id'       =>      $code['language_id'],
                'user_id'           =>      $code['user_id'],
                'problem_id'        =>      $code['problem_id'],
                'code'              =>      $code['code'],
                'cpu'               =>      0,
                'memory'            =>      0,
                'verdict'           =>      7,
                'created_at'        =>      now(),
            ]);
        }catch (QueryException $ex){
            //dd($ex->getMessage());
            die("An Error Occur. Please Try Later.");
        }
    }

    public static function update_submission_status($submission_id, $values){
        try{
            DB::table('submission')
                ->where('sub_id', $submission_id)
                ->update([
                    'verdict' => $values['verdict'],
                    'cpu' => $values['cpu'],
                    'memory' => $values['memory']
                ]);
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later.");
            //dd($ex->getMessage());
        }
    }

    public static function get_submissions_of_a_problem($problem_id){
        try{
            return DB::table('submission')
                ->select('submission.sub_id', 'submission.verdict', 'submission.created_at', 'submission.language_id', 'submission.verdict', 'users.id', 'users.username')
                ->leftJoin('users', 'submission.user_id', '=', 'users.id')
                ->orderBy('submission.sub_id', 'desc')
                ->where('submission.problem_id', $problem_id)
                ->paginate(config('app.standard_limit'));
        }catch (QueryException $ex){
            //die("An Error Occur. Please Try Later.");
            dd($ex->getMessage());
        }
    }

}
