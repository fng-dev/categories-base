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

    public function create(Request $request)
    {
        $this->validate($request, Category::getRules());

        $category = Category::create($request->all());

        return response()->json($category);
    }

    public function update(Request $request)
    {
        $this->validate($request, Category::getRules());

        $category = Category::find($request->id);

        if ($category) {
            $category->update($request->all());
        }

        return response()->json($category);
    }

    public function getById(Request $request)
    {
        $category = Category::find($request->id);

        if ($category) {
            return response()->json($category);
        }

        return response()->json(['Category not found'], 404);
    }

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

    public function delete(Request $request)
    {
        $category = Category::find($request->id);

        if ($category) {
            $category->delete();
            return response()->json(['Category deleted'], 404);
        }

        return response()->json(['Category not found'], 404);
    }
}
