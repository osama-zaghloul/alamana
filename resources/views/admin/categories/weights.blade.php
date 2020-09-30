@extends('admin/include/master')
@section('title') لوحة التحكم |  الاقسام  @endsection
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">   
                <div class="box-header with-border">
                    <h3 class="box-title">قسم الأوزان  </h3>
                    <button style="float:left" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-addclass"><i class="fa fa-plus" aria-hidden="true"></i> إضافة وزن جديد</button>
                </div>  
                
                <div class="modal fade" id="modal-addclass" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">إضافة وزن جديد  </h4>
                        </div>
                        <div class="modal-body">
                            {{ Form::open(array('method' => 'POST','files'=>true,'url' => 'adminpanel/wcategories')) }}
                            
                            
                                <div class="form-group col-md-12">
                                    <label>اختر المنتج</label>
                                        <div class="form-group col-md-12">
                                            <select name="item" id="">
                                            <option value="">اختر المنتج</option>
                                                @foreach ($items as $item)
                                                               
                                            <option  value="{{$item->id}}">{{$item->artitle}}</option>

                                                 @endforeach
                                            </select>
                                             @if ($errors->has('item'))
                                              <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('item') }}</div>
                                             @endif
                                        </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>اسم الوزن</label>
                                    <div class="form-group col-md-12">
                                        <input style="width:100%;" type="text" class="form-control" name="weight_name" placeholder="اسم الوزن"  required>
                                        @if ($errors->has('weight_name'))
                                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('weight_name') }}</div>
                                        @endif
                                    </div>  
                                </div>  
                                <div class="form-group col-md-12">
                                    <label> سعر الوزن</label>
                                    <div class="form-group col-md-12">
                                        <input style="width:100%;" type="text" class="form-control" name="price" placeholder="سعر الوزن"  required>
                                        @if ($errors->has('price'))
                                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('price') }}</div>
                                        @endif
                                    </div>  
                                </div>  

                               

                                <button type="submit" class="btn btn-primary col-md-offset-4 col-md-4">اضافة</button>
                            {!! Form::close() !!}    
                        </div>
                        <div class="modal-footer">
                            
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">اغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="active tab-pane" id="activity">
                    <div class="table-responsive box-body">
                        {{-- <button style="margin-bottom: 10px;float:left;" class="btn btn-danger delete_all" data-url="{{ url('mycategoriesDeleteAll') }}"><i class="fa fa-trash-o" aria-hidden="true"></i> حذف المحدد</button> --}}
                        <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        
                                        <th style="text-align:center;">الوزن</th>
                                        <th style="text-align:center;">المنتج</th>
                                        
                                        <th style="text-align:center;"> السعر </th>
                                        <th style="text-align:center;"> تعديل </th>
                                        <th style="text-align:center;"> حذف</th> 
                                        {{-- <th width="50px"><input type="checkbox" id="master"></th> --}}
                                    </tr>
                                </thead>
                        
                                <tbody> 
                                    @foreach($weights as $weight)
                                    <?php
                                    // use App\item ;
                                    $item = DB::table('items')->where('id', $weight->item_id)->first();
                                    ?>
                                        <tr>
                                            <td>
                                                {{ $weight->weight_name}}
                                            </td>
                                            <td>
                                                {{ $item->artitle}}
                                            </td>
                                            <td>
                                                {{ $weight->price}}
                                            </td>
                                            
                                            
                                            
                                            <td>
                                                <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#modal-upclass{{$weight->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                            </td>

                                            <td>
                                                {{ Form::open(array('method' => 'DELETE',"onclick"=>"return confirm('هل انت متأكد ؟!')",'files' => true,'url' => array('adminpanel/wcategories/'.$weight->id))) }}
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                {!! Form::close() !!}
                                            </td>
                                            {{-- <td><input type="checkbox" class="sub_chk"      data-id="{{$cutting->id}}"></td> --}}
                                        </tr>

                                    <div class="modal fade" id="modal-upclass{{$weight->id}}" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title">تعديل الوزن</h4>
                                            </div>
                                            <div class="modal-body">
                                                {{ Form::open(array('method' => 'patch','files'=>true,'url' => 'adminpanel/wcategories/'.$weight->id )) }}
                                                
                                                
                                                    <div class="form-group col-md-12">
                                                    <label>اختر المنتج</label>
                                                    <div class="form-group col-md-12">
                                                        <select name="item" id="">
                                                        <!--<option value="">اختر المنتج</option>-->
                                                        
                                                            
                                                            @foreach ($items as $item)
                                                               @if($item->id == $weight->item_id)
                                                               <option  value="{{$item->id}}" selected >{{$item->artitle}}</option>
                                                              @else
                                                               <option  value="{{$item->id}}">{{$item->artitle}}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                            @if ($errors->has('item'))
                                                                <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('item') }}</div>
                                                             @endif
                                                    </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-md-12">
                                                        <label>اسم الوزن</label>
                                                        <div class="form-group col-md-12">
                                                            <input style="width:100%;" type="text" class="form-control" name="weight_name" placeholder="اسم الوزن" value="{{$weight->weight_name}}" required>
                                                            @if ($errors->has('weight_name'))
                                                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('weight_name') }}</div>
                                                            @endif
                                                        </div>  
                                                    </div>  
                                                    
                                                    <div class="form-group col-md-12">
                                                        <label> سعر الوزن</label>
                                                        <div class="form-group col-md-12">
                                                            <input style="width:100%;" type="text" class="form-control" name="price" placeholder="سعر الوزن"" value="{{$weight->price}}"  required>
                                                        @if ($errors->has('price'))
                                                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('price') }}</div>
                                                        @endif
                                                        </div>  
                                                        </div>  

                                                      

                                                    <button type="submit" class="btn btn-primary col-md-offset-4 col-md-4">تعديل</button>
                                                {!! Form::close() !!}    
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">اغلاق</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody> 
                            </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {

        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });


        $('.delete_all').on('click', function(e) {
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  


            if(allVals.length <=0)  
            {  
                alert("حدد عنصر واحد ع الاقل ");  
            }  else {  


                var check = confirm("هل انت متاكد؟");  
                if(check == true){  
                    var join_selected_values = allVals.join(","); 
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });


                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });


        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });


        $(document).on('confirm', function (e) {
            var ele = e.target;
            e.preventDefault();

            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });
            return false;
        });
    });
</script>

@endsection
