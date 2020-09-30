@extends('admin.include.master')
@section('title') لوحة التحكم | اعدادات التطبيق @endsection
@section('content')

<section class="content">
        <div class="row">
        <div class="col-md-12">
        <div class="box-header">
                <h3 class="box-title">اعدادات التطبيق</h3>
            </div>  
                <div class="box">
                    {{ Form::open(array('method' => 'PATCH','files' => true,'url' =>'adminpanel/setapp/'.$changelogo->id )) }}
                        <input type="hidden" name="addbrand">
                        <div class="box-body">

                        <div class="form-group col-md-6">
                            <label>لوجو  التطبيق</label>
                            <input style="width:100%;" type="file" class="form-control" name="logo">
                            @if ($errors->has('logo'))
                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('logo') }}</div>
                            @endif
                        </div>

      
                         <div class="form-group col-md-6">
                            <label>صورة لوجو  التطبيق </label>
                            <div style="margin-bottom: 0;" class="login-logo">
                                <img class="img-thumbnail" style="height: 10%;" src="{{asset('users/images/'.$changelogo->logo)}}" alt="Logo"><br>
                            </div>
                        </div>

                        <div style="padding: 24px;" class="box-footer col-md-offset-4 col-md-4">
                            <button type="submit" class="btn btn-primary col-md-12">تغيير</button>
                        </div>
                    {!! Form::close() !!}
                    </div>
                </div> 
        </div>
        </div>
</section>
<section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box-header">
                <h3 class="box-title">ارسال الاشعارات لجميع المستخدمين</h3>
            </div>  
                   <div class="box box-danger">
                    {{ Form::open(array('method' => 'POST','url' =>'adminpanel/setapp')) }}
                        <div class="box-body">

                        <div class="form-group col-md-12">
                        <label>محتوى الاشعار</label>
                            <input style="width:100%;" type="text" class="form-control" name="notification">
                            @if ($errors->has('notification'))
                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('notification') }}</div>
                            @endif
                        </div>
                        
                        <div style="padding: 24px;" class="box-footer col-md-offset-4 col-md-4">
                            <button type="submit" class="btn btn-primary col-md-12">ارسال</button>
                        </div>
                    {!! Form::close() !!}
                    </div>
                </div> 
        </div>
        </div>
</section>

@endsection