<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Uuid;

class MenuAccess extends Model
{
    use HasFactory;
    
    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Menu\Database\factories\MenuAccessFactory::new();
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->menu_access_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }

    public function menuRelation()
    {
        return $this->belongsTo(Menu::class, 'menu_access_menu', 'menu_id');
    }
}
