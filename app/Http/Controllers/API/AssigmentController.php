<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assigments\ViewTaskAssigment;
use App\Http\Resources\AssigmentCollection;
use App\Models\Assigments\MasterNextAction;
use App\Models\Assigments\MasterCaseCategory;
use App\Models\Assigments\MasterAssetDebitur;
use App\Models\Assigments\MasterKondisiPekerjaan;
use App\Models\Assigments\ActivityAssigment;
use App\Models\Assigments\ViewCekActivity;
use App\Models\Assigments\VisitTempatTinggal;
use App\Models\Assigments\VisitJaminan;
use App\Models\Assigments\PaymentAssigment;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification\NotificationModels;
use App\Models\Notification\NotificationTo;
use App\Models\userRoles;
use App\User;
use Browser;
use App\File;
use App\Events\ListingViewed;
use Auth;
use DB;
use App\Events\EveryoneEvent;
use App\Notifications\Assigment;
use Notification;
use Carbon\Carbon;

class AssigmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $successStatus = 200;
    public function index()
    {
      if (Auth::user()->hasRole(['Area Recovery Collections', 'Remedial Collections', 'Field Collections'])) {
        $x = ViewTaskAssigment::where(['user_id'=>Auth::user()->user_id, 'flag_aktif'=>1])->orderBy('task_code', 'DESC');
        if (request()->q != '') {
            $x = $x->where([
              'user_id'=>Auth::user()->user_id,
              'flag_aktif'=>1,
              'task_code'=> 'like', '%' .request()->q. '%'
              ])
            ->orWhere('no_rekening', 'like', '%' .request()->q. '%')
            ->orWhere('nama_nasabah', 'like', '%' .request()->q. '%')
            ->orWhere('alamat', 'like', '%' .request()->q. '%')
            ->orWhere('total_tunggakan', 'like', '%' .request()->q. '%')
            ->orWhere('angsuran', 'like', '%' .request()->q. '%')
            ->orWhere('total_tagihan', 'like', '%' .request()->q. '%')
            ->orWhere('collect_fee', 'like', '%' .request()->q. '%')
            ->orWhere('ang_ke', 'like', '%' .request()->q. '%')
            ->orWhere('tgl_jt_tempo', 'like', '%' .request()->q. '%')
            ->orWhere('tenor', 'like', '%' .request()->q. '%')
            ->orWhere('baki_debet', 'like', '%' .request()->q. '%')
            ->orWhere('ft_hari', 'like', '%' .request()->q. '%');
        }
      }else {
        $x = ViewTaskAssigment::orderBy('task_code', 'DESC');
        if (request()->q != '') {
            $x = $x->where(
              'task_code', 'like', '%' .request()->q. '%')
            ->orWhere('no_rekening', 'like', '%' .request()->q. '%')
            ->orWhere('nama_nasabah', 'like', '%' .request()->q. '%')
            ->orWhere('alamat', 'like', '%' .request()->q. '%')
            ->orWhere('total_tunggakan', 'like', '%' .request()->q. '%')
            ->orWhere('angsuran', 'like', '%' .request()->q. '%')
            ->orWhere('total_tagihan', 'like', '%' .request()->q. '%')
            ->orWhere('collect_fee', 'like', '%' .request()->q. '%')
            ->orWhere('ang_ke', 'like', '%' .request()->q. '%')
            ->orWhere('tgl_jt_tempo', 'like', '%' .request()->q. '%')
            ->orWhere('tenor', 'like', '%' .request()->q. '%')
            ->orWhere('baki_debet', 'like', '%' .request()->q. '%')
            ->orWhere('ft_hari', 'like', '%' .request()->q. '%');
        }
      }
      return new AssigmentCollection($x->paginate(10));
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
    public function assigmentActivityStore(Request $request)
    {
      date_default_timezone_set('Asia/Jakarta');
      $input=$request->all();
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
        "take_foto" => 'required|image|mimes:jpeg,png,jpg|max:2048'
      ]);
      if ($input['janji_byr']=="N") {
        $tjb=null;
      }else{
        $days = (strtotime($input['tgl_janji_byr']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
        if ($days >= 7 || date('m',strtotime($input['tgl_janji_byr'])) !== date('m')) {
          return response()->json([""=>422,"message"=>"The given data was invalid.","errors"=>["tgl_janji_byr"=>["tanggal janji bayar harus dalam bulan ini dan tidak boleh melebihi 7 hari"]]], 422);
        }else{
          $tjb=$input['tgl_janji_byr'];
          $cx=ViewTaskAssigment::where('task_code',$request->task_code)->first();
          if ($cx->status_activity != 0) {
            return response()->json(['status' => 'danger','message'=>'You can only make payments after creating an activity for this customer!'], 200);
          }else{
            $path = storage_path('app/public/img/activity/'.date('F-Y'));
            $folderName = public_path('img/activity/'.date('F-Y'));
            if (!file_exists($path)||!file_exists($folderName)) {
                mkdir($path,0777);
                mkdir($folderName,0777);
            }
            $safeName = time().'-activity-'.$input['task_code'].'.'.'png';
            $file_path_storage = $request->file('take_foto')->storeAs('img/activity/'.date('F-Y'), $safeName, 'public');
            $filestorage= $file_path_storage;
            $request->file('take_foto')->move($folderName, $safeName);
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
                'file'=>$filestorage,
                'invalid_no'=>$input['invalid_no'],
              ];
            ActivityAssigment::create($dataPost);
            $v=ViewTaskAssigment::where('task_code',$request->task_code)->first();
            $stat_act=($v->status_activity == 0) ? true : false;
            $stat_vst=($v->status_visit == 0) ? true : false;
            $stat_pym=($v->status_payment == 0) ? true : false;
            $act=[
              'stat_act'=>$stat_act,
              'stat_vst'=>$stat_vst,
              'stat_pym'=>$stat_pym
            ];
            // Notif
            $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
            $this->event(
              'User dengan akun username: '.Auth::user()->name.' telah menambahkan activity baru dengan task_code: '.$input['task_code'].' pada tab activity yang terdapat pada menu task assigment.',
              ucfirst(strtolower(Auth::user()->name)).' telah menambahkan activity baru dengan no pengajuan : '.$f->no_rekening.'.',
              'activity',
              'activity',
              'store',
              'Y',
              $request->ip()
            );
            // Notif
            return response()->json(['status' => 'success','message'=>'created data activity is successfuly','act'=>$act], 200);
          }
        }
      }
    }
    public function assigmentVisitAStore(Request $request)
    {
      $this->validate($request, [
        "task_code" => "required|numeric",
        "kondisi_tempat_tinggal" => "required|string",
        "latitude_tempat_tinggal" => "required|string",
        "longitude_tempat_tinggal" => "required|string",
        "imageTempatTinggal1" => "required|image|mimes:jpeg,png,jpg|max:2048"
      ]);
      $input=$request->all();
      $cx=ViewTaskAssigment::where('task_code',$request->task_code)->first();
      if ($cx->status_activity == 0) {
        return response()->json(['status' => 'danger','message'=>'You can only make payments after creating an activity for this customer!'], 200);
      }else{
        $path = storage_path('app/public/img/visit/'.date('F-Y'));
        $folderName = public_path('img/visit/'.date('F-Y'));
        if (!file_exists($path)) {
            mkdir($path,0777);
            mkdir($folderName,0777);
        }
        $safeName1 = time().'-visit-tempat-tinggal-first-'.$input['task_code'].'.'.'png';
        $file_path_storage1 = $request->file('imageTempatTinggal1')->storeAs('img/visit/'.date('F-Y'), $safeName1, 'public');
        $filestorage1= $file_path_storage1;
        $request->file('imageTempatTinggal1')->move($folderName, $safeName1);
        $stored=VisitTempatTinggal::create([
            'task_code' => $input['task_code'],
            'kondisi_tempat' => $input['kondisi_tempat_tinggal'],
            'latitude' => $input['latitude_tempat_tinggal'],
            'longitude' => $input['longitude_tempat_tinggal'],
            'file1' => $filestorage1
        ]);
        $v=ViewTaskAssigment::where('task_code',$request->task_code)->first();
        $stat_act=($v->status_activity == 0) ? true : false;
        $stat_vst=($v->status_visit == 0) ? true : false;
        $stat_pym=($v->status_payment == 0) ? true : false;
        $act=[
          'stat_act'=>$stat_act,
          'stat_vst'=>$stat_vst,
          'stat_pym'=>$stat_pym
        ];
        // Notif
        $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
        $this->event(
          'User dengan akun username: '.Auth::user()->name.' telah menambahkan visit tempat tinggal baru dengan task_code: '.$input['task_code'].' pada form visit yang terdapat pada menu task assigment.',
          ucfirst(strtolower(Auth::user()->name)).' telah menambahkan visit tempat tinggal baru dengan no pengajuan : '.$f->no_rekening.'.',
          'visit',
          'visit',
          'visit store',
          'Y',
          $request->ip()
        );
        // Notif
        return response()->json(['status' => 'success','message'=>'created data visit tempat tinggal is successfuly','act'=>$act], 200);
      }
    }
    public function assigmentVisitBStore(Request $request)
    {
      $this->validate($request, [
        "task_code" => "required|numeric",
        "kondisi_jaminan" => "required|string",
        "latitude_jaminan" => "required|string",
        "longitude_jaminan" => "required|string",
        "imageJaminan1" => "required|image|mimes:jpeg,png,jpg|max:2048"
      ]);
      $input=$request->all();
      $cx=ViewTaskAssigment::where('task_code',$request->task_code)->first();
      if ($cx->status_activity == 0) {
        return response()->json(['status' => 'danger','message'=>'You can only make payments after creating an activity for this customer!'], 200);
      }else{
        $path = storage_path('app/public/img/visit/'.date('F-Y'));
        $folderName = public_path('img/visit/'.date('F-Y'));
        if (!file_exists($path)) {
            mkdir($path,0777);
            mkdir($folderName,0777);
        }
        $safeName1 = time().'-visit-jaminan-first-'.$input['task_code'].'.'.'png';
        $file_path_storage1 = $request->file('imageJaminan1')->storeAs('img/visit/'.date('F-Y'), $safeName1, 'public');
        $filestorage1= $file_path_storage1;
        $request->file('imageJaminan1')->move($folderName, $safeName1);
        $stored=VisitJaminan::create([
            'task_code' => $input['task_code'],
            'kondisi_tempat' => $input['kondisi_jaminan'],
            'latitude' => $input['latitude_jaminan'],
            'longitude' => $input['longitude_jaminan'],
            'file1' => $filestorage1
        ]);
        $v=ViewTaskAssigment::where('task_code',$request->task_code)->first();
        $stat_act=($v->status_activity == 0) ? true : false;
        $stat_vst=($v->status_visit == 0) ? true : false;
        $stat_pym=($v->status_payment == 0) ? true : false;
        $act=[
          'stat_act'=>$stat_act,
          'stat_vst'=>$stat_vst,
          'stat_pym'=>$stat_pym
        ];
        // Notif
        $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
        $this->event(
          'User dengan akun username: '.Auth::user()->name.' telah menambahkan visit jaminan baru dengan task_code: '.$input['task_code'].' pada form visit yang terdapat pada menu task assigment.',
          ucfirst(strtolower(Auth::user()->name)).' telah menambahkan visit jaminan baru dengan no pengajuan : '.$f->no_rekening.'.',
          'visit',
          'visit',
          'visit store',
          'Y',
          $request->ip()
        );
        // Notif
        return response()->json(['status' => 'success','message'=>'created data visit jaminan is successfuly','act'=>$act], 200);
      }
    }
    public function assigmentPaymentStore(Request $request)
    {
      $this->validate($request, [
        "task_code" => "required|numeric",
        "nama" => "required|string",
        "no_rek" => "required|string",
        "os_pokok" => "required|numeric",
        "a_tertunggak" => "required|numeric",
        "b_tertunggak" => "required|numeric",
        "angsuran" => "required|numeric",
        "bayar_denda" => "required|numeric",
        "collect_fee" => "required|numeric",
        "titipan" => "required|numeric",
        "ang_ke" => "required|numeric",
        "tenor" => "required|numeric",
        "no_bss" => "required|numeric",
        "total_bayar_angsuran" => "required|numeric",
        "sisa_angsuran" => "required|numeric",
        "sisa_denda" => "required|numeric",
        "tgl_jt" => "required|date_format:Y-m-d",
        "take_foto" => "required|image|mimes:jpeg,png,jpg|max:2048",
        "signature64Nasabah" => "required|string",
        "signature64Collection" => "required|string"
      ]);
      $input=$request->all();
      $cx=ViewTaskAssigment::where('task_code',$request->task_code)->first();
      if ($cx->status_activity == 0) {
        return response()->json(['status' => 'danger','message'=>'You can only make payments after creating an activity for this customer!'], 200);
      }else {
        $file = $request->file('take_foto');
        $fileSignatureNasabah = base64_decode($input['signature64Nasabah']);
        $fileSignatureCollection = base64_decode($input['signature64Collection']);
        $P_folderName = public_path('img/payment/pic/'.date('F-Y'));
        $P_SignaturefolderName = public_path('img/payment/signature/'.date('F-Y'));
        $S_folderName = storage_path('app/public/img/payment/pic/'.date('F-Y'));
        $S_SignaturefolderName = storage_path('app/public/img/payment/signature/'.date('F-Y'));
        if (!file_exists($P_folderName)||!file_exists($P_SignaturefolderName)||!file_exists($S_folderName)||!file_exists($S_SignaturefolderName)) {
            mkdir($P_folderName,0777);
            mkdir($P_SignaturefolderName,0777);
            mkdir($S_folderName,0777);
            mkdir($S_SignaturefolderName,0777);
        }
        $safeName = time().'-payment-'.$input['task_code'].'.'.'png';
        $safeNameSN = time().'-payment-signature-nasabah-'.$input['task_code'].'.'.'png';
        $safeNameCOL = time().'-payment-signature-collection-'.$input['task_code'].'.'.'png';
        // storage
        $file_path_pic = $request->file('take_foto')->storeAs('img/payment/pic/'.date('F-Y'), $safeName, 'public');
        file_put_contents(storage_path().'/app/public/img/payment/signature/'.date('F-Y').'/'.time().'-payment-signature-nasabah-'.$input['task_code'].'.'.'png', $fileSignatureNasabah);
        file_put_contents(storage_path().'/app/public/img/payment/signature/'.date('F-Y').'/'.time().'-payment-signature-collection-'.$input['task_code'].'.'.'png', $fileSignatureCollection);
        $file->move($P_folderName, $safeName);
        file_put_contents(public_path().'/img/payment/signature/'.date('F-Y').'/'.$safeNameSN, $fileSignatureNasabah);
        file_put_contents(public_path().'/img/payment/signature/'.date('F-Y').'/'.$safeNameCOL, $fileSignatureCollection);
        $filestorage1= $file_path_pic;
        $filestorage2= 'img/payment/signature/'.date('F-Y').'/'.$safeNameSN;
        $filestorage3= 'img/payment/signature/'.date('F-Y').'/'.$safeNameCOL;
        PaymentAssigment::create([
            'task_code'=>$input['task_code'],
            'ang_ke'=>$input['ang_ke'],
            'tenor'=>$input['tenor'],
            'angsuran'=>$input['angsuran'],
            'denda'=>$input['bayar_denda'],
            'collect_fee'=>$input['collect_fee'],
            'titipan'=>$input['titipan'],
            'total_bayar'=>$input['total_bayar_angsuran'],
            'sisa_angsuran'=>$input['sisa_angsuran'],
            'sisa_denda'=>$input['sisa_denda'],
            'no_bss'=>$input['no_bss'],
            'tgl_jt_tempo'=>$input['tgl_jt'],
            'no_rekening'=>$input['no_rek'],
            'nama_nasabah'=>$input['nama'],
            'file'=>$filestorage1,
            'file_ttd_nasabah'=>$filestorage2,
            'file_ttd_collection'=>$filestorage3
        ]);
        $v=ViewTaskAssigment::where('task_code',$request->task_code)->first();
        $stat_act=($v->status_activity == 0) ? true : false;
        $stat_vst=($v->status_visit == 0) ? true : false;
        $stat_pym=($v->status_payment == 0) ? true : false;
        $act=[
          'stat_act'=>$stat_act,
          'stat_vst'=>$stat_vst,
          'stat_pym'=>$stat_pym
        ];
        // Notif
        $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
        $this->event(
          'User dengan akun username: '.Auth::user()->name.' telah menambahkan payment baru dengan task_code: '.$input['task_code'].' pada form payment yang terdapat pada menu task assigment.',
          ucfirst(strtolower(Auth::user()->name)).' telah menambahkan payment baru dengan no pengajuan : '.$f->no_rekening.'.',
          'payment',
          'payment',
          'payment store',
          'Y',
          $request->ip()
        );
        // Notif
        return response()->json(['status' => 'success','message'=>'created data visit jaminan is successfuly','act'=>$act], 200);
      }
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
    public function setNextActions()
    {
        return response()->json(MasterNextAction::get(), $this->successStatus);
    }
    public function setCaseCategory()
    {
        return response()->json(MasterCaseCategory::get(), $this->successStatus);
    }
    public function setAssetDeb()
    {
        return response()->json(MasterAssetDebitur::get(), $this->successStatus);
    }
    public function setKondisiPekerjaan()
    {
        return response()->json(MasterKondisiPekerjaan::get(), $this->successStatus);
    }

    public function assigmentCount()
    {
      return response()->json(ViewTaskAssigment::count(), $this->successStatus);
    }
    public function activityCount()
    {
      $q=ActivityAssigment::count();
      $s=ActivityAssigment::where('user_id',Auth::user()->id)
      ->whereDate('created_at',Carbon::today())
      ->count();
      return response()->json(['all'=>$q,'self'=>$s], $this->successStatus);
    }
    public function visitCount()
    {
      $x1=VisitTempatTinggal::count();
      $y1=VisitJaminan::count();
      $h1=$x1+$y1;
      $x2=VisitTempatTinggal::where('user_id',Auth::user()->id)
      ->whereDate('created_at',Carbon::today())->count();
      $y2=VisitJaminan::where('user_id',Auth::user()->id)
      ->whereDate('created_at',Carbon::today())->count();
      $h2=$x2+$y2;
      return response()->json(['all'=>$h1,'self'=>$h2], $this->successStatus);
    }
    public function paymentCount()
    {
        $q=PaymentAssigment::count();
        $s=PaymentAssigment::where('user_id',Auth::user()->id)
        ->whereDate('created_at',Carbon::today())
        ->count();
        return response()->json(['all'=>$q,'self'=>$s], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $v=ViewTaskAssigment::where('task_code',$id)->first();
      $y=ViewCekActivity::where('no_rekening', 'LIKE', '%' . $v->no_rekening . '%')->first();
      $stat_act=($v->status_activity == 0) ? true : false;
      $stat_vst=($v->status_visit == 0) ? true : false;
      $stat_pym=($v->status_payment == 0) ? true : false;
      $a=[
        'task_code'=>$v->task_code,
        'kunjungan_ke'=>$v->ang_ke+1,
        'bertemu'=>"",
        'karakter_debitur'=>(!is_null($y))?$y->karakter_debitur:"",
        'total_penghasilan'=>(!is_null($y))?$y->total_penghasilan:"",
        'kondisi_pekerjaan'=>(!is_null($y))?$y->kondisi_pekerjaan:"",
        'asset_debt'=>(!is_null($y))?$y->asset_debt:"",
        'janji_byr'=>"",
        'case_category'=>"",
        'next_action'=>"",
        'keterangan'=>"",
        'take_foto'=>""
      ];
      $v_a=[
        'task_code'=>$v->task_code,
        'kondisi_tempat_tinggal'=>"",
        'latitude_tempat_tinggal'=>"",
        'longitude_tempat_tinggal'=>"",
        'imageTempatTinggal1'=>"",
        'imageTempatTinggal2'=>"",
        'imageTempatTinggal3'=>""
      ];
      $v_b=[
        'task_code'=>$v->task_code,
        'kondisi_jaminan'=>"",
        'latitude_jaminan'=>"",
        'longitude_jaminan'=>"",
        'imageJaminan1'=>"",
        'imageJaminan2'=>"",
        'imageJaminan3'=>""
      ];
      $p=[
        'task_code'=>$v->task_code,
        'nama'=>$v->nama_nasabah,
        'no_rek'=>$v->no_rekening,
        'os_pokok'=>$v->baki_debet,
        'a_tertunggak'=>$v->total_tagihan,
        'b_tertunggak'=>$v->angsuran,
        'angsuran'=>$v->angsuran,
        'bayar_denda'=>"",
        'collect_fee'=>$v->collect_fee,
        'titipan'=>"",
        'ang_ke'=>$v->ang_ke,
        'tenor'=>$v->tenor,
        'no_bss'=>"",
        'total_bayar_angsuran'=>"",
        'sisa_angsuran'=>"",
        'sisa_denda'=>"",
        'tgl_jt'=>$v->tgl_jt_tempo,
        'take_foto'=>"",
        'signature64Nasabah'=>"",
        'signature64Collection'=>"",
      ];
      $act=[
        'stat_act'=>$stat_act,
        'stat_vst'=>$stat_vst,
        'stat_pym'=>$stat_pym
      ];
      return response()->json(['activity'=>$a,'visit_a'=>$v_a,'visit_b'=>$v_b,'payment'=>$p,'act'=>$act], $this->successStatus);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
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
    public function push(){
     Notification::send(User::all(),new Assigment);
     return response()->json(['status' => 'success'], 200);
    }
}
