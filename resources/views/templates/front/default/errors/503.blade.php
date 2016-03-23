@extends(zbase_view_template_layout(null, 'static'))
@section('content')
{!! view(zbase_view_file_contents('errors.503'), compact('msg', 'code')) !!}
@stop