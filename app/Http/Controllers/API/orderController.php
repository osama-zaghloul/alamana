<?php

namespace App\Http\Controllers\API;

use App\Cutting;
use App\Events\alertNot;
use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use App\member;
use App\notification;
use App\item;
use App\item_image;
use App\order_item;
use App\order;
use App\weight;
use Carbon\Carbon;
use DB;


class orderController extends BaseController
{

    public function makeorder(Request $request)
    {
        if ($request->total != 0) {
            $user = member::where('id', $request->user_id)->first();
            if ($user) {
                $neworder                = new order();
                $neworder->order_number  = date('dmY') . rand(0, 999);
                $neworder->user_id       = $request->user_id;
                $neworder->total         = $request->total;
                $neworder->paid          = $request->paid;
                $neworder->lat          = $request->lat;
                $neworder->lng          = $request->lng;
                $neworder->save();

                $orderarr  = $request->orderarr;
                $new_array = json_decode($orderarr, true);
                foreach ($new_array as $arr) {
                    $neworderitem = new order_item();
                    $neworderitem->order_id = $neworder->id;
                    $neworderitem->item_id = $arr['item_id'];
                    $neworderitem->qty     = $arr['qty'];
                    $neworderitem->price   = $arr['price'];
                    $neworderitem->cutting_id   = $arr['cutting_id'];
                    if ($arr['weight_id']) {
                        $neworderitem->weight_id   = $arr['weight_id'];
                    }
                    if ($arr['headType']) {
                        $neworderitem->headType   = $arr['headType'];
                    }

                    $neworderitem->skin   = $arr['skin'];
                    $neworderitem->packaging   = $arr['packaging'];
                    $neworderitem->minced   = $arr['minced'];
                    $neworderitem->place   = $arr['place'];
                    $neworderitem->date   = $arr['date'];
                    $neworderitem->time   = $arr['time'];
                    $neworderitem->notes   = $arr['notes'];
                    $neworderitem->bowels   = $arr['bowels'];
                    $neworderitem->deliverDay   = $arr['deliverDay '];
                    $neworderitem->save();
                }


                $notification                = new notification();
                $notification->user_id       = $request->user_id;
                $notification->notification  = 'تم إنشاء طلب جديد';
                $notification->save();

                $data = ['name' => $user->name];
                event(new alertNot($data));



                $usertoken = member::where('id', $request->user_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->value('firebase_token');
                if ($usertoken) {
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60 * 20);

                    $notificationBuilder = new PayloadNotificationBuilder('إنشاء طلب جديد');
                    $notificationBuilder->setBody($request->notification)
                        ->setSound('default');

                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['a_type' => 'message']);
                    $option       = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data         = $dataBuilder->build();
                    $token        = $usertoken;

                    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                    $downstreamResponse->tokensToDelete();
                    $downstreamResponse->tokensToModify();
                    $downstreamResponse->tokensToRetry();
                }



                $errormessage = 'تم ارسال الطلب بنجاح';
                $msg['bill_number'] = $neworder->order_number;
                $msg['message'] = $errormessage;
                return $this->sendResponse('success', $msg);
            } else {
                $errormessage = 'المستخدم غير موجود';
                return $this->sendError('success', $errormessage);
            }
        } else {
            return $this->sendError('success', 'عفوا الطلب غير صحيح من فضلك أضف منتجات');
        }
    }

    public function myorders(Request $request)
    {
        $user = member::where('id', $request->user_id)->first();
        if ($user) {
            $myorders = order::where('user_id', $request->user_id)->get();
            if (count($myorders) != 0) {
                return $this->sendResponse('success', $myorders);
            } else {
                $errormessage = 'لا يوجد طلبات';
                return $this->sendError('success', $errormessage);
            }
        } else {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }

    public function showorder(Request $request)
    {
        $showorder = order::where('id', $request->order_id)->first();
        if ($showorder) {

            $userinfo     = member::where('id', $showorder->user_id)->first();
            $orderitems   = order_item::where('order_id', $showorder->id)->get();
            $orderdetails = array();
            $itemarr      = array();

            foreach ($orderitems as $item) {
                $orderitem = item::where('id', $item->item_id)->first();
                $image     = item_image::where('item_id', $item->item_id)->value('image');
                $cutting = Cutting::where('id', $item->cutting_id)->first();
                $weight = weight::where('id', $item->weight_id)->first();
                if ($weight) {
                    array_push(
                        $itemarr,
                        array(
                            "id"           => $orderitem->id,
                            'image'        => $image,
                            'title'        => $orderitem->artitle,
                            'price'        => $item->price,
                            'qty'          => $item->qty,
                            'cutting'      => $cutting->cutting_name,
                            'weight'       => $weight->weight_name,
                            'skin'         => $item->skin,
                            'packaging'    => $item->packaging,
                            'skin'         => $item->skin,
                            'minced'       => $item->minced,
                            'place'       => $item->place,
                            'date'       => $item->date,
                            'time'       => $item->time,
                            'notes'        => $item->notes,
                            'bowels'        => $item->bowels,
                            'deliverDay'        => $item->deliverDay,
                            'headType'        => $item->headType,
                        )
                    );
                } else {
                    array_push(
                        $itemarr,
                        array(
                            "id"           => $orderitem->id,
                            'image'        => $image,
                            'title'        => $orderitem->artitle,
                            'price'        => $item->price,
                            'qty'          => $item->qty,
                            'cutting'      => $cutting->cutting_name,
                            'skin'         => $item->skin,
                            'packaging'    => $item->packaging,
                            'skin'         => $item->skin,
                            'minced'       => $item->minced,
                            'place'       => $item->place,
                            'date'       => $item->date,
                            'time'       => $item->time,
                            'notes'        => $item->notes,
                            'bowels'        => $item->bowels,
                            'deliverDay'        => $item->deliverDay,
                            'headType'        => $item->headType,
                        )
                    );
                }
            }
            array_push(
                $orderdetails,
                array(
                    "id"            => $showorder->id,
                    "order_number"  => $showorder->order_number,
                    "user_id"       => $showorder->user_id,
                    "user_name"     => $userinfo->name,
                    "user_address"  => $userinfo->address,
                    "total"         => $showorder->total,
                    "status"        => $showorder->status,
                    "paid"          => $showorder->paid,
                    "created_at"    => $showorder->created_at,
                    "items"         => $itemarr,
                )
            );
            return $this->sendResponse('success', $orderdetails);
        } else {
            $errormessage = 'الطلب غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }
}