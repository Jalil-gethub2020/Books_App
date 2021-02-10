<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Product as ProductResource;
use App\Http\Controllers\API\BaseController as BaseController;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $this->sendResponse(
            ProductResource::collection($products),
            'All Products Sent'
        );
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $product = Product::create($input);
        return $this->sendResponse(new ProductResource($product), 'Product Created Successfully');
    }


    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return $this->sendError('Product Not Found');
        }

        return $this->sendResponse(new ProductResource($product), 'Product Successfully Found');
    }


    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->price = $input['price'];
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product Successfully Updated');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse(new ProductResource($product), 'Product Successfully Deleted');
    }
}
