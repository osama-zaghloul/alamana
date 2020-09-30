<?php

namespace App\Http\Controllers;

use App\Cutting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Carbon\Carbon;
use DB;
use App\notification;
use App\member;
use App\transfer;
use App\item;
use App\order;
use App\order_item;


class adminorderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainactive      = 'orders';
        $subactive       = 'itemorders';
        $logo            = DB::table('settings')->value('logo');
        $daytotal        = 0;
        $weektotal       = 0;
        $monthtotal      = 0;
        $yeartotal       = 0;
        $mytotal         = 0;
        $nowyear         = Carbon::createFromFormat('Y-m-d H:i:s', now())->year;
        $nowmonth        = Carbon::createFromFormat('Y-m-d H:i:s', now())->month;
        $nowweek         = Carbon::createFromFormat('Y-m-d H:i:s', now())->week;
        $nowday          = Carbon::createFromFormat('Y-m-d H:i:s', now())->day;
        $itemorders      = order::orderBy('id','desc')->get();
    
        return view('admin.orders.index',compact('mainactive','subactive','logo','itemorders','daytotal','weektotal','monthtotal','yeartotal','mytotal','nowyear','nowmonth','nowweek','nowday'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mainactive  ='orders';
        $subactive   ='all';
        $logo        = DB::table('settings')->value('logo');
        $showorder   = order::findorfail($id);
        $ownerinfo   = DB::table('members')->where('id',$showorder->user_id)->first();
        $itemorders  = order_item::where('order_id',$id)->get();
        // $cutting     = DB::table('cuttings')->where('id', $itemorders->cutting_id)->first();
        // $t = order_item::all();
        $total       = 0;
        return view('admin.orders.show',compact('mainactive','subactive','logo','showorder','ownerinfo','itemorders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $uporder = order::find($id);
        if(Input::has('accept'))
        {
            DB::table('orders')->where('id',$id)->update(['status' => 1]);
            $notification                = new notification();
            $notification->user_id       = $uporder->user_id;
            $notification->notification  = 'تم قبول الطلب ';
            $notification->save();
            $usertoken = member::where('id',$uporder->user_id)->where('firebase_token','!=',null)->where('firebase_token','!=',0)->value('firebase_token');
            if($usertoken)
            {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
              
                $notificationBuilder = new PayloadNotificationBuilder('قبول الطلب');
                $notificationBuilder->setBody($request->notification)
                                    ->setSound('default');
              
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(['a_type' => 'message']);
                $option       = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $data         = $dataBuilder->build();
                $token        = $usertoken ;
              
                $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
              
                $downstreamResponse->numberSuccess();
                $downstreamResponse->numberFailure();
                $downstreamResponse->numberModification();            
                $downstreamResponse->tokensToDelete();
                $downstreamResponse->tokensToModify();
                $downstreamResponse->tokensToRetry();
            }
            session()->flash('success','تم قبول الطلب بنجاح');
            return back();
        }
        elseif(Input::has('reject'))
        {
            DB::table('orders')->where('id',$id)->update(['status' => 2]);
            $notification                = new notification();
            $notification->user_id       = $uporder->user_id;
            $notification->notification  = 'تم رفض الطلب ';
            $notification->save();

            $usertoken = member::where('id',$uporder->user_id)->where('firebase_token','!=',null)->where('firebase_token','!=',0)->value('firebase_token');
            if($usertoken)
            {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
              
                $notificationBuilder = new PayloadNotificationBuilder('رفض الطلب');
                $notificationBuilder->setBody($request->notification)
                                    ->setSound('default');
              
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(['a_type' => 'message']);
                $option       = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $data         = $dataBuilder->build();
                $token        = $usertoken ;
              
                $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
              
                $downstreamResponse->numberSuccess();
                $downstreamResponse->numberFailure();
                $downstreamResponse->numberModification();            
                $downstreamResponse->tokensToDelete();
                $downstreamResponse->tokensToModify();
                $downstreamResponse->tokensToRetry();
            }
            session()->flash('success','تم رفض الطلب بنجاح');
            return back();
        }
        elseif(Input::has('finish'))
        {
            DB::table('orders')->where('id',$id)->update(['status' => 3]);
            $notification                = new notification();
            $notification->user_id       = $uporder->user_id;
            $notification->notification  = 'تم تسليم الطلب ';
            $notification->save();
            $usertoken = member::where('id',$uporder->user_id)->where('firebase_token','!=',null)->where('firebase_token','!=',0)->value('firebase_token');
            if($usertoken)
            {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
              
                $notificationBuilder = new PayloadNotificationBuilder('تسليم الطلب');
                $notificationBuilder->setBody($request->notification)
                                    ->setSound('default');
              
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(['a_type' => 'message']);
                $option       = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $data         = $dataBuilder->build();
                $token        = $usertoken ;
              
                $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
              
                $downstreamResponse->numberSuccess();
                $downstreamResponse->numberFailure();
                $downstreamResponse->numberModification();            
                $downstreamResponse->tokensToDelete();
                $downstreamResponse->tokensToModify();
                $downstreamResponse->tokensToRetry();
            }
            session()->flash('success','تم تسليم الطلب بنجاح');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delorder = order::find($id);
        if($delorder)
        {
            order_item::where('order_id',$id)->delete();
            transfer::where('bill_number', $delorder->order_number)->delete();
            $delorder->delete();
            
        }
        session()->flash('success','تم حذف الطلب بنجاح');
        return back();
    }

    public function deleteAll(Request $request)
    {
        $ids            = $request->ids;
        $selectedorders = DB::table("orders")->whereIn('id',explode(",",$ids))->get();
        foreach($selectedorders as $order)
        {
            order_item::where('order_id',$order->id)->delete();
            transfer::where('bill_number', $order->order_number)->delete();
            DB::table("orders")->where('id',$order->id)->delete();
        }
        return response()->json(['success'=>"تم الحذف بنجاح"]);
    }

}
