<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as Image;

class ProductController extends Controller
{
    public function index(Request $request) 
    {
        $products = Product::latest('id')->with('product_images');

        if ($request->get('keyword') != "") {
            $products = $products->where('title','like','%'.$request->keyword.'%');
        }

        $products = $products->paginate(10);
        //dd($products);
        $data['products'] = $products;
        return view('admin.products.list',$data);
    }

    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {

        // dd($request->image_array);
        // exit();
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        // Pesan kesalahan kustom
        $messages = [
            'title.required' => 'Judul wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'sku.required' => 'SKU wajib diisi.',
            'track_qty.required' => 'Track Quantity wajib diisi.',
            'track_qty.in' => 'Track Quantity harus Yes atau No.',
            'category.required' => 'Kategori wajib diisi.',
            'category.numeric' => 'Kategori harus berupa angka.',
            'is_featured.required' => 'Status featured wajib diisi.',
            'is_featured.in' => 'Status featured harus Yes atau No.',
            'qty.required' => 'Jumlah wajib diisi jika Track Quantity adalah Yes.',
            'qty.numeric' => 'Jumlah harus berupa angka.',
        ];

        $validator = Validator::make($request->all(),$rules, $messages);

        if ($validator->passes()) {

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->category_id = $request->category;
            $product->status = $request->status;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();

            // Simpan Gambar Gallery
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); // seperti jpg,gif,png dll

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    // Generate Thumbnail Produk

                    // Gambar Besar
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    // Gambar Kecil
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);

                }
            }

            Session::flash('success', 'Produk berhasil disimpan');

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil disimpan'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) 
    {
        $product = Product::find($id);

        if (empty($product)) {
            return redirect()->route('products.index')->with('error','Produk tidak ditemukan');
        }
 
        // Fetch Product Image
        $productImages = ProductImage::where('product_id',$product->id)->get();

        $subCategories = SubCategory::where('category_id',$product->category_id)->get();

        $relatedProducts = [];
        // fetch related products
        if ($product->related_products != '') {
            $productArray = explode(',',$product->related_products);

            $relatedProducts = Product::whereIn('id',$productArray)->with('product_images')->get();
        }

        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['relatedProducts'] = $relatedProducts;

        return view('admin.products.edit',$data);
    }

    public function update($id, Request $request) {

        $product = Product::find($id);
        
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        // Pesan kesalahan kustom
        $messages = [
            'title.required' => 'Judul wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'sku.required' => 'SKU wajib diisi.',
            'track_qty.required' => 'Track Quantity wajib diisi.',
            'track_qty.in' => 'Track Quantity harus Yes atau No.',
            'category.required' => 'Kategori wajib diisi.',
            'category.numeric' => 'Kategori harus berupa angka.',
            'is_featured.required' => 'Status featured wajib diisi.',
            'is_featured.in' => 'Status featured harus Yes atau No.',
            'qty.required' => 'Jumlah wajib diisi jika Track Quantity adalah Yes.',
            'qty.numeric' => 'Jumlah harus berupa angka.',
        ];

        $validator = Validator::make($request->all(),$rules, $messages);

        if ($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->category_id = $request->category;
            $product->status = $request->status;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();

            // Simpan Gambar Gallery
            Session::flash('success', 'Produk berhasil di Update');

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil di Update'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) 
    {
        $product = Product::find($id);
        
        if (empty($product)) {
            
            Session::flash('error', 'Produk tidak ditemukan');

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id',$id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }

            ProductImage::where('product_id',$id)->delete();
        }

        $product->delete();

        Session::flash('success', 'Produk berhasil di Hapus');

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    } 

    public function getProducts(Request $request) {

        $tempProduct = [];
        if ($request->term != "") {
            $products = Product::where('title','like','%'.$request->term.'%')->get();


            if ($products != null) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);

    }
}
