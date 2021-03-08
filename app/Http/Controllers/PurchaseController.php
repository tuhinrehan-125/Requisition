<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Category;
use App\Http\Requests\PurchaseFormValidate;
use App\Item;
use App\Purchase;
use App\Stock;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchaseList = Purchase::Active()->get();
        $categories = Category::Active()->where('parent_id', null)->get();
        $products = Item::Active()->get();
        $vendors = Vendor::Active()->get();
        return view('layouts.purchase', compact('purchaseList', 'vendors', 'categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseFormValidate $request)
    {

        $stcokAvl = Stock::where('product_id', $request->product_Name)->first();
        if ($stcokAvl) {
            $stcokAvl->in_stock += $request->quantity;
            $stcokAvl->save();
        } else {
            //add stock for product
            $stock = new Stock;
            $stock->product_id = $request->product_Name;
            $stock->in_stock += $request->quantity;
            $stock->save();
        }

        //add new purchase
        if (!empty($request->sub_cat_name)) {
            $category_id = $request->sub_cat_name;
        } else {
            $category_id = $request->category_Name;
        }

        $purchase = new Purchase;
        $purchase->category_id = $category_id;
        $purchase->item_id = $request->product_Name;
        $purchase->qty = $request->quantity;
        $purchase->created_by = Auth::user()->id;
        if ($request->has('vendor')) {
            $purchase->vendor_id = $request->vendor;
        }
        if ($request->has('other_field')) {
            $purchase->other_vendor = $request->other_field;
        }

        $purchase->purchase_date = $request->purchase_date;

        //        add document
        if ($request->hasFile('purchase_document')) {
            $name = 'purchase_documents/documents/' . time() . '.' . $request->purchase_document->getClientOriginalExtension();
            $request->purchase_document->move(public_path('purchase_documents/documents'), $name);
            $purchase->document = $name;
        }

        $purchase->save();
        $data = "New Purchase Item has been saved successfully.";
        $reponse = array(
            'product' => $purchase->item->name,
            'category' => $purchase->category->name,
            'qty' => $purchase->qty,
            'vendor_name' => $purchase->vendor_id ? $purchase->vendor->name : $purchase->other_vendor,
            'date' => $purchase->purchase_date,
            'id' => $purchase->id,
            'created_by' => $purchase->item->createdBy->name,
        );
        return array($data, $reponse);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::find($id);
        $reponse = array(
            'id' => $purchase->id,
            'product_id' => $purchase->item_id,
            'product' => $purchase->item->name,
            'category_id' => $purchase->category_id,
            'category' => $purchase->category->name,
            'qty' => $purchase->qty,
            'vendor' => $purchase->vendor_id,
            'vendor_name' => $purchase->vendor->name,
            'purchase_date' => $purchase->purchase_date,
        );
        return array($purchase = $reponse);
    }

    public function view_purchase(Request $request)
    {
        $purchase = Purchase::find($request->id);
        $reponse = array(
            'id' => $purchase->id,
            'product_id' => $purchase->item_id,
            'product' => $purchase->item->name,
            'category_id' => $purchase->category_id,
            'category' => $purchase->category->name,
            'qty' => $purchase->qty,
            'vendor' => $purchase->vendor_id,
            'vendor_name' => $purchase->vendor?$purchase->vendor->name:$purchase->other_vendor,
            'purchase_date' => $purchase->purchase_date,
            'document' => $purchase->document,
        );
        return array($purchase = $reponse);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseFormValidate $request)
    {
        $purchase = Purchase::findOrFail($request->purchase_id);
        $purchase->category_id = $request->category_Name;
        $purchase->item_id = $request->product_Name;
        $purchase->qty = $request->quantity;
        $purchase->vendor_id = $request->vendor;
        $purchase->updated_by = Auth::user()->id;
        $purchase->purchase_date = $request->purchase_date;

        $purchase->save();
        $data = "New Purchase Item has been saved successfully.";
        $reponse = array(
            'id' => $purchase->id,
            'product' => $purchase->item->name,
            'category' => $purchase->category->name,
            'qty' => $purchase->qty,
            'vendor' => $purchase->vendor_id,
            'vendor_name' => $purchase->vendor_id ? $purchase->vendor->name : $purchase->other_vendor,
            'purchase_date' => $purchase->purchase_date,
            'created_by' => $purchase->item->createdBy->name,
        );
        return array($data, $reponse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $purchase = Purchase::findOrFail($request->id);
        $purchase->is_deleted = 1;
        $purchase->update();
        $data = "Requested Purchase has been deleted successfully!";
        return array($data);
    }

    public function getProductByCategory($id)
    {
        $products = Item::where('category_id', $id)->get();
        return json_encode($products);
    }

    public function report_purchase()
    {
        // $startDate = Carbon::now();
        // $from_date = $startDate->firstOfMonth(); ;
        // $end_date= \Carbon\Carbon::now()->endOfMonth();
        // $dept_id = Auth::user()->dept_id;

        // // $stocks=DeptStock::where('dept_id', $deptid)->with('product')->get();
        $purchases = Purchase::with('vendor', 'item')->get();


        return view('layouts.report-total-purchase', compact('purchases'));
    }

    public function searchPurchase(Request $request)
    {
        $from_date = $request->from_date;
        $end_date = $request->end_date;

//        dd($from_date);

        $dept_id = Auth::user()->dept_id;
        $purchases = Purchase::with('vendor', 'item')
            ->whereBetween('purchase_date', [$from_date, $end_date])
            ->get();

        $table = '';
        if (!empty($purchases)) {
            foreach ($purchases as $key => $value) {
                $product_name = $value->item->name;
                $table .= "<tr>";
                $table .= "<td>$product_name</td>";
                $table .= "<td>$value->qty</td>";
                $table .= "<td>$value->purchase_date</td>";

                $table .= "</tr>";
            }
        } else {

            $table .= "Not found";
        }

        return response()->json(['table' => $table,'purchases'=>$purchases]);

    }
}
