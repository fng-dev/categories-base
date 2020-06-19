<?php

namespace Fng\CategoryBase\Models;

use Fng\CategoryBase\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "fng_categories";

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'image',
        'category_id',
        'type_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected $hidden = [];

    /**
     *
     * Array with validation rules
     *
     * @var array
     *
     */

    protected static $rules = [
        'name' => 'string',
        'slug' => 'string',
        'icon' => 'string',
        'image' => 'string',
        'category_id' => 'exists:fng_categories,id',
        'type_id' => 'exists:fng_types,id',
    ];


    /**
     * Validation Rules
     *
     * @var array
     */

    static public function getRules(): array
    {
        return self::$rules;
    }


    /**
     * Filter fields
     *
     * @var array
     */

    protected static $fields = [
        'id',
        'name',
        'slug',
        'icon',
        'image',
        'category_id',
        'type_id',
    ];

    static public function getFields()
    {
        return collect(self::$fields);
    }

    public function category()
    {
        return $this->hasMany(Category::class)->with(['category' => function($query) {
            $query->with('type');
        }]);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'fng_category_product');
    }
}
