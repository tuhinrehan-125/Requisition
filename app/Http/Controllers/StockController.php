<?php

namespace App\Http\Controllers;

use App\DeptStock;
use App\Item;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Item::Active()->get();
        $stocks = Stock::Active()->get();
        return view('layouts.stock', compact('products','stocks'));
    }

    /**
     * add opening stock
     *
     * @return \Illuminate\Http\Response
     */
    public function addOpeningStock(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required',
            'quantity' => 'required',
        ]);
        if ($validated) {
            $stock = Stock::where('product_id',$request->product)->first();
            if ($stock) {
                $stock->opening_stock = $request->quantity;
                $stock->in_stock += $request->quantity;
                $stock->save();
            } else {
                $stock = new Stock;
                $stock->product_id = $request->product;
                $stock->opening_stock = $request->quantity;
                $stock->in_stock = $request->quantity;
                $stock->save();
            }
            $reponse=array(
                'id'=>$stock->id,
                'product'=>$stock->item->name,
                'opening_stock'=>$stock->opening_stock,
                'in_stock'=>$stock->qty,
                'udpated'=>$stock->updated_at,
            );
            $data = "Opening Stock saved successfully.";
            return array($data, $reponse);
        }
    }

    /**
     * show dept stock
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mystock(Request $request)
    {
        if(auth()->user()->role->role=='dept-user')
        {
            $deptid=auth()->user()->dept_id;
            $deptstock=DeptStock::with('product')->select(DB::raw('dept_stocks.*, SUM(dept_stocks.qty) as qty'))->where('dept_id', $deptid)
            ->groupBy('dept_stocks.product_id')->get();

            return view('layouts.mystock', compact('deptstock'));
        }else
        {
            return redirect('/home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
