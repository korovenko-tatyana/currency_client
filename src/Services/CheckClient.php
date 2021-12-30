<?php

namespace App\Services;

use App\Entity\Clients;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckClient
{

    public function checkClientJson($json)
    {
        if (!$json) {
            $data['error'] = "Empty request";
            return new JsonResponse($data, 400);
        }

        if (!isset($json['login']) || !isset($json['password'])) {
            $data['error'] = "Request without login or password";
            return new JsonResponse($data, 400);
        }
    }

    public function checkClient($client, $json)
    {

        if (!$client) {
            $data['error'] = "Client is not found";
            return new JsonResponse($data, 400);
        }

        if (!password_verify($json['password'], $client->getPassword())) {
            $data['error'] = "Wrong password";
            return new JsonResponse($data, 400);
        }

        if (!($client->getActive())) {
            $data['error'] = "Client is not active";
            return new JsonResponse($data, 400);
        }
    }
}