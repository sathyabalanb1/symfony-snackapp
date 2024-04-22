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
use App\Repository\SnacksRepository;
use App\Entity\Selection;
use App\Entity\Roles;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;





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
     * @Route("/snackdetails", name="snacks_info")
     */
    public function chooseSnack(Session $session,Request $request,ManagerRegistry $doctrine, SnackassignmentRepository $assignmentreposit, ValidatorInterface $validator): Response
    {
       // $assignment = $doctrine->getRepository(Snackassignment::class)->findBy(array('presentdate'=>date('Y-m-d')));
       
        $logged=$this->get('session')->get('logged');
        
        $roleid=$this->get('session')->get('roleid');
        
        if($logged!=true && $roleid!=1)
        {
            return $this->redirectToRoute('dssnacker_login');
            
        }
        
       $assignment = $doctrine->getRepository(Snackassignment::class)->findAssignmentdetails();
              
              
       date_default_timezone_set("Asia/Kolkata");
       
       $predefinedtime=strtotime("today 06:30 pm");
       
       $currenttime=time();
       
       if($currenttime>=$predefinedtime)
       {
           return $this->render('choose_snack/choosesnack.html.twig', ['presentdate' => '', 'snackinfo'=>'','selectionerror'=>'', 'error'=>'Time Is Over to Choose the Snack']);
       }
       
       $userid=$this->get('session')->get('userid');
      if(count($assignment)==0)
      {
          return $this->render('choose_snack/nosnack.html.twig', ['error'=>'Today Snack is Not Yet Assigned']);      
      }
      else{
          $currentdate=date('Y-m-d');
          $selection = $doctrine->getRepository(Selection::class)->fetchSelectiondetails($userid, $currentdate);
          foreach($assignment as $k=>$v){
              // dd($v->getId());
              $assignmentid=$v->getId();
            
              
           //   $session->set('assignmentid',$assignmentid);
              $this->get('session')->set('assignmentid', $assignmentid);
              
              $snackid=$v->getSnack()->getId();
              $sname=$v->getSnack()->getSnackname();
             // $session->set('snackname',$sname);
              $this->get('session')->set('snackname', $sname);
              
              $presentdate=$v->getPresentdate();
              break;
          }
          
          $pdate= $presentdate->format('d-m-Y');
          
          if(count($selection)==1)
          {
          foreach($selection as $k=>$v)
          {
           // $assignmentid= $v->getAssignment()->getId();             
             $selectionvalue = $v->getIsselected();
             return $this->render('choose_snack/changeselection.html.twig', ['value' => $selectionvalue]);  
          }
          }
          $snackname = $doctrine->getRepository(Snacks::class)->findSnackname($snackid);
          
          
          if($request->request->get('pdate') != ''){
              $selection = new Selection();
              $selection->setCreatedtime(new \DateTime());
              $selection->setAssignment($this->getDoctrine()->getManager()->getReference(Snackassignment::class,$assignmentid));
              $selection->setUser($this->getDoctrine()->getManager()->getReference(Users::class,$userid));
              $selectionvalue = $request->request->get("myselection");
              
              if($selectionvalue == '')
              {
                  $selectionerror="Please Choose Yes or No for the Snack";
                  return $this->render('choose_snack/choosesnack.html.twig',['presentdate' => $pdate,'snackinfo'=>$snackname,'selectionerror' => $selectionerror,'error' => '']);
                  
              }            
              
              $selection->setIsselected($selectionvalue);
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($selection);
              $entityManager->flush();
              $this->addFlash(
                  'choosesnacksuccess',
                  'Snack Assignment is Completed Successfully'
                  );
              return $this->redirectToRoute('snacks_info');
              
           }
      return $this->render('choose_snack/choosesnack.html.twig', ['presentdate' => $pdate, 'snackinfo'=>$snackname,'selectionerror'=>'', 'error'=>'']);  
      }
     
     }
     /**
      * @Route("/updatechoice", name="snacks_edit_no")
      */
     public function updateTono (ManagerRegistry $doctrine,Request $req)
     {
         date_default_timezone_set("Asia/Kolkata");
         
         $predefinedtime=strtotime("today 09:50 pm");
         
         $currenttime=time();
         //dd($req);
         
         if($currenttime>=$predefinedtime)
         {
             return $this->render('choose_snack/choosesnack.html.twig', ['presentdate' => '', 'snackinfo'=>'', 'error'=>'Time Is Over to Choose the Snack']);
         }
         $userid=$this->get('session')->get('userid');
         $assignmentid=$this->get('session')->get('assignmentid');
         $selection = $doctrine->getRepository(Selection::class)->updateNochoice($userid, $assignmentid);
        // return $this->render('choose_snack/choosesnack.html.twig', ['presentdate' => '', 'snackinfo'=>'', 'error'=>'Your Choice is Submitted Successfully','selectionerror'=>'']);
        return $this->redirectToRoute('snacks_info');   
     }
     
     /**
      * @Route("/editchoice", name="snacks_edit_yes")
      */
     public function updateToyes (ManagerRegistry $doctrine)
     {
         date_default_timezone_set("Asia/Kolkata");
         
         $predefinedtime=strtotime("today 08:00 pm");
         
         $currenttime=time();
         
         if($currenttime>=$predefinedtime)
         {
             return $this->render('choose_snack/choosesnack.html.twig', ['presentdate' => '', 'snackinfo'=>'', 'error'=>'Time Is Over to Choose the Snack','selectionerror'=>'']);
         }
         $userid=$this->get('session')->get('userid');
         $assignmentid=$this->get('session')->get('assignmentid');
         $selection = $doctrine->getRepository(Selection::class)->updateYeschoice($userid, $assignmentid);
      //   return $this->render('choose_snack/choosesnack.html.twig', ['presentdate' => '', 'snackinfo'=>'', 'error'=>'Your Choice is Submitted Successfully','selectionerror'=>'']);
      return $this->redirectToRoute('snacks_info'); 
     }
}