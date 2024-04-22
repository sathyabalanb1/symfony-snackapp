<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Roles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SnackassignmentRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;




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
    public function loginsnack(AuthenticationUtils $authenticationUtils,ManagerRegistry $doctrine,Request $request,LoggerInterface $logger, ValidatorInterface $validator): Response
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
            
            $input = ['EmployeeName' => $email,'Password'=>$pwd];
            
            
            $constraints = new Assert\Collection([
                'EmployeeName' => [new Assert\NotBlank],
                'Password' => [new Assert\notBlank]
            ]); 
            
            $violations = $validator->validate($input, $constraints);
            
            if (count($violations) > 0) {
                
                $accessor = PropertyAccess::createPropertyAccessor();
                
                $errorMessages = [];
                
                foreach ($violations as $violation) {
                    $accessor->setValue($errorMessages,$violation->getPropertyPath(),$violation->getMessage());
                    
                }
                return $this->render('security/login.html.twig',['errors' => $errorMessages,'error' => '','last_username'=>'']);
            }
            
            $reclogin = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'username' => $email,
                'password' => $pwd,
            ]);
            
            if($reclogin){
                $userdetails = $doctrine->getRepository(Users::class)->fetchUserdetails($email,$pwd);
                foreach($userdetails as $k => $v){
                    //echo $v['rl'].' '.$v['id'];
                    $userid=$v['id'];
                    $roleid=$v['rl'];
                    $employeename=$v['employeename'];
                    break;
                }
                $session = new Session();
                $session->set('logged',true);
                $session->set('username', $email);
                $session->set('employeename',$employeename);
                $session->set('userid',$userid);
                $session->set('roleid',$roleid);
                
            }
            else {
                return $this->render('security/login.html.twig',['last_username' => '','error' => '','invalidlogin' => 'Invalid Login Credentials']);
            }
         //  return $this->redirectToRoute('dssnacker_login');
           return $this->redirectToRoute('app_snackassignment_count');
           
          //  dd($reclogin);
        }
        else{
            return $this->render('security/login.html.twig',['last_username' => '', 'error' => '']);
        }
      
    }
    
    

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        $session->invalidate();
        return $this->redirectToRoute('app_home_page');
        exit;
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("/usertable", name="app_user_table")
     */
    public function displayUsertable():Response
    {
        $logged=$this->get('session')->get('logged');
        
        $roleid=$this->get('session')->get('roleid');
        
        if($logged!=true && $roleid!=1)
        {
            return $this->redirectToRoute('dssnacker_login');
            
        }
        $repository=$this->getDoctrine()->getRepository(Users::class);
        
        $users=$repository->findAll();
        
        // dd($users);
        
        return $this->render('security/usertable.html.twig',['users'=>$users]);
        
    }
    
    /**
     * @Route("/user/edit/{id}", name="app_role_edit")
     */
    
    public function createAdmin(ManagerRegistry $doctrine, int $id):Response
    {
        $entityManager = $doctrine->getManager(); 
        $employee = $entityManager->getRepository(Users::class)->find($id);
        $employee->setRole($this->getDoctrine()->getManager()->getReference(Roles::class,'1'));
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Role is Changed Successfully'
            );
        
        return $this->redirectToRoute('app_user_table');
        
    }
    
    /**
     * @Route("/admin/edit/{id}", name="app_adminrole_edit")
     */
    
    public function removeAdmin(ManagerRegistry $doctrine, int $id):Response
    {
        $entityManager = $doctrine->getManager();
        $employee = $entityManager->getRepository(Users::class)->find($id);
        $employee->setRole($this->getDoctrine()->getManager()->getReference(Roles::class,'2'));
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Role is Changed Successfully'
            );
        
        return $this->redirectToRoute('app_user_table');
        
    }
    
}
