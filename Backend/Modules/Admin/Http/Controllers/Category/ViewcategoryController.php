<?php

namespace Modules\Admin\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ViewcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function category()
    {
        $categores = Category::all();
        return view('admin::category',[
           "categores" => $categores
        ]);
    }

}
