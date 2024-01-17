<?php

namespace App\Service;


class ResponseService
{

    private static function messageUpdate($status)
    {
        return $status?'mise à jour succès':'mise àjour à une erreur';
    }

    private static function messageCreate($status)
    {
        return $status?'creation succès':'création à une erreur';
    }

    private static function messageDelete($status)
    {
        return $status?'suppression succès':'suppression à une erreur';
    }

    public static function updated($status)
    {
        return ['message'=>self::messageUpdate($status),'status'=>$status];
    }

    public static function create($status)
    {
        return ['message'=>self::messageCreate($status),'status'=>$status];
    }

    public static function delete($status)
    {
        return ['message'=>self::messageDelete($status),'status'=>$status];
    }



}

