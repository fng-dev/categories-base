<?php

namespace Fng\CategoryBase\Http\Controllers;

use App\Http\Controllers\Controller;
use Fng\CategoryBase\Models\Category;
use Illuminate\Http\Request;

class FngCategoryController extends Controller
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
     * Create a new category
     *
     * @return Category
     */

    public function create(Request $request)
    {
        $this->validate($request, Category::getRules());

        $category = Category::create($request->all());

        if (isset($request->product_id)) {
            $category->product()->attach($request->product_id);
            $category->product;
        }

        return response()->json($category);
    }

    /**
     * Update a category
     *
     * @return Category
     */

    public function update(Request $request)
    {
        $this->validate($request, Category::getRules());

        $category = Category::find($request->id);

        if ($category) {
            $category->update($request->all());

            if (isset($request->product_id)) {
                if (is_array($request->product_id)) {
                    $category->product()->sync($request->product_id);
                } else {
                    $category->product()->syncWithoutDetaching($request->product_id);
                }
                $category->product;
            }

            return response()->json($category);
        }

        return response()->json(['Category not found'], 404);
    }

    /**
     * get a category by id
     *
     * @return Category
     */

    public function getById(Request $request)
    {
        $category = Category::find($request->id);

        if ($category) {
            $category->product;
            return response()->json($category);
        }

        return response()->json(['Category not found'], 404);
    }

    /**
     * get all categories
     *
     * @return Category
     */

    public function getAll(Request $request)
    {
        $fields = Category::getFields();
        $categories = null;
        foreach ($request->all() as $key => $filters) {
            if ($categories !== null) {
                if ($fields->contains($key)) {
                    $categories->where($key, 'LIKE', "%{$filters}%");
                }
            } else {
                if ($fields->contains($key)) {
                    $categories = Category::where($key, 'LIKE', "%{$filters}%");
                }
            }
        }

        $paginate = isset($request->paginate) ? intval($request->paginate) : 12;

        if ($categories) {
            $categories = $categories->paginate($paginate);
        } else {
            $categories = Category::paginate($paginate);
        }
        $categories->appends($request->all())->links();

        return response()->json($categories);
    }

    /**
     * Get a category if is a father category (caegory_id = null)
     *
     * @return Category
     */

    public function getFather(Request $request)
    {
        $fields = Category::getFields();
        $categories = null;
        foreach ($request->all() as $key => $filters) {
            if ($categories !== null) {
                if ($fields->contains($key)) {
                    $categories->where($key, 'LIKE', "%{$filters}%");
                }
            } else {
                if ($fields->contains($key)) {
                    $categories = Category::where($key, 'LIKE', "%{$filters}%");
                }
            }
        }

        $paginate = isset($request->paginate) ? intval($request->paginate) : 12;

        if ($categories) {
            $categories = $categories->with('type')
                ->with(['category' => function ($query) {
                    $query->with('type');
                }])
                ->whereNull('category_id')
                ->paginate($paginate);
        } else {
            $categories = Category::with('type')
                ->with(['category' => function ($query) {
                    $query->with('type');
                }])->whereNull('category_id')
                ->paginate($paginate);
        }
        $categories->appends($request->all())->links();

        return response()->json($categories);
    }

    /**
     * delete a category
     *
     * @return Category
     */

    public function delete(Request $request)
    {
        $category = Category::find($request->id);

        if ($category) {
            $category->delete();
            return response()->json(['Category deleted'], 200);
        }

        return response()->json(['Category not found'], 404);
    }
}
