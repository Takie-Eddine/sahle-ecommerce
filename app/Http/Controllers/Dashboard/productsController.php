<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class productsController extends Controller
{
    public function index(){

    }

    public function create(){
        $data=[];
        $data['brands'] = Brand::active() -> select('id') ->get();
        $data['tags'] = Tag::select('id') ->get();
        $data['categories'] = Category::active() -> select('id') ->get();

        return view('dashboard.products.general.create',$data);

    }


    public function store(GeneralRequest $request){
        DB::beginTransaction();

        //validation

        if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
        else
            $request->request->add(['is_active' => 1]);

        $product = Product::create([
            'slug' => $request->slug,
            'brand_id' => $request->brand_id,
            'is_active' => $request->is_active,
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->save();



        $product->categories()->attach($request->categories);



        DB::commit();
        return redirect()->route('admin.products')->with(['success' => 'تم ألاضافة بنجاح']);

    }
}
