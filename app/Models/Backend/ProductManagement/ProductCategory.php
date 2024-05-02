<?php

namespace App\Models\Backend\ProductManagement;

use App\Models\Backend\AdditionalFeatureManagement\SiteSeo;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'parent_id',
        'name',
        'image',
        'icon_code',
        'is_featured',
        'slug',
        'order',
        'status',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'product_categories';

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($productCat){
            if (!empty($productCat->productCategories))
            {
                $productCat->productCategories->each->delete();
            }
            if (!empty($productCat->siteSeos))
            {
                $productCat->siteSeos->each->delete();
            }
        });
    }

    public static function saveOrUpdateProductCategory ($request, $id = null)
    {
        ProductCategory::updateOrCreate(['id' => $id], [
            'parent_id'     => empty($request->parent_id) ? $request->parent_id : 0,
            'name'          => $request->name,
            'icon_code'     => $request->icon_code,
            'is_featured'   => $request->is_featured == 'on' ? 1 : 0,
            'slug'          => str_replace(' ', '-', $request->name),
            'image'         => imageUpload($request->file('image'), 'product-management/product-categories/', 'product-category', '300', '200', (isset($id) ? static::find($id)->image : null)),
            'order'         => !empty($request->order) ? $request->order : 1,
            'status'        => $request->status == 'on' ? 1 : 0
        ]);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function products2()
    {
        return $this->belongsToMany(Product::class);
    }

    public function siteSeos()
    {
        return $this->hasMany(SiteSeo::class, 'parent_model_id');
//        return $this->hasMany(SiteSeo::class, 'parent_model_id')->where('model_type', 'product');
    }
}
