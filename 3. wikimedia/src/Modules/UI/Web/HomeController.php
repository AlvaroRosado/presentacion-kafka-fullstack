<?php

namespace App\Modules\UI\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends BaseController
{
    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        return $this->render('website/index.html.twig', [], new Response('', 200));
    }
}
