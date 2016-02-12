@extends(zbase_view_template_layout())
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
					{!! view(zbase_view_file_contents('auth.password.reset'), compact('token','email')) !!}
                </div>
            </div>
        </div>
    </div>
</div>

@stop