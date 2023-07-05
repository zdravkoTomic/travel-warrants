<?php

namespace App\Service\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordDto
{
    #[Assert\Email]
    #[Assert\NotBlank]
    public $email;

    #[Assert\NotBlank]
    public $password;
}