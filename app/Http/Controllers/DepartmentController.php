<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepartmentFormValidate;

use App\Department;

class DepartmentController extends Controller
{
    public function getDepartment()
    {
        $departments = Department::orderBy('created_at', 'desc')->where('status', true)->get();
        return view('layouts.department', compact('departments'));
    }

    public function store(DepartmentFormValidate  $request)
    {
        $department = new Department;
        $department->name = $request->name;
        $department->dept_code = $request->dept_code;
        $department->details = $request->details;
        $department->created_by = auth()->user()->id;
        $department->status = true;
        $department->save();
        $data = "New Department has been saved successfully.";
        return array($data, $department);
    }

    public function getOneDepartment($id)
    {
        return array($vendor = Department::where('id', $id)->first());
    }

    public function update(DepartmentFormValidate $request)
    {
        $department = Department::findOrFail($request->department_id);
        $department->name = $request->name;
        $department->dept_code = $request->dept_code;
        $department->details = $request->details;
        $department->created_by = auth()->user()->id;
        $department->update();
        $data = "Requested Department has been Updated successfully.";
        return array($data, $department);
    }

    public function delete(Request $request)
    {
        $department = Department::findOrFail($request->id);
        $department->status = false;
        $department->update();
        $data = "Requested Vendor has been deleted successfully!";
        return array($data);
    }
}
