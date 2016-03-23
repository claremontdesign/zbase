@extends(zbase_view_template_layout())
@section('content')
<?php echo view(zbase_view_file_contents($ui->widgetViewFile()), $ui->viewParams()); ?>
@stop
