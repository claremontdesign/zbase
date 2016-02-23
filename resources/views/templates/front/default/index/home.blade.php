@extends(zbase_view_template_layout())
@section('content')
  Home | <a href="{{ zbase_url_create('logout') }}" title="Logout">Logout</a>
@stop