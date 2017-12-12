<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon;
use App;

class QueuesController extends Controller
{
    public function index()
    {
    	return view('queue.index');
    }

    public function showGenerateForm()
    {
    	return view('queue.generate');
    }

    public function generate(Request $request)
    {

    	$name = $request->get('name');
    	$category = $request->get('category');
    	$purpose = $request->get('purpose');

    	$validator = Validator::make([
    		'category' => $category,
    		'name' => $name,
    		'purpose' => $purpose
		],App\Voucher::rules());

		if($validator->fails())
		{
			\Alert::error('Problem encountered while creating a queue')->flash();
			return back()
					->withInput()
					->withErrors($validator);
		}

    	$voucher = new App\Voucher;
    	$voucher->customer_name = $name;
    	$voucher->category = $category;
    	$voucher->purpose = $purpose;
    	$voucher->validity = Carbon\Carbon::now()->endOfDay();
    	$voucher->status = 'on queue';
    	$voucher->save();

    	\Alert::success('Queue Generated')->flash();

    	return back();
    }

    public function showAttendForm(Request $request)
    {
        $id = $request->get('id');

        $this->data['voucher'] = App\Voucher::find($id);
        $this->data['voucher']->status = 'currently attended';
        $this->data['voucher']->save();

        return view('queue.attend',$this->data);
    }

    public function attend(Request $request)
    {
        $id = $request->get('id');

        $voucher = App\Voucher::find($id);
        $voucher->status = 'attended';
        $voucher->save();

        \Alert::success('Request Attended')->flash();

        return redirect(config('backpack.base.route_prefix').'/dashboard');
    }

    public function cancel(Request $request,$id)
    {
        $id = $request->get('id');

        $voucher = App\Voucher::find($id);
        $voucher->status = 'on queue';
        $voucher->save();

        \Alert::success('Request Cancelled')->flash();

        return redirect(config('backpack.base.route_prefix').'/dashboard');
    }

    public function printVoucher(Request $request)
    {

    }
}