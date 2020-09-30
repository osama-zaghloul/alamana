<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\member;
use App\order;
use App\item;
use App\item_image;
use App\rate;
use App\notification;
use App\favorite_item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;
use DB;

class userController extends BaseController
{
    //registeration process 
    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'name'           => 'required',
                'email'          => 'required', 
                'phone'          => 'required|unique:members',
                'address'        => 'required',
                'password'       => 'required|min:6',  
            ],
            [
                'name.required'         => 'هذا الحقل مطلوب',
                'email.required'        => 'هذا الحقل مطلوب',
                'phone.required'        => 'هذا الحقل مطلوب',
                'phone.unique'          => 'تم اخذ هذا الهاتف سابقا',
                'address.required'      => 'هذا الحقل مطلوب',
                'password.required'     => 'هذا الحقل مطلوب',
                'password.min'          => 'كلمة المرور لا تقل عن 6 احرف', 
            ]
        );
       
        if($validator->fails())
        {
            return $this->sendError('success', $validator->errors());       
        }

        $newmember                 = new member;
        $newmember->name           = $request['name'];
        $newmember->email          = $request['email'];
        $newmember->phone          = $request['phone'];
        $newmember->address        = $request['address'];
        $newmember->password       = Hash::make($request['password']);
        $newmember->firebase_token = $request['firebase_token'];    
        $newmember->save();
        $reguser = member::find($newmember->id);

        $notification                = new notification();
        $notification->user_id       = $newmember->id;
        $notification->notification  = 'تم تسجيل حسابك بنجاح';
        $notification->save();

        return $this->sendResponse('success', $reguser); 
    }

    //Login process
    public function login(Request $request)
    {
        
            $validator = Validator::make($request->all(), [
            'phone'          => 'required',
            'password'       => 'required',
        ],[
            'phone.required' => 'هذا الحقل مطلوب',
        ]);
        

        if($validator->fails())
        {
            return $this->sendError('success', $validator->errors());
        }

        if(Auth::attempt(['phone' => $request->phone , 'password' => $request->password , 'suspensed' => 0 ])) 
        {
            $user                 = Auth::user();
            $user->firebase_token = $request->firebase_token;
            $user->save();
            return $this->sendResponse('success', $user);
        }
        else
        {
           $errormessage = 'رقم الجوال أو كلمة المرور غير صحيحة';
            return $this->sendError('success', $errormessage);
        }
    }

    //forgetpassword process
    public function forgetpassword(Request $request)
    {
        $user = member::where('email',$request->email)->first();
        if(!$user)
        {
            $errormessage = ' الإيميل غير صحيح';
            return $this->sendError('success', $errormessage);
        }
        else
        {
            $randomcode        = substr(str_shuffle("0123456789"), 0, 4);
            $user->forgetcode  = $randomcode;
            $user->save();
            
            // $to      = $request->email;
            // $subject = "Confirm Code";
            // $txt     = "Confirm Code :". $user->forgetcode;
            // $headers = "From: kabsh@eltamiuz.net";
            // if(mail($to,$subject,$txt,$headers))
            // {
            //     return $this->sendResponse('success',$randomcode);
            // }
            // else
            // {
            //     return $this->sendError('success','Failed Sent Email');
            // }
            
            return $this->sendResponse('success',$user->forgetcode);
        } 
    }

    public function activcode(Request $request)
    {
      $user = member::where('email',$request->email)->where('forgetcode',$request->forgetcode)->first();
      if($user)
      {
        return $this->sendResponse('success','true');
      }
      else 
      {
        $errormessage = ' الكود غير صحيح';
        return $this->sendError('success',$errormessage);
      }
    }
    
        //rechangepassword process
    public function rechangepass(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
          'new_password'    => 'required',
        ]);
           
      if($validator->fails())
        {
            return $this->sendError('success', $validator->errors());       
        }

        $user = member::where('email',$request->email)->first();
        if($user)
        {
            $user->password = Hash::make($request->new_password);
            $user->save();
            $errormessage = 'تم تغيير كلمة المرور بنجاح';
            return $this->sendResponse('success',$errormessage);
        }
        else 
        {
            $errormessage ='هذا الإيميل غير صحيح';
            return $this->sendError('success', $errormessage);
        }
     
    }

    //profile process
    public function profile(Request $request)
    {
        $user = member::where('id',$request->user_id)->where('suspensed',0)->first();
        if(!$user)
        { 
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
        else
        {
            return $this->sendResponse('success', $user);
        } 
    }

    //updating profile process
    public function update(Request $request)
    {
       $upuser = member::where('id',$request->user_id)->first();
        if($upuser)
        {
                $validator = Validator::make($request->all(), [
                    'name'        => 'required',
                    'email'       => 'required',
                    'phone'       => 'required|unique:members,phone,'.$upuser->id,
                    'address'     => 'required',
                    
                ]);
                
                if($validator->fails())
                {
                    return $this->sendError('success', $validator->errors());       
                }

                $upuser->name      = $request['name']    ;
                $upuser->email     = $request['email']   ;
                $upuser->phone     = $request['phone']   ;
                $upuser->address   = $request['address'] ;
                $upuser->password  = $request['password'] ? Hash::make($request['password']) : $upuser->password;
                $upuser->save();
                return $this->sendResponse('success', $upuser);
            }
          else
          {    
            $errormessage ='هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
          }
    }

    public function mynotification(Request $request)
    {
        DB::table('notifications')->where('user_id', $request->user_id)->update(['readed' => 1]);
        $mynotifs = notification::where('user_id',$request->user_id)->orderBy('id','desc')->get();
        if(count($mynotifs) != 0)
        {
            return $this->sendResponse('success', $mynotifs);
        }
        else 
        {
            $errormessage = 'لا يوجد تنبيهات';
            return $this->sendError('success', $errormessage);
        }
    }
    
    public function deletenotification(Request $request)
    {
        $notification= DB::table('notifications')->where('user_id', $request->user_id)->where('id', $request->notification_id);
        $notification->delete();
            $errormessage = 'تم حذف الاشعار';
            return $this->sendResponse('success', $errormessage);
        
    }

    public function myfavoriteitems(Request $request)
    {
        $favitems  = favorite_item::where('user_id',$request->user_id)->orderBy('id','desc')->get(); 
            if(count($favitems) == 0)
            {  
              $errormessage = 'لا يوجد منتجات ف المفضلة';
              return $this->sendError('success', $errormessage);
            }
            else 
            {
              foreach($favitems as $item)
              {
                $allfavads[] = item::where('id',$item->item_id)->first();
              }
              
              $currentitems = array();
              foreach($allfavads as $item)
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
               
                $fav = DB::table('favorite_items')->where('user_id',$request->user_id)->where('item_id',$item->id)->get();
                $favorited = count($fav) != 0 ? 1 : 0;
                
                array_push($currentitems, 
                array(
                    "id"              => $item->id,
                    'image'           => $image,
                    'title'           => $item->artitle,
                    "price"           => $item->price,
                    "discountprice"   => $item->discountprice,
                    "details"         => $item->details,
                    'rate'            => $fullrate,
                    'favorited'       => $favorited,
                    ));
              }
              return $this->sendResponse('success', $currentitems);

            return $this->sendResponse('success', $allfavads);
            }
    }
    
    public function updatefirebasebyid(Request $request)
    { 
       $user = member::where('id',$request->user_id)->first();
        if($user)
        {
            $user->firebase_token = Hash::make($request->firebase_token);
            $user->save();
            $errormessage ='تم التحديث';
            return $this->sendResponse('success',$errormessage);  
        }
        else
        {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }
    
        //changepassword process
    public function changepassword(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
          'new_password'    => 'required',
        ]);
           
      if($validator->fails())
        {
            return $this->sendError('success', $validator->errors());       
        }

        $user = member::where('id',$request->user_id)->first();
        if($user)
        {
            $user->password = Hash::make($request->new_password);
            $user->save();
            $errormessage = 'تم تغيير كلمة المرور بنجاح';
            return $this->sendResponse('success',$errormessage);
        }
        else 
        {
            $errormessage ='هذا العضو غير موجود';
            return $this->sendError('success', $errormessage);
        }
     
    }
}
