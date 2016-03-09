@extends(zbase_view_template_layout())
@section('content')
  Dashboard | <a href="{{ zbase_url_from_route('admin.logout') }}" title="Logout">Logout</a>
@stop