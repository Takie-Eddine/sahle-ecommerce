<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AttributesController extends Controller
{
    public function index()
    {
        $attributes = Attribute::orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('dashboard.attributes.create');
    }


    public function store(AttributeRequest $request)
    {


        DB::beginTransaction();
        $attribute = Attribute::create([]);


        $attribute->name = $request->name;
        $attribute->save();
        DB::commit();
        return redirect()->route('admin.attributes')->with(['success' => 'تم ألاضافة بنجاح']);



    }


    public function edit($id)
    {

        $attribute = Attribute::find($id);

        if (!$attribute)
            return redirect()->route('admin.attributes')->with(['error' => 'هذا العنصر  غير موجود ']);

        return view('dashboard.attributes.edit', compact('attribute'));

    }


    public function update($id, AttributeRequest $request)
    {
        try {

            $attribute = Attribute::find($id);

            if (!$attribute)
                return redirect()->route('admin.attributes')->with(['error' => 'هذا العنصر غير موجود']);


            DB::beginTransaction();


            $attribute->name = $request->name;
            $attribute->save();

            DB::commit();
            return redirect()->route('admin.attributes')->with(['success' => 'تم ألتحديث بنجاح']);

        } catch (\Exception $ex) {

            DB::rollback();
            return redirect()->route('admin.attributes')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $attribute = Attribute::find($id);

            if (!$attribute){
                return redirect()->route('admin.attributes')->with(['error' => 'هذا الماركة غير موجود ']);
            }


            $attibuteTra = AttributeTranslation::where('attribute_id' , '=' , $id);


            $attribute->delete();
            $attibuteTra->delete();

            DB::commit();
            return redirect()->route('admin.attributes')->with(['success' => 'تم  الحذف بنجاح']);

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.attributes')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
