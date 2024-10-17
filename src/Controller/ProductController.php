<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/all', name: 'all_products')]
    public function allProducts(ProductRepository $pr){
        $products = $pr->findAll();
        return $this->render('product/allProducts.html.twig', ['products' => $products]);
    }

    #[Route('/product/add', name: 'add_product')]
    public function addProduct(ProductRepository $pr, EntityManagerInterface $em,Request $request){
        if($request->isMethod('POST')){
            $product = new Product();
            $product->setName($request->request->get('name'));
            $product->setDateOfProduction(new DateTime($request->request->get('dateOfProduction')));
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('all_products');
        }
        return $this->render('product/addProduct.html.twig');
    }

    #[Route('/product/update/{id}', name: 'update_product')]
    public function updateProduct(EntityManagerInterface $em,Request $request,ProductRepository $pr,$id){
        $product = $pr->find($id);
        if($request->isMethod('POST')){
            $product->setName($request->request->get('name'));
            $product->setDateOfProduction(new DateTime($request->request->get('dateOfProduction')));
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('all_products');
        }
        return $this->render('product/updateProduct.html.twig', ['product' => $product]);
    }

    #[Route('/product/delete/{id}', name: 'delete_product')]
    public function deleteProduct(EntityManagerInterface $em,ProductRepository $pr,$id){
        $product = $pr->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('all_products');
    }

}
