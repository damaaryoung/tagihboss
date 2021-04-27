<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification\NotificationTo;
use App\Models\Notification\NotificationModels;
use Auth;
use App\Http\Resources\ApiCollection;

class NotifController extends Controller
{
  public function index()
  {
    $x = NotificationModels::select('notification_models.*','notification_to.show')->join('notification_to', 'notification_models.id', '=', 'notification_to.notification_models_id');
    if (request()->q != '') {
        $x = $x->where([
          'notification_to.user_id'=>Auth::user()->id,
          'documentations_models.title'=> 'like', '%' .request()->q. '%'
          ]);
    }
    $x->where('notification_to.user_id', Auth::user()->id)->orderBy('notification_to.created_at', 'DESC');
    return new ApiCollection($x->paginate(10));
  }
  public function edit($id)
  {
      $v=NotificationModels::find($id);
      NotificationTo::where(['notification_models_id'=>$v->id, 'user_id'=>Auth::user()->id])->update(['show'=>'y']);
      $x=($v->event !== "") ? $v->event : 'activity' ;
      return response()->json($x, 200);
  }
}
