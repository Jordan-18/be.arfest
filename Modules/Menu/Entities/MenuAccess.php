<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class MenuAccess extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Menu\Database\factories\MenuAccessFactory::new();
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->menu_access_id = Uuid::uuid4()->toString();
        });
    }

    public function menuRelation()
    {
        return $this->belongsTo(Menu::class, 'menu_access_menu', 'menu_id');
    }
}
