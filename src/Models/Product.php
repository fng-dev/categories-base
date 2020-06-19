<?php

namespace Fng\CategoryBase\Models;

use Fng\CategoryBase\Models\Type;
use Fng\CategoryBase\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "fng_products";

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'unit',
        'price',
        'sale_price',
        'discount',
        'quantity',
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
        'sku' => 'string|unique:fng_products,sku',
        'name' => 'string|max:190',
        'slug' => 'string|max:190',
        'description' => 'string',
        'unit' => 'string|max:190',
        'price' => 'numeric',
        'sale_price' => 'numeric',
        'discount' => 'numeric',
        'quantity' => 'integer',
        'category_id' =>'exists:fng_categories,id',
        'type_id' =>'required|exists:fng_types,id'
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
        'sku',
        'name',
        'slug',
        'description',
        'unit',
        'price',
        'sale_price',
        'discount',
        'quantity',
        'type_id'
    ];

    static public function getFields()
    {
        return collect(self::$fields);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'fng_category_product');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
