<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use Notifiable;

    protected $table = 'categories';

    protected $fillable = [
      'name', 'slug'
    ];

    public static function getAllCategories()
    {
        return DB::table('categories')->orderBy('id')->get();
    }

    public static function getAllCategoriesFirstLevel()
    {
        return DB::table('categories')->where('parent_id', '=', 0)->orderBy('id')->get();
    }

    public static function getAllCategoriesSecondLevel()
    {
        return DB::table('categories')->where('parent_id', '!=', 0)->orderBy('id')->get();
    }

    public static function getCategoryById($id)
    {
        return DB::table('categories')->where('id', '=', $id)->first();
    }

    public static function createCategory($data)
    {
        $category = new Category();
        $category->name = $data['name'];
        $category->slug = $data['slug'];
        $category->parent_id = $data['parent_id'];
        $category->thumb= $data['thumb'];
        return $category->save();
    }

    public static function updateCategory($request)
    {
        return DB::table('categories')->where('id', '=', $request['category_id'])->update([
            'name' => $request['name'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent_id'],
            'thumb' => $request['thumb'],
            'updated_at' => Carbon::now()]);
    }
}
