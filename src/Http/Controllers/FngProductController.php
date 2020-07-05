<?php

namespace Fng\CategoryBase\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fng\CategoryBase\Models\Gallery;
use Fng\CategoryBase\Models\Product;
use Fng\CategoryBase\Models\Category;
use Illuminate\Support\Facades\Storage;

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

        if(isset($request->image)) {
            if(is_array($request->image)) {
                foreach($request->image as $image) {
                    $url = SELF::decodeAndSendImg($image);
                    $product->images()->create([
                        'url' => $url
                    ]);
                }
            }else {
                $url = SELF::decodeAndSendImg($request->image);
                $product->images()->create([
                    'url' => $url
                ]);
            }
        }

        if (isset($request->categories)) {
            $product->category()->attach($request->categories);
            $product->category;
        }

        $product->images;

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
        $this->validate($request, $rules);

        $product = Product::find($request->id);

        if ($product) {
            $product->update($request->all());

            if(isset($request->image)) {

                foreach($product->images as $image) {
                    Storage::disk('local')->delete($image->url);
                    $image->delete();
                }

                if(is_array($request->image)) {
                    foreach($request->image as $image) {
                        $url = SELF::decodeAndSendImg($image);
                        $product->images()->create([
                            'url' => $url
                        ]);
                    }
                }else {
                    $url = SELF::decodeAndSendImg($request->image);
                    $product->images()->create([
                        'url' => $url
                    ]);
                }
            }

            if (isset($request->categories)) {
                $product->category()->sync($request->categories);
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
        $product = Product::where('id', $request->id)->where('active', 1)->get()->first();

        if ($product) {
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
                foreach ($request->category_id as $id) {
                    if ($products) {
                        $products = $products->whereExists(function ($query) use ($id) {
                            $query->select('*')
                                ->from('gux_category_product')
                                ->whereRaw("category_id = '" . $id . "'")
                                ->whereRaw("gux_products.id = gux_category_product.product_id");
                        });
                    } else {
                        $products = Product::whereExists(function ($query) use ($id) {
                            $query->select('*')
                                ->from('gux_category_product')
                                ->whereRaw("category_id = '" . $id . "'")
                                ->whereRaw("gux_products.id = gux_category_product.product_id");
                        });
                    }
                }
            } else {
                if ($products) {
                    $products = $products->whereExists(function ($query) use ($request) {
                        $query->select('*')
                            ->from('gux_category_product')
                            ->whereRaw("category_id = '" . $request->category_id . "'")
                            ->whereRaw("gux_products.id = gux_category_product.product_id");
                    });
                } else {
                    $products = Product::whereExists(function ($query) use ($request) {
                        $query->select('*')
                            ->from('gux_category_product')
                            ->whereRaw("category_id = '" . $request->category_id . "'")
                            ->whereRaw("gux_products.id = gux_category_product.product_id");
                    });
                }
            }
        }

        $paginate = isset($request->paginate) ? intval($request->paginate) : 4;

        if (isset($request->order)) {
            if (is_array($request->order)) {
                foreach ($request->order as $order) {
                    $ob = explode(",", $order);
                    if ($products !== null) {
                        $products->orderBy($ob[0], $ob[1]);
                    } else {
                        $products = Product::orderBY($ob[0], $ob[1]);
                    }
                }
            } else {
                $ob = explode(",", $request->order);
                if ($products !== null) {
                    $products->orderBy($ob[0], $ob[1]);
                } else {
                    $products = Product::orderBY($ob[0], $ob[1]);
                }
            }
        }

        if(!isset($request->active)) {
            if ($products) {
                $products = $products->actives();
            } else {
                $products = Product::actives();
            }
        }

        if ($products) {
            $products = $products->with(['category', 'images'])->paginate($paginate);
        } else {
            $products = Product::with(['category', 'images'])->paginate($paginate);
        }

        $products->appends($request->all())->links();

        $response = array_merge($products->toArray(), [
            "categories" => Category::all()->toArray()
        ]);

        return response()->json($response);
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

    public static function decodeAndSendImg($data)
    {

        $type = "";

        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {

            $data = substr($data, strpos($data, ',') + 1);

            $type = strtolower($type[1]);

            $data = base64_decode($data);

            if ($data === false) {
                return 'Base64 Failed';
            }
        } else {

            return 'Did not match data URI with image data';
        }

        $name = SELF::randomString() . "." . $type;

        Storage::disk('local')->put($name, $data);

        return $name;
    }

    public static function randomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 50; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }
}
