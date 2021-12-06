<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class products extends Controller
{

    public function index(){
        // Get The Products by newest
        $product = Product::orderBy('created_at' , 'desc')->get([
            'productName' ,
            'description' ,
            'expireDate' ,
            'oldPrice' ,
            'quantity' ,
            'dateOne' ,
            'priceOne' ,
            'dateTwo' ,
            'priceTwo' ,
            'dateThree' ,
            'priceThree',
            'imgUrl' ,
            'Seens'
        ]);

        // $curPrice = calcPrice()

        $jsonContent = json_decode($product , true);

        return response()->json([
            'message' => "The List Of Product : ",
            'Products' => $jsonContent
        ]);
    }

    public function store(Request $request){

        $valid = Validator::make($request->all() , [
            'productName' => ['required' , 'string'],
            'description' => ['string'],
            'expireDate' => ['required'],
            'oldPrice' => ['required'],
            'quantity' => ['required'],
            'category' => ['required' , 'string'],
        ]);

        if($valid->fails()){
            return response()->json($valid->errors()->all());
        }

        $product = new Product();

        $product->productName = $request->input('productName');
        $product->description = $request->input('description');
        $product->expireDate = $request->input('expireDate');
        $product->oldPrice = $request->input('oldPrice');
        $product->imgUrl = $request->input('imgUrl');
        $product->quantity = $request->input('quantity');
        $product->category = $request->input('category');
        // Don't Forget The Owner ID from the Token in the Header
        $product->ownerId = $request->header('ownerId');
        $product->dateOne = $request->input('dateOne');
        $product->priceOne = $request->input('priceOne');
        $product->dateTwo = $request->input('dateTwo');
        $product->priceTwo = $request->input('priceTwo');
        $product->dateThree = $request->input('dateThree');
        $product->priceThree = $request->input('priceThree');

        $product->save();

        return response()->json(['message' => 'The Product has benn added successfully'],200);
    }


    public function show($productId){
        // Get The Product
        $product = Product::find($productId)->get([
            'productName' ,
            'description' ,
            'expireDate' ,
            'oldPrice' ,
            'quantity' ,
            'dateOne' ,
            'priceOne' ,
            'dateTwo' ,
            'priceTwo' ,
            'dateThree' ,
            'priceThree',
            'imgUrl' ,
            'Seens'
        ]);

        // Handling Wrong ID
        if(!$product){
            return response()->json(['message' => 'Invalid ID']);
        }

        // Don't Forget to handel the variables that you want to send
        $jsonContent = json_decode($product , true);
        return response()->json([
            "Products" => $jsonContent
        ]);
    }

    public function search(Request $request){
        $valid = Validator::make($request->all() , [
            'searchBy' => ['required' , 'string'],
            'search' => ['required' , 'string'],
        ]);

        if($valid->fails()){
            return response()->json($valid->errors()->all());
        }
        $searchBy = $request->input('searchBy');
        $search = $request->input('search');

        $product = Product::where($searchBy , $search)->get([
            'productName' ,
            'description' ,
            'expireDate' ,
            'oldPrice' ,
            'quantity' ,
            'dateOne' ,
            'priceOne' ,
            'dateTwo' ,
            'priceTwo' ,
            'dateThree' ,
            'priceThree',
            'imgUrl' ,
            'Seens'
        ]);

        $jsonContent = json_decode($product , true);
        return response()->json([
            "Products" => $jsonContent
        ]);
    }


    public function destroy($productId){
        // Get the product where the id is equal to productId
        $product = Product::find($productId);

        if(!$product){
            return response()->json(['message' => 'Invalid ID']);
        }

        // Delete it
        $product->delete();
        return response()->json(['message' => 'The Product Has Been Delete successfully']);
    }

    public function update(Request $request,$productId){

    }
}

// Image URL
// Model Binding
// Token
// Login
// What to send
// Update

