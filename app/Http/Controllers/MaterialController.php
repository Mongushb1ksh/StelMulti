<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function getAll(Request $request)
    {
        return Material::getAll($request);
    }

    public function getById(Request $request, $id)
    {
        return Material::getById($request, $id);
    }

    public function create(Request $request)
    {
        return Material::createMaterial($request);
    }

    public function update(Request $request, $id)
    {
        return Material::updateMaterial($request, $id);
    }

    public function delete(Request $request, $id)
    {
        return Material::deleteMaterial($request, $id);
    }
}