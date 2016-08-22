<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$calAwardTimeInterval = function ($awardTime) {
    return \Carbon\Carbon::now()->diffInSeconds($awardTime, false);
};

$guanjun = function ($mRacing) {
    $data = [];
    foreach ($mRacing as $item) {
        $item['awardNumbers'] = explode(',', $item['awardNumbers']);
        $data[$item['periodNumber']][0] = $item['awardNumbers'][0] + $item['awardNumbers'][1];
        $data[$item['periodNumber']][1] = 11 < $data[$item['periodNumber']][0] ? '大' : '小';
        $data[$item['periodNumber']][2] = 0 === $data[$item['periodNumber']][0] % 2 ? '双' : '单';
    }
    return $data;
};

$longhu = function ($mRacing) {
    $data = [];
    foreach ($mRacing as $item) {
        $data[$item['periodNumber']][0] = $item['awardNumbers'][0] > $item['awardNumbers'][9] ? "龙" : "虎";
        $data[$item['periodNumber']][1] = $item['awardNumbers'][1] > $item['awardNumbers'][8] ? "龙" : "虎";
        $data[$item['periodNumber']][2] = $item['awardNumbers'][2] > $item['awardNumbers'][7] ? "龙" : "虎";
        $data[$item['periodNumber']][3] = $item['awardNumbers'][3] > $item['awardNumbers'][6] ? "龙" : "虎";
        $data[$item['periodNumber']][4] = $item['awardNumbers'][4] > $item['awardNumbers'][5] ? "龙" : "虎";
    }
    return $data;
};

Route::get('/', function () {
    echo \Carbon\Carbon::now();
    return view('index');
});

Route::get('/table', function () use ($guanjun, $longhu) {
    $mRacing = \App\Racing::where('expired', 1)->where(
        'awardTime', '<',\Carbon\Carbon::now()->addDay()->toDateString()
        )->where(
        'awardTime', '>',\Carbon\Carbon::now()->toDateString()
        )->orderBy('periodNumber', 'desc')->get();
    $gj = $guanjun($mRacing);
    $lh = $longhu($mRacing);

    return view('table', compact('mRacing', 'gj', 'lh'));
});

Route::get('/pk10', function () {
    return view('pk10');
});

Route::get('/ajax', function() use ($calAwardTimeInterval) {
    $Racing = App\Racing::OfCurrentAndNext()->get();

    $res = collect([]);
    $res['time'] = time();
    $res['current'] = $Racing[1];

    $res['next'] = $Racing[0];
    $res['next']['awardTimeInterval'] = $calAwardTimeInterval($Racing[0]['awardTime']);
    unset($res['next']['awardNumbers']);
    $res['next']['delayTimeInterval'] = 0;

    return response()->json($res);
});

Route::get('now', function () {
    //$hour = \Carbon\Carbon::now()->hour;
    //$minute = \Carbon\Carbon::now()->minute;
    //$second = \Carbon\Carbon::now()->second;
    //echo \Carbon\Carbon::now()->setTime($hour, $minute, 0);

    echo \Carbon\Carbon::now();
});

Route::get('admin', ['middleware' => 'login', function() use ($calAwardTimeInterval) {
    $Racing = App\Racing::OfCurrentAndNext()->get();

    $res = $Racing[0];
    $res['awardTimeInterval'] = $calAwardTimeInterval($Racing[0]['awardTime']);
    $res['awardNumbers'] = explode(',', $Racing[0]->awardNumbers);

    return view('admin', ['mRacing' => $res]);
}]);

Route::post('update', ['middleware' => 'login', function (\Illuminate\Http\Request $request) {
    $periodNumber = $request->input('periodNumber');
    $awardNumbers = $request->input('awardNumbers');

    $mRacing = \App\Racing::where('expired', 0)->Where('periodNumber', $periodNumber)->first();
    $mRacing->awardNumbers = $awardNumbers;
    $mRacing->save();
}]);

Route::post('changeDate', ['middleware' => 'login', function (\Illuminate\Http\Request $request) use ($calAwardTimeInterval)  {
    $periodNumber = $request->input('periodNumber');
    $awardTime = $request->input('awardTime');
    $awardTime = \Carbon\Carbon::parse($awardTime);

    $mRacing = \App\Racing::where('expired', 0)->Where('periodNumber', $periodNumber)->first();
    $mRacing->awardTime = $awardTime;
    $mRacing->save();

    return $calAwardTimeInterval($awardTime);
}]);

Route::get('setting', function() {
    $TimeInterval = Cache::rememberForever('TimeInterval', function() {
        return 5;
    });
    $SystemSwitch = Cache::rememberForever('SystemSwitch', function() {
        return false;
    });

    return view('setting', compact('TimeInterval', 'SystemSwitch'));
});

Route::post('setting', function(\Illuminate\Http\Request $request) {
    $TimeInterval = $request->input('TimeInterval');
    $SystemSwitch = (boolean)$request->input('SystemSwitch');
    Cache::forever('TimeInterval', $TimeInterval);
    Cache::forever('SystemSwitch', $SystemSwitch);
    return back()->withInput();
});

// 认证路由...
Route::get('auth/login', function() {
     return view('auth.login');
});
Route::post('auth/login', function (\Illuminate\Http\Request $request) {
    $password = $request->input('password');
    if ($password == env('admin', 'admin!@#456')) {
        $request->session()->put('login', true);
        return redirect('/admin');
    } else {
        $request->session()->put('login', false);
        return redirect('auth/login');
    }
});
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('clear', ['middleware' => 'login', function() use ($calAwardTimeInterval) {
    \Cache::forget('mRacing');
    echo '清空缓存';
}]);
