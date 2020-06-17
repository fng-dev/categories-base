<?php

namespace Fng\CategoryBase\Models;

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
        'category_id' => 'integer',
        'type_id' => 'integer',
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
}
