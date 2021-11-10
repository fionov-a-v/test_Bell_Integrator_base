<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BookSearch
{
    /**
     * @Assert\NotBlank()
     */
    public $title;
}