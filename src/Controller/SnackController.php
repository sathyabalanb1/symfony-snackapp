<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Snacks;
use App\Form\SnackType;
use App\Repository\SnacksRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;





class SnackController extends AbstractController
{
    /**
     * @Route("/snack", name="app_snack")
     */
    public function index(): Response
    {
        return $this->render('snack/index.html.twig', [
            'controller_name' => 'SnackController',
        ]);
    }
    
    /**
     * @Route("/snackform", name="app_snackform")
     */
    
    public function new(Request $request, SnacksRepository $sksRepository, ValidatorInterface $validator): Response
    {
        // creates a task object and initializes some data for this example
        $sks = new Snacks();
        $form =$this->createForm(SnackType::class,$sks);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $sks->setCreatedtime(new \DateTime());
            // $roleid = $request->request->get('role_id'); // doubt
            // $urs->setRole($this->getDoctrine()->getManager()->getReference(Roles::class,'2')); //doubt
            //$ename = $request->request->get("employeename");
            $sname=$form->get('snackname')->getData();
                   
            $input = ['sname' => $sname];
            
            $constraints = new Assert\Collection([
                'sname' => [new Assert\NotBlank]
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
                return $this->render('snack/snack.html.twig',['errors' => $errorMessages,'snackinfo' => $form->createView()]);
                
                
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sks);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Your post was added'
                );
            
            return $this->redirectToRoute('app_snack');
            
            //return $this->redirectToRoute('app_register1');
        }
        return $this->render('snack/snack.html.twig',['snackinfo' => $form->createView()]);
    }
}
