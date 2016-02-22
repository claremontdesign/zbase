@extends(zbase_view_template_layout())
@section('content')
{!! view(zbase_view_file_contents('errors.500'), compact('msg', 'code')) !!}
@stop