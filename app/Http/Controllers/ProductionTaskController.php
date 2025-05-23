<?php

namespace App\Http\Controllers;

use App\Models\ProductionTask;
use Illuminate\Http\Request;

class ProductionTaskController extends Controller
{
    public function getAll(Request $request)
    {
        return ProductionTask::getAll($request);
    }

    public function getById(Request $request, $id)
    {
        return ProductionTask::getById($request, $id);
    }

    public function create(Request $request)
    {
        return ProductionTask::createTask($request);
    }

    public function update(Request $request, $id)
    {
        return ProductionTask::updateTask($request, $id);
    }

    public function delete(Request $request, $id)
    {
        return ProductionTask::deleteTask($request, $id);
    }
}