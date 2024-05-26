<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class SubCategoryController extends Controller
{

    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftjoin('categories', 'categories.id', 'sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories
                ->where('sub_categories.name', 'like', '%' . $request
                    ->get('keyword') . '%');

            $subCategories = $subCategories
                ->orWhere('categories.name', 'like', '%' . $request
                    ->get('keyword') . '%');
        }

        $subCategories = $subCategories->paginate(10);

        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        return view('admin.sub_category.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan, silakan pilih slug yang lain.',
            'category.required' => 'Kategori wajib diisi.',
            'status.required' => 'Status wajib diisi.'
        ]);

        if ($validator->passes()) {

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            Session::flash('success', 'Sub Kategori Berhasil di Tambah');

            return response([
                'status' => true,
                'message' => 'Sub Kategori berhasil di Tambah.'
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {

        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            Session::flash('error', 'Data tidak ditemukan');
            return redirect()->route('sub-categories.index');
        }

        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        return view('admin.sub_category.edit', $data);
    }

    public function update($id, Request $request)
    {

        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            Session::flash('error', 'Data tidak ditemukan');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            //return redirect()->route('sub-categories.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            //'slug' => 'required|unique:sub_categories',
            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
            'category' => 'required',
            'status' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan, silakan pilih slug yang lain.',
            'category.required' => 'Kategori wajib diisi.',
            'status.required' => 'Status wajib diisi.'
        ]);

        if ($validator->passes()) {

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            Session::flash('success', 'Sub Kategori Berhasil di Update');

            return response([
                'status' => true,
                'message' => 'Sub Kategori berhasil di Update.'
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            Session::flash('error', 'Data tidak ditemukan');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $subCategory->delete();

        Session::flash('success', 'Sub Kategori Berhasil di Hapus');

        return response([
            'status' => true,
            'message' => 'Sub Kategori berhasil di Hapus.'
        ]);
    }
}
