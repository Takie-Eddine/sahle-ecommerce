<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\OptionsRequest;
use App\Models\Attribute;
use App\Models\Option;
use App\Models\OptionTranslation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function index()
    {
        $options = Option::with(['product' => function ($prod) {
            $prod->select('id');
        }, 'attribute' => function ($attr) {
            $attr->select('id');
        }])->select('id', 'product_id', 'attribute_id', 'price')->paginate(PAGINATION_COUNT);

        return view('dashboard.options.index', compact('options'));
    }

    public function create()
    {
        $data = [];
        $data['products'] = Product::active()->select('id')->get();
        $data['attributes'] = Attribute::select('id')->get();

        return view('dashboard.options.create', $data);
    }

    public function store(OptionsRequest $request)
    {


        DB::beginTransaction();


        $option = Option::create([
            'attribute_id' => $request->attribute_id,
            'product_id' => $request->product_id,
            'price' => $request->price,
        ]);

        $option->name = $request->name;
        $option->save();
        DB::commit();

        return redirect()->route('admin.options')->with(['success' => 'تم ألاضافة بنجاح']);
    }

    public function edit($optionId)
    {

        $data = [];
        $data['option'] = Option::find($optionId);

        if (!$data['option'])
            return redirect()->route('admin.options')->with(['error' => 'هذه القيمة غير موجود ']);

        $data['products'] = Product::active()->select('id')->get();
        $data['attributes'] = Attribute::select('id')->get();

        return view('dashboard.options.edit', $data);

    }

    public function update($id, OptionsRequest $request)
    {
        try {

            $option = Option::find($id);

            if (!$option)
                return redirect()->route('admin.options')->with(['error' => 'هذا ألعنصر غير موجود']);

            $option->update($request->only(['price','product_id','attribute_id']));

            $option->name = $request->name;
            $option->save();

            return redirect()->route('admin.options')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.options')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $option = Option::orderBy('id', 'DESC')->find($id);

            if (!$option){
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
            }
            $optionTra = OptionTranslation::where('option_id' , '=' , $id);

            $option->delete();
            $optionTra->delete();


            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم  الحذف بنجاح']);

        } catch (\Exception $ex) {

            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

}
