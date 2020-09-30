@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة بيانات العضو @endsection
@section('content')  
<section class="content">
    <div class="row">
    
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li style="margin-right: 0px; width:25%" class="active "><a href="#activity" data-toggle="tab"> بيانات العضو الشخصية </a></li>
                    <li style="margin-right: 0px; width:25%"><a href="#activity1" data-toggle="tab">طلباتى</a></li>
                    <li style="margin-right: 0px; width:25%;"><a href="#activity2" data-toggle="tab">المفضلة</a></li>
                    <li style="margin-right: 0px; width:25%;"><a href="#activity3" data-toggle="tab">الفواتير</a></li>
                </ul>
                <div class="tab-content">

                    <div class="active tab-pane" id="activity">
                                    <div class="box-body">
                                        <div style="margin-top: 7%;" class="col-md-6">
                                            
                                            <div class="form-group col-md-12">
                                                <label>الاسم بالكامل</label>
                                                <input type="text" class="form-control" value="{{$showuser->name}}" readonly> 
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>الإيميل</label>
                                                <input type="text" class="form-control" value="{{$showuser->email}}" readonly> 
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>رقم الجوال</label>
                                                <input type="text" class="form-control" value="{{$showuser->phone}}" readonly> 
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>العنوان</label>
                                                <input type="text" class="form-control" value="{{$showuser->address}}" readonly> 
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                        
                                        <h3 class="box-title" style="float:left;"> {{$showuser->name}}</h3>
                                        
                                            <h4 style="float:right;margin-top: 5%;">
                                                @if($showuser->suspensed == 0)
                                                غير معطل<span> <i class="fa fa-unlock text-success"></i> </span>
                                                @else 
                                                معطل<span> <i class="fa fa-lock text-danger"></i> </span>
                                                @endif 
                                            </h4>
                                            
                                            <div class="col-md-12">
                                                @if($showuser->image == null)
                                                <img class="img-circle" style="width:100%; height:50%;" src="{{asset('users/images/default2.png')}}" alt="{{$showuser->name}}">
                                                @else 
                                                <img class="img-circle" style="width:100%; height:50%;" src="{{asset('users/images/'.$showuser->image)}}" alt="{{$showuser->name}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>  
                    </div>
            
                    <div class="tab-pane" id="activity1">
                        <div class="box">  
                            <h3 class="box-title">طلباتى</h3>
                            @if(count($myorders) != 0)
                                <div class="table-responsive box-body">
                                    <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">رقم الطلب</th>
                                                <th style="text-align:center;">إجمالى الطلب</th>
                                                <th style="text-align:center;">تاريخ الطلب</th>
                                                <th style="text-align:center;">حالة الطلب</th>
                                                <th style="text-align:center;">مشاهدة</th>
                                                <th style="text-align:center;"> حذف</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($myorders  as $order)
                                            <tr>
                                                <td style="text-align:center;">#{{$order->order_number}} </td>
                                                <td style="text-align:center;">{{$order->total}} ريال</td>
                                                <td style="text-align:center;">{{$order->created_at}} </td>
                                                <td style="text-align:center;"> 
                                                    @if($order->status == 0)
                                                        <span style="border-radius: 3px;border: 1px solid green;color: orange;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">قيد الانتظار</span>
                                                    @elseif($order->status == 1) 
                                                            <span style="border-radius: 3px;border: 1px solid green;color: springgreen;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">جارى التجهيز</span>
                                                    @elseif($order->status == 2)   
                                                            <span style="border-radius: 3px;border: 1px solid #c22356;float:left;color:crimson;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم رفض الطلب</span>
                                                    @elseif($order->status == 3)   
                                                            <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم التسليم</span>
                                                    @endif   
                                                </td>
                                                <td style="text-align:center;">
                                                    <a href='{{asset("adminpanel/orders/".$order->id)}}' class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                </td style="text-align:center;">
                                                <td style="text-align:center;">
                                                    {{ Form::open(array('method' => 'DELETE','id' => 'del'.$order->id,"onclick"=>"return confirm('هل انت متأكد ؟!')",'files' => true,'url' => array('adminpanel/orders/'.$order->id))) }}
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                            <?php $mytotal += $order->total; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col-md-12">
                                        <h3>الاجمالى : <span style="color:#500253">{{$mytotal}}</span> ريال</h3>
                                    </div>  
                                </div>
                            @else 
                            <p> لا يوجد طلبات </p>
                            @endif 
                        </div> 
                    </div>

                    <div class="tab-pane" id="activity2">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">المفضلة</h3>
                            </div>
            
                            <div class="table-responsive box-body">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;"> اسم المنتج</th>
                                            <th style="text-align:center;"> حالة المنتج</th>
                                            <th style="text-align:center;">مشاهدة</th>
                                            <th style="text-align:center;"> تعطيل </th>
                                            <th style="text-align:center;"> تعديل </th>
                                            <th style="text-align:center;"> حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($myfavourites  as $favourite)
                                    <?php $item = DB::table('items')->where('id',$favourite->item_id)->first();  ?>
                                        <tr>
                                            <td>{{$item->artitle}} </td>
                                        
                                            <td>
                                                <a href='{{asset("adminpanel/items/".$item->id)}}' class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            </td>
                                            <td>
                                            {{ Form::open(array('method' => 'patch',"onclick"=>"return confirm('هل انت متاكد ؟!')",'files' => true,'url' =>'adminpanel/items/'.$item->id )) }}
                                                <input type="hidden" name="suspensed" >
                                                <button type="submit" class="btn btn-default">
                                                @if($item->suspensed == 1) 
                                                <i style="color:crimson" class="fa fa-lock" aria-hidden="true"></i> 
                                                @else 
                                                <i style="color:#1FAB89" class="fa fa-unlock" aria-hidden="true"></i> 
                                                @endif
                                                </button>
                                            {!! Form::close() !!}
                                            </td>
                                            <td>
                                                <a href='{{asset("adminpanel/items/".$item->id."/edit")}}' class="btn btn-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            </td>

                                            <td>
                                                {{ Form::open(array('method' => 'DELETE',"onclick"=>"return confirm('هل انت متأكد ؟!')",'files' => true,'url' => array('adminpanel/items/'.$item->id))) }}
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            
                            </div>   

                        </div>
                    </div>
                    
                    <div class="tab-pane" id="activity3">
                        <div class="box">  
                            <h3 class="box-title">الفواتير</h3>
                            @if(count($myorders) != 0)
                                <div class="table-responsive box-body">
                                    <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">رقم الفاتورة</th>
                                                <th style="text-align:center;">إجمالى الفاتورة</th>
                                                <th style="text-align:center;">تاريخ الفاتورة</th>
                                                <th style="text-align:center;">حالة الفاتورة</th>
                                                <th style="text-align:center;">مشاهدة</th>
                                                <th style="text-align:center;"> حذف</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($myorders  as $order)
                                            <tr>
                                                <td style="text-align:center;">#{{$order->order_number}} </td>
                                                <td style="text-align:center;">{{$order->total}} ريال</td>
                                                <td style="text-align:center;">{{$order->created_at}} </td>
                                                <td style="text-align:center;"> 
                                                    @if($order->paid == 0)   
                                                            <span style="border-radius: 3px;border: 1px solid #c22356;float:left;color:crimson;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">غير مفعلة</span>
                                                    @elseif($order->paid == 1)   
                                                            <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">مفعلة</span>
                                                    @endif   
                                                </td>
                                                <td style="text-align:center;">
                                                    <a href='{{asset("adminpanel/orders/".$order->id)}}' class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                </td style="text-align:center;">
                                                <td style="text-align:center;">
                                                    {{ Form::open(array('method' => 'DELETE','id' => 'del'.$order->id,"onclick"=>"return confirm('هل انت متأكد ؟!')",'files' => true,'url' => array('adminpanel/orders/'.$order->id))) }}
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                            <?php $mytotal += $order->total; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col-md-12">
                                        <h3>الاجمالى : <span style="color:#500253">{{$mytotal}}</span> ريال</h3>
                                    </div>  
                                </div>
                            @else 
                            <p> لا يوجد فواتير </p>
                            @endif 
                        </div>  
                    </div>
            </div>
        </div>
    </div>
</section> 

@endsection