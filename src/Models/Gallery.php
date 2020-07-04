<?php

namespace Fng\CategoryBase\Models;

use Fng\CategoryBase\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = "gux_product_galery";

    protected $fillable = [
        'name',
        'url',
        'product_id'
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
    ];

    static public function getFields()
    {
        return collect(self::$fields);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
