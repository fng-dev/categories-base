<?php

namespace Fng\CategoryBase\Models;

use Fng\CategoryBase\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = "gux_types";

    protected $fillable = [
        'name',
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
        'name' => 'required|string',
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

    public function category()
    {
        return $this->hasMany(Category::class);
    }
}
