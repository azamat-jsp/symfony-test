<?php
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class MyEvent extends Event
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * @psalm-api
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }
}