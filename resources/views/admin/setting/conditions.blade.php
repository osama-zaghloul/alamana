@extends('admin.include.master')
@section('title') لوحة التحكم | الشروط والاحكام @endsection
@section('content')

<section class="content">
        <div class="row">
                <div class="col-xs-12">
              {{ Form::open(array('method' => 'patch','url' => 'adminpanel/conditions/'.$cancelpolicy->id )) }}
                <div class="box-body">

                    <!-- editor -->
                    <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header">
                                <h3 class="box-title" >
                                 الشروط والاحكام 
                                </h3>
                                </div>
                                <div class="box-body pad">
                                    <textarea id="editor1" name="arconditions" rows="10" cols="80" required>{!! $cancelpolicy->arconditions !!}</textarea>
                                    @if ($errors->has('arconditions'))
                                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('arconditions') }}</div>
                                    @endif
                                </div>
                            </div>
                    </div>

                      <!-- editor -->
                    

    <div class="box-footer">
        <button type="submit" class="btn btn-primary col-md-offset-4 col-md-4">تعديل</button>
    </div>
    {!! Form::close() !!}
    </div>
</div> 
</div>
</section>

@endsection 