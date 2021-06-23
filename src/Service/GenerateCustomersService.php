<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use App\Entity\Customers;
use Doctrine\ORM\EntityManagerInterface;

class GenerateCustomersService
{
    public function __construct(EntityManagerInterface $emi)
    {
        $this->EntityManagerInterface = $emi;
    }

    public function generateCustomers(): Response{
        $nationality = "AU";
        $client = HttpClient::create();
        $cnt = $this->countRows();

        if($cnt >= 100){
            $response = new Response();
            $response->setContent(json_encode([
                'status' => '200 ',
                'description' => 'Fetched and save customers data'
            ]));
        }

        while($cnt < 100){
            $results = $client->request('GET', $_SERVER['RANDOMUSER_API'].'/?nat='.$nationality);
            $users = $results->toArray();

            if(isset($users['error'])){
                $response = new Response();
                $response->setContent(json_encode([
                    'status' => '503 ',
                    'description' => 'Error on fetching customer data'
                ]));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
                
            }else{
                $customer = $this->EntityManagerInterface->getRepository(Customers::class)->findOneBy(array('email' => $users['results'][0]['email']));

                $customer = new Customers();
                $customer->setFirstName($users['results'][0]['name']['first']);
                $customer->setLastName($users['results'][0]['name']['last']);
                $customer->setEmail($users['results'][0]['email']);
                $customer->setUsername($users['results'][0]['login']['username']);
                $customer->setPassword($users['results'][0]['login']['md5']);
                $customer->setGender($users['results'][0]['gender']);
                $customer->setCountry($users['results'][0]['location']['country']);
                $customer->setCity($users['results'][0]['location']['city']);
                $customer->setPhone($users['results'][0]['phone']);


                $this->EntityManagerInterface->persist($customer);
                $this->EntityManagerInterface->flush();

                $response = new Response();
                $response->setContent(json_encode([
                    'status' => '200 ',
                    'description' => 'Fetched and save customers data'
                ]));
            }

            $cnt = $this->countRows();
        }
        return $response;
    }

   public function countRows(){
        $repository = $this->EntityManagerInterface->getRepository(Customers::class);
        $qb = $repository->createQueryBuilder('customers');
        return $qb
            ->select('count(customers.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}