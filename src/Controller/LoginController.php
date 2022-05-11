<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/snacklogin", name="snackapp_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('app_register');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    /**
     * @Route("/dssnacklogin", name="dssnacker_login")
     */
    public function loginsnack(AuthenticationUtils $authenticationUtils,Request $request,LoggerInterface $logger): Response
    {
        // dd($request);
       if($request->request->get("_csrf_token")){
            $token = $request->request->get("_csrf_token");
            if (!$this->isCsrfTokenValid('authenticate', $token)) {
                
                $logger->info("CSRF failure");
                
                return new Response("Operation not allowed", Response::HTTP_OK,
                    ['content-type' => 'text/plain']);
            }
            $email = $request->request->get("username");
            $pwd = $request->request->get("password");
            
            $reclogin = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'username' => $email,
                'password' => $pwd,
            ]);
            if($reclogin){
                $session = new Session();
                $session->set('id', $email);
            }
            else {
             return $this->render('security/login.html.twig',['last_username' => 'Diwa', 'error' => 'Invalid Login Credentials']);
            }
          //  return $this->redirectToRoute('dssnacker_login');
            
          //  dd($reclogin);
        }
        else{
            return $this->render('security/login.html.twig',['last_username' => 'Diwa', 'error' => '']);
        }
      
    }
    public function createAdmin()
    {
        $product = $doctrine->getRepository(Users::class)->makeAdmin();
        
        if (!$product) {
            throw $this->createNotFoundException(
                ' '.$id
                );
        }
        
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
