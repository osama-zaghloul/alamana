<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Mail\notificationmail;
use App\Mail\contactmail;
use Illuminate\Support\Facades\Mail;
use DB;    
use Carbon\Carbon;
use App\category;
use App\order_item;
use App\Cutting;


class admincuttingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()   
    {
        $mainactive      = 'categories';
        $subactive       = 'category';
        $logo            = DB::table('settings')->value('logo');
        // $allcategories   = category::where('parent',0)->get();
        $cuttings = Cutting::all();
        return view('admin.categories.cuttings',compact('mainactive','subactive','logo','cuttings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'cutting_name'   => 'required',
           
         ]);

        $newcategory              = new Cutting;
        $newcategory->cutting_name = $request['cutting_name'];
       
        $newcategory->save();
        session()->flash('success','تم اضافة نوع تقطيع جديد');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mainactive      = 'categories';
        $subactive       = 'category';
        $logo         = DB::table('settings')->value('logo');
        // $showcategory = category::where('id',$id)->first();
         $showcategory = Cutting::where('id',$id)->first();
        if($showcategory)
        {
            $allcategories = Cutting::where('parent',$id)->get();
            return view('admin.categories.show',compact('mainactive','subactive','logo','showcategory','allcategories'));
        }
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
        $upcategory = Cutting::find($id);
        $this->validate($request,[
            'cutting_name'   => 'required',
            
         ]);

        $upcategory->cutting_name     = $request['cutting_name'];
        
        
        $upcategory->save();
        session()->flash('success','تم تعديل اسم التقطيع بنجاح');
        return back();
    }

    // public static function delete_parent($id)
    // {
    //     $category_parent = Cutting::where('parent', $id)->get();
    //     foreach ($category_parent as $sub) 
    //     {
    //         self::delete_parent($sub->id);
    //         $subdepartment = Cutting::find($sub->id);
    //         if (!empty($subdepartment)) 
    //         {
    //             $subdepartment->delete();
    //         }
    //     }
    //     $dep = Cutting::find($id)->delete(); 
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delcategory = Cutting::find($id);
        // if($delcategory)
        // {
        //     self::delete_parent($id);
        //     session()->flash('success','تم حذف التقطيع بنجاح');
        // }
        $delcategory->delete();
        return back();   
    }

    public function deleteAll(Request $request)
    {
        $ids    = $request->ids;
        $categories = DB::table("cuttings")->whereIn('id',explode(",",$ids))->get();
        foreach($categories as $category)
        {
            self::delete_parent($category->id);
        }
        return response()->json(['success'=>"تم الحذف بنجاح"]);
    }
}
