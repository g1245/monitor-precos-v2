<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $department = Department::find(
                $request->route()->parameter('departmentId')
            );

            return view('department.index', compact('department'));
        } catch (\Exception $e) {
            Log::error('Department not found: '.$e->getMessage());
            abort(404, 'Departamento não encontrado');
        }
    }
}
