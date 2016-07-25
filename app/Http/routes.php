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
    $calMicrotime = function () {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    };

    return round(strtotime($awardTime) - $calMicrotime(), 3) * 1000;
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

Route::get('/', function () use ($guanjun, $longhu) {
    $mRacing = \App\Racing::where('expired', 1)->orderBy('periodNumber', 'desc')->take(12)->get();
    $gj = $guanjun($mRacing);
    $lh = $longhu($mRacing);
    return view('index', compact('mRacing', 'gj', 'lh'));
});

Route::get('/pk10', function () {
    return view('pk10');
});

Route::get('/ajax', function() use ($calAwardTimeInterval) {
    $periodNumber = \Cache::get('periodNumber');

    $Racing = App\Racing::OfCurrentAndNext($periodNumber)->get();

    $res = collect([]);
    $res['time'] = time();
    $res['current'] = $Racing[0];

    $res['next'] = $Racing[1];
    $res['next']['awardTimeInterval'] = $calAwardTimeInterval($Racing[1]['awardTime']);
    unset($res['next']['awardNumbers']);
    $res['next']['delayTimeInterval'] = 0;

    return response()->json($res);
});

Route::get('now', function () {
    echo \Carbon\Carbon::now();
});

Route::get('admin', function() use ($calAwardTimeInterval) {
    $periodNumber = \Cache::get('periodNumber');
    $Racing = App\Racing::OfCurrentAndNext($periodNumber)->get();

    $res = $Racing[1];
    $res['awardTimeInterval'] = $calAwardTimeInterval($Racing[1]['awardTime']);
    $res['awardNumbers'] = explode(',', $Racing[1]->awardNumbers);

    return view('admin', ['mRacing' => $res]);
});

Route::post('update', function (\Illuminate\Http\Request $request) {
    $periodNumber = $request->input('periodNumber');
    $awardNumbers = $request->input('awardNumbers');

    $mRacing = \App\Racing::where('expired', 0)->Where('periodNumber', $periodNumber)->first();
    $mRacing->awardNumbers = $awardNumbers;
    $mRacing->save();
});

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
