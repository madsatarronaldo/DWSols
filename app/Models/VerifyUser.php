<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    use HasFactory;
    public $table = "verify_users"; 
    /** 
    * @return response()
    */
   protected $fillable = [
       'user_id',
       'token',
   ];
 
   /**
    * Write code on Method
    *
    * @return response()
    */
   public function user()
   {
       return $this->belongsTo('App\Models\User' , 'user_id');
   }
}
