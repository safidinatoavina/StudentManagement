<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $appends = ['last_update'];

    protected function lastUpdate(): Attribute
    {
        return new Attribute(
            get: fn () => \Carbon\Carbon::parse($this->updated_at)->isoFormat('lll'),
        );
    }

}
