<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SnackassignmentRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Snackassignment;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Snacks;


class ChooseSnackController extends AbstractController
{
    /**
     * @Route("/choose/snack", name="app_choose_snack")
     */
    public function index(): Response
    {
        return $this->render('choose_snack/index.html.twig', [
            'controller_name' => 'ChooseSnackController',
        ]);
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
    /**
     * @Route("/snackdetails", name="snacks_info")
     */
    public function chooseSnack(Request $request,ManagerRegistry $doctrine, SnackassignmentRepository $assignmentreposit): Response
    {
       // $assignment = $doctrine->getRepository(Snackassignment::class)->findBy(array('presentdate'=>date('Y-m-d')));
       $assignment = $doctrine->getRepository(Snackassignment::class)->findAssignmentdetails();
      foreach($assignment as $k=>$v){
         // dd($v->getId());
         
          dd($v->getSnack()->getId());
      }
       dd($assignment);
        
        //$snackid = $assignment->request->get('snackid');
        
        
        
       // dd($snackid);
        
       // $snackname = $doctrine->getRepository(Snacks::class)->findSnackname();
        
      //  $assignmentdate= $doctrine->getRepository(Snackassignment::class)->findAssigneddate();
      
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
                );
        }
        return $this->render('Prod/product.html.twig',['productinfo' => $product]);
     }
}