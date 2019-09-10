<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CheckoutType;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/default", name="default")
     */
    public function default()
    {
        return $this->render('default/default.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

//    /**
//     * @Route("/afrekenen", name="afrekenen")
//     */
//    public function afrekenen(Request $request): Response
//    {
//        $form = $this->createForm(CheckoutType::class);
//        $form->handleRequest($request);
//
//        return $this->render('product/afrekenen.html.twig', [
//            'form' => $form->createView()
//        ]);
//    }
}
