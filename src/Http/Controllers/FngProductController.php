<?php

namespace Fng\CategoryBase\Http\Controllers;

use App\Http\Controllers\Controller;
use Fng\CategoryBase\Models\Category;
use Fng\CategoryBase\Models\Product;
use Illuminate\Http\Request;

class FngProductController extends Controller
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
     * Create a new Product
     *
     * @return Product
     */

    public function create(Request $request)
    {
        $this->validate($request, Product::getRules());

        $product = Product::create($request->all());

        if (isset($request->category_id)) {
            $product->category()->attach($request->category_id);
            $product->category;
        }

        $product->type;

        return response()->json($product);
    }


    /**
     * Update a product
     *
     * @return Product
     */

    public function update(Request $request)
    {
        $rules = Product::getRules();
        $rules['sku'] = $rules['sku'] . ',' . $request->id;
        // return response()->json($rules);
        $this->validate($request, $rules);

        $product = Product::find($request->id);

        if ($product) {
            $product->update($request->all());

            if (isset($request->category_id)) {
                if (is_array($request->category_id)) {
                    $product->category()->sync($request->category_id);
                } else {
                    $product->category()->syncWithoutDetaching($request->category_id);
                }
            }
            $product->category;
            return response()->json($product);
        }

        return response()->json(['Product not found'], 404);
    }

    /**
     * Get a product By Id
     *
     * @return Product
     */

    public function getById(Request $request)
    {
        $product = Product::find($request->id);

        if ($product) {
            $product->type;
            $product->category;
            return response()->json($product);
        }

        return response()->json(['Product not found'], 404);
    }

    /**
     * Get all products
     *
     * @return Product
     */

    public function getAll(Request $request)
    {
        $fields = Product::getFields();
        $products = null;
        foreach ($request->all() as $key => $filters) {
            if ($products !== null) {
                if ($fields->contains($key)) {
                    $products->where($key, 'LIKE', "%{$filters}%");
                }
            } else {
                if ($fields->contains($key)) {
                    $products = Product::where($key, 'LIKE', "%{$filters}%");
                }
            }
        }

        if (isset($request->category_id)) {
            if (is_array($request->category_id)) {
                foreach($request->category_id as $id) {
                    if ($products) {
                        $products = $products->whereExists(function ($query) use ($id) {
                            $query->select('*')
                                ->from('gux_category_product')
                                ->whereRaw("category_id = '".$id."'")
                                ->whereRaw("gux_products.id = gux_category_product.product_id");
                        });
                    } else {
                        $products = Product::whereExists(function ($query) use ($id) {
                            $query->select('*')
                                ->from('gux_category_product')
                                ->whereRaw("category_id = '".$id."'")
                                ->whereRaw("gux_products.id = gux_category_product.product_id");
                        });
                    }
                }
            } else {
                if ($products) {
                    $products = $products->whereExists(function ($query) use ($request) {
                        $query->select('*')
                            ->from('gux_category_product')
                            ->whereRaw("category_id = '".$request->category_id."'")
                            ->whereRaw("gux_products.id = gux_category_product.product_id");
                    });
                } else {
                    $products = Product::whereExists(function ($query) use ($request) {
                        $query->select('*')
                            ->from('gux_category_product')
                            ->whereRaw("category_id = '".$request->category_id."'")
                            ->whereRaw("gux_products.id = gux_category_product.product_id");
                    });
                }
            }
        }

        $paginate = isset($request->paginate) ? intval($request->paginate) : 12;

        if ($products) {
            $products = $products->with(['category', 'type'])->paginate($paginate);
        } else {
            $products = Product::with(['category', 'type'])->paginate($paginate);
        }

        $products->appends($request->all())->links();

        return response()->json($products);
    }


    /**
     * delect a product
     *
     * @return Product
     */

    public function delete(Request $request)
    {
        $product = Product::find($request->id);

        if ($product) {
            $product->delete();
            return response()->json(['Product deleted'], 200);
        }

        return response()->json(['Product not found'], 404);
    }
}
