<?php

namespace Modules\Event\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Uuid;

class Event extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Event\Database\factories\EventFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->event_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }

    function eventCreatedBy(){
        return $this->belongsTo(User::class,'event_created_by', 'user_id');
    }
    function eventUpdatedBy(){
        return $this->belongsTo(User::class,'event_updated_by', 'user_id');
    }
}
