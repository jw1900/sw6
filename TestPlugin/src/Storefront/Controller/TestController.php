<?php declare(strict_types=1);

namespace TestPlugin\Storefront\Controller;

use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class TestController extends StorefrontController
{
    /**
    * @Route("/fastorder", name="frontend.test.fastorder", methods={"GET"})
    */
    public function fastOrder(): Response
    {
        return $this->renderStorefront('@TestPlugin/storefront/page/fastorder.html.twig', [
            'title_h3' => 'ADDING 10 ARTICLES AT ONCE'
        ]);
    }
    
}