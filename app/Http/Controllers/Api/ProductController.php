<?php

namespace App\Http\Controllers\Api;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //get all data
    public function index()
    {
        $products = Product::get();
        if($products->count() > 0){
            //ProductResource it return data in json format
            return ProductResource::collection($products);

        }else{
            return response()->json(['message'=>'Record not availble!'], 200);
        }
    }

    //store function
    public function store(Request $request)
    {
        //check validated requested data input
        $validator=Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'description'=>'required',
            'price'=>'required|integer',
        ]);
       
        if($validator->fails()){
            return response()->json([
                'message'=>'All field are mandatory',
                'error'=>$validator->messages(),
            ],422);
        }

        //insert data
        $product=Product::create([

            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price
        ]);
        //display data in json format and message
        return response()->json(['message'=>'product created successfully!',
        'data'=>new ProductResource($product)
        ],200);
    }

    //show function display specific data using id
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    //update function
    public function update(Request $request, Product $product)
    {
         //check validated requested data input
         $validator=Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'description'=>'required',
            'price'=>'required|integer',
        ]);
       
        if($validator->fails()){
            return response()->json([
                'message'=>'All field are mandatory',
                'error'=>$validator->messages(),
            ],422);
        }

        //update data
        $product->update([

            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price
        ]);
        //display data in json format and message
        return response()->json(['message'=>'product update successfully!',
        'data'=>new ProductResource($product)
        ],200);
    }
    

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message'=>'Product Deleted Successfully',
    ],200);
    }
}
