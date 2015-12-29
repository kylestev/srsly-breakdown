<?php

namespace Srsly;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{

    protected $table = 'servers';

    protected $fillable = ['year', 'ip', 'port'];

}
