<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\notification;
use App\setting;
use App\contact;
use App\slider;
use App\category;
use App\Cutting;
use App\item;
use App\item_image;
use App\member;
use App\City;
use App\District;
use App\rate;
use App\maincategory;
use App\transfer;
use App\weight;
use Settings;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;

class appsettingController  extends BaseController
{
    public function settingindex(Request $request)
    {
                      
        $jsonarr              = array();
        $setting              = setting::select('arprivacy','arabout','arconditions','logo')->get();
        $jsonarr['info']      = $setting;
        return $this->sendResponse('success', $jsonarr);
    }

    public function contactus(Request $request)
    {
        $newcontact          = new contact();
        $newcontact->name    = $request->name;
        $newcontact->phone   = $request->phone;
        $newcontact->email   = $request->email;
        $newcontact->message = $request->message;
        $newcontact->save();
        $errormessage =  'تم ارسال الرسالة بنجاح';
        return $this->sendResponse('success',$errormessage); 
    }

    public function categories(Request $request)
    {
        //main categories
        $cuttings= array();
        $weights = array();
        $current = array();
        $allcuttings = Cutting::orderBy('id','desc')->get();
        $allweights  = weight::where('item_id',$request->item_id)->orderBy('id','desc')->get();
        
    
        foreach ($allcuttings as $cutting) 
        {           
            array_push($cuttings, 
            array(
                    "id"      => $cutting->id,
                    "name"    => $cutting->cutting_name,
                ));
        }

        foreach ($allweights as $weight) 
        {           
            array_push($weights, 
            array(
                    "id"      => $weight->id,
                    "name"    => $weight->weight_name,
                    "price"    => $weight->price,
                ));
        }

        $current['cuttings'] = $cuttings;
        $current['weights']  = $weights;
        return $this->sendResponse('success', $current);
    }
    
    public function items(Request $request)
    {
 if($request->category_id)
 {
        $lastitems=array();
     $items = item::where('suspensed',0)->where('category_id',$request->category_id)->orderBy('id','desc')->get();

        foreach ($items as $item) 
            {  
                $image     = item_image::where('item_id',$item->id)->first();
                $favorited = 0;
                $sumrates  = 0;
                $adrates   = rate::where('item_id',$item->id)->get();
                foreach($adrates as $value)
                {
                   $sumrates+= $value->rate;
                }
                $fullrate = $sumrates != 0 ? $sumrates/count($adrates) : 0; 
               
                // if($request->user_id)
                // {
                //     $fav = DB::table('favorite_items')->where('user_id',$request->user_id)->where('item_id',$item->id)->get();
                //     $favorited = count($fav) != 0 ? 1 : 0;
                // }
                array_push($lastitems, 
                array(
                      "id"           => $item->id,
                      'image'        => $image,
                      'title'        => $item->artitle,
                      'discount'     => $item->discountprice,
                      'price'        => $item->price,
                      'details'      => $item->details,
                      'rate'         => $fullrate,
                    //   'favorited'    => $favorited,
                    ));
            }
            
        //     $allcities = City::orderBy('id','desc')->get();
            
        // foreach ($allcities as $city) 
        // {
        //     array_push($cities,array(
        //     "id"  => $city->id,
        //     "name" => $city->name,
        // ));
        // }
        
        // $alldistricts = District::orderBy('id','desc')->get();
        // foreach ($alldistricts as $district) 
        // {
        //      array_push($districts,array(
        //     "id"  => $district->id,
        //     "name" => $district->name,
        //     "city_id" => $district->cities_id,
        // ));
        // }
        
            $current['items']     = $lastitems; 
           
            return $this->sendResponse('success', $current);
 }else
 {
     $lastitems=array();
     $items = item::where('suspensed',0)->orderBy('id','desc')->get();

        foreach ($items as $item) 
            {  
                $image     = item_image::where('item_id',$item->id)->first();
                $favorited = 0;
                $sumrates  = 0;
                $adrates   = rate::where('item_id',$item->id)->get();
                foreach($adrates as $value)
                {
                   $sumrates+= $value->rate;
                }
                $fullrate = $sumrates != 0 ? $sumrates/count($adrates) : 0; 
               
                // if($request->user_id)
                // {
                //     $fav = DB::table('favorite_items')->where('user_id',$request->user_id)->where('item_id',$item->id)->get();
                //     $favorited = count($fav) != 0 ? 1 : 0;
                // }
                array_push($lastitems, 
                array(
                      "id"           => $item->id,
                      'category_id'   =>     $item->category_id,
                      'image'        => $image,
                      'title'        => $item->artitle,
                      'discount'     => $item->discountprice,
                      'price'        => $item->price,
                      'details'      => $item->details,
                      'rate'         => $fullrate,
                    //   'favorited'    => $favorited,
                    ));
            }
            
        //     $allcities = City::orderBy('id','desc')->get();
            
        // foreach ($allcities as $city) 
        // {
        //     array_push($cities,array(
        //     "id"  => $city->id,
        //     "name" => $city->name,
        // ));
        // }
        
        // $alldistricts = District::orderBy('id','desc')->get();
        // foreach ($alldistricts as $district) 
        // {
        //      array_push($districts,array(
        //     "id"  => $district->id,
        //     "name" => $district->name,
        //     "city_id" => $district->cities_id,
        // ));
        // }
        
            $current['items']     = $lastitems; 
           
            return $this->sendResponse('success', $current);
 }
    }

    public function home(Request $request)
    {
        $topsliders      = array();
        // $maincategories  = array();
        $bottomslider    = array();
        $lastitems       = array();
        $cities          = array();
        $districts       = array();
        $current         = array();
        
        
        //top sliders
        $sliders = slider::where('top',1)->where('suspensed',0)->orderBy('id','desc')->get();
        foreach ($sliders as $slider) 
            {  
                array_push($topsliders, 
                array(
                      "id"      => $slider->id,
                      'image'   => $slider->image,
                      'title'   => $slider->artitle,
                      'url'    => $slider->url,
                      'text'    => $slider->text,
                    ));
            }
        
        // //main categories
        // $groups = category::where('parent',0)->get();
        //     foreach ($groups as $group) 
        //     {           
        //         array_push($maincategories, 
        //         array(
        //              "id"      => $group->id,
        //              "name"    => $group[$lang.'category'],
        //              'image'   => $group->image,
        //             ));
        //     }

        //bottom sliders
        $sliders = slider::where('top',0)->where('suspensed',0)->orderBy('id','desc')->get();
        foreach ($sliders as $slider) 
            {  
                array_push($bottomslider, 
                array(
                      "id"      => $slider->id,
                      'image'   => $slider->image,
                      'title'   => $slider->artitle,
                      'url'    => $slider->url,
                      'text'    => $slider->text,
                    ));
            }

        //last items
        $items = item::where('suspensed',0)->orderBy('id','desc')->get();
        foreach ($items as $item) 
            {  
                $image     = item_image::where('item_id',$item->id)->first();
                $favorited = 0;
                $sumrates  = 0;
                $adrates   = rate::where('item_id',$item->id)->get();
                foreach($adrates as $value)
                {
                   $sumrates+= $value->rate;
                }
                $fullrate = $sumrates != 0 ? $sumrates/count($adrates) : 0; 
               
                if($request->user_id)
                {
                    $fav = DB::table('favorite_items')->where('user_id',$request->user_id)->where('item_id',$item->id)->get();
                    $favorited = count($fav) != 0 ? 1 : 0;
                }
                array_push($lastitems, 
                array(
                      "id"           => $item->id,
                      'image'        => $image,
                      'title'        => $item->artitle,
                      'discount'     => $item->discountprice,
                      'price'        => $item->price,
                      'details'      => $item->details,
                      'rate'         => $fullrate,
                      'favorited'    => $favorited,
                    ));
            }
            
            $allcities = City::orderBy('id','desc')->get();
            
        foreach ($allcities as $city) 
        {
            array_push($cities,array(
            "id"  => $city->id,
            "name" => $city->name,
        ));
        }
        
        $alldistricts = District::orderBy('id','desc')->get();
        foreach ($alldistricts as $district) 
        {
             array_push($districts,array(
            "id"  => $district->id,
            "name" => $district->name,
            "city_id" => $district->cities_id,
        ));
        }
        $allcats = maincategory::orderBy('id','desc')->get();   
            $current['categories']     = $allcats; 
            $current['topsliders']     = $topsliders;        
            $current['bottomslider']   = $bottomslider; 
            // $current['lastitems']      = $lastitems;
            $current['cities']         = $cities;
            $current['districts']      = $districts;
            return $this->sendResponse('success', $current);
    }
    
    public function addtransfer(Request $request)
    {
        $newtransfer                = new transfer();
        $newtransfer->name          = $request->name;
        $newtransfer->phone         = $request->phone;
        $newtransfer->bank_name         = $request->bank_name;
        // $newtransfer->bill_number   = $request->bill_number;
        $info=  DB::table('orders')->where('order_number',$request->bill_number)->first();

        if($info){
        $newtransfer->bill_number   = $request->bill_number;
        }
        
        if($request->hasFile('image'))
        {
            $image    = $request['image'];
            $filename = rand(0,9999).'.'.$image->getClientOriginalExtension();
            $image->move(base_path('users/images/'),$filename);
            $newtransfer->image = $filename;
        }
        $newtransfer->save();
        
            $notification                = new notification();
            $notification->user_id       = $request->user_id;
            $notification->notification  = 'تم انشاء طلب جديد ';
            $notification->save();
            
            $usertoken = member::where('id',$request->user_id)->where('firebase_token','!=',null)->where('firebase_token','!=',0)->value('firebase_token');
            if($usertoken)
            {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
              
                $notificationBuilder = new PayloadNotificationBuilder('إنشاء طلب جديد');
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
        $errormessage = 'تم ارسال التحويل بنجاح';
        return $this->sendResponse('success',$errormessage); 
    }

   

}
