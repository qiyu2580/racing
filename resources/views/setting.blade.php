<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>系统设置</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/css.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-switch.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
        <div class="controller">
            <div class="panel panel-default">
              <div class="panel-heading">
                系统设置
                <a href="/admin" class="pull-right">返回</a>
              </div>
              <form action="/setting" method="post">
                  <div class="panel-body">
                        <div class="checkbox text-right" style="margin-top: 0">
                          <input type="checkbox" name="SystemSwitch" {{ $SystemSwitch ? 'checked' : ''  }} data-on-text="开" data-off-text="关">
                        </div>
                        <div class="input-group">
                          <span class="input-group-addon">间隔</span>
                          <input type="number" class="form-control text-right" value="{{ $TimeInterval }}" name="TimeInterval">
                          <span class="input-group-addon">分</span>
                        </div>
                  </div>
                  <div class="panel-footer text-right">
                      <button type="submit" class="btn btn-default">提交</button>
                  </div>
              </form>
            </div>
        </div>
    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/bootstrap-switch.min.js"></script>
    <script>
        $("[name='SystemSwitch']").bootstrapSwitch();
    </script>
  </body>
</html>
