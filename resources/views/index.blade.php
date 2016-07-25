<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>开奖</title>
    <link href="css/lanren.css" type="text/css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" type="text/css" />
</head>
<body>
<center>

<div class="top_daohang">
<nav class="navbar navbar-inverse navbar-static-top" style="width:980px">
</nav>
</div>
<div id="topDiv" align="center">
    <table width="1010" border="0" cellpadding="0" cellspacing="0">
    <!--iframe src="sp.html" width="980" height="630" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe-->
    <!--http://www.52pkgame.com/mobile_52pkgame/pk10/-->
    <iframe src="/pk10" width="980" height="630" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
    </table>
</div>

<div class="g_w1000min open_form">
    <div class="g_w1000 ">
        <div class="sub_right  ">
            <div class="sub_r_bot">

                <div class="sub_bot_one">
                    <span class="sub_bot_bt">开奖记录</span>
                </div>
            </div>
<style>
    .dx_shadow { width: 1000px; }

    .top_right { border-bottom: 1px solid #e3e3e3; font-size: 12px; height: 25px; line-height: 25px; }
    .top_right_red { border-bottom: 1px solid #e3e3e3; color: red; font-size: 12px; height: 25px; line-height: 25px; }
    .chuxian { height: 25px; line-height: 25px; }
</style>

<div class="sm g_hide"></div>



<table class="sub_table" cellpadding="0" cellspacing="0" border="0" width="100%">
  <thead>
    <tr id="th_header">
      <th width="163" class="">时间</th>
      <th width="103" class="">期数</th>
      <th width="380" class="">开奖号码</th>
      <th colspan='3' class="g_r_line">冠亚</th>
      <th colspan='5'>1-5球龙虎</th></tr>
  </thead>
  <tbody id="reslist" linNos="0,1,2,5">
    @foreach($mRacing as $k => $item)
    <tr class="{{ $k % 2 == 0 ? '' : 'line_row' }}">
      <td>{{ $item->awardTime }}</td>
      <td>{{ $item->periodNumber }}</td>
      <td>
        @foreach($item['awardNumbers'] as $num)
        <span class="ball_pks_  ball_pks{{$num}} ball_lenght10  " title="3">&nbsp;</span>
        @endforeach
      <td class="count">{{ $gj[$item['periodNumber']][0] }}</td>
      <td class="blue">{{ $gj[$item['periodNumber']][1] }}</td>
      <td class="gray g_r_line g_td_p_right">{{ $gj[$item['periodNumber']][2] }}</td>
      <td class="blue g_td_p_left">{{ $lh[$item['periodNumber']][0] }}</td>
      <td class="blue">{{ $lh[$item['periodNumber']][1] }}</td>
      <td class="blue">{{ $lh[$item['periodNumber']][2] }}</td>
      <td class="blue">{{ $lh[$item['periodNumber']][3] }}</td>
      <td class="blue">{{ $lh[$item['periodNumber']][4] }}</td></tr>
    @endforeach
  </tbody>
</table>


            <div class="sub_hr">
            </div>
        </div>
        <div class="clear">
        </div>
    </div>
</div>
<center>
<br>
<br>
微账官方版权所有&copy 2016 <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1259190395'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/stat.php%3Fid%3D1259190395%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>
<br>
<br>
<span>
</center>

</body>
</html>
