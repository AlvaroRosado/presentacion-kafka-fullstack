<?php

namespace App\Modules\UI\Web;

use App\Modules\Shared\Application\CommandBus;
use App\Modules\Shared\Application\EventBus;
use App\Modules\Shared\Application\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\Turbo\TurboBundle;

class BaseController extends AbstractController
{
    public const int DEFAULT_PER_PAGE = 10;
    public const int DEFAULT_PAGE = 1;

    #[Required]
    public CommandBus $commandBus;
    #[Required]
    public EventBus $eventBus;
    #[Required]
    public QueryBus $queryBus;
    #[Required]
    public RequestStack $requestStack;

    public function isTurboFrame(Request $request): bool
    {
        return TurboBundle::STREAM_FORMAT === $request->getPreferredFormat() || $request->headers->has('Turbo-Frame');
    }

    public function getTurboFrameId(Request $request): ?string
    {
        return $request->headers->get('Turbo-Frame');
    }

    public function currentUserId(): string
    {
        return $this->getUser()->getUserIdentifier();
    }

    public function renderTurboForApi(string $template, array $context = []): Response
    {
        $response = $this->render($template, $context);
        $response->headers->set('Content-Type', 'text/vnd.turbo-stream.html');
        return $response;
    }
}
