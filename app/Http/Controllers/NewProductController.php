<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbsentResponsibleAdmin;
use App\Models\AdminPanel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NewProductController extends Controller
{
    public function showAddPage($subCategory) 
    {
        return view('e_shop.add-product', [
            'subCategory' => SubCategory::find($subCategory)
        ]);
    }
    public function add(AddProductRequest $request) 
    {
        $product = Product::create($request->validated());
        $product->sub_category_id = $request->sub_category_id;
        $product->save();
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Успешно добавлено!']);
        if (!AdminPanel::where('responsible_category', $product->sub_category_id)->first()) //todo выключить двухфакторку (либо фикс метода) и расскомментировать
          Mail::to(config('mail.from.address'))->queue(new AbsentResponsibleAdmin(Auth::user(), $product, $_SERVER['SERVER_NAME'].'/product/'.$product->id));
        return redirect()->route('showCategory', ['page' => $request->sub_category_id]);
    }
}
