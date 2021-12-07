<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seen;
use Illuminate\Support\Facades\Validator;

class products extends Controller
{
    // Show All Products
    public function index(){
        // Get The Products by newest
        $product = Product::orderBy('created_at' , 'desc')->get([
            'productName',
            'description',
            'expireDate',
            'oldPrice',
            'quantity',
            'dateOne',
            'priceOne',
            'dateTwo',
            'priceTwo',
            'dateThree',
            'priceThree',
            'imgUrl',
            'ownerId',
            'seens'
        ]);
        // $curPrice = calcPrice()
        $jsonContent = json_decode($product , true);
        return response()->json([
            'message' => "The List Of Product : ",
            'Products' => $jsonContent
        ]);
    }
    // Store new Product
    public function store(Request $request){
        //The validation
        $valid = Validator::make($request->all() , [
            'productName' => ['required' , 'string'],
            'description' => ['string'],
            'expireDate' => ['required'],
            'oldPrice' => ['required'],
            'quantity' => ['required'],
            'category' => ['required' , 'string'],
        ]);
        if($valid->fails())
            return response()->json($valid->errors()->all());
        //creat a new row in product table
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
        if(!Product::where('id',$productId)->exists())
            return response()->json(['message' => 'Invalid ID'],404);
        // Handling the Seen
        // Don't Forget to get the userId from the Token
        $this->seen($productId , 1);
        // Get The Product
        $product = Product::where('id',$productId)->get([
            'productName',
            'description',
            'expireDate',
            'oldPrice',
            'quantity',
            'dateOne',
            'priceOne',
            'dateTwo',
            'priceTwo',
            'dateThree',
            'priceThree',
            'imgUrl',
            'ownerId',
            'seens'
        ]);
        $jsonContent = json_decode($product , true);
        return response()->json([
            "Products" => $jsonContent
        ]);
    }

    public function seen($productId , $userId){
        //Get the Product Views
        $seen = Seen::where('productId',$productId)->get();
        $jsonContent = json_decode($seen , true);
        $flag = true;
        foreach ($jsonContent as $array){
            //check if the user had seen the product before
            if($array['userId'] == $userId){
                $flag = false;
                break;
            }
        }
        if($flag){
            //Edit the Seen on the product table
            $product = Product::where('id',$productId)->get();
            $counter = $product[0]['seens'];
            Product::where('id',$productId)->update(['seens' => $counter+1]);
            // store the seen
            $seen = new Seen();
            $seen->productId = $productId;
            $seen->userId = $userId;
            $seen->save();
        }
    }

    public function destroy($productId){
        // Get the product where the id is equal to productId
        $product = Product::find($productId);
        if(!$product)
            return response()->json(['message' => 'Invalid ID']);
        // Delete it
        $product->delete();
        return response()->json(['message' => 'The Product Has Been Delete successfully']);
    }

    public function update(Request $request,$productId){
        $valid = Validator::make($request->all() , [
            'productName' => ['string'],
            'description' => ['string'],
            'category' => ['string'],
        ]);
        if($valid->fails())
            return response()->json($valid->errors()->all());

        $product = Product::find($productId);
        $product->productName = !empty($request->productName) ?$request->productName : $product->productName;
        $product->description = !empty($request->description) ?$request->description : $product->description;
        $product->oldPrice = !empty($request->oldPrice) ?$request->oldPrice : $product->oldPrice;
        $product->imgUrl = !empty($request->imgUrl) ?$request->imgUrl : $product->imgUrl;
        $product->quantity = !empty($request->quantity) ?$request->quantity : $product->quantity;
        $product->category = !empty($request->category) ?$request->category : $product->category;
        $product->dateOne = !empty($request->dateOne) ?$request->dateOne : $product->dateOne;
        $product->priceOne = !empty($request->priceOne) ?$request->priceOne : $product->priceOne;
        $product->dateTwo = !empty($request->dateTwo) ?$request->dateTwo : $product->dateTwo;
        $product->priceTwo = !empty($request->priceTwo) ?$request->priceTwo : $product->priceTwo;
        $product->dateThree = !empty($request->dateThree) ?$request->dateThree : $product->dateThree;
        $product->priceThree = !empty($request->priceThree) ?$request->priceThree : $product->priceThree;
        $product->save();

        return response()->json(['message' => 'The Product Has Been Edit Successfully']);
    }

    public function myProducts($userId){
        $product = Product::where('ownerId',$userId)->get([
            'productName',
            'description',
            'expireDate',
            'oldPrice',
            'quantity',
            'dateOne',
            'priceOne',
            'dateTwo',
            'priceTwo',
            'dateThree',
            'priceThree',
            'imgUrl',
            'ownerId',
            'seens'
        ]);
        $jsonContent = json_decode($product , true);
        if(!$jsonContent)
            return response()->json(['message' => 'Sorry , You Dont Have Any Products']);
        else
            return response()->json(['My Products : ' => $jsonContent]);
    }
}

// Image URL
// Model Binding
// Token
// Login
// What to send
