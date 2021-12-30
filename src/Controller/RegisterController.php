<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = password_hash($client->getPassword(),PASSWORD_DEFAULT);
            $client->setPassword($password);

            $client->setActive(true);

            $date = new DateTime();
            $token_update = $date;
            $client->setTokenUpdate($token_update);

            $hash_login = (string)($client->getLogin());
            $hash_salt = (string)($token_update->format('Y-m-d H:i:s'));
            $hash = $hash_login . $hash_salt;
            $client->setToken(hash('ripemd160', $hash));

            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();

            //add login of client to cookie
            $loginn = $request->request->get('clients')['login'];
            setcookie('login', $loginn, 0, '/');

            return $this->redirectToRoute('register');
        }

        $loginFromCookie = $_COOKIE['login'] ?? '';
        
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(), 'loginFromCookie' => $loginFromCookie
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response
    {
        setcookie('login', '', -10, '/');
        return $this -> redirectToRoute('register');
    }
}

