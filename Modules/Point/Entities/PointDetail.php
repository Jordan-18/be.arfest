<?php

namespace Modules\Point\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Uuid;

class PointDetail extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Point\Database\factories\PointDetailFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->point_detail_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }
}
