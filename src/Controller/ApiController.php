<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Clients;
use App\Controller\Currency;
use App\Repository\ClientsRepository;
use App\Repository\CurrencyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiController extends AbstractController
{
    private $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/api/auth", name="auth", methods={"POST"})
     */
    public function authToken(Request $request){

        $json = json_decode($request->getContent(), true);

        if (!$json)
        {
            $data['error'] = "Empty request";
            return new JsonResponse($data, 400);
        }
        if (!isset($json['login']) || !isset($json['password']))
        {
            $data['error'] = "Request without login or password";
            return new JsonResponse($data, 400);
        }
        $client = $this -> getDoctrine() -> getRepository(Clients::class) -> findOneBy(['login' => $json['login']]);
        if (!$client)
        {
            $data['error'] = "Client is not found";
            return new JsonResponse($data, 400);
        }
        if ($client -> getPassword() != $json['password'])
        {
            $data['error'] = "Wrong password";
            return new JsonResponse($data, 400);
        }
        if (!($client -> getActive()))
        {
            $data['error'] = "Client is not active";
            return new JsonResponse($data, 400);
        }
        $token_json = ['token' => $client -> getToken()];
        return new JsonResponse($token_json);

    }


    /**
     * @param Request $request
     * @param $code
     * @return JsonResponse
     * @Route("/currency/{code}", name="currency_code", methods={"GET"})
     */
    public function currencyValue(Request $request, $code){

        $token = $request->query->get('token');


        if (!$token)
        {
            $data['error'] = "Token not found";
            return new JsonResponse($data, 400);
        }
        $client = $this -> getDoctrine() -> getRepository(Clients::class) -> findOneBy(['token' => $token]);
        if (!$client)
        {
            $data['error'] = "Client is not found";
            return new JsonResponse($data, 400);
        }
        if (!($client -> getActive()))
        {
            $data['error'] = "Client is not active";
            return new JsonResponse($data, 400);
        }

        $currency = $this-> currencyRepository -> findOneBy(['CharCode' => $code]);
       // $currency = $this -> getDoctrine() -> getRepository(Currency::class) -> findOneBy(['CharCode' => $code]);
        if (!$currency)
        {
            $data['error'] = "Currency not found";
            return new JsonResponse($data, 400);
        }

        return new JsonResponse($currency);

    }


    /**
     * @param Request $request
     * @param $code
     * @return JsonResponse
     * @Route("/currencies", name="currency_list", methods={"GET"})
     */
    public function currencyList(Request $request){

        $token = $request->query->get('token');

        if (!$token)
        {
            $data['error'] = "Token not found";
            return new JsonResponse($data, 400);
        }
        $client = $this -> getDoctrine() -> getRepository(Clients::class) -> findOneBy(['token' => $token]);
        if (!$client)
        {
            $data['error'] = "Client is not found";
            return new JsonResponse($data, 400);
        }
        if (!($client -> getActive()))
        {
            $data['error'] = "Client is not active";
            return new JsonResponse($data, 400);
        }

        $currency = $this-> currencyRepository -> findAll();
        if (!$currency)
        {
            $data['error'] = "List of currency is empty";
            return new JsonResponse($data, 400);
        }

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($currency, 'json');

        return new Response($jsonContent);

    }




    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/calculate", name="calculate", methods={"POST"})
     */
    public function calculateCurrency(Request $request){

        $json = json_decode($request->getContent(), true);
        $token = $request->query->get('token');

        if (!$token)
        {
            $data['error'] = "Token not found";
            return new JsonResponse($data, 400);
        }
        $client = $this -> getDoctrine() -> getRepository(Clients::class) -> findOneBy(['token' => $token]);
        if (!$client)
        {
            $data['error'] = "Client is not found";
            return new JsonResponse($data, 400);
        }
        if (!($client -> getActive()))
        {
            $data['error'] = "Client is not active";
            return new JsonResponse($data, 400);
        }
        if (!$json)
        {
            $data['error'] = "Empty request";
            return new JsonResponse($data, 400);
        }
        if (!isset($json['summ']) || !isset($json['code']))
        {
            $data['error'] = "Request without sum or currency code";
            return new JsonResponse($data, 400);
        }

        $currency = $this-> currencyRepository -> findOneBy(['CharCode' => $json['code']]);
        if (!$currency)
        {
            $data['error'] = "Currency not found";
            return new JsonResponse($data, 400);
        }
        $convert = $json['summ'] * $currency -> getValue() / $currency -> getNominal();
        $convert_json = ['convert' => $convert];
        return new JsonResponse($convert_json);

    }


}
