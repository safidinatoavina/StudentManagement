<?php
namespace App\Service\Notification;

use App\Models\Notification;

class NotificationService{

    public $auth;

    public function __construct(){
        $this->auth=auth()->user();
    }

    public function create($title,$contenu)
    {

        if(!$this->validate($contenu))
            abort(500,"contenu de notification n'est pas autorisé");

        return Notification::create([
            'user_id'  => $this->auth->id,
            'title'    => $title,
            'content'  => $contenu
        ]);

    }

    public function read(Notification $notification)
    {
        return $notification->update([
                    'is_read' => 1
                ]);
    }

    public function get()
    {
        return Notification::where('user_id',$this->auth->id)->orderBy('id','desc')->paginate(10);
    }

    public function badge(){
        return Notification::where('user_id',$this->auth->id)->where('is_read',0)->count();
    }

    public function readAll()
    {
        return Notification::where('user_id',$this->auth->id)
                ->update([
                    'is_read' => 1
                ]);
    }

    public function delete(Notification $notification)
    {
        return $notification->delete();
    }

    private function validate($contenu){
        return true;
    }

}


