@extends(zbase_view_template_layout())
@section('content')
Site Index
| <a href="<?php echo zbase_url_from_route('admin') ?>">Admin</a>
| <a href="<?php echo zbase_url_from_route('login') ?>">Login</a>
| <a href="<?php echo zbase_url_from_route('register') ?>">Register</a>
@stop