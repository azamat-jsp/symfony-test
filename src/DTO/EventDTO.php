<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EventDTO
{
    #[Assert\NotBlank(message: "Сообщение не передано")]
    #[Assert\Length(max: 255)]
    public string|null $message = '';
}