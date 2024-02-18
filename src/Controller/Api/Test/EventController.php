<?php

namespace App\Controller\Api\Test;

use App\DTO\EventDTO;
use App\Service\EventService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

/**
 * @psalm-api
 */
class EventController extends AbstractController
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    #[Route('/api/test/event/', name: 'api_test_event')]
    public function index(
        ValidatorInterface $validator,
        ParameterBagInterface $parameters,
        EventService $eventService,
        Request $request,
    ): JsonResponse
    {
        $eventDTO = new EventDTO();
        $eventDTO->message = $request->get('message');
        $errors = $validator->validate($eventDTO);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $eventService->eventPusher($eventDTO->message, $parameters);
    }
}
