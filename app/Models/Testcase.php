<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Testcase extends Model
{
    use HasFactory;

    protected $table = "testcase";


    /**
     * @param $problem_id
     * @return mixed
     * return all the test cases
     */

    public static function getAllTestCase($problem_id){
        try{
            return Testcase::where('problem_id', $problem_id)->orderBy('id', 'asc')->get();
        }catch (QueryException $ex){
            //dd($ex->getMessage());
            die('An error Occur. Please try later.');
        }
    }

    public static function deleteTestCase($testcase_id, $problem_id){
        try{
            return Testcase::where('id', $testcase_id)->where('problem_id', $problem_id)->delete();
        }catch (QueryException $ex){
            //dd($ex->getMessage());
            die('An error Occur. Please try later.');
        }
    }

    public static function getOneTestCase($testcase_id, $problem_id){
        try{
            return Testcase::where('id', $testcase_id)->where('problem_id', $problem_id)->first();
        }catch (QueryException $ex){
            //dd($ex->getMessage());
            die('An error Occur. Please try later.');
        }
    }

    public static function insertTestcase($problem_id, $input, $output){
        try{
            return Testcase::insert([
                'problem_id' => $problem_id,
                'input' => $input,
                'output' => $output,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }catch (QueryException $ex){
            //dd($ex->getMessage());
            die('An error Occur. Please try later.');
        }
    }
}
