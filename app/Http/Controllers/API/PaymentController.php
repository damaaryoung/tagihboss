<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assigments\PaymentAssigment;
use App\Http\Resources\ApiCollection;
use PDF;
use Auth;
use App\Models\userRoles;
use App\User;
use Browser;
use App\File;
use App\Events\ListingViewed;
use App\Events\EveryoneEvent;
use App\Notifications\Assigment;
use Notification;

class PaymentController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:payment-list|payment-create|payment-edit|payment-delete', ['only' => ['index','store']]);
         $this->middleware('permission:payment-create', ['only' => ['create','store']]);
         $this->middleware('permission:payment-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $x = PaymentAssigment::orderBy('created_at', 'DESC');
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
        $q=PaymentAssigment::find($id);
        $data=[
          'ang_ke'=>$q->ang_ke,
          'angsuran'=>$q->angsuran,
          'collect_fee'=>$q->collect_fee,
          'denda'=>$q->denda,
          'file'=>$q->file,
          'file_ttd_collection'=>$q->file_ttd_collection,
          'file_ttd_nasabah'=>$q->file_ttd_nasabah,
          'nama_nasabah'=>$q->nama_nasabah,
          'no_bss'=>$q->no_bss,
          'no_rekening'=>$q->no_rekening,
          'sisa_angsuran'=>$q->sisa_angsuran,
          'sisa_denda'=>$q->sisa_denda,
          'task_code'=>$q->task_code,
          'tenor'=>$q->tenor,
          'tgl_jt_tempo'=>$q->tgl_jt_tempo,
          'titipan'=>$q->titipan,
          'total_bayar'=>$q->total_bayar
        ];
        return response()->json($data, 200);
    }
    public function print($id)
    {
      $q=PaymentAssigment::find($id);
      $q['auth']=Auth::user()->name;
      $pdf = PDF::loadview('download.pdf.paymentpdf',['q'=>$q],[
        'title' => 'Invoice',
        'format' => 'A4-L',
        'orientation' => 'L'
      ]);
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah mencetak invoice data payment dengan taskcode: '.$q->task_code.' pada menu master payment.',
        ucfirst(strtolower(Auth::user()->name)).' telah mencetak invoice data payment dengan taskcode : '.$q->task_code.'.',
        'payment',
        'payment',
        'payment update',
        'Y',
        $request->ip()
      );
      // Notif
      return $pdf->download('payment-receipt-pdf');
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
        "ang_ke" => "required|numeric",
        "angsuran" => "required|numeric",
        "collect_fee" => "required|numeric",
        "denda" => "required|numeric",
        "nama_nasabah" => 'required|regex:/^[\pL\s\-]+$/u|max:255',
        "no_bss" => "required|string",
        "no_rekening" => "required|string",
        "sisa_angsuran" => "required|numeric",
        "sisa_denda" => "required|numeric",
        "task_code" => "required|numeric",
        "tenor" => "required|numeric",
        "titipan" => "required|numeric",
        "total_bayar" => "required|numeric",
        "sisa_denda" => "required|numeric",
        "tgl_jt_tempo" => "required|date_format:Y-m-d",
        "file" => "required|string",
        "file_ttd_collection" => "required|string",
        "file_ttd_nasabah" => "required|string"
      ]);
      $input=$request->all();
      $q=PaymentAssigment::find($id)->update($input);
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data payment dengan taskcode: '.$input['task_code'].' pada menu master payment.',
        ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui data payment dengan taskcode : '.$input['task_code'].'.',
        'payment',
        'payment',
        'payment update',
        'Y',
        $request->ip()
      );
      // Notif
      return response()->json(['status' => 'success','message'=>'updated data payment is successfuly'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $find=PaymentAssigment::find($id);
      $img1checkpublicfile=public_path($find->file);
      $img1checkstoragefile=storage_path('app/public/'.$find->file);
      $img1checkpublicfileTTDcollection=public_path($find->file_ttd_collection);
      $img1checkstoragefileTTDcollection=storage_path('app/public/'.$find->file_ttd_collection);
      $img1checkpublicfileTTDnasabah=public_path($find->file_ttd_nasabah);
      $img1checkstoragefileTTDnasabah=storage_path('app/public/'.$find->file_ttd_nasabah);
      $extractPath=explode("/",$img1checkpublicfile);
      if (file_exists($img1checkpublicfile) && file_exists($img1checkstoragefile)) {
          $extractPath=explode("/",$img1checkpublicfile);
          if (date("F-Y")==$extractPath[10]) {
              unlink($img1checkpublicfile);
              unlink($img1checkstoragefile);
          }
      }
      if (file_exists($img1checkpublicfileTTDcollection) && file_exists($img1checkstoragefileTTDcollection)) {
          $extractPath1=explode("/",$img1checkpublicfileTTDcollection);
          if (date("F-Y")==$extractPath1[10]) {
              unlink($img1checkpublicfileTTDcollection);
              unlink($img1checkstoragefileTTDcollection);
          }
      }
      if (file_exists($img1checkpublicfileTTDnasabah) && file_exists($img1checkstoragefileTTDnasabah)) {
          $extractPath2=explode("/",$img1checkpublicfileTTDnasabah);
          if (date("F-Y")==$extractPath2[10]) {
              unlink($img1checkpublicfile);
              unlink($img1checkstoragefile);
          }
      }
      // Notif
      $this->event(
        'User dengan akun username: '.Auth::user()->name.' telah menghapus data payment dengan taskcode: '.$find->task_code.' pada menu master payment.',
        ucfirst(strtolower(Auth::user()->name)).' telah menghapus data payment dengan taskcode : '.$find->task_code.'.',
        'payment',
        'payment',
        'payment delete',
        'Y',
        $request->ip()
      );
      // Notif
      $find->delete();
      return response()->json(['status' => 'success','message'=>'delete data payment is successfuly'], 200);
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
