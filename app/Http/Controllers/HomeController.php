<?php

namespace App\Http\Controllers;
use App\customer;
use App\purchase_transaction;
use App\voucher;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function homeIndex()
    {
      return view('index');
    }

    public function startCampaign(Request $request)
    { 
      voucher::truncate();
      $customer_list = $this->getRedeemCustomerList();

      return redirect(route('getCustomerList'));
    }

    public function getCustomerList()
    {
      $customer_list = $this->getRedeemCustomerList();
      $customer_list = $customer_list->sortByDesc('redeem');

      $check_voucher = voucher::where('redeemed', 'yes')->get();
      $fully_redeem = false;
      if(count($check_voucher) >= 1000)
      {
        $fully_redeem = true;
      }

      return view('customer_list', compact('customer_list', 'fully_redeem'));
    }

    public function redeemLink($customer_id)
    {
      $customer_list = $this->getRedeemCustomerList($customer_id);
      $customer_detail = null;
      foreach($customer_list as $customer)
      {
        $customer_detail = $customer;
      }

      if(!$customer_detail)
      {
        $message = "Customer not found, please try another.";
        return view('failed', compact('message'));
      }
      elseif($customer_detail->redeem == false)
      {
        $message = "This customer cannot redeem voucher, please check the term and condition.";
        return view('failed', compact('message'));
      }

      $voucher = voucher::where('customer_id', $customer_id)->first();
      if(!$voucher)
      {
        $voucher = voucher::create([
          'customer_id' => $customer_id,
          'voucher_code' => (string) Str::uuid(),
          'expired_at' => date('Y-m-d H:i:s', strtotime(now()." +10 minutes"))
        ]);
      }

      if(strtotime($voucher->expired_at) < strtotime(now()))
      {
        return view('expired');
      }

      $check_voucher = voucher::where('redeemed', 'yes')->get();
      if(count($check_voucher) >= 1000)
      {
        return view('fully_redeem');
      }

      return view('redeem', compact('voucher'));
    }

    public function submitRedeem(Request $request)
    {
      if($request->voucher_code)
      {
        $check_voucher = voucher::where('redeemed', 'yes')->get();
        if(count($check_voucher) >= 1000)
        {
          return view('fully_redeem');
        }

        $voucher = voucher::where('voucher_code', $request->voucher_code)->whereNull('redeemed')->first();

        if($voucher)
        {
          if(strtotime($voucher->expired_at) < strtotime(now()))
          {
            return view('expired');
          }

          $img = $request->img;

          $check_img = $this->checkImg($img);

          if($check_img == true)
          {
            $img->store('voucher');
            voucher::where('id', $voucher->id)->whereNull('redeemed')->update([
              'redeemed' => "yes"
            ]);

            return view('success', compact('voucher'));
          }
        }
        else
        {
          return view('failed');
        }
      }

      return view('failed');
    }

    public function getRedeemCustomerList($customer_id = null)
    {
      if($customer_id == null)
      {
        $customer_list = customer::leftJoin('vouchers', 'vouchers.customer_id', '=', 'customers.id')->select('customers.*', 'vouchers.redeemed')->limit(100)->get();

        $transaction_list = purchase_transaction::leftJoin('customers', 'customers.id', '=', 'purchase_transaction.customer_id')->whereDate('purchase_transaction.transaction_at', '>=', date('Y-m-d H:i:s', strtotime(now()." -30 days")))->select('purchase_transaction.*', 'customers.first_name', 'customers.last_name', \DB::raw("SUM(purchase_transaction.total_spent) as total_sum_spent"), \DB::raw("SUM(purchase_transaction.total_saving) as total_sum_saving"))->groupBy('purchase_transaction.customer_id')->get();
      }
      else
      {
        $customer_list = customer::leftJoin('vouchers', 'vouchers.customer_id', '=', 'customers.id')->where('customers.id', $customer_id)->select('customers.*', 'vouchers.redeemed')->get();

        $transaction_list = purchase_transaction::leftJoin('customers', 'customers.id', '=', 'purchase_transaction.customer_id')->where('purchase_transaction.customer_id', $customer_id)->whereDate('purchase_transaction.transaction_at', '>=', date('Y-m-d H:i:s', strtotime(now()." -30 days")))->select('purchase_transaction.*', 'customers.first_name', 'customers.last_name', \DB::raw("SUM(purchase_transaction.total_spent) as total_sum_spent"), \DB::raw("SUM(purchase_transaction.total_saving) as total_sum_saving"))->groupBy('purchase_transaction.customer_id')->get();
      }

      foreach($customer_list as $customer)
      {
        $customer->redeem = false;
        $customer->voucher_redeemed = false;

        if($customer->redeemed)
        {
          $customer->voucher_redeemed = true;
        }

        foreach($transaction_list as $transaction)
        {
          if($transaction->customer_id == $customer->id)
          {
            if(($transaction->total_sum_spent - $transaction->total_sum_saving) >= 100)
            {
              $customer->redeem = true;
            }
            break;
          }
        }
      }

      return $customer_list;
    }

    public function checkImg()
    {
      return true;
    }
}
