<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainCategoriesController extends Controller
{

    public function index(){

        $categories = Category::parent() -> orderBy('id','DESC') -> paginate(PAGINATION_COUNT);

        return view('dashboard.categories.index',compact('categories'));

    }


    public function Create(){

        $categories =   Category::select('id','parent_id')->get();
        return view('dashboard.categories.create',compact('categories'));

    }


    public function store(MainCategoryRequest $request){

        try {

            DB::beginTransaction();

            //validation

            if (!$request->has('is_active')){
                $request->request->add(['is_active' => 0]);
            }
            else{
                $request->request->add(['is_active' => 1]);
            }

            $category = Category::create($request->except('_token'));

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألاضافة بنجاح']);
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function edit($id){

        $category = Category::find($id);

        if(!$category){
            return redirect() -> route('admin.maincategories') -> with(['error' => 'category not found']);
        }

        return view('dashboard.categories.edit',compact('category'));

    }


    public function update($id,MainCategoryRequest $request){

        try{

            //return $request;
            DB::beginTransaction();

            if(!$request->has('is_active')){
                $request -> request -> add(['is_active' => 0]);
            }
            else{
                $request -> request -> add(['is_active' => 1]);
            }
            $category = Category::find($id);

            if(!$category){
                return redirect() -> route('admin.maincategories') -> with(['error' => 'category not found']);
            }
            //return $request;


            $category -> update($request -> all());

            $category -> name = $request  -> name;
            $category -> save();
            DB::commit();

            return redirect() -> route('admin.maincategories') -> with(['success' => 'success']);

        }catch(Exception $ex){
            DB::rollBack();

            return redirect() -> route('admin.maincategories') -> with(['error' => 'error']);

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
