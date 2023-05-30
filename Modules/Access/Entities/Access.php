<?php

namespace Modules\Access\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Menu\Entities\MenuAccess;
use Ramsey\Uuid\Uuid;

class Access extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded  = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Access\Database\factories\AccessFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->access_id = str_replace("-","",Uuid::uuid4()->toString());
        });
    }

    public function menuByAccess()
    {
        return $this->hasMany(MenuAccess::class,'menu_access_access','access_id')
            ->join('menus','menus.menu_id','=','menu_accesses.menu_access_menu')
            ->orderBy('menus.menu_order', 'asc');
    }
}
