<?php

namespace App\Http\Controllers;

use App\Helpet\Helper;
use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\ShowRequest;
use App\Http\Requests\SpecificCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class CategoryController extends Controller
{
    use HasApiTokens;

    public function index(ShowRequest $request)
    {
        //TODO make request validation file  -> Done ..
        //TODO clean up the code for index method -> Done ..
        $page = $request->has('page') ? $request->page : 1;
        if ($request->has('search')) {
            $data = Category::where('name', 'like', '%' . $request->search . '%')->paginate(8);
        }else{
            $data = Category::paginate(8);
        }
        $categories =CategoryResource::collection($data);

        return Helper::responseData('Retrieved', true,$categories, Response::HTTP_OK);
    }

    public function store(StoreCategoryRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('create-category')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $input = $request->except('image');
        $image = null;
        if ($request->hasFile('image')) {
            $categoriesFolder = public_path('categories');
            $imageName = Str::slug($request->name) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move($categoriesFolder, $imageName);
            $image = config('app.url') . '/categories/' . $imageName;
        }
        try {
            $category = Category::create([
                'added_by' => $user->id,
                'name' => $request->name,
                'image' => $image,
            ]);
            return Helper::responseData('Created', true, CategoryResource::make($category), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(SpecificCategoryRequest $request) // .. To Get Specific Category By id ..
    {
        $category = Category::where('id', $request->id)->first();
        return  Helper::responseData('Retrieved', true,CategoryResource::make($category), Response::HTTP_OK);
    }

    public function update(UpdateCategoryRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('update-category')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $category = Category::find($request->id);
        $input = $request->except('image');
        $input['updated_by'] = auth()->id();
        if ($request->hasFile('image')) {
            $categoriesFolder = public_path('categories');
            $imageName = Str::slug($request->name) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move($categoriesFolder, $imageName);
            $input['image'] =config('app.url').'/categories/'.$imageName;
        }
        $category->update($input);
        return  Helper::responseData('Updated', true,CategoryResource::make($category), Response::HTTP_OK);
    }

    public function destroy(DeleteCategoryRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('delete-category')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $category = Category::find($request->id);
        $category->delete();
        return  Helper::responseData('Deleted', true,null, Response::HTTP_OK);
    }
}
