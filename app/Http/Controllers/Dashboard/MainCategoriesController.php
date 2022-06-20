<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainCategoriesController extends Controller
{

    public function index()
    {
        $categories = Category::with('_parent')->orderBy('id','DESC') -> paginate(PAGINATION_COUNT);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories =   Category::select('id','parent_id')->get();
        return view('dashboard.categories.create',compact('categories'));
    }

    public function store(MainCategoryRequest $request)
    {

        try {

            DB::beginTransaction();



            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            //if user choose main category then we must remove paret id from the request

            if($request -> type == CategoryType::mainCategory)
            {
                $request->request->add(['parent_id' => null]);
            }

            //if he choose child category we mus t add parent id


            $category = Category::create($request->except('_token'));


            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألاضافة بنجاح']);
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function edit($id)
    {


        $category = Category::orderBy('id', 'DESC')->find($id);

        if (!$category)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

        return view('dashboard.categories.edit', compact('category'));

    }


    public function update($id, MainCategoryRequest $request)
    {
        try {





            $category = Category::find($id);

            if (!$category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $category->update($request->all());

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function destroy($id){

        try{

            DB::beginTransaction();

            $category = Category::find($id);


            if(!$category){
                return redirect() -> route('admin.maincategories') -> with(['error' => 'category not found']);
            }
            $categoryTra = CategoryTranslation::where('category_id' , '=' , $id);


            $category -> delete();

            $categoryTra -> delete();

            DB::commit();
            return redirect() -> route('admin.maincategories') -> with(['success' => 'success']);


        }catch(Exception $ex){
            DB::rollBack();

            return redirect() -> route('admin.maincategories') -> with(['error' => 'error']);

        }

    }

}
