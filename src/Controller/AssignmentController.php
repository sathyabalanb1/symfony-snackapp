<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SnackassignmentRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;
use App\Entity\Snackassignment;
use App\Form\AssignmentType;





class AssignmentController extends AbstractController
{
    /**
     * @Route("/assignment", name="app_assignment")
     */
    public function index(): Response
    {
        return $this->render('assignment/index.html.twig', [
            'controller_name' => 'AssignmentController',
        ]);
    }
    
    /**
     * @Route("/assignmentform", name="app_snackassignment")
     */
    
    public function new(Request $request, SnackassignmentRepository $sksRepository, ValidatorInterface $validator): Response
    {
        // creates a task object and initializes some data for this example
        $assign = new Snackassignment();
        $form =$this->createForm(AssignmentType::class,$assign);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $assign->setCreatedtime(new \DateTime());
            // $roleid = $request->request->get('role_id'); // doubt
            // $urs->setRole($this->getDoctrine()->getManager()->getReference(Roles::class,'2')); //doubt
            //$ename = $request->request->get("employeename");
            $pdate=$form->get('presentdate')->getData();
            $snackid=$form->get('snack')->getData();
            $vendorid=$form->get('vendor')->getData();
            
            
            $input = ['snackid' => $snackid, 'vendorid'=>$vendorid, 'presentdate'=>$pdate];
            
            $constraints = new Assert\Collection([
                'snackid' => [new Assert\NotBlank],
                'vendorid' => [new Assert\notBlank],
                'presentdate' => [new Assert\notBlank],
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
  return $this->render('assignment/assignment.html.twig',['errors' => $errorMessages,'assignmentinfo' => $form->createView()]);
                
                
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($assign);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Your post was added'
                );
            
            return $this->redirectToRoute('app_assignment');
            
            //return $this->redirectToRoute('app_register1');
        }
        return $this->render('assignment/assignment.html.twig',['assignmentinfo' => $form->createView()]);
    }
}