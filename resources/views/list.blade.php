<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台管理</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
        <div class="">
            <div class="panel panel-default">
                  <!-- Default panel contents -->
                  <div class="panel-heading">中奖历史</div>

                  <!-- Table -->
                  <table class="table">
                    <thead>
                      <tr>
                        <th>期号</th>
                        <th>中奖号</th>
                        <th>时间</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($mRacing as $item)
                      <tr>
                        <th scope="row">{{$item->periodNumber}}</th>
                        <td>{{$item->awardNumbers}}</td>
                        <td>{{$item->awardTime}}</td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
        </div>
    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/Sortable.min.js"></script>
    <script src="bootstrap/jquery.binding.js"></script>
    <script src="bootstrap/bootstrap-datetimepicker.min.js"></script>
    <script src="bootstrap/bootstrap-datetimepicker.zh-CN.js"></script>

    <script>
        $('#awardTimeInterval').html(function () {
            $('.panel').removeClass('panel-danger').addClass('panel-primary');
            $( "#items" ).sortable({
                onUpdate: function () {
                    var periodNumber = $('#periodNumber').html();
                    var data = $("#items").sortable("toArray").join(',');
                    $.post("update", { awardNumbers : data, periodNumber : periodNumber} );
                }
            });

            countdown();
            return ($(this).html() / 1E3).toFixed(0);
        });

        var timeoutID;
        function countdown () {
            $('#awardTimeInterval').html(function () {
                var s = $(this).html() - 1;
                if (s <= -1) {
                    $('.panel').removeClass('panel-primary').addClass('panel-danger');
                    window.clearTimeout(timeoutID);
                } else if (s <= 1) {
                    location.reload();
                } else if (s <= 5) {
                    $('.panel').removeClass('panel-primary').addClass('panel-danger');
                    $('#items').sortable("destroy");
                    timeoutID = window.setTimeout(countdown, 1000);
                } else {
                    timeoutID = window.setTimeout(countdown, 1000);
                }
                return s;
            })
        }
    </script>
  </body>
</html>
