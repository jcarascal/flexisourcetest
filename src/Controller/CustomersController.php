<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use App\Entity\Customers;
use Doctrine\ORM\EntityManagerInterface;

class CustomersController extends AbstractController
{
    /**
    * @Route("/customers", methods={"GET"}) 
    */
    public function getAllCustomers(): Response{
        $repository = $this->getDoctrine()->getRepository(Customers::class);
        $customers = $repository->findAll();
        $customerarray = array();

        foreach ($customers as $customer) {
            $customerarray[] = array(
                'fullname' => $customer->getFirstName().' '. $customer->getLastName(),
                'email' => $customer->getEmail(),
                'country' => $customer->getCountry()
            );
        }
        return  $this->json($customerarray, '200');
    }

    /**
    * @Route("/customers/{customerid}", methods={"GET"}) 
    */
    public function getCustomerById(string $customerid): Response{
        $repository = $this->getDoctrine()->getRepository(Customers::class);
        $customers = $repository->find($customerid);
        $customerarray = array();

        if(isset($customers)){
            $customerarray[] = array(
                'fullname' => $customers->getFirstName().' '. $customers->getLastName(),
                'email' => $customers->getEmail(),
                'username' => $customers->getUsername(),
                'gender' => $customers->getCountry(),
                'country' => $customers->getCountry(),
                'city' => $customers->getCity(),
                'phone' => $customers->getPhone(),
            );
             return  $this->json($customerarray, '200');
        }
       
        $response = new Response();
        return $response->setContent(json_encode([
            'status' => '200 ',
            'description' => 'No customer found with given id'
        ]));
    }

}


