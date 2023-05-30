<?php

namespace Modules\JenisBusur\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class JenisBusur extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\JenisBusur\Database\factories\JenisBusurFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->jenis_busur_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }
}
