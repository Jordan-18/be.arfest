<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Menu extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Menu\Database\factories\MenuFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->menu_id = Uuid::uuid4()->toString();
        });
    }

    public function menus()
    {
        return $this->hasMany(Menu::class,'menu_parent','menu_id');
    }
}
