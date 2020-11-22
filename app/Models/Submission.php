<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use DB;
class Submission extends Model
{
    use HasFactory;

    public static function get_submission_list($user_id, $limit){
        try{
            return DB::table('submission')
                ->select('submission.sub_id', 'problems.title', 'submission.cpu', 'submission.memory', 'submission.verdict', 'submission.updated_at')
                ->leftJoin('problems','submission.problem_id', '=', 'problems.id')
                ->where('submission.user_id', $user_id)->paginate($limit);
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later.");
            //dd($ex->getMessage());
        }
    }


    public static function submit_code(){

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


}
