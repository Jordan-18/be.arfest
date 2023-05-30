<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Uuid;

class Menu extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Menu\Database\factories\MenuFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->menu_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }

    public function menus()
    {
        return $this->hasMany(Menu::class,'menu_parent','menu_id');
    }
}
