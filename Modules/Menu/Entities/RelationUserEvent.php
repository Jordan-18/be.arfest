<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Uuid;

class RelationUserEvent extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Menu\Database\factories\RelationUserEventFactory::new();
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->relation_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }
}
