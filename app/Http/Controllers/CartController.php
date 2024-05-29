<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function addToCart(Request $request) {
        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        if (Cart::count() > 0) {
            //echo "Product already in cart";
            // Produk ketemu di keranjang
            // Cek produk ini sudah dikeranjang atau belum
            // Return as message bahwa produk sudah ditambahkan di keranjang Anda
            // Jika produk tidak ditemukan di keranjang, tambahkan produk di keranjang

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty
                ($product->product_images)) ? $product->product_images->first() : '']);

                $status = true;
                $message = '<strong>'.$product->title.'</strong> ditambah di keranjang.';
                session()->flash('success',$message);
            
            } else {
                $status = false;
                $message = $product->title." Sudah ditambah di keranjang";
            }


        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>'.$product->title.'</strong> ditambah di keranjang.';
            session()->flash('success',$message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);

    }

    public function cart() {
        $cartContent = Cart::content();
        //dd($cartContent);
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request) {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        // check qty available in stock

        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart update sucessfully';  
                $status = true;
                session()->flash('success',$message);
            } else {
                $message = 'Requested qty('.$qty.') not available in stock.';
                $status = false;
                session()->flash('error',$message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart update sucessfully';  
            $status = true;
            session()->flash('success',$message);
        }

        session()->flash('success', $message);
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request) {
        
        $itemInfo = Cart::get($request->rowId);

        if($itemInfo == null) {
            $errorMessage = 'Tidak ada item di keranjang';
            session()->flash('error',$errorMessage);
            
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }
        Cart::remove($request->rowId);
        
        $message = 'Item berhasil dihapus dari keranjang.';
        
        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}