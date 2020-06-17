<?php

namespace Fng\CategoryBase\Models;

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
        'sku' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'unit' => 'string',
        'price' => 'numeric',
        'sale_price' => 'numeric',
        'discount' => 'numeric',
        'quantity' => 'integer',
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
}
