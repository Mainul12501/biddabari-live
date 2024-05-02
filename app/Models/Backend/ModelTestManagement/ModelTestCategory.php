<?php

namespace App\Models\Backend\ModelTestManagement;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelTestCategory extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'model_test_category_id',
        'name',
        'image',
        'slug',
        'status',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'model_test_categories';

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($modelCategory){
            if (!empty($modelCategory->modelTestCategories))
            {
                foreach ($modelCategory->modelTestCategories as $category)
                {
                    if (file_exists($category->image))
                    {
                        unlink($category->image);
                    }
                    $category->delete();
                }
            }
            if (!empty($modelCategory->modelTests))
            {
                $modelCategory->modelTests->each->delete();
            }
        });
    }

    protected static $modelTestCategory;

    public static function createOrUpdateModelTestCategory($request, $id = null)
    {
        if ($id)
        {
            self::$modelTestCategory = ModelTestCategory::find($id);
        }else{
            self::$modelTestCategory = new ModelTestCategory();
        }
        self::$modelTestCategory->model_test_category_id    = !empty($request->model_test_category_id) ? $request->model_test_category_id : 0;
        self::$modelTestCategory->name                      = $request->name;
        self::$modelTestCategory->image                     = isset($id) ? imageUpload($request->file('image'), 'model-test-management/model-test-categories/', 'model-test-category-', '', '', ModelTestCategory::find($id)->image) : imageUpload($request->file('image'), 'model-test-management/model-test-categories/', 'model-test-category-', '', '');
        self::$modelTestCategory->slug                      = str_replace(' ', '-', $request->name);
        self::$modelTestCategory->status                    = $request->status == 'on' ? 1 : 0;
        self::$modelTestCategory->save();
    }

    public function modelTestCategory()
    {
        return $this->belongsTo(ModelTestCategory::class);
    }

    public function modelTestCategories()
    {
        return $this->hasMany(ModelTestCategory::class);
    }

    public function modelTests()
    {
        return $this->hasMany(ModelTest::class);
    }
}