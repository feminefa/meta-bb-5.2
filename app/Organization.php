<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
class Organization extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function  getResponseAttribute() {
        $r=[1=>'Migrate to canvas', 2=>'Backup before delete', 3=>'Delete entirely'];
        return $r[$this->action];
    }

}
