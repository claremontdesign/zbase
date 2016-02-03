@extends(zbase_view_template_layout())
@section('content')
<form action="{{ zbase_url_from_current() }}" method="post" >
	{{ zbase_csrf_token_field() }}
	<input type="email" name="email" placeholder="Enter Email Address" value="" />
	<input type="submit" value="Submit">
</form>
@stop
