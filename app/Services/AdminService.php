<?php

namespace App\Services;

use App\Repositories\UserRepository;

class AdminService {

    public $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

}