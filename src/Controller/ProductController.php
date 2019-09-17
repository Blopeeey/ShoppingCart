<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CheckoutType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Swift_Mailer;
use Swift_Message;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/{id}/cart", name="AddToCart")
     */
    public function AddToCart(Product $product)
    {
        $e = $product->getId();
        $getCart = $this->session->get('cart', []);
        $totaal = 0;
        if (isset($getCart[$e])) {
            $getCart[$e]['aantal']++;
        } else {
            $getCart[$e] = array(
                'aantal' => 1,
                'naam' => $product->getNaam(),
                'prijs' => $product->getPrijs(),

            );
        }
        $this->session->set('cart', $getCart);
        $cart = $this->session->get('cart');
        $cartArray = [];
        foreach ($cart as $e => $product) {
            $res = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($e);
            array_push($cartArray, [$e, $product['aantal'], $res]);
            $totaal = $totaal +($product['aantal'] * $res->getPrijs());
        }
        return $this->render('product/addtocart.html.twig', [
            'product' => $cartArray,
            'totaal' => $totaal
        ]);
    }

    /**
     * @Route("/afrekenen", name="afrekenen", methods={"POST", "GET"})
     */
    public function afrekenen(Request $request, Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(CheckoutType::class);
        $form->handleRequest($request);
        $cart = $this->session->get('cart');

        foreach ($cart as $e => $product) {
            $res = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($e);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $naam = $form->get('name')->getData();
            $email = $form->get('e-mail')->getData();

            $message = (new Swift_Message('Factuur'))
                ->setFrom('blopeeey@gmail.com')
                ->setTo('blopeeey@gmail.com')
                ->setBody('
                <h2>Thank you for purchasing our product' . $naam . ' </h2><br/>
                Your order information: <br/> Product name: ' . $product['naam'] . '<br/> Product amount ' . $product['aantal'] . '<br/><br/> Total price ' . $product['prijs']

                    , 'text/html');
            $mailer->send($message);
            $this->session->clear();
            return $this->redirect('/');
        }
        $totaal = 0;
        $cart = $this->session->get('cart');
        $cartArray = [];
        foreach ($cart as $e => $product) {
            $res = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($e);
            array_push($cartArray, [$e, $product['aantal'], $res]);
            $totaal = $totaal +($product['aantal'] * $res->getPrijs());
        }


        return $this->render('product/afrekenen.html.twig', [
            'form' => $form->createView(),
            'product' => $cartArray,
            'totaal' => $totaal
        ]);
    }
}
