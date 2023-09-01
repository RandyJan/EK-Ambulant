<?php 

namespace App\Services;
use App\User;

class UserServices {

    protected $user;

    public function __construct(User $user){ 
        $this->user = $user;
    }

    
}