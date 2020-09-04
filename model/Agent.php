<?php 

use Illuminate\Database\Eloquent\Model;

class Agent extends Model

{

   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

   protected $fillable = [

       'id', 'parent_id', 'user_id', 'type', 'name',
       'location', '_geo_location', 'short_description',
       'long_description', 'create_timestamp', 'status',
       'is_verified', 'public_location', 'update_timestamp',
       'subsite_id'

   ];

 }