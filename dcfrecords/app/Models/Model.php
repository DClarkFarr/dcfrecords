<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public $timestamps = false;

    public static function getTableStatic(){
        return ((new static)->getTable());
    }
    public function getTable(){
        return $this->table;
    }
}
