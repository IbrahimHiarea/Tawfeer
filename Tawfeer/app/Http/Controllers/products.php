<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seen;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class products extends Controller
{
    // Show All Products
    public function index(){
        // Get The Products by newest
        $product = Product::orderBy('created_at' , 'desc')->get();
        //calc price
        foreach ($product as $array){
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            $discount = array(
                $array->firstDate => $array->firstDiscount,
                $array->secondDate => $array->secondDiscount,
                $array->thirdDate => $array->thirdDiscount
            );
            $time = array($array->firstDate , $array->secondDate , $array->thirdDate);
            rsort($time);
            foreach ($time as $date){
                if($currentDate >= $date){
                    $price = $array->oldPrice - (($discount[$date]/100)*$array->oldPrice);
                    Product::where('id' , $array->id)->update(['currentPrice' => $price]);
                    break;
                }
            }
            if($currentDate >= $array->expireDate){
                $product = Product::find($array->id);
                $product->delete();
            }
        }
        $product = Product::orderBy('created_at' , 'desc')->get();
        return response()->json([
            'message' => "The List Of Product : ",
            'Products' => $product
        ],200);
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
            'firstDate' => ['date'],
            'secondDate' => ['date'],
            'thirdDate' => ['date'],
            'img' => ['mimes:jpg,png,jpeg'],
        ]);
        if($valid->fails())
            return response()->json($valid->errors()->all(),400);

        //creat a new row in product table
        $product = new Product();
        $product->productName = $request->input('productName');
        $product->description = $request->input('description');
        $product->expireDate = $request->input('expireDate');
        $product->oldPrice = $request->input('oldPrice');
        $product->currentPrice = $request->input('oldPrice');
        $product->quantity = $request->input('quantity');
        $product->ownerId = auth()->user()->id;
        $product->firstDate = $request->input('firstDate');
        $product->firstDiscount = $request->input('firstDiscount');
        $product->secondDate = $request->input('secondDate');
        $product->secondDiscount = $request->input('secondDiscount');
        $product->thirdDate = $request->input('thirdDate');
        $product->thirdDiscount = $request->input('thirdDiscount');
        // Handling the Category
        $name = $request->input('category');
        if(!Category::where('name',$name)->exists()){
            $category = new Category();
            $category->name = $name;
            $category->save();
            $product->categoryId = $category->id;
        }
        else{
            $category = Category::where('name',$name)->get();
            $product->categoryId = $category[0]['id'];
        }
        $product->category = $request->input('category');
        // handling the image
        if($request->hasFile('img')){
            //get the image
            $img = $request->file('img');
            //image Name
            $imgName = time() . '-' . $product->productName . '.' . $request->file('img')->extension();
            //store the img in public folder
            $img->move(public_path('storage/app/public/img'),$imgName);
            $product->imgUrl = "storage/app/public/img/$imgName";
        }
        $product->save();

        // Seen
        $seen = new Seen();
        $seen->productId = $product->id;
        $seen->userId = auth()->user()->id;
        $seen->save();

        return response()->json(['message' => 'The Product has benn added successfully'],200);
    }

    // Show Product
    public function show($productId){
        // check if Wrong id
        if(!Product::where('id',$productId)->exists())
            return response()->json(['message' => 'Invalid ID'],400);

        // Handling the Seen
        $userId = auth()->user()->id;
        $this->seen($productId , $userId); // call seen Function

        // Get The Product
//        $product = Product::where('id',$productId)->get();
        $product = Product::find($productId);

        return response()->json([
            "Products" => $product
        ],200);
    }

    // calc the seen
    public function seen($productId , $userId){
        //Get the Product Views
        $seen = Seen::where('productId',$productId)->get();

        $flag = true;
        foreach ($seen as $seenArray){
            //check if the user had seen the product before
            if($seenArray->userId == $userId){
                $flag = false;
                break;
            }
        }
        if($flag){
            //Edit the Seen on the product table
            $product = Product::find($productId);
            $product->seens = $product->seens + 1;
            $product->save();
            // store the seen
            $seen = new Seen();
            $seen->productId = $productId;
            $seen->userId = $userId;
            $seen->save();
        }
    }

    // delete product
    public function destroy($productId){
        // check if Wrong id
        if(!Product::where('id',$productId)->exists())
            return response()->json(['message' => 'Invalid ID'],400);

        // Get the product where the id is equal to productId
        $product = Product::find($productId);

        // check if the user has this product
        $userId = auth()->user()->id;
        if($product->ownerId != $userId)
            return response()->json(['message' => 'You cant delete this product'] , 400);

        // Delete it
        $product->delete();
        return response()->json(['message' => 'The Product Has Been Delete successfully'],200);
    }

    // update on product
    public function update(Request $request,$productId){
        // check if Wrong id
        if(!Product::where('id',$productId)->exists())
            return response()->json(['message' => 'Invalid ID'],400);

        $valid = Validator::make($request->all() , [
            'productName' => ['string'],
            'description' => ['string'],
            'category' => ['string'],
        ]);
        if($valid->fails())
            return response()->json($valid->errors()->all(),400);

        $userId = auth()->user()->id;
        $product = Product::find($productId);
        // check ig this user has this product
        if($userId != $product->ownerId)
            return response()->json(['message' => 'You cant update this product'] , 400);

        $product->productName = !empty($request->productName) ? $request->productName : $product->productName;
        $product->description = !empty($request->description) ? $request->description : $product->description;
        $product->oldPrice = !empty($request->oldPrice) ? $request->oldPrice : $product->oldPrice;
        $product->quantity = !empty($request->quantity) ? $request->quantity : $product->quantity;
        $product->firstDate = !empty($request->firstDate) ? $request->firstDate : $product->firstDate;
        $product->firstDiscount = !empty($request->firstDiscount) ? $request->firstDiscount : $product->firstDiscount;
        $product->secondDate = !empty($request->secondDate) ? $request->secondDate : $product->secondDate;
        $product->secondDiscount = !empty($request->secondDiscount) ? $request->secondDiscount : $product->secondDiscount;
        $product->thirdDate = !empty($request->thirdDate) ? $request->thirdDate : $product->thirdDate;
        $product->thirdDiscount = !empty($request->thirdDiscount) ? $request->thirdDiscount : $product->thirdDiscount;
        // Category
        if(!empty($request->category)){
            $name = $request->input('category');
            if(!Category::where('name',$name)->exists()){
                $category = new Category();
                $category->name = $name;
                $category->save();
                $product->categoryId = $category->id;
            }
            else{
                $category = Category::where('name',$name)->get();
                $product->categoryId = $category[0]['id'];
            }
            $product->category = $request->input('category');
        }
        // Image
        if($request->hasFile('img')){
            //get the image
            $img = $request->file('img');
            //image Name
            $imgName = time() . '-' . $product->productName . '.' . $request->file('img')->extension();
            //store the img in public folder
            $img->move(public_path('storage/app/public/img'),$imgName);
            $product->imgUrl = "storage/app/public/img/$imgName";
        }

        $product->save();

        return response()->json(['message' => 'The Product Has Been Edit Successfully'],200);
    }

    // show user product
    public function myProducts(){
        $userId = auth()->user()->id;
        $product = Product::where('ownerId',$userId)->get();
        if(!isEmpty($product))
            return response()->json(['message' => 'Sorry , You Dont Have Any Products'],200);
        else
            return response()->json(['My Products : ' => $product],200);
    }
}


//[
//    'productName',
//    'description',
//    'expireDate',
//    'oldPrice',
//    'quantity',
//    'firstDate',
//    'firstDiscount',
//    'secondDate',
//    'secondDiscount',
//    'thirdDate',
//    'thirdDiscount',
//    'imgUrl',
//    'ownerId',
//    'seens'
//]


//if($currentDate >= $array->expireDate){
//    $product = Product::find($array->id);
//    $product->delete();
//}
//            if($currentDate >= $array->expireDate){
//                $product = Product::find($array->id);
//                $product->delete();
//            }
//            else if($currentDate >= $array->thirdDate){
//                $price = $array->oldPrice - (($array->thirdDiscount/100)*$array->oldPrice);
//                Product::where('id' , $array->id)->update(['currentPrice' => $price]);
//            }
//            else if($currentDate >= $array->secondDate){
//                $price = $array->oldPrice - (($array->secondDiscount/100)*$array->oldPrice);
//                Product::where('id' , $array->id)->update(['currentPrice' => $price]);
//            }
//            else if($currentDate >= $array->firstDate){
//                $price = $array->oldPrice - (($array->firstDiscount/100)*$array->oldPrice);
//                Product::where('id' , $array->id)->update(['currentPrice' => $price]);
//            }

