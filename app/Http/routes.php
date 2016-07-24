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

Route::get('/', function () {
    return view('index');
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
