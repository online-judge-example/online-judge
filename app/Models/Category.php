<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';

    public static function get_problems_id($category_id){
        $id = DB::table('problem_category_linker')
            ->select('problem_id')
            ->where('category_id','=',$category_id)
            ->get();
        $id_list = array();
        foreach ($id as $item) {array_push($id_list, $item->problem_id);}
        return $id_list;
    }

    public static function get_categories_of_a_problem($problem_id){
        try{

            $categories_id = DB::table('problem_category_linker')->where('problem_id', $problem_id)->get();

            $id_list = array();
            foreach ($categories_id as $item) {array_push($id_list, $item->category_id);}
            return $id_list;

        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later");
        }
    }

    /**
     * @return object (only visible categories)
     * for normal user
     */
    public static function get_available_category_list(){

        try{
            $categories = Category::where('visibility',1)->orderBy('position')->get();
            return $categories;

        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later");
        }
    }

    /**
     * @return object (visible, non-visible, all category)
     * for problem setter and admin
     */
    public static function get_all_category_list(){
        try{
            return DB::table('category')
                ->orderBy('position')
                ->get();
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later");
        }
    }

    public static function create_category($name){
        try{
            if(!Category::where('name', $name)->exists()){
                $position = DB::table('category')->max('position');

                return DB::table('category')
                    ->insert(
                        ['name' => $name, 'position' => $position+1, 'visibility' => 0]
                    );
            }
            return false;

        }catch (QueryException $ex){
            //die("An Error Occur. Please Try Later");
            //dd($ex->getMessage());
        }

    }

    public static function update_category($category){
        $find = Category::where('id', $category['id'])->first();
        if($find === null) return;
        // update category name

        if(Category::where('id', $category['id'])->update(['name'=> $category['name']])){
            if($find->position != $category['position']){
                // update position
                Category::update_position($category['id'], $category['position']);
                return true;
            }
            return true;
        }

        return false;
    }


    /**
     * @param $category_id (row we want to update)
     * @param $position (value we want to set)
     */
    public static function update_position($category_id, $position){
        // swap the position of the category
        // a = y;
        // b = x;
        // we want to set a = x;
        // get the current value of a;
        // update b by a; (b = y);
        // set a = x;

        try{

            // get the current position of the category_id
            $current_position = DB::table('category')->select('position')->where('id',$category_id)->first();

            // update b; (b = y)
            DB::table('category')->where('position',$position)->update(['position' => $current_position->position]);

            // update the position for this category_id (a = x)
            DB::table('category')->where('id',$category_id)->update(['position' => $position]);



        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later");
        }
    }

    public static function update_visibility($category_id, $value){
        try{
            DB::table('category')->where('id', $category_id)->update(['visibility'=> $value]);
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later");
        }
    }

    public static function delete_category($category_id){
            // delete rows from category_problem_linker table
            // delete from category table
        try{
            //DB::table('table_name')->where('id',$category_id)->delete();
            DB::table('category')->where('id', $category_id)->delete();
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later");
        }
    }

    public static function delete_problem_category($problem_id){

        try{
            DB::table('problem_category_linker')->where('problem_id', $problem_id)->delete();
        }catch (QueryException $ex){
            die("An Error Occur. Please Try Later");
        }
    }

    /**
     * @param $category array
     * @param $problem_id
     */
    public static function add_problem_category($category, $problem_id){
        try{
            $insert_array = array();

            foreach ($category as $category_id){
                $temp = array();
                $temp['category_id'] = $category_id;
                $temp['problem_id'] = $problem_id;

                $insert_array[] = $temp;
            }
            DB::table('problem_category_linker')->insert($insert_array);
        }catch (QueryException $ex){
            //die("An Error Occur. Please Try Later");
            dd($ex->getMessage());
        }
    }

}
