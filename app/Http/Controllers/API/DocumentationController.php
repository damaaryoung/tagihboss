<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use Spatie\Permission\Models\Permission;
use App\Models\Documentations\DocumentationsModels;
use Illuminate\Support\Str;
use Auth;
use App\Models\userRoles;
use App\User;
use Browser;
use App\File;
use App\Events\ListingViewed;
use App\Events\EveryoneEvent;
use App\Notifications\Assigment;
use Notification;

class DocumentationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:documentation-list|documentation-create|documentation-edit|documentation-delete', [
            'only' => ['index','store']
        ]);
        $this->middleware('permission:documentation-create', ['only' => ['create','store']]);
        $this->middleware('permission:documentation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:documentation-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $x = DocumentationsModels::orderBy('created_at', 'DESC');
      if (request()->q != '') {
          $x = $x->where('title', 'LIKE', '%' . request()->q . '%');
      }
      return new ApiCollection($x->paginate(10));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermission()
    {
        return response()->json(Permission::get(), 200);
    }
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
      $this->validate($request, [
        "title" => 'required|string',
        "information" => 'required|string',
        "attention" => 'required|string',
        "authorization" => 'required|string',
        "state" => 'required|string'
      ]);
      $input=$request->all();
      DocumentationsModels::create([
          'title'=>$input['title'],
          'slug'=>Str::slug($input['title']),
          'information'=>$input['information'],
          'attention'=>$input['attention'],
          'authorization'=>$input['authorization'],
          'state'=>$input['state'],
          'created_by_name'=>Auth::user()->name
      ]);
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah menambahkan data documentation dengan slug: '.Str::slug($input['title']).' pada menu master documentation.',
        ucfirst(strtolower(Auth::user()->name)).' telah menambahkan data documentation dengan slug : '.Str::slug($input['title']).'.',
        'helper',
        'helper',
        'helper store',
        'Y',
        $request->ip()
      );
      // Notif
      return response()->json(['status' => 'success','message'=>'saved data is successfuly'], 200);
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
      $x=DocumentationsModels::find($id);
      $data=[
        'title'=>$x->title,
        'slug'=>$x->slug,
        'information'=>$x->information,
        'attention'=>$x->attention,
        'authorization'=>$x->authorization,
        'state'=>$x->state,
        'created_by_name'=>$x->created_by_name
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
        "title" => 'required|string',
        "information" => 'required|string',
        "attention" => 'required|string',
        "authorization" => 'required|string',
        "state" => 'required|string'
      ]);
      $input=$request->all();
      $update=DocumentationsModels::find($id)->update([
          'title'=>$input['title'],
          'slug'=>Str::slug($input['title']),
          'information'=>$input['information'],
          'attention'=>$input['attention'],
          'authorization'=>$input['authorization'],
          'state'=>$input['state'],
          'created_by_name'=>Auth::user()->name
      ]);
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data documentation dengan slug: '.Str::slug($input['title']).' pada menu master documentation.',
        ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui data documentation dengan slug : '.Str::slug($input['title']).'.',
        'helper',
        'helper',
        'helper update',
        'Y',
        $request->ip()
      );
      // Notif
      return response()->json(['status' => 'success','message'=>'updated data is successfuly'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $find=DocumentationsModels::find($id);
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah menghapus data documentation dengan slug: '.$find->slug.' pada menu master documentation.',
        ucfirst(strtolower(Auth::user()->name)).' telah menghapus data documentation dengan slug : '.$find->slug.'.',
        'helper',
        'helper',
        'helper delete',
        'Y',
        $request->ip()
      );
      // Notif
      $find->delete();
      return response()->json(['status' => 'success','message'=>'delete data is successfuly'], 200);
    }

    public function help()
    {
      return response()->json(DocumentationsModels::get(), 200);
    }
    public function helpPick($id)
    {
      return response()->json(DocumentationsModels::find($id), 200);
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
