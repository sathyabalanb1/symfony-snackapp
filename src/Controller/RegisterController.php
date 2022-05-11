<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Roles;
use App\Repository\UsersRepository;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;




class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function index(): Response
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
    /**
     * @Route("/applogin", name="app_login")
     */
    public function applogin(): Response
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
    
    /**
     * @Route("/registerform", name="app_registerform")
     */
    
    public function new(Request $request, UsersRepository $ursRepository, ValidatorInterface $validator): Response
    {
        // creates a task object and initializes some data for this example
        $urs = new Users();
        $form =$this->createForm(RegisterType::class,$urs);
        // dd($form);
        
        $form->handleRequest($request); 
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $urs->setCreatedtime(new \DateTime());
            $roleid = $request->request->get('role_id'); // doubt
            $urs->setRole($this->getDoctrine()->getManager()->getReference(Roles::class,'2')); //doubt
            //$ename = $request->request->get("employeename");
            $ename=$form->get('employeename')->getData();
            $uname=$form->get('username')->getData();
            $pwd=$form->get('password')->getData();
            
            
            
            $input = ['ename' => $ename, 'uname' => $uname,'pwd'=>$pwd];
            
            $constraints = new Assert\Collection([
                'pwd' => [new Assert\Length(['min' => 4]), new Assert\NotBlank],
                'uname' => [new Assert\Email(), new Assert\notBlank],
                'ename' => [new Assert\notBlank],
            ]);
            
            $violations = $validator->validate($input, $constraints);
            
            
            if (count($violations) > 0) {
                
                $accessor = PropertyAccess::createPropertyAccessor();
                
                $errorMessages = [];
                
                foreach ($violations as $violation) {
                    
                    $accessor->setValue($errorMessages,$violation->getPropertyPath(),$violation->getMessage());
                    
                   // $violation->getPropertyPath(),
                   // $violation->getMessage());
                }
               // return $this->render('Register/registerfail.html.twig',['registerfail' => $errorMessages]);
                return $this->render('Register/register.html.twig',['errors' => $errorMessages,'registerinfo' => $form->createView()]);
                
                
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($urs);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Your post was added'
                );
            
            return $this->redirectToRoute('app_register');
           
            //return $this->redirectToRoute('app_register1');
        }
        return $this->render('Register/register.html.twig',['registerinfo' => $form->createView()]);   
    }
   
}
