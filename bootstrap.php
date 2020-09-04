<?php

require "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([

   "driver"     => "pgsql",
   "host"       =>"mapas.siapp.one",
   "port"       => 5432,
   "database"   => "mapas",
   "username"   => "mapas",
   "password"   => "mapas"

]);

// Make this Capsule instance available globally.
$capsule->setAsGlobal();

// Setup the Eloquent ORM.
$capsule->bootEloquent();
$capsule->bootEloquent();