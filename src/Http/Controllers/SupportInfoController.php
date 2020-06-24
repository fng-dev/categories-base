<?php

namespace Fng\CategoryBase\Http\Controllers;

use App\Http\Controllers\Controller;
use Fng\CategoryBase\Models\Category;
use Fng\CategoryBase\Models\Type;
use Illuminate\Http\Request;

class SupportInfoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a new type
     *
     * @return Type
     */

    public function getInitProductInfo()
    {
        $categories = Category::all();
        $types = Type::all();

        return response()->json([
            "categories" => $categories,
            "types" => $types
        ]);
    }
}
