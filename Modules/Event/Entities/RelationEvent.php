<?php

namespace Modules\Event\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Point\Entities\Point;
use Ramsey\Uuid\Uuid;

class RelationEvent extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Event\Database\factories\RelationEventFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->relation_event_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }

    public function relationevent(){
        return $this->belongsTo(Event::class, 'relation_event_event', 'event_id');
    }

    public function relationuser(){
        return $this->belongsTo(User::class, 'relation_event_user', 'user_id');
    }
    public function relationpoint(){
        return $this->belongsTo(Point::class,'relation_event_point','point_id');
    }
}
