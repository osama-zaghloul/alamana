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
use App\weight;
use App\item;


class adminweightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()   
    {
        $mainactive      = 'categories';
        $subactive       = 'wcategory';
        $logo            = DB::table('settings')->value('logo');
        // $allcategories   = category::where('parent',0)->get();
        $items = item::all();
        $weights = weight::all();
        return view('admin.categories.weights',compact('mainactive','subactive','logo','weights','items'));
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
            'weight_name'   => 'required',
            'item'   => 'required',
            'price'   => 'required',
           
         ]);

        $newcategory              = new weight;
        $newcategory->item_id = $request['item'];
        $newcategory->weight_name = $request['weight_name'];
        $newcategory->price = $request['price'];
       
        $newcategory->save();
        session()->flash('success','تم اضافة وزن جديد');
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
         $showcategory = weight::where('id',$id)->first();
        if($showcategory)
        {
            $allcategories = weight::where('parent',$id)->get();
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
        $upcategory = weight::find($id);
        $this->validate($request,[
            'weight_name'   => 'required',
            'item'   => 'required',
            'price'   => 'required',
            
         ]);

        $upcategory->item_id    = $request['item'];
        $upcategory->weight_name     = $request['weight_name'];
        $upcategory->price      = $request['price'];
        
        
        $upcategory->save();
        session()->flash('success','تم تعديل الوزن بنجاح');
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
        $delcategory = weight::find($id);
        // if($delcategory)
        // {
        //     self::delete_parent($id);
        //     session()->flash('success','تم حذف التقطيع بنجاح');
        // }
        
        $delcategory->delete();
        
        return back();   
    }

    // public function deleteAll(Request $request)
    // {
    //     $ids    = $request->ids;
    //     $categories = DB::table("cuttings")->whereIn('id',explode(",",$ids))->get();
    //     foreach($categories as $category)
    //     {
    //         self::delete_parent($category->id);
    //     }
    //     return response()->json(['success'=>"تم الحذف بنجاح"]);
    // }
}
