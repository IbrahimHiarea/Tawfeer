<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class products extends Controller
{

    // private function calcPrice($price1 , $price2 , $price3 , $date1 , $date2 , $date3){
    //     //the Curent Date
    //     $curDay = date('j');
    //     $curMonth = date('n');
    //     $curYear = date('Y');


    // }
    public function index(){
        // Get The Products by newest
        $product = Product::orderBy('created_at' , 'desc')->get();

        // $curPrice = calcPrice()

        $jsoncontnet = json_decode($product , true);
        return response()->json([
            'messaeg' => "The List Of Product : ",
            'Products' => $jsoncontnet
        ]);
    }

    public function store(Request $request){

        $valid = Validator::make($request->all() , [
            'name' => ['required' , 'string'],
            'description' => ['string'],
            'expiryDate' => ['required'],
            'mainPrice' => ['required'],
            'quantity' => ['required'],
            'category' => ['required' , 'string'],
        ]);

        if($valid->fails()){
            return response()->json($valid->errors()->all());
        }

        $product = new Product();

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->expiryDate = $request->input('expiryDate');
        $product->mainPrice = $request->input('mainPrice');
        $product->imgUrl = $request->input('imgUrl');
        $product->quantity = $request->input('quantity');
        $product->category = $request->input('category');
        // Don't Forget The Owner ID from the Token in the Header
        $product->ownerId = $request->header('ownerId');
        $product->date1 = $request->input('date1');
        $product->price1 = $request->input('price1');
        $product->date2 = $request->input('date2');
        $product->price2 = $request->input('price2');
        $product->date3 = $request->input('date3');
        $product->price3 = $request->input('price3');

        $product->save();

        return response()->json(['message' => 'The Product has benn added successfully'],200);
    }


    public function show($productId){
        // Get The Product
        $product = Product::find($productId);

        // Handling Wrong ID
        if(!$product){
            return response()->json(['message' => 'Invalid ID']);
        }

        // Don't Forget to handel the variables that you want to send
        $jsoncontnet = json_decode($product , true);
        return response()->json([
            "Products" => $jsoncontnet
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

        $product = Product::where($searchBy , $search)->get();
        $jsoncontnet = json_decode($product , true);
        return response()->json([
            "Products" => $jsoncontnet
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

