<?php 

use Illuminate\Database\Eloquent\Model;

class User extends Model

{

    public $timestamps = false;

    protected $table = "usr";

   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

   protected $fillable = [

       'id', 'auth_provider', 'auth_uid', 'email', 'last_login_timestamp',
       'create_timestamp', 'status', 'profile_id'

   ];

 }