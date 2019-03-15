<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
    * @Route("/product",name="product_index")
    */
    public function index()
    {
        $product = $this
        ->getDoctrine()
        ->getRepository(Product::class)
        ->findAll();
        return $this->render('Product/index.html.twig',['products' => $product,]);
    }

    /**
    * @Route("/product/create",name="product_create")
    */
    public function new(Request $request)
    {
        $product = new Product();
        $form = $this->createProductForm($product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em -> persist($product);
            $em -> flush();
            $this->addFlash('success','Le client a bien été sauvegardé.');
            return $this->redirectToRoute('product_read',['id' => $product->getId()]);
        }
        return $this->render('Product/new.html.twig',['form' => $form->createView(),]);
    }
    
    /**
    * @Route("/product/{id}",name="product_read")
    */
    public function read(Request $request)
    {
        $product = $this->findCustomer($request);
        return $this->render('Customer/read.html.twig',['product'=> $product,]);
    }
    
    // /**
    // * @Route("/customer/update/{id}",name="customer_update")
    // */
    // public function update(Request $request)
    // {
    //     $customer =$this->findCustomer($request);
    //     $form = $this->createCustomerForm($customer);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $em = $this->getDoctrine()->getManager();
    //         $em -> persist($customer);
    //         $em -> flush();
    //         return $this->redirectToRoute('customer_update', ['id'=> $customer->getId()]);
    //     }
    //     return $this->render('Customer/update.html.twig',['form' => $form->createView(),]);
    // }
    
    private function createProductForm(Product $product)
    {
        return $this
        ->createFormBuilder($product)
        ->add('designation')
        ->add('reference')
        ->add('brand')
        ->add('price', Type\MoneyType::class)
        ->add('stock', Type\IntegerType::class)
        ->add('active', Type\CheckboxType::class)
        ->add('description',Type\TextareaType::class)
        ->add('submit', Type\SubmitType::class)
        ->getForm();
    }
    
    private function findProduct(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find(
            $request->attributes->get('id')
        );
        
        if(null === $product)
        {
            throw $this->createNotFoundException(
                "Product not found"
            );
        }
        return $product;
    }
    // /**
    // * @Route("/customer/delete/{id}",name="customer_delete")
    // */
    // public function delete(Request $request)
    // {
    //     $customer =$this->findCustomer($request);
    //     $form = $this
    //     ->createFormBuilder()
    //     ->add('confirm', Type\CheckboxType::class, [
    //         'label' => 'Confirmer la suppression ?'
    //     ])
    //     ->add('submit', Type\SubmitType::class)
    //     ->getForm();
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $em = $this->getDoctrine()->getManager();
    //         $em -> remove($customer);
    //         $em -> flush();
    //         $this->addFlash('success','Le client a bien été supprimé.');
    //         return $this->redirectToRoute('customer_index');
    //     }
    //     return $this->render('Customer/delete.html.twig',['form' => $form->createView(),]);
    // }
}