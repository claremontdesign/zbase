@extends(zbase_view_template_layout())
@section('content')
  Home
  | <a href="{{ zbase_url_from_route('account') }}" title="Logout">Account</a>
  | <a href="{{ zbase_url_from_route('logout') }}" title="Logout">Logout</a>
@stop