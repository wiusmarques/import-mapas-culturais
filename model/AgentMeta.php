<?php 

use Illuminate\Database\Eloquent\Model;

class AgentMeta extends Model

{

   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

   protected $fillable = [
       'object_id', 'key', 'value', 'id'
   ];

 }