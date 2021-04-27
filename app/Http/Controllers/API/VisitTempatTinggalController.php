<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Models\Assigments\VisitTempatTinggal;
use Auth;
use App\Models\userRoles;
use Browser;
use App\File;
use App\Events\ListingViewed;
use App\Events\EveryoneEvent;
use App\Notifications\Assigment;
use Notification;

class VisitTempatTinggalController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:visit-list|user-create|visit-edit|visit-delete', ['only' => ['index','store']]);
         $this->middleware('permission:visit-create', ['only' => ['store']]);
         $this->middleware('permission:visit-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:visit-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $x = VisitTempatTinggal::orderBy('created_at', 'DESC');
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $q = VisitTempatTinggal::where('id',$id)->first();
      $data=[
        'task_code'=>$q->task_code,
        'kondisi_tempat_tinggal'=>$q->kondisi_tempat,
        'latitude_tempat_tinggal'=>$q->latitude,
        'longitude_tempat_tinggal'=>$q->longitude,
        'imageTempatTinggal1'=>$q->file1,
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
        "task_code" => "required|numeric",
        "kondisi_tempat_tinggal" => "required|string",
        "latitude_tempat_tinggal" => "required|string",
        "longitude_tempat_tinggal" => "required|string"
      ]);
      $input=$request->all();
      $stored=VisitTempatTinggal::find($id)->update([
          'task_code' => $input['task_code'],
          'kondisi_tempat' => $input['kondisi_tempat_tinggal'],
          'latitude' => $input['latitude_tempat_tinggal'],
          'longitude' => $input['longitude_tempat_tinggal']
      ]);
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data visit tempat tinggal dengan taskcode: '.$input['task_code'].' pada menu master visit jaminan.',
        ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui data visit tempat tinggal dengan taskcode : '.$input['task_code'].'.',
        'visit',
        'visit',
        'visit update',
        'Y',
        $request->ip()
      );
      // Notif
      return response()->json(['status' => 'success','message'=>'updated data visit tempat tinggal is successfuly'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $find=VisitTempatTinggal::find($id);
        $img1checkpublic=public_path($find->file1);
        $img1checkstorage=storage_path('app/public/'.$find->file1);
        if (file_exists($img1checkpublic) && file_exists($img1checkstorage)) {
            $extractPath=explode("/",$img1checkpublic);
            if (date("F-Y")==$extractPath[9]) {
                unlink($img1checkpublic);
                unlink($img1checkstorage);
            }else{
                rmdir(public_path('img/visit/'.$extractPath[9]),0777);
                rmdir(storage_path('app/public/'.$extractPath[9]),0777);
            }
        }
        // Notif
        $this->event(
          'User dengan akun username: '.Auth::user()->name.' telah menghapus data visit tempat tinggal dengan taskcode: '.$find->task_code.' pada menu master payment.',
          ucfirst(strtolower(Auth::user()->name)).' telah menghapus data visit tempat tinggal dengan taskcode : '.$find->task_code.'.',
          'visit',
          'visit',
          'visit delete',
          'Y',
          $request->ip()
        );
        // Notif
        $find->delete();
        return response()->json(['status' => 'success','message'=>'delete data visit tempat tinggal is successfuly'], 200);
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
