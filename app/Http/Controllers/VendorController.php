<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VendorFormValidate;

use Illuminate\Support\Facades\Validator;

use App\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getVendor()
    {
        $vendors = Vendor::orderBy('created_at', 'desc')->where('status', true)->get();
        return view('layouts.vendor', compact('vendors'));
    }
    public function store(VendorFormValidate  $request)
    {
        $vendor = new Vendor;
        $vendor->name = $request->name;
        $vendor->phone_no = $request->phone_no;
        $vendor->email = $request->email;
        $vendor->address = $request->address;
        $vendor->contact_person = $request->contact_person;
        $vendor->created_by = auth()->user()->id;
        $vendor->status = true;
        $vendor->save();
        $data = "New Vendor has been saved successfully.";
        return array($data, $vendor);
    }

    public function getOneVendor($id)
    {
        return array($vendor = Vendor::where('id', $id)->first());
    }

    public function update(VendorFormValidate $request)
    {
        $vendor = Vendor::findOrFail($request->vendor_id);
        $vendor->name = $request->name;
        $vendor->phone_no = $request->phone_no;
        $vendor->email = $request->email;
        $vendor->address = $request->address;
        $vendor->contact_person = $request->contact_person;
        $vendor->created_by = auth()->user()->id;
        $vendor->update();
        $data = "Requested Vendor has been Updated successfully.";
        return array($data, $vendor);
    }

    public function delete(Request $request)
    {
        $item = Vendor::findOrFail($request->id);
        $item->status = false;
        $item->update();
        $data = "Requested Vendor has been deleted successfully!";
        return array($data);
    }
}
