<?php

namespace Modules\Point\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Uuid;

class Point extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Point\Database\factories\PointFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->point_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }

    public function PointDetail()
    {
        return $this->hasMany(PointDetail::class, 'point_detail_induk', 'point_id');
    }
}
