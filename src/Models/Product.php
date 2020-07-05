<?php

namespace Fng\CategoryBase\Models;

use Fng\CategoryBase\Models\Type;
use Fng\CategoryBase\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "gux_products";

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
        'active'
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
        'sku' => 'string|unique:gux_products,sku',
        'name' => 'string|max:190',
        'slug' => 'string|max:190',
        'description' => 'string',
        'unit' => 'string|max:190',
        'price' => 'numeric',
        'sale_price' => 'numeric|nullable',
        'discount' => 'numeric|nullable',
        'quantity' => 'integer',
        'categories' =>'nullable|array',
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
    ];

    static public function getFields()
    {
        return collect(self::$fields);
    }

    public function setSlugAttribute($value) {
        $this->attributes['slug'] = str_replace(" ", "_", strtolower($value));
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'gux_category_product');
    }

    public function images()
    {
        return $this->hasMany(Gallery::class, 'product_id', 'id');
    }

    public function scopeActives($query)
    {
        return $query->where('active', 1);
    }
}
