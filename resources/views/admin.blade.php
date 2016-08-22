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
  <body onselectstart="return false">
    <div class="container">
        <div class="controller">
            <div class="panel panel-danger">
              <!-- Default panel contents -->
              <div class="panel-heading">
                期号: <span id="periodNumber">{{ $mRacing->periodNumber }}</span>
                <span class="pull-right">倒计时: <span id="awardTimeInterval">{{ $mRacing->awardTimeInterval }}</span></span>
              </div>
              <div class="panel-body text-center">
                <input type="text" id="changeDate" value="{{ $mRacing->awardTime }}" readonly class="form_datetime form-control text-center" disabled>
              </div>

              <!-- List group -->
              <ul class="list-group" id="items">
                @foreach ($mRacing->awardNumbers as $awardNumber)
                <li class="list-group-item" data-id="{{$awardNumber}}">
                    {{$awardNumber}}
                </li>
                @endforeach
              </ul>
              <div class="panel-footer text-center">
                <a href="/setting">系统设置</a>
              </div>
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
        var periodNumber = $('#periodNumber').html();
        $('#awardTimeInterval').html(function () {
            $('.panel').removeClass('panel-danger').addClass('panel-primary');
            $('#changeDate').removeAttr('disabled');
            $( "#items" ).sortable({
                onUpdate: function () {
                    var data = $("#items").sortable("toArray").join(',');
                    $.post("update", { awardNumbers : data, periodNumber : periodNumber} );
                }
            });

            countdown();
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
                    $("#changeDate").attr('disabled','disabled');

                    $('#items').sortable("destroy");
                    timeoutID = window.setTimeout(countdown, 1000);
                } else {
                    timeoutID = window.setTimeout(countdown, 1000);
                }
                return s;
            })
        }
        $(".form_datetime").datetimepicker({
            format: 'yyyy-mm-dd hh:ii:00',
            autoclose: true,
            todayBtn: true,
            showMeridian: true,
            minuteStep: 1
        }).on('changeDate', function(ev){
            $.post("changeDate", { awardTime : ev.date, periodNumber : periodNumber}, function(awardTimeInterval) {
                $("#awardTimeInterval").html(awardTimeInterval);
            });
        });
    </script>
  </body>
</html>
