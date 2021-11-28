<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Clients;
use App\Repository\ClientsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiController extends AbstractController
{
    
    /**
     * @param Request $request
     * @param ClientsRepository $clientRepository
     * @return JsonResponse
     * @Route("/api/auth", name="auth", methods={"POST"})
     */
    public function addPost(Request $request, ClientsRepository $clientRepository){

        $json = json_decode($request->getContent(), true);

        $data = [
            'status' => 400,
            'errors' => "",
        ];

        if (!$json)
        {
            $data['errors'] = "Empty request";
            return new JsonResponse($data, 400);
        }
        if (!isset($json['login']) || !isset($json['password']))
        {
            $data['errors'] = "Request without login or password";
            return new JsonResponse($data, 400);
        }
        $client = $this -> getDoctrine() -> getRepository(Clients::class) -> findOneBy(['login' => $json['login']]);
        if (!$client)
        {
            $data['errors'] = "Client is not found";
            return new JsonResponse($data, 400);
        }
        if ($client -> getPassword() != $json['password'])
        {
            $data['errors'] = "Wrong password";
            return new JsonResponse($data, 400);
        }
        if (!($client -> getActive()))
        {
            $data['errors'] = "Client is not active";
            return new JsonResponse($data, 400);
        }
        $token_json = ['token' => $client -> getToken()];
        return new JsonResponse($token_json);

    }

}
