<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Notification extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $appends = ['time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function time():Attribute{

        return new Attribute(
            get: fn () => \Carbon\Carbon::parse($this->created_at)->isoFormat('lll')
        );
    }

}
