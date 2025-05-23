<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAll(Request $request)
    {
        return Category::getAll($request);
    }

    public function getById(Request $request, $id)
    {
        return Category::getById($request, $id);
    }

    public function create(Request $request)
    {
        return Category::create($request);
    }

    public function update(Request $request, $id)
    {
        return Category::update($request, $id);
    }

    public function delete(Request $request, $id)
    {
        return Category::delete($request, $id);
    }
}