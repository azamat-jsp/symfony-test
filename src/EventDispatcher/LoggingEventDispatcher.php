<?php
namespace App\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class LoggingEventDispatcher implements EventDispatcherInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface $logger
     */
    public function __construct(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     * @throws \Throwable
     */
    public function dispatch(object $event): void
    {
        $eventName = get_class($event);

        try {
            $this->logger->info("Dispatching event: $eventName");
            $this->dispatcher->dispatch($event);
            $this->logger->info("Event dispatched successfully: $eventName", ['message' => $event->getData()]);
        } catch (\Throwable $exception) {
            $this->logger->error("Failed to dispatch event: $eventName. Error: " . $exception->getMessage());
            throw $exception; // Re-throw the exception after logging
        }
    }
}