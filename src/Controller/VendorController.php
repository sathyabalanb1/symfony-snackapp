<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Vendor;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\VendorRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\VendorType;
use Symfony\Component\PropertyAccess\PropertyAccess;



class VendorController extends AbstractController
{
    /**
     * @Route("/vendor", name="app_vendor")
     */
    public function index(): Response
    {
        return $this->render('vendor/index.html.twig', [
            'controller_name' => 'VendorController',
        ]);
    }
    
    /**
     * @Route("/vendorform", name="app_vendorform")
     */
    
    public function new(Request $request, VendorRepository $vrsRepository, ValidatorInterface $validator): Response
    {
        // creates a task object and initializes some data for this example
        $logged=$this->get('session')->get('logged');
        
        $roleid=$this->get('session')->get('roleid');
        
        if($logged!=true && $roleid!=1)
        {
            return $this->redirectToRoute('dssnacker_login');
            
        }
        $vrs = new Vendor();
        $form =$this->createForm(VendorType::class,$vrs);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $vrs->setCreatedtime(new \DateTime());
           // $roleid = $request->request->get('role_id'); // doubt
           // $urs->setRole($this->getDoctrine()->getManager()->getReference(Roles::class,'2')); //doubt
            //$ename = $request->request->get("employeename");
            $vname=$form->get('vendorname')->getData();
            $vlocation=$form->get('vendorlocation')->getData();
            $vnumber=$form->get('cnumber')->getData();           
            
            $input = ['vname' => $vname, 'vlocation' => $vlocation, 'vnumber'=>$vnumber];
            
            $constraints = new Assert\Collection([
                'vname' => [new Assert\NotBlank],
                'vlocation' => [new Assert\notBlank],
                'vnumber' => [new Assert\notBlank],
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
                return $this->render('vendor/vendor.html.twig',['errors' => $errorMessages,'vendorinfo' => $form->createView()]);
                
                
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vrs);
            $entityManager->flush();
            $this->addFlash(
                'vendoraddsuccess',
                'New Vendor Was Added Successfully'
                );
            
            return $this->redirectToRoute('app_vendor_table');
            
            //return $this->redirectToRoute('app_register1');
        }
        return $this->render('vendor/vendor.html.twig',['vendorinfo' => $form->createView()]);
    }
    /**
     * @Route("/vendortable", name="app_vendor_table")
     */
    public function displaySnackstable():Response
    {
        $logged=$this->get('session')->get('logged');
        
        $roleid=$this->get('session')->get('roleid');
        
        if($logged!=true && $roleid!=1)
        {
            return $this->redirectToRoute('dssnacker_login');
            
        }
        $repository=$this->getDoctrine()->getRepository(Vendor::class);
        
        $vendors=$repository->findAll();
        
        // dd($snacks);
        
        return $this->render('vendor/vendortable.html.twig',['controller_name'=>'VendorController','vendors'=>$vendors]);
        
    }
}
