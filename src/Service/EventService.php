<?php
namespace App\Service;

use App\Event\MyEvent;
use App\EventDispatcher\LoggingEventDispatcher;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EventService {

    /**
     * @param string $message
     * @param ParameterBagInterface $parameters
     * @return JsonResponse
     * @throws Throwable
     */
    public function eventPusher(string $message, ParameterBagInterface $parameters): JsonResponse
    {

        $logsDir = ($parameters->get('kernel.logs_dir') ?? '') . '/event.log';
        $realEventDispatcher = new EventDispatcher();
        $logger = new Logger('event_dispatcher');
        $logger->pushHandler(new StreamHandler($logsDir, Level::Info));

        $loggingEventDispatcher = new LoggingEventDispatcher($realEventDispatcher, $logger);
        $event = new MyEvent($message);
        $loggingEventDispatcher->dispatch($event);

        return new JsonResponse(['message' => 'Сообщение успешно передано'], Response::HTTP_OK);    }

}