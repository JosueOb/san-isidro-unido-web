<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    protected $table = "role_user";
    public $timestamps = true;
    public $state;
}
