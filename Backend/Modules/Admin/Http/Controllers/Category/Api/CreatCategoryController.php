<?php

namespace Modules\Admin\Http\Controllers\Category\Api;

use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
//use Validator;
use Illuminate\Support\Facades\Validator;
class CreatCategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'string',
            'slug' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $category = Category::create(array_merge(
                    $validator->validated(),
                ));
        return response()->json([
            'message' => 'Category successfully registered',
            'user' => $category
        ], 201);
    }
}
