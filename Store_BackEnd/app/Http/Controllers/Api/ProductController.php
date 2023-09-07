<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

class ProductController extends Controller
{
    /**
     * this function to get all products in database
     * @return JsonResponse product
     */
    public function index()
    {
        // Handling the process
        try {
            // Get all products
            $products = Product::all();

            // Check if there are products
            if ($products->isEmpty()) {
                return response()->json([
                    'message' => __('No products found'),
                ], 404); // Use an appropriate HTTP status code for no data found
            }

            // Return products
            return response()->json([
                'products' => $products,
                'message' => __('Successfully retrieved products'),
            ], 200);
        }catch (Exception $e){
            return response()->json([
                'message' => __('database connection error')
            ], 500);
        }
    }

    /**
     * this function to get all products in database
     * @return JsonResponse product
     */
    public function category_products(Request $request)
    {
        // Handling the process
        try {
            // Get all products
            $products = Product::firstOrFail('category_Id', $request->id)->get();

            // Check if there are products
            if (empty($products)) {
                return response()->json([
                    'message' => __('No products found'),
                ], 404); // Use an appropriate HTTP status code for no data found
            }

            // Return products
            return response()->json([
                'products' => $products,
                'message' => __('Successfully retrieved products'),
            ], 200);
        }catch (Exception $e){
            return response()->json([
                'message' => __('database connection error')
            ], 500);
        }
    }

    /**
     * Create new product
     * @param Request $request the product data
     * @return JsonResponse
     */
    public function store(Request $request){

        // Validate inputs
        $request->validate([
            'category_id' => ['required', 'string','exists:categories,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'product_description' => ['nullable', 'string', 'max:1000'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'product_img' => ['required','image', 'min:2048','mimes:jpeg,png,gif,bmp']
        ]);

        // Handling the process
        try {
            // To store product image path
            $product_img=null;
            // Handle the image upload if provided
            if($request->hasFile('product_img')){
                $file_extension=$request->file('product_img')->getClientOriginalExtension();
                $file_name=Hash::make(now()).'.'.$file_extension;
                $path='images\product_image';
                $request->file('product_img')->move(public_path($path),$file_name);
                $product_img=$path.'\\'.$file_name;
            }
            // Create new prodict
            $product = Product::create([
                'id'=>Hash::make(now()),
                'category_id' => $request->category_id,
                'product_name' =>$request->product_name,
                'product_description' =>$request->product_description,
                'quantity' => $request->quantity,
                'price'=>$request->price,
                'product_img'=>$product_img
            ]);
            // Check if create success
            if(!$product){

                return response()->json([
                    'message'=>__('created failed')
                ],550);
            }

            // Get the category of product
            $category=Category::findOrFail($product->category_id);
            // Update the product quantity  in the category
            $category->product_quantity_in_category=$category->product_quantity_in_category+1;

            $state=$category->save();

            if(!$state){
                return response()->json([
                    'message'=>__('failed update product quantity in category')
                ],200);
            }

            return response()->json([
                'message'=>__('created successfully')
            ],200);

        }catch (Exception $e){
            return response()->json([
                'message' => __('database connection error')
            ], 500);
        }

    }
}
