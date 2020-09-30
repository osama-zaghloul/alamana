<?php

namespace App\Http\Controllers;

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
use App\item;
use App\order;
use App\order_item;


class adminillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainactive      = 'bills';
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
        return view('admin.bills.index',compact('mainactive','subactive','logo','itemorders','daytotal','weektotal','monthtotal','yeartotal','mytotal','nowyear','nowmonth','nowweek','nowday'));
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
        $mainactive  ='bills';
        $subactive   ='all';
        $logo        = DB::table('settings')->value('logo');
        $showorder   = order::findorfail($id);
        $ownerinfo   = DB::table('members')->where('id',$showorder->user_id)->first();
        $itemorders  = order_item::where('order_id',$id)->get();
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
        if(Input::has('confirm'))
        {
            DB::table('orders')->where('id',$id)->update(['paid' => 2]);
            
            $notification                = new notification();
            $notification->user_id       = $uporder->user_id;
            $notification->notification  = 'تم تفعيل الفاتورة بنجاح والطلب ف الطريق اليك';
            $notification->save();
            
            $usertoken = member::where('id',$uporder->user_id)->where('firebase_token','!=',null)->where('firebase_token','!=',0)->value('firebase_token');
            if($usertoken)
            {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
              
                $notificationBuilder = new PayloadNotificationBuilder('تفعيل الفاتورة');
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
            session()->flash('success','تم تفعيل الفاتورة بنجاح');
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
            $delorder->delete();
        }
        session()->flash('success','تم حذف الفاتورة بنجاح');
        return back();
    }

    public function deleteAll(Request $request)
    {
        $ids            = $request->ids;
        $selectedorders = DB::table("orders")->whereIn('id',explode(",",$ids))->get();
        foreach($selectedorders as $order)
        {
            order_item::where('order_id',$order->id)->delete();
            DB::table("orders")->where('id',$order->id)->delete();
        }
        return response()->json(['success'=>"تم الحذف بنجاح"]);
    }

}
