<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assigments\ActivityAssigment;
use App\Http\Resources\ApiCollection;
use App\Models\Notification\NotificationModels;
use App\Models\Notification\NotificationTo;
use App\Models\userRoles;
use App\User;
use Browser;
use App\File;
use App\Events\ListingViewed;
use Auth;
use App\Events\EveryoneEvent;
use App\Notifications\Assigment;
use Notification;

class ActivityController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:activity-list|user-create|activity-edit|activity-delete', ['only' => ['index','store']]);
         $this->middleware('permission:activity-create', ['only' => ['store']]);
         $this->middleware('permission:activity-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:activity-delete', ['only' => ['destroy']]);
    }
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Responsename
     */
    public function index()
    {
      $x = ActivityAssigment::orderBy('created_at', 'DESC');
      if (request()->q != '') {
          $x = $x->where('task_code', 'LIKE', '%' . request()->q . '%');
      }
      return new ApiCollection($x->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $x=ActivityAssigment::find($id);
        $data=[
          'task_code'=>$x->task_code,
          'kunjungan_ke'=>$x->kunjungan_ke,
          'bertemu'=>$x->bertemu,
          'karakter_debitur'=>$x->karakter_debitur,
          'total_penghasilan'=>$x->total_penghasilan,
          'kondisi_pekerjaan'=>$x->kondisi_pekerjaan,
          'asset_debt'=>$x->asset_debt,
          'janji_byr'=>$x->janji_byr,
          'tgl_janji_byr'=>$x->tgl_janji_byr,
          'case_category'=>$x->case_category,
          'next_action'=>$x->next_action,
          'keterangan'=>$x->keterangan,
          'take_foto'=>$x->file,
          'invalid_no'=>$x->invalid_no
        ];
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
          "task_code" => 'required|numeric',
          "kunjungan_ke" => 'required|numeric',
          "bertemu" => 'required|string',
          "karakter_debitur" => 'required|string',
          "total_penghasilan" => 'required|numeric',
          "kondisi_pekerjaan" => 'required|string',
          "asset_debt" => 'required|string',
          "janji_byr" => 'required|string',
          "case_category" => 'required|string',
          "next_action" => 'required|string',
          "keterangan" => 'required|string'
        ]);
        $input=$request->all();
        $tjb=($input['janji_byr']=="N")? null : date('Y-m-d',strtotime($input['tgl_janji_byr']));
        $dataPost=[
          'task_code'=>$input['task_code'],
          'kunjungan_ke'=>$input['kunjungan_ke'],
          'bertemu'=>$input['bertemu'],
          'karakter_debitur'=>$input['karakter_debitur'],
          'total_penghasilan'=>$input['total_penghasilan'],
          'kondisi_pekerjaan'=>$input['kondisi_pekerjaan'],
          'asset_debt'=>$input['asset_debt'],
          'janji_byr'=>$input['janji_byr'],
          'tgl_janji_byr'=>$tjb,
          'case_category'=>$input['case_category'],
          'next_action'=>$input['next_action'],
          'keterangan'=>$input['keterangan'],
        ];
        $find=ActivityAssigment::find($id);
        // Notif
        $this->event(
          'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data activity dengan taskcode: '.$find->task_code.' pada menu master activity.',
          ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui data activity dengan no taskcode : '.$find->task_code.'.',
          'activity',
          'activity',
          'activity update',
          'Y',
          $request->ip()
        );
        // Notif
        $find->update($dataPost);
        return response()->json(['status' => 'success','message'=>'created data activity is successfuly'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $find=ActivityAssigment::find($id);
      $imgCheck=$find->file;
      if (file_exists($imgCheck)) {
          $extractPath=explode("/",$imgCheck);
          if (date("F-Y")==$extractPath[2]) {
              unlink($imgCheck);
          }else{
              rmdir(public_path('img/activity/'.$extractPath[2]),0777);
          }
      }
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah menghapus data activity dengan taskcode: '.$find->task_code.' pada menu master activity.',
        ucfirst(strtolower(Auth::user()->name)).' telah menghapus data activity dengan no taskcode : '.$find->task_code.'.',
        'activity',
        'activity',
        'activity delete',
        'Y',
        $request->ip()
      );
      // Notif
      $delete=$find->delete();
      return response()->json(['status' => 'success','message'=>'destroy data activity is successfuly'], 200);
    }

    // function recording
    public function event($desc1,$desc2,$event,$link,$title,$broadcast,$ip)
    {
      if (Browser::isDesktop() == true) {
          $device = 'Computer/Laptop/Notebook';
      }elseif (Browser::isTablet() == true) {
          $device = 'Tablet Device';
      }else{
          $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
      }
      $user = User::find(Auth::user()->id);
      $user->log_timelines()->create([
          'name'=>Auth::user()->name,
          'title'=>$title,
          'event'=>$event,
          'description'=>$desc1,
          'ip_address'=>$ip,
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
      ]);
      // TIMELINE LOG::ENDED
      // NOTIF::STARTED
      $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
      $UC=userRoles::where('rolename','Unit Head Collections')->first();
      $DC=userRoles::where('rolename','Dept Head Collections')->first();
      $SA=userRoles::where('rolename','Superadmin')->first();
      $notifCreate=NotificationModels::create([
          'event'=>$event,
          'user_id'=>Auth::user()->id,
          'desc'=>$desc2,
          'link_show'=>$link
      ]);
      $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
      $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
      $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
      $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
      $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
      $notifCreate->notifTo()->saveMany($dataNotifTo);
      // NOTIF::ENDED
      if ($broadcast=='Y') {
        event(new \App\Events\EveryoneEvent());
      }
    }
    // function recording
}
