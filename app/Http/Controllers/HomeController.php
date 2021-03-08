<?php

namespace App\Http\Controllers;

use App\Mail\ReqApproved;
use App\Category;
use App\Certificate;
use App\Department;
use App\Http\Requests\CategoryFormValidate;
use App\Http\Requests\ItemFormValidate;
use App\Http\Requests\UserFormVal;
use App\Http\Requests\updateUserFormVal;
use App\Item;
use App\Order;
use App\OrderApproval;
use App\OrderItem;
use App\Purchase;
use App\Role;
use App\Stock;
use App\DeptStock;
use App\User;
use App\Vessel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changeLang($lang)
    {
        // App::setLocale($lang);
        App::setLocale($lang);
        session()->put('locale', $lang);
        return redirect()->back();
    }

    public function index()
    {

        if (!empty(auth()->user()->role->role)) {
            $user_id = Auth::user()->id;
            $dept_id = Auth::user()->dept_id;
            $number_of_categories = Category::Active()->count();
            $number_of_products = Item::Active()->count();
            $number_of_stocks = auth()->user()->role->role == 'dept-user' ? DeptStock::where('dept_id', auth()->user()->dept_id)->latest() : Stock::latest();
            $number_of_purchase = Purchase::Active();
            $number_of_deliver_item = OrderItem::CountDeliver();
            $approved_requisition = Order::ApprovedCount();
            $pending_requistion = Order::Pending();
            $rejected_requisition = Order::Rejected()->count();
            return view('home', compact('number_of_categories', 'number_of_deliver_item', 'number_of_products', 'number_of_stocks', 'number_of_purchase', 'approved_requisition', 'rejected_requisition', 'pending_requistion'));
        }
    }

    public function getItem()
    {
        $categories = Category::Active()->where('parent_id', null)->get();
        $items = Item::Active()->get();
        return view('layouts.item', compact('categories', 'items'));
    }

    public function getCategory()
    {
        $categories = Category::orderBy('created_at', 'desc')->where('status', true)->get();
        $sub_categories = Category::orderBy('created_at', 'desc')->where('status', true)->where('parent_id', null)->get();


        return view('layouts.category', compact('categories', 'sub_categories'));
    }

    public function storeCategory(CategoryFormValidate $request)
    {
        $category = new Category;
        if ($request->has('sub_category_checkbox_name')) {
            $category->parent_id = $request->sub_category_name;
        } else {
            $category->parent_id = null;
        }
        $category->name = $request->name;
        $category->save();
        $data = "New Category has been added successfully.";
        $reponse = array(
            'id' => $category->id,
            'category' => $category->parentcat ? $category->parentcat->name : $category->name,
            'subcategory' => $category->parentcat ? $category->name : 'N/A',
        );
        return array($data, $reponse);
    }

    public function editCategory(Request $request)
    {
        $item = Category::with('parentcat')->find($request->id);

        //        $rows = Category::get();
        $rows = Category::orderBy('created_at', 'desc')->where('status', true)->where('parent_id', null)->get();
        if ($item->parent_id !== null) {
            $sub_category_name = Category::where('id', $item->parent_id)->first();

            $drops = "";

            foreach ($rows as $row) {

                if ($row->id == $sub_category_name->id) {
                    $drops .= "<option value='$row->id' selected> $row->name </option>";
                } else {
                    $drops .= "<option value='$row->id'> $row->name </option>";
                }
            }

            return response()->json([
                'item' => $item,
                'sub_category_name' => $sub_category_name,
                'dropdowns' => $drops,
            ]);
        } else {

            $normal_selects = "";
            $normal_selects .= "<option value='' selected> Select Here </option>";
            foreach ($rows as $row) {


                $normal_selects .= "<option value='$row->id'> $row->name </option>";
            }
            return response()->json([
                'item' => $item,
                'normal_selects' => $normal_selects,
            ]);
        }
    }

    public function updateCategory(CategoryFormValidate $request)
    {
        $category = Category::findOrFail($request->id);
        //        if ($request->has('sub_category_checkbox_name_2')) {
        //            $category_id = $request->sub_category_name_2;
        //        } else {
        //            $category_id = null;
        //        }
        $category->name = $request->name;
        //        $category->parent_id = $category_id;
        $category->update();
        $data = "Requested Category has been updated successfully.";
        $item = Category::with('parentcat')->orderBy('created_at', 'desc')->find($category->id);
        return array($data, $item, $category);
    }

    public function deleteCategory(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status = false;
        $category->update();
        $data = "Requested Category has been deleted successfully!";
        return array($data);
    }

    public function storeItem(ItemFormValidate $request)
    {
        $item = new Item;
        $item->name = $request->Item_Name;
        $item->category_id = $request->Category_Name;
        $item->sub_category_id = $request->sub_cat_name;
        $item->unit = $request->Measurement_Unit;
        $item->impa_code = $request->code;
        $item->created_by = auth()->user()->id;
        $item->save();
        $data = "New Product has been saved successfully.";
        $reponse = array(
            'id' => $item->id,
            'name' => $item->name,
            'unit' => $item->unit,
            'impa_code' => $item->impa_code,
            'category' => $item->category->name,
            'created_by' => $item->createdBy->name,
        );
        return array($data, $reponse);
    }

    public function getOneItem($id)
    {
        return array($item = Item::with('category')->where('id', $id)->first());
    }

    public function getSubCategory($id)
    {
        $subCategories = Category::Active()->where('parent_id', $id)->get();
        return json_encode($subCategories);
    }

    public function edit_product(Request $request)
    {
        $item = Item::with('category')->find($request->id);
        $sub_categories = Category::where('parent_id', $item->parent_id)->get();
        $sub_category_option = "";

        foreach ($sub_categories as $sub_category) {

            if ($item->sub_category_id == $sub_category->id) {
                $sub_category_option .= "<option value='$sub_category->id' selected disabled> $sub_category->name </option>";
            } else {
                $sub_category_option .= "<option value='$sub_category->id'> $sub_category->name </option>";
            }
        }
        return response()->json([
            'item' => $item,
            'sub_category_option' => $sub_category_option,
        ]);
    }

    public function updateOneItem(ItemFormValidate $request)
    {
        $item = Item::findOrFail($request->id);
        $item->name = $request->Item_Name;
        $item->category_id = $request->Category_Name;
        $item->sub_category_id = $request->sub_cat_name;
        $item->impa_code = $request->impa_code;
        $item->unit = $request->Measurement_Unit;
        $item->updated_by = auth()->user()->id;
        $item->update();
        $data = "Requested Item has been Updated successfully.";
        $items = Item::with('category', 'updatedBy')->Active()->find($item->id);
        return array($data, $items, $item->category, $item->updatedBy);
    }


    public function editPendingRequisition(Request $request)
    {
        $items = Item::Active()->get();
        $categories = Category::Active()->get();
        $orders = Order::Pending()->get();
        return view('layouts.order', compact('items', 'categories', 'orders'));
    }


    public function deleteOneItem(Request $request)
    {
        $item = Item::findOrFail($request->id);
        $item->status = false;
        $item->update();
        $data = "Requested Item has been deleted successfully!";
        return array($data);
    }

    public function getPendingOrder()
    {
        $items = Item::Active()->get();
        $categories = Category::Active()->get();
        $orders = Order::Pending()->get();
        return view('layouts.order', compact('items', 'categories', 'orders'));
    }


    public function getRejectOrder()
    {
        $items = Item::Active()->get();
        $categories = Category::Active()->get();
        $orders = Order::Active()
            ->whereHas('orderApproval', function ($q) {
                $q->where(function ($query) {
                    $query->where('rejection_by_admin', '!=', null)
                        ->orWhere('rejection_by_officer', '!=', null);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('layouts.order', compact('items', 'categories', 'orders'));
    }

    public function getApprovedOrder()
    {
        $items = Item::Active()->get();
        $categories = Category::Active()->get();
        $orders = Order::Active()
            ->whereHas('orderApproval', function ($q) {
                $q->where(function ($query) {
                    $query->where('admin_officer', '!=', null)
                        ->orWhere('sr_officer', '!=', null);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.order', compact('items', 'categories', 'orders'));
    }

    public function receivedProduct()
    {
        $userOrders = auth()->user()->dept->orders->pluck('id');
        $orders = Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'order_items.id as order_item_id',
                'items.id as itemId',
                'items.name',
                'items.unit',
                'order_items.received_qty',
                'order_items.updated_at'
            )
            ->where('order_items.received_qty', '!=', null)
            ->whereIn('order_items.order_id', $userOrders)
            ->orderBy('order_items.id', 'desc')
            ->get();

        return view('layouts.view-received-product', compact('orders'));
    }

    public function createOrder()
    {
        $categories = Category::Active()->where('parent_id', null)->get();
        return view('layouts.create-order', compact('categories'));
    }

    public function editPending($id)
    {
        //        dd($id);
        $categories = Category::Active()->where('parent_id', null)->get();
        $order = Order::with('orderApproval', 'dept')->find($id);
        $order_items = OrderItem::where('order_id', $order->id)->pluck('item_id');
        $orders = Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->leftjoin('stocks', 'stocks.product_id', '=', 'order_items.item_id')
            ->join('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'orders.dept_id',
                'orders.id',
                'orders.status',
                'items.id as itemId',
                'items.impa_code',
                'items.name',
                'items.unit',
                'stocks.opening_stock',
                'stocks.in_stock',
                'order_items.item_qty',
                'order_items.approved_qty',
                'order_items.delivered_qty',
                'order_items.received_qty'
            )
            ->where('orders.id', $id)
            ->orderBy('order_items.id', 'asc')
            ->get();

        return view('layouts.edit-pending-requisition', compact('categories', 'orders'));
    }

    public function editOrder($id)
    {
        $orderid = $id;
        $order = Order::find($id);
        $categories = Category::Active()->where('parent_id', null)->get();
        return view('layouts.edit-order', compact('categories', 'order', 'orderid'));
    }

    public function getOrderItems(Request $req)
    {
        $orderid = $req->orderid;
        $order = Order::find($orderid);
        $orderitems = $order->Items;


        $array = $orderitems->map(function ($orderitem) {
            return [
                'id' => $orderitem->item->id,
                'name' => $orderitem->item->name,
                'status' => $orderitem->item->status,
                'cat_name' => $orderitem->item->category->name,
                'category_id' => $orderitem->item->category_id,
                'sub_category_id' => $orderitem->item->sub_category_id,
                'unit' => $orderitem->item->unit,
                'qty' => $orderitem->item_qty
            ];
        });
        return json_encode($array);
    }

    public function updateOrder(Request $req)
    {
        $orderid = $req->edit_order_id;
        for ($i = 0; $i < count($req->item_id); $i++) {
            $orderitem = OrderItem::where('order_id', $orderid)->where('item_id', $req->item_id[$i])->first();
            if ($orderitem) {
                $orderitem->item_qty = $req->item_qty[$i];
                $orderitem->save();
            } else {
                $orderitem = new OrderItem;
                $orderitem->order_id = $orderid;
                $orderitem->item_id = $req->item_id[$i];
                $orderitem->item_qty = $req->item_qty[$i];
                $orderitem->save();
            }
        }
        $alert = "success";
        $data = "Requested Item has been updated successfully!";
        return array($alert, $data);
    }


    public function pendingDelete(Request $req)
    {
        $order = Order::Pending()->findOrFail($req->id);

        if (!empty($order)) {
            $order->delete();
            $data = "Requested Requisition has been deleted successfully!";
            return array($data);
        }
    }

    public function getItemsByCat($cat_id)
    {
        $category = Category::findOrFail($cat_id);
        $items = $category->items;
        $array = $items->map(function ($item) {
            return [
                'category_id' => $item->category_id,
                'created_at' => $item->created_at,
                'created_by' => $item->created_by,
                'id' => $item->id,
                'impa_code' => $item->impa_code,
                'name' => $item->name,
                'status' => $item->status,
                'sub_category_id' => $item->sub_category_id,
                'unit' => $item->unit,
                'updated_at' => $item->updated_at,
                'updated_by' => $item->updated_by,
                'stock' => $item->stock ? $item->stock->in_stock : null,
            ];
        });
        return $array;
    }

    public function storeOrder(Request $request)
    {
        // $counter = 0;
        // $counter = Order::where('dept_id', auth()->user()->dept_id)
        //     ->whereYear('req_date', Carbon::now()->year)->get()->count();
        // $counter += 1;
        // if ($counter < 10) {
        //     $counter = '0' . $counter;
        // }
        DB::beginTransaction();
        try {
            $order_qty = 0;
            if (!empty($request->item_id) && count($request->item_id) > 0) {
                $order = new Order;
                $order->dept_id = auth()->user()->dept_id;
                $order->req_date = Carbon::now();
                $order->req_no = 'IRD-' . auth()->user()->dept->name . '-' . time();
                $order->created_by = auth()->user()->id;
                $order->save();
                for ($i = 0; $i < count($request->item_id); $i++) {
                    $orderitem = new OrderItem;
                    $orderitem->order_id = $order->id;
                    $orderitem->item_id = $request->item_id[$i];
                    $orderitem->item_qty = $request->item_qty[$i];
                    $orderitem->save();
                    $order_qty += $request->item_qty[$i];
                }
                $OrderApproval = new OrderApproval;
                $OrderApproval->order_id = $order->id;
                $OrderApproval->save();
                $user = User::find(auth()->user()->id);
                if ($user->req_limit !== null) {
                    if ($order_qty > $user->req_limit) {
                        DB::rollback();
                        $alert = "fail";
                        $data = trans('messages.limit_exceed');
                        return array($alert, $data);
                    } else {
                        $user->req_limit -= $order_qty;
                        $user->save();
                    }
                }
                DB::commit();
                $alert = "success";
                $data = "New Requisition has been submitted successfully!";
                return array($alert, $data);
            } else {
                $alert = "fail";
                $data = "Add item and retry. Thank You.";
                return array($alert, $data);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'errmsg' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }

    public function viewOrderDetail($id)
    {
        $order = Order::with('orderApproval', 'dept')->find($id);
        $order_items = OrderItem::where('order_id', $order->id)->pluck('item_id');
        $orders = Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->leftjoin('stocks', 'stocks.product_id', '=', 'order_items.item_id')
            ->join('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'orders.dept_id',
                'orders.id',
                'orders.status',
                'items.id as itemId',
                'items.impa_code',
                'items.name',
                'items.unit',
                'stocks.opening_stock',
                'stocks.in_stock',
                'order_items.item_qty',
                'order_items.approved_qty',
                'order_items.delivered_qty',
                'order_items.received_qty'
            )
            ->where('orders.id', $id)
            ->orderBy('order_items.id', 'asc')
            ->get();
        return view('layouts.view-order-detail', compact('order', 'orders'));
    }


    // public function allTrash()
    // {

    //     $orders = Order::orderBy('updated_at', 'desc')->get();
    //     $categories = Category::orderBy('updated_at', 'desc')->where('status', false)->get();
    //     // $surveys =Survey::orderBy('updated_at','desc')->where('status',false)->get();
    //     $vessel_certificates = VesselCertificate::orderBy('updated_at', 'desc')->where('status', false)->get();
    //     $vessel_surveys = VesselSurvey::orderBy('updated_at', 'desc')->where('status', false)->get();
    //     $vessels = Vessel::orderBy('updated_at', 'desc')->where('status', false)->get();
    //     $items = Item::orderBy('updated_at', 'desc')->where('status', false)->get();
    //     $orderitems = OrderItem::orderBy('updated_at', 'desc')->where('status', false)->get();

    //     return view('layouts.trash-detail', compact(
    //         'orders',
    //         'categories',
    //         // 'surveys',
    //         'vessel_certificates',
    //         'vessel_surveys',
    //         'vessels',
    //         'items',
    //         'orderitems'
    //     ));
    // }

    // public function restore(Request $r)
    // {
    //     if ($r->type == 'survey') {
    //         $servey = VesselSurvey::find($r->id);
    //         $vessel = Vessel::find($servey->vessel_id);
    //         if (!$vessel->status) {
    //             $this->restoreVessel($vessel->id);
    //         }
    //         $servey->status = true;
    //         $servey->update();
    //         return response()->json(['url' => route('get.all.survey')]);
    //     }
    //     if ($r->type == 'certificate') {

    //         $certificate = VesselCertificate::find($r->id);
    //         $vessel = Vessel::find($certificate->vessel_id);
    //         if (!$vessel->status) {
    //             $this->restoreVessel($vessel->id);
    //         }
    //         $certificate->status = true;
    //         $certificate->update();
    //         return response()->json(['url' => route('get.certificate')]);
    //     }
    //     if ($r->type == 'order') {
    //         $order = Order::find($r->id);
    //         $vessel = Vessel::find($order->vessel_id);
    //         $category = Category::find($order->category_id);
    //         if (!$vessel->status) {
    //             $this->restoreVessel($vessel->id);
    //         }
    //         if (!$category->status) {
    //             $category->status = true;
    //             $category->update();
    //         }
    //         $certificate->status = true;
    //         $certificate->update();
    //         return response()->json(['url' => route('get.all.order')]);
    //     }
    //     if ($r->type == 'item') {
    //         $item = Item::find($r->id);
    //         $category = Category::find($item->category_id);
    //         if (!$category->status) {
    //             $category->status = true;
    //             $category->update();
    //         }
    //         $item->status = true;
    //         $item->update();
    //         return response()->json(['url' => route('get.all.item')]);
    //     }

    //     if ($r->type == 'category') {
    //         $category = Category::find($r->id);
    //         $category->status = true;
    //         $category->update();

    //         return response()->json(['url' => route('get.all.category')]);
    //     }

    //     if ($r->type == 'vessel') {
    //         $this->restoreVessel($r->id);
    //         return response()->json(['url' => route('get.vessels')]);
    //     }
    //     return response()->json(['error' => 'Please try again.']);
    // }

    public function permanentDelete(Request $r)
    {
        if ($r->type == 'order') {
            $order = Order::find($r->id);
            $delete = $order->delete();
        }
        if ($r->type == 'item') {
            $item = Item::find($r->id);
            $delete = $item->delete();
        }
        if ($r->type == 'category') {
            $category = Category::find($r->id);
            $delete = $category->delete();
        }
        if (!$delete) {
            return response()->json(['deleted' => false]);
        }
        return response()->json(['deleted' => true]);
    }

    public function getUser()
    {
        $data['users'] = User::with('role', 'dept')->get();
        $roles = Role::get();
        $departments = Department::get();
        return view('layouts.user', compact('data', 'roles', 'departments'));
    }

    public function storeUser(UserFormVal $request)
    {
        $user = new User;
        $user->name = $request->User_Name;
        $user->role_id = $request->User_Role;
        $user->dept_id = $request->department_name;
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->req_limit = $request->limit;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();
        $data = "New user has been created successfully.";
        $item = User::with('role', 'dept')->find($user->id);
        return array($data, $item);
    }

    public function deleteUser(Request $req)
    {
        $user = User::findOrFail($req->id);
        $user->status = false;
        $user->update();
        $data = "Requested User has been deleted successfully!";
        return array($data);
    }

    public function getOneUser($id)
    {
        $user = User::findOrFail($id);
        $role = Role::findOrFail($user->role->id);
        $vessel_name = !empty($role->vessel->name) ? $role->vessel->name : '';
        return array($user, $role, $vessel_name);
    }

    public function editUser(Request $request)
    {
        $item = User::with('role', 'dept')->find($request->id);
        return response()->json([
            'item' => $item
        ]);
    }

    public function updateOneUser(updateUserFormVal $request)
    {
        $user = User::findOrFail($request->id);
        if (!empty($request->email)) {
            $user->email = $request->email;
        }
        if (!empty($request->User_Name)) {
            $user->name = $request->User_Name;
        }
        if (!empty($request->User_Role)) {
            $user->role_id = $request->User_Role;
        }
        if (!empty($request->department_name)) {
            $user->dept_id = $request->department_name;
        }
        if (!empty($request->limit)) {
            $user->req_limit = $request->limit;
        }
        if (!empty($request->username)) {
            $user->username = $request->username;
        }
        if (!empty($request->designation)) {
            $user->designation = $request->designation;
        }
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->update();
        $data = "Requested user has been updated successfully.";
        $item = User::with('role', 'dept')->find($user->id);

        return array($data, $item);
    }

    public function addDelQty(Request $req)
    {
        $order = Order::findOrFail($req->orderId);
        $tempReq = null;
        $colname = '';
        if (!empty($req->deliver_qty && auth()->user()->role->role == 'am-ssm')) {
            $tempReq = $req->deliver_qty;
            $colname = 'del_item_qty';
        } elseif (!empty($req->rcv_qty && auth()->user()->role->role == 'second-engineer')) {
            $tempReq = $req->rcv_qty;
            $colname = 'rcv_item_qty';
        } elseif (!empty($req->req_qty && auth()->user()->role->role == 'am-srd')) {
            $tempReq = $req->req_qty;
            $colname = 'item_qty';
        }
        foreach ($tempReq as $key => $value) {
            foreach ($order->orderItems as $orderItem) {
                if ($orderItem->id == $key) {
                    $orderitem = OrderItem::findOrFail($key);
                    $orderitem->$colname = $value;
                    $orderitem->update();
                }
            }
        }
        if (auth()->user()->role->role == 'am-ssm') {
            $data = 'Delivered Quantity Updated Successfully!';
        } elseif (auth()->user()->role->role == 'second-engineer') {
            $data = 'Received Quantity Updated Successfully!';
        } elseif (auth()->user()->role->role == 'am-srd') {
            $data = 'Required Quantity Updated Successfully!';
        }
        return array($data);
    }

    public function addsingleDelQty(Request $r)
    {
        $colname = '';
        if (auth()->user()->role->role == 'am-ssm') {
            $colname = 'del_item_qty';
            $data = 'Requested Delivered Quantity Updated Successfully!';
        } elseif (auth()->user()->role->role == 'second-engineer') {
            $colname = 'rcv_item_qty';
            $data = 'Requested Received Quantity Updated Successfully!';
        } elseif (auth()->user()->role->role == 'am-srd') {
            $colname = 'item_qty';
            $data = 'Requested Required Quantity Updated Successfully!';
        }
        $orderitem = OrderItem::findOrFail($r->itemId);
        $orderitem->$colname = $r->itemValue;
        $orderitem->update();

        return array($data);
    }

    public function report_requisition()
    {
        $startDate = Carbon::now();
        $from_date = $startDate->firstOfMonth();;
        $end_date = \Carbon\Carbon::now()->endOfMonth();
        $lists = Order::with('category', 'dept')->get();

        return view('layouts.report-requisition', compact('from_date', 'end_date', 'lists'));
    }


    public function searchRequisition(Request $request)
    {
        $status_id = $request->status_name;

        if ($request->has('from_date') && $request->has('end_date')) {
            $from_date = $request->from_date;
            $end_date = $request->end_date;
        } else {
            $from_date = null;
            $end_date = null;
        }
        $active_requisitions = Order::Approved()->with('category', 'dept')->whereBetween('req_date', [$from_date, $end_date])->get();
        $pending_requisitions = Order::with('category', 'dept')->Pending()->whereBetween('req_date', [$from_date, $end_date])->get();
        $rejected_requisitions = Order::with('category', 'dept')->Rejected()->whereBetween('req_date', [$from_date, $end_date])->get();
        $table = '';
        if ($status_id == 1 && !empty($active_requisitions)) {
            foreach ($active_requisitions as $key => $value) {

                //                <tbody id="report_requisition-".$value->id>
                $dept_name = $value->dept->name;
                $table .= "<tr>";
                $table .= "<td>$value->req_no</td>";
                $table .= "<td>$dept_name</td>";
                $table .= "<td>$value->req_date</td>";
                $table .= "<td>$value->status</td>";
                $table .= "<td>$value->created_by</td>";
                $table .= "</tr>";
            }
        } else if ($status_id == 2 && !empty($pending_requisitions)) {
            foreach ($pending_requisitions as $key => $value) {
                $dept_name = $value->dept->name;
                $table .= "<tr>";
                $table .= "<td>$value->req_no</td>";
                $table .= "<td>$dept_name</td>";
                $table .= "<td>$value->req_date</td>";
                $table .= "<td>$value->status</td>";
                $table .= "<td>$value->created_by</td>";
                $table .= "</tr>";
            }
        } else if ($status_id == 3 && !empty($rejected_requisitions)) {
            foreach ($rejected_requisitions as $key => $value) {
                $dept_name = $value->dept->name;
                $table .= "<tr>";
                $table .= "<td>$value->req_no</td>";
                $table .= "<td>$dept_name</td>";
                $table .= "<td>$value->req_date</td>";
                $table .= "<td>$value->status</td>";
                $table .= "<td>$value->created_by</td>";
                $table .= "</tr>";
            }
        } else {
            $table .= "Not found";
        }
        return response()->json(['ship_id' => $status_id, 'table' => $table]);
    }

    public function report_stock()
    {

        $startDate = Carbon::now();
        $from_date = $startDate->firstOfMonth();;
        $end_date = \Carbon\Carbon::now()->endOfMonth();
        $stocks = Stock::with('item')->Active()->get();

        return view('layouts.report-stock', compact('stocks', 'from_date', 'end_date'));
    }



    public function searchStock(Request $request)
    {
        $from_date = date($request->from_date);
        $end_date = date($request->end_date);
        $purhcase = Purchase::select('item_id')
            ->selectRaw("SUM(qty) as total_qty")
            ->whereBetween('created_at', [$from_date, $end_date])
            ->groupBy('item_id')
            ->get();
        $delivered = OrderItem::select('item_id')
            ->whereBetween('created_at', [$from_date, $end_date])
            ->selectRaw("SUM(received_qty) as qty")
            ->groupBy('item_id')
            ->get();
        $table = '';
        if (!empty($purhcase)) {
            foreach ($purhcase as $put) {
                $product_name = $put->item->name;
                $table .= "<tr>";
                $table .= "<td>$product_name</td>";
                $delivereditem = $this->searchForId($put->item_id, $delivered);
                if ($delivereditem) {
                    $totalqty=$put->total_qty-$delivereditem->qty;
                    $table .= "<td>$totalqty</td>";
                } else {
                    $table .= "<td>$put->total_qty</td>";
                }
                $table .= "</tr>";
            }
        } else {

            $table .= "Not found";
        }
        return response()->json(['table' => $table]);
    }

    /* function for search in a collection by itemid
        and return particular value
    */
    function searchForId($itemid, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['item_id'] === $itemid) {
                return $val;
            }
        }
        return null;
    }

    public function report_total_delivered()
    {
        $startDate = Carbon::now();
        $from_date = $startDate->firstOfMonth();;
        $end_date = \Carbon\Carbon::now()->endOfMonth();
        $delivered_lists = OrderItem::with('order', 'item')->where('received_qty', '!=', null)->get();

        return view('layouts.report-total-delivered', compact('delivered_lists', 'from_date', 'end_date'));
    }

    public function report_total_received()
    {
        //        $startDate = Carbon::now();
        //        $from_date = $startDate->firstOfMonth();;
        //        $end_date = \Carbon\Carbon::now()->endOfMonth();

        $userOrders = auth()->user()->dept->orders->pluck('id');
        $lists = Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'order_items.id as order_item_id',
                'items.id as itemId',
                'items.name',
                'items.unit',
                'order_items.received_qty',
                'order_items.updated_at'
            )
            ->where('order_items.received_qty', '!=', null)
            ->whereIn('order_items.order_id', $userOrders)
            ->orderBy('order_items.id', 'desc')
            ->get();
        //        dd($lists);
        return view('layouts.report-received-product', compact('lists'));
    }

    public function search_total_received(Request $request)
    {
        $from_date = $request->from_date;
        $end_date = $request->end_date;


        $userOrders = auth()->user()->dept->orders->pluck('id');
        $filtered_orders = Order::whereBetween('updated_at', [$from_date, $end_date])->get();

        $lists = Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'order_items.id as order_item_id',
                'items.id as itemId',
                'items.name',
                'items.unit',
                'order_items.received_qty',
                'order_items.updated_at'
            )
            ->where('order_items.received_qty', '!=', null)
            ->whereBetween('orders.updated_at', [$from_date, $end_date])
            ->whereIn('order_items.order_id', $userOrders)
            ->orderBy('order_items.id', 'desc')
            ->get();
        //            dd($lists);
        return response()->json(['lists' => $lists]);
    }

    public function searchTotalDelivered(Request $request)
    {
        $from_date = $request->from_date;
        $end_date = $request->end_date;


        $lists = OrderItem::with('order', 'item')->where('received_qty', '!=', null)
            ->whereBetween('created_at', [$from_date, $end_date])->get();

        $table = '';
        if (!empty($lists)) {
            foreach ($lists as $key => $value) {
                $user_id = $value->order->createdBy->username;
                $user_name = $value->order->createdBy->name;
                $dept_name = $value->order->dept->name;
                $product_name = $value->item->name;
                $delivered_qty = $value->delivered_qty;
                $table .= "<tr>";
                $table .= "<td>$user_id</td>";
                $table .= "<td>$user_name</td>";
                $table .= "<td>$dept_name</td>";
                $table .= "<td>$product_name</td>";
                $table .= "<td>$delivered_qty</td>";

                $table .= "</tr>";
            }
        } else {

            $table .= "Not found";
        }

        return response()->json(['table' => $table]);
    }


    public function searchOrder(Request $req)
    {
        $items = Item::Active()->get();
        $categories = Category::Active()->get();
        $vessels = Vessel::orderBy('created_at', 'desc')->where('status', true)->get();
        $ship_id = $req->ship_id;
        $cat_id = $req->cat_id;
        $item_id = $req->item_id;
        $dateBetween = null;
        $category = null;
        $from_date = null;
        $end_date = null;
        if (!empty($cat_id)) {
            $category = Category::where('id', $cat_id)->first();
        }
        if ($req->from_date != '' && $req->end_date == '') {
            $from_date = $req->from_date;
        }
        if ($req->from_date == '' && $req->end_date != '') {
            $end_date = $req->end_date;
        }
        if ($req->from_date != '' && $req->end_date != '') {
            $dateBetween = array('from' => $req->from_date, 'to' => $req->end_date);
        }
        $orders = Order::where('ord_status', true)
            ->when($ship_id, function ($query, $ship_id) {
                return $query->where('vessel_id', $ship_id);
            })
            ->when($cat_id, function ($query, $cat_id) {
                return $query->where('category_id', $cat_id);
            })
            ->when($dateBetween, function ($query, $dateBetween) {
                return $query->whereBetween('req_date', [$dateBetween['from'], $dateBetween['to']]);
            })
            ->when($from_date, function ($query, $from_date) {
                return $query->whereDate('req_date', $from_date);
            })
            ->when($end_date, function ($query, $end_date) {
                return $query->whereDate('req_date', $end_date);
            })
            ->when($item_id, function ($query, $item_id) {
                return $query->whereHas('orderItems', function ($query1) use ($item_id) {
                    return $query1->where('item_id', $item_id);
                })->orWhereDoesntHave('orderItems');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view(!empty($item_id) ? 'layouts.order-item' : 'layouts.order', compact('orders', 'items', 'categories', 'vessels', 'item_id', 'ship_id', 'cat_id', 'from_date', 'end_date', 'category'));
    }

    public function getProfile()
    {
        $profile = User::findOrFail(auth()->id());
        return view('profile', compact('profile'));
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6'
        ]);
        $user = User::findOrFail(auth()->user()->id);
        if (Hash::check($request->current_password, $user['password']) && $request->new_password == $request->new_password_confirmation) {
            $user->password = bcrypt($request->new_password);
            $user->update();
            return back()->with('success', 'Password Changed Successfully!');
        } else {
            return back()->with('error', 'Old Password does not matched!');
        }
    }

    public function changeFile(Request $request)
    {
        if ($request->photo == '' && $request->signature == '') {
            return back()->with('warning', 'Photo & Signature required');
        }
        $user = User::findOrFail(auth()->user()->id);
        if ($user->photo == '' && $user->sign == '') {
            $this->validate($request, [
                'photo' => 'required|mimes:jpeg,jpg,png|max:1000',
                'signature' => 'required|mimes:jpeg,jpg,png|max:1000'
            ]);
        } elseif ($user->photo == '') {
            $this->validate($request, [
                'photo' => 'required|mimes:jpeg,jpg,png|max:1000',
            ]);
        } elseif ($user->sign == '') {
            $this->validate($request, [
                'signature' => 'required|mimes:jpeg,jpg,png|max:1000',
            ]);
        }
        if ($request->hasFile('photo')) {
            $name = 'images/userphoto/' . time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('images/userphoto'), $name);
            $user->photo = $name;
        }
        if ($request->hasFile('signature')) {
            $name = 'images/signature/' . time() . '.' . $request->signature->getClientOriginalExtension();
            $request->signature->move(public_path('images/signature'), $name);
            $user->sign = $name;
        }
        $user->update();
        return back()->with('success', 'Photo & Signature Updated Successfully!');
    }
}
