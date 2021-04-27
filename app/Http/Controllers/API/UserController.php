<?php

namespace App\Http\Controllers\API;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use Spatie\Permission\Models\Permission;
use App\Models\LogTimelines\LogTimelinesModels;
use App\User;
use Validator;
use Hash;
use DB;
use Auth;
use App\Models\userRoles;
use Browser;
use App\File;
use App\Events\ListingViewed;
use App\Events\EveryoneEvent;
use App\Notifications\Assigment;
use Notification;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    public $successStatus = 200;

    public function login(){
        $loginType = filter_var(request('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $login = [
            $loginType => request('email'),
            'password' => request('password')
        ];
        if(Auth::attempt($login)){
            $user = Auth::user();
            return response()->json(['token' => $user->createToken('nApp')->accessToken], $this->successStatus);
        }
        else{
            return response()->json(['token'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function logout(Request $res)
    {
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['success' => $user], $this->successStatus);
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function userLists()
   {
        $user = User::get();
        return new UserCollection($user);
    }

    public function getUserLogin()
    {
        $user = request()->user();
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (request()->user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        $user['permission'] = $permissions;
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function index()
    {
      $x = User::orderBy('created_at', 'DESC');
      if (request()->q != '') {
          $x = $x->where('name', 'LIKE', '%' . request()->q . '%');
      }
      return new UserCollection($x->paginate(10));
    }
    public function userHistory(Request $request)
    {
      $input=$request->all();
      $x = LogTimelinesModels::where('user_id',$input['user']);
      return new UserCollection($x->paginate(10));
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
    public function checkUserMicro(Request $request)
    {
      $this->validate($request, [
          'usernamemicro' => 'required',
          'passwordmicro' => 'required',
      ]);
      $input=$request->all();
      $client = new Client(['base_uri' => 'http://103.31.232.146/']);
      $response = $client->request('POST', '/API_ABSENSI/login', ['form_params' => [
        'user' => $input['usernamemicro'],
        'password' => $input['passwordmicro']
      ]]);
      $api=$response->getBody()->getContents();
      $data=json_decode($api,true);
      $res=[
        'user_id'=>$data['user']['user_id'],
        'id_lokasi'=>$data['user']['id_lokasi'],
        'name'=>$data['payload']['usename'],
        'email'=>$data['payload']['email'],
        'password'=>$input['passwordmicro'],
        'nik'=>$data['user']['nik'],
      ];
      // dd($res);
      return response()->json($res, $this->successStatus);
    }
    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|unique:users,name|string|max:150',
        'email' => 'required|email',
        'password' => 'required|min:6|string',
        'roles'=> 'required',
        'id_lokasi'=> 'required',
        'nik'=> 'required',
        'user_id'=> 'required',
      ]);
      $input=$request->all();
       $user = User::create([
           'user_id' => $input['user_id'],
           'location' => $input['id_lokasi'],
           'nik' => $input['nik'],
           'name' => $input['name'],
           'email' => $input['email'],
           'password' => Hash::make($input['password']),
           'role' => $input['roles']
       ]);
       $user->assignRole($input['roles']);
       // Notif
       $this->event(
         'User dengan akun username: '.Auth::user()->name.' telah menambahkan data users atas nama: '.$input['name'].' pada menu master users.',
         ucfirst(strtolower(Auth::user()->name)).' telah menambahkan data users atas nama : '.$input['name'].'.',
         'helper',
         'helper',
         'helper store',
         'Y',
         $request->ip()
       );
       // Notif
       return response()->json(['status' => 'success'], 200);
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
      $user = User::where('id',$id)->first();
      $data=[
        'user_id'=>$user->user_id,
        'id_lokasi'=>$user->location,
        'name'=>$user->name,
        'email'=>$user->email,
        'password'=>'',
        'nik'=>$user->nik,
        'roles'=> '',
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
        'name' => 'required|string|max:150',
        'email' => 'required|email',
        'id_lokasi'=> 'required',
        'nik'=> 'required',
        'user_id'=> 'required',
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }
        $user = User::find($id);
        $user->update($input);
        if(!empty($input['roles'])){
          DB::table('model_has_roles')->where('model_id',$id)->delete();
          $user->assignRole($request->input('roles'));
        }
        // Notif
        $this->event(
          'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data users atas nama: '.$user->name.' pada menu master users.',
          ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui data users atas nama : '.$user->name.'.',
          'helper',
          'helper',
          'helper update',
          'Y',
          $request->ip()
        );
        // Notif
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $find=User::find($id);
        // Notif
        $this->event(
          'User dengan akun username: '.Auth::user()->name.' telah menghapus data users atas nama: '.$find->name.' pada menu master users.',
          ucfirst(strtolower(Auth::user()->name)).' telah menghapus data users atas nama : '.$find->name.'.',
          'helper',
          'helper',
          'helper delete',
          'Y',
          $request->ip()
        );
        // Notif
        $find->delete();
        return response()->json(['status' => 'success'], 200);
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
