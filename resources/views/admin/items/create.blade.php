@extends('admin/include/master')
@section('title') لوحة التحكم | إضافة منتج جديد @endsection
@section('content')

<section class="content">
        <div class="row">
                <div class="col-xs-12">
                <div class="box box-primary">

              <div class="box-header with-border">
                <h3 class="box-title">إضافة منتج جديد</h3>
              </div>
              
              {!! Form::open(array('method' => 'POST','files' => true,'url' =>'adminpanel/items')) !!}
                <div class="box-body">  
                
                  <div class="form-group col-md-12">
                    <label>كود المنتج</label>
                    <input type="text" class="form-control" name="code" placeholder="ادخل كود المنتج" value="{{ old('code') }}" required>
                    @if ($errors->has('code'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('code') }}</div>
                    @endif  
                  </div>
                  
                  <div class="form-group col-md-6">
                    <label>اسم المنتج </label>
                    <input type="text" class="form-control" name="artitle" placeholder="ادخل اسم المنتج " value="{{ old('artitle') }}" required>
                    @if ($errors->has('artitle'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('artitle') }}</div>
                    @endif  
                  </div>
                  
                   <div class="form-group col-md-6">
                        <label>الاقسام</label>
                        <select class="form-control"  name="category_id" required>
                            <option value="0" disabled="" selected="">اختار القسم</option>
                            @foreach($allcats as $cat)
                                <option value="{{$cat->id}}"> {{$cat->name}} </option>
                            @endforeach
                        </select>
                        @if ($errors->has('category_id'))
                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('category_id') }}</div>
                        @endif  
                    </div>

                  {{-- <div class="form-group col-md-6">
                    <label>اسم المنتج باللغة الانجليزية</label>
                    <input type="text" class="form-control" name="entitle" placeholder="ادخل اسم المنتج باللغة الانجليزية" value="{{ old('entitle') }}" required>
                    @if ($errors->has('entitle'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('entitle') }}</div>
                    @endif  
                  </div>

                    <div class="form-group col-md-6">
                        <label>الاقسام</label>
                        <select class="form-control"  name="category_id" required>
                            <option value="0" disabled="" selected="">اختار القسم</option>
                            @foreach($allcats as $cat)
                                <option value="{{$cat->id}}"> {{$cat->arcategory}} </option>
                            @endforeach
                        </select>
                        @if ($errors->has('category_id'))
                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('category_id') }}</div>
                        @endif  
                    </div>

                    <div class="form-group col-md-6">
                      <label>رقم الواتس</label>
                      <input type="text" name="whatsapp" class="form-control" placeholder = 'رقم الواتس'>
                      @if ($errors->has('whatsapp'))
                          <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('whatsapp') }}</div>
                      @endif  
                  </div> --}}

                  <div class="form-group col-md-6">
                    <label>السعر [ريال]</label>
                    <input type="number" name="price" class="form-control" placeholder = 'ادخل السعر' required>
                    @if ($errors->has('price'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('price') }}</div>
                    @endif  
                  </div>
                  
                   <div class="form-group col-md-6">
                    <label>الخصم </label>
                    <input type="number" name="discount" class="form-control" placeholder = 'ادخل الخصم' >
                 
                  </div>

                  {{-- <div class="form-group col-md-6">
                        <label>حالة المنتج</label>
                        <select id="itme_offer" class="form-control"  name="offer" required>
                            <option value="0" selected>عادى</option>
                            <option value="1" >حالة عرض</option>
                        </select>
                        @if ($errors->has('offer'))
                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('offer') }}</div>
                        @endif  
                  </div> --}}

                  {{-- <div id="discountprice" style="display:none;" class="form-group col-md-12">
                    <label>السعر بعد الخصم [ريال]</label>
                    <input type="number" name="discountprice" class="form-control" placeholder = 'السعر بعد الخصم'>
                    @if ($errors->has('discountprice'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('discountprice') }}</div>
                    @endif  
                  </div> --}}

                  <div class="form-group col-md-12">
                      <label>صور اكثر عن الاعلان [يمكنك رفع اكثر من صورة]</label>
                      <input type="file" name="items[]" multiple>
                      @if ($errors->has('items'))
                          <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('items') }}</div>
                      @endif  
                  </div>

                  <div class="col-md-12">
                      <div class="box box-info">
                          <div class="box-header">
                          <h3 class="box-title" > تفاصيل المنتج </h3>
                          </div>
                          <div class="box-body pad">
                              <textarea id="editor1" name="ardesc" rows="10" cols="167" required>{!! old('ardesc') !!}</textarea>
                              @if ($errors->has('ardesc'))
                                  <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('ardesc') }}</div>
                              @endif
                          </div>
                      </div>
                  </div>

                  {{-- <div class="col-md-12">
                      <div class="box box-info">
                          <div class="box-header">
                          <h3 class="box-title" > تفاصيل الاعلان باللغة الانجليزية</h3>
                          </div>
                          <div class="box-body pad">
                              <textarea id="editor2" name="endesc" rows="10" cols="167" required>{!! old('endesc') !!}</textarea>
                              @if ($errors->has('endesc'))
                                  <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('endesc') }}</div>
                              @endif
                          </div>
                      </div>
                  </div> --}}
                    
                </div>
                
                <div class="box-footer">
                  <button style="width: 20%;margin-right: 40%;" type="submit" class="btn btn-primary">إضافة</button>
                </div>
                {!! Form::close() !!}
          </div>
          </div>
          </div>
</section>

<script type="text/javascript">

    $(document).ready(function () {
        $('#itme_offer').change(function() {
         if($(this).val() == 1)  
         {
            $("#discountprice").css('display', 'block');  
         } else {  
            $("#discountprice").css('display', 'none');   
         }  
        });
    });

</script>

@endsection 
