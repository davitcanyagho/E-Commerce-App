<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class BrandController extends Controller
{

    public function index(Request $request) {
        $brands = Brand::latest('id');

        if($request->get('keyword')) {
            $brands = $brands->where('name', 'like', '%'.$request->get('keyword').'%');
        }

        $brands = $brands->paginate(10);

        return view('admin.brands.list',compact('brands'));
    }
    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            Session::flash('success', 'Brand berhasil ditambahkan');
            return response()->json([
                'status' => true,
                'message' => 'Brand berhasil ditambahkan.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $brand = Brand::find($id);
        if (empty($brand)) {
            Session::flash('error','Data tidak ditemukan');
            return redirect()->route('brands.index');
        }

        $data['brand'] = $brand;
        return view('admin.brands.edit',$data);
    }

    public function update($id, Request $request) {

        $brand = Brand::find($id);
        if (empty($brand)) {
            Session::flash('error','Data tidak ditemukan');
            
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Data not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
        ]);

        if($validator->passes()) {
            
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            Session::flash('success', 'Brand berhasil di Update');
            return response()->json([
                'status' => true,
                'message' => 'Brand berhasil di update.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request){
        $brand = Brand::find($id);
        if (empty($brand)) {
            Session::flash('error', 'Data tidak ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $brand->delete();
        Session::flash('success', 'Brand berhasil di Hapus');

        return response()->json([
            'status' => true,
            'message' => 'Brand berhasil di Hapus'
        ]);
    }
}
