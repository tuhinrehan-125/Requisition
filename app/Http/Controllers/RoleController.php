<?php

namespace App\Http\Controllers;

use App\Category;
use App\DeptStock;
use App\Item;
use App\Mail\ReqApproved;
use App\Mail\ReqDelivered;
use App\Mail\ReqRejected;
use App\Order;
use App\Vessel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\OrderApproval;
use App\OrderItem;
use App\Stock;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RoleController extends Controller
{
	public $approved_by_admin_officer = 'Approved by Admin-officer';
	public $reject_by_admin_officer = 'Rejected by Admin-officer';
	public $approved_by_sr_officer = 'Approved by Sr-officer';
	public $reject_by_sr_officer = 'Rejected by Sr-officer';
	public $forwarded_to_sr_officer = 'Forwarded to Sr-officer';


	public $approved_by_cfiefOfcr = 'approved by chief-officer';
	public $approved_by_second_eng = 'approved by second-engineer';
	public $approved_by_master = 'approved by master';
	public $approved_by_chief_eng = 'approved by chief-engineer';
	public $approved_by_srd_ast_m = 'approved by srd-assistant-manager';
	public $approved_by_srd_ag_m = 'approved by srd-assistant-general-manager';
	public $approved_by_srd_g_m = 'approved by srd-general-manager';
	public $approved_by_ssm_dg_m = 'approved by ssm-deputy-general-manager';
	public $approved_by_ssm_ag_m = 'approved by ssm-assistant-general-manager';
	public $forwarded_to_agm_by_srd_gm = " forwarded to Asst. General Manager (srd) by General-manager (srd)";
	public $forwarded_to_ast_m_by_srd_agm = " forwarded to Asst. Manager (srd) by Asst. General-manager (srd)";
	public $approved_by_ssm_a_m = 'delivered';
	public $approved_by_secondEngineer = 'received';

	public function createdOrder()
	{
		return $orders = Order::where('status', 'ready')
			->where('vessel_id', auth()->user()->role->vessel->id)
			->where('created_by_role', auth()->user()->role->role)
			->orderBy('created_at', 'desc')
			->where('ord_status', true)
			->get();
	}
	public function approveRequisition(Request $req)
	{
		$order = Order::findOrFail($req->id);
		$order_approval = OrderApproval::where('order_id', $req->id)->first();
		$approve_qty = $req->deliver_qty;
		$already_approved = false;
		if (!$order_approval || !$order) {
			$data = "Sorry! Something went wrong !!";
			return array($data);
		}
		DB::beginTransaction();
		try {
			$authrole = auth()->user()->role->role;
			if ($authrole == 'admin-officer') {
				if ($order_approval->admin_officer != null) {
					$already_approved = true;
				} elseif ($order_approval->sr_officer != null) {
					$already_approved = true;
				} else {
					foreach ($approve_qty as $index => $value) {
						$item_ordered = OrderItem::where("order_id", $order->id)->where('item_id', $index)->first();
						$item_ordered->approved_qty = $value;
						$item_ordered->save();
					}
					// DeptStock::addStock($order->dept_id, $deliver_qty);
					// OrderItem::substructStock($deliver_qty);
					$order->status = $this->approved_by_admin_officer;
					$order->update();
					$order_approval->admin_officer = 1;
					$order_approval->update();
				}
				$user = User::find($order->created_by);
				//sending email after approval
				Mail::to($user->email)->send(new ReqApproved($user, $order->id));
			} elseif ($authrole == 'sr-officer') {
				if ($order_approval->sr_officer != null) {
					$already_approved = true;
				} elseif ($order_approval->admin_officer != null) {
					$already_approved = true;
				} else {
					foreach ($approve_qty as $index => $value) {
						$item_ordered = OrderItem::where("order_id", $order->id)->where('item_id', $index)->first();
						$item_ordered->approved_qty = $value;
						$item_ordered->save();
					}
					// DeptStock::addStock($order->dept_id, $deliver_qty);
					// OrderItem::substructStock($deliver_qty);
					$order->status = $this->approved_by_sr_officer;
					$order->update();
					$order_approval->sr_officer = 1;
					$order_approval->update();
				}
				$user = User::find($order->created_by);
				//sending email after approval
				Mail::to($user->email)->send(new ReqApproved($user, $order->id));
			} else {
				$data = "You are not authorized to perform this action!";
				return array($data);
			}
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['success' => false, 'errmsg' => $e->getMessage(), 'line' => $e->getLine()], 500);
		}
		DB::commit();
		if ($already_approved == true) {
			$data = "Requested Requisition already approved!";
			return array($data);
		} else {
			$data = "Requested Requisition has been approved successfully!";
			return array($data);
		}
	}
	public function forwardRequisition(Request $req)
	{
		$order = Order::findOrFail($req->id);
		$order_approval = OrderApproval::where('order_id', $req->id)->first();
		$already_forwarded = false;
		if (!$order_approval || !$order) {
			$data = "Sorry! Something went wrong !!";
			return array($data);
		}
		$authrole = auth()->user()->role->role;
		if ($authrole == 'admin-officer') {
			if ($order_approval->forward_to_officer != null) {
				$already_forwarded = true;
			} else {
				$order->status = $this->forwarded_to_sr_officer;
				$order->update();
				$order_approval->forward_to_officer = 1;
				$order_approval->update();
			}
		} else {
			$data = "You are not authorized to perform this action!";
			return array($data);
		}

		if ($already_forwarded == true) {
			$data = "Requested Requisition already forwarded!";
			return array($data);
		} else {
			$data = "Requested Requisition has been forwarded successfully!";
			return array($data);
		}
	}


	public function deliveredRequisition(Request $req)
	{
		$order = Order::findOrFail($req->id);
		$deliver_qty = $req->stock_delivered_qnty;
		$order_approval = OrderApproval::where('order_id', $req->id)->first();
		if (!$order_approval || !$order) {
			$data = "Sorry! Something went wrong !!";
			return array($data);
		}
		$already_delivered = false;
		DB::beginTransaction();
		try {
			$authrole = auth()->user()->role->role;
			if ($authrole == 'stock-officer') {
				if ($order_approval->stock_officer != null) {
					$already_delivered = true;
				} else {
					foreach ($deliver_qty as $index => $value) {
						$item_ordered = OrderItem::where("order_id", $order->id)->where('item_id', $index)->first();
						$item_ordered->delivered_qty = $value;
						$item_ordered->save();
					}
					OrderItem::substructStock($deliver_qty);
					// $order->status = 'Delivered';
					// $order->update();
					$order_approval->stock_officer = 1;
					$order_approval->update();
				}

				$user = User::find($order->created_by);
				//sending email after delivered
				//Mail::to($user->email)->send(new ReqDelivered($user, $order->id));
			} else {
				$data = "You are not authorized to perform this action!";
				return array($data);
			}
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['success' => false, 'errmsg' => $e->getMessage(), 'line' => $e->getLine()], 500);
		}
		DB::commit();
		if ($already_delivered == true) {
			$data = "Requested Requisition already delivered!";
			return array($data);
		} else {
			$data = "Requested Requisition has been delivered!";
			return array($data);
		}
	}

	public function receivedRequisition(Request $req)
	{
		$order = Order::findOrFail($req->id);
		$received_qty = $req->stock_received_qnty;
		$order_approval = OrderApproval::where('order_id', $req->id)->first();
		if (!$order_approval || !$order) {
			$data = "Sorry! Something went wrong !!";
			return array($data);
		}
		$already_received = false;
		DB::beginTransaction();
		try {
			$authrole = auth()->user()->role->role;
			if ($authrole == 'dept-user') {
				if ($order_approval->user_received != null) {
					$already_received = true;
				} else {
					foreach ($received_qty as $index => $value) {
						$item_ordered = OrderItem::where("order_id", $order->id)->where('item_id', $index)->first();
						$item_ordered->received_qty = $value;
						$item_ordered->save();
					}
					DeptStock::addStock($order->dept_id, $received_qty);
					$order_approval->user_received = 1;
					$order_approval->update();
				}

			} else {
				$data = "You are not authorized to perform this action!";
				return array($data);
			}
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['success' => false, 'errmsg' => $e->getMessage(), 'line' => $e->getLine()], 500);
		}
		DB::commit();
		if ($already_received == true) {
			$data = "Requested Requisition already received!";
			return array($data);
		} else {
			$data = "Requested Requisition has been received!";
			return array($data);
		}

	}


	public function rejectRequisition(Request $req)
	{
		$order = Order::findOrFail($req->id);
		$order_approval = OrderApproval::where('order_id', $req->id)->first();
		$already_reject = false;
		if (!$order_approval || !$order) {
			$data = "Sorry! Something went wrong !!";
			return array($data);
		}
		$authrole = auth()->user()->role->role;
		if ($authrole == 'admin-officer') {
			if ($order_approval->rejection_by_admin != null) {
				$already_reject = true;
			} elseif ($order_approval->rejection_by_officer != null) {
				$already_reject = true;
			} else {
				$order->status = $this->reject_by_admin_officer;
				$order->update();
				$order_approval->rejection_by_admin = 1;
				$order_approval->rejection_note_by_admin = $req->reason;
				$order_approval->update();
			}
			$user = User::find($order->created_by);
			//sending email after rejection
			Mail::to($user->email)->send(new ReqRejected($user, $order->id));
		} elseif ($authrole == 'sr-officer') {
			if ($order_approval->rejection_by_officer != null) {
				$already_reject = true;
			} elseif ($order_approval->rejection_by_admin != null) {
				$already_reject = true;
			} else {
				$order->status = $this->reject_by_sr_officer;
				$order->update();
				$order_approval->rejection_by_officer = 1;
				$order_approval->rejection_note_by_officer = $req->reason;
				$order_approval->update();
			}
			$user = User::find($order->created_by);
			//sending email after rejection
			Mail::to($user->email)->send(new ReqRejected($user, $order->id));
		} else {
			$data = "You are not authorized to perform this action!";
			return array($data);
		}

		if ($already_reject == true) {
			$data = "Requested Requisition already rejected!";
			return array($data);
		} else {
			$data = "Requested Requisition has been rejected successfully!";
			return array($data);
		}
	}
}
