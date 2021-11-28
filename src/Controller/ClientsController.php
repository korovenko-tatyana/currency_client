<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\ClientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientsController extends AbstractController
{
//    /**
//     * @Route("/clients", name="clients")
//     */
//    public function index(): Response
//    {
//        return $this->render('clients/index.html.twig', [
//            'controller_name' => 'ClientsController'
//        ]);
//    }

    private $clientRepository;

    public function __construct(ClientsRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    /**
     * @Route("/clients", name="clients")
     */
    public function clients()
    {
        $clients = $this-> clientRepository -> findAll();

        return $this->render('clients/index.html.twig', [
            'clients' => $clients
        ]);
    }

    /**
     * @Route("/clients/{login}", name="clientsLogin")
     */
    public function editActiveOfClient(Request $request)
    {
        $clLogin = ($request -> attributes -> get('login'));
        $editClient = $this -> getDoctrine() -> getRepository(Clients::class) -> findOneBy(['login' => $clLogin]);

        if ($editClient){
            if ($editClient -> getActive()) {
                $editClient->setActive(false);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }


        //$editClient = $this-> clientRepository -> findOneBy(['login' => $client -> getLogin()]);


        return $this -> redirectToRoute('clients');
    }
}
