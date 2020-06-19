<?php

namespace Fng\CategoryBase\Http\Controllers;

use App\Http\Controllers\Controller;
use Fng\CategoryBase\Models\Type;
use Illuminate\Http\Request;

class FngTypeController extends Controller
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

    public function create(Request $request)
    {
        $this->validate($request, Type::getRules());

        $type = Type::create($request->all());

        return response()->json($type);
    }

    /**
     * Update a type
     *
     * @return Type
     */

    public function update(Request $request)
    {
        $this->validate($request, Type::getRules());

        $type = Type::find($request->id);

        if ($type) {
            $type->update($request->all());
            return response()->json($type);
        }

        return response()->json(['Type not found'], 404);
    }

    /**
     * get a type by id
     *
     * @return Type
     */

    public function getById(Request $request)
    {
        $type = Type::find($request->id);

        if ($type) {
            $type->product;
            return response()->json($type);
        }

        return response()->json(['Type not found'], 404);
    }

    /**
     * get all types
     *
     * @return Type
     */

    public function getAll(Request $request)
    {
        $fields = Type::getFields();
        $types = null;
        foreach ($request->all() as $key => $filters) {
            if ($types !== null) {
                if ($fields->contains($key)) {
                    $types->where($key, 'LIKE', "%{$filters}%");
                }
            } else {
                if ($fields->contains($key)) {
                    $types = Type::where($key, 'LIKE', "%{$filters}%");
                }
            }
        }

        $paginate = isset($request->paginate) ? intval($request->paginate) : 12;

        if ($types) {
            $types = $types->with('product')->paginate($paginate);
        } else {
            $types = Type::with('product')->paginate($paginate);
        }
        $types->appends($request->all())->links();

        return response()->json($types);
    }

    /**
     * delete a type
     *
     * @return Type
     */

    public function delete(Request $request)
    {
        $type = Type::find($request->id);

        if ($type) {
            $type->delete();
            return response()->json(['Type deleted'], 200);
        }

        return response()->json(['Type not found'], 404);
    }
}
