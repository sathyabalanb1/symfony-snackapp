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
use App\Entity\Users;
use App\Entity\vendor;
use App\Entity\Selection;
use App\Form\AssignmentType;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Persistence\ManagerRegistry;







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
    
    public function new(Request $request, SnackassignmentRepository $sksRepository, ValidatorInterface $validator, ManagerRegistry $doctrine): Response
    {
        // creates a task object and initializes some data for this example
        
        $logged=$this->get('session')->get('logged');
        
        $roleid=$this->get('session')->get('roleid');
        
        
        
        if($logged!=true && $roleid!=1)
        {
            return $this->redirectToRoute('dssnacker_login');
            
        }
        
        $assignmentcount = $doctrine->getRepository(Snackassignment::class)->findAssignmentCount();
        $count = $assignmentcount['count'];
        
        if($count>0)
        {
            return $this->render('assignment/duplicateassignment.html.twig',['error' => 'Already Snack Assigned for Today']);
        }
        
        $assign = new Snackassignment();
        
        //dd($assign);
                
        $form =$this->createForm(AssignmentType::class,$assign);
        
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) { 
            $assign->setCreatedtime(new \DateTime());
            // $roleid = $request->request->get('role_id'); // doubt
            // $urs->setRole($this->getDoctrine()->getManager()->getReference(Roles::class,'2')); //doubt
            //$ename = $request->request->get("employeename");
            $pdate=$form->get('presentdate')->getData();
           // $pdate=$form->get('presentdate');
           // dd($pdate);
         //   $assignmentid=$form->get('id')->getData();
            $snackid=$form->get('snack')->getData();
            $vendorid=$form->get('vendor')->getData();
            
           // $session->set('assignmentid',$assignmentid);
            
            
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
              //  $trans_assign_title=$trans->trans('trans_assign_title',[],null,'en');
  return $this->render('assignment/assignment.html.twig',['errors' => $errorMessages,'assignmentinfo' => $form->createView()]);
  
              //  return $this->render('assignment/assignment.html.twig',['trans_assign_title' => $trans_reg_head,'errors' => $errorMessages,'assignmentinfo' => $form->createView()]);
  
                
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($assign);
            $entityManager->flush();
            $this->addFlash(
                'assignmentsuccess',
                'Snack Assignment is Completed Successfully'
                );
            
            return $this->redirectToRoute('app_snackassignment_count');
            
            //return $this->redirectToRoute('app_register1');
        }
        return $this->render('assignment/assignment.html.twig',['assignmentinfo' => $form->createView()]);
    }
    /**
     * @Route("/assignmentcount", name="app_snackassignment_count")
     */
    public function getAssignmentCount(ManagerRegistry $doctrine)
    {
        $assignmentcount = $doctrine->getRepository(Snackassignment::class)->findAssignmentCount();
        $count = $assignmentcount['count'];
        $sessmem =  $this->get('session')->get('roleid');
        
        
        if($count>0)
        {
            if($sessmem ==1)
            {
                $emparray=$this->getDsquaddetails($doctrine);
                
                $selectionarray=$this->getTodayselectionresponse($doctrine,$emparray);
                
                $sname=$this->getTodayassignedsnackname($doctrine);
                                
                $vcount=$doctrine->getRepository(Vendor::class)->findAll();
                $vendor_details = array();
                foreach($vcount as $k=>$v1){
                    $vendor_details[$v1->getId()] = $v1->getVendorname();
                }
             
                $vendorcount=count($vcount);       
                
                $total=$this->findPurchasedvendorcount($doctrine);
                
                $total_days = array_sum($total);
                $percent = array();
                foreach($total as $k=>$v){
                    $percent[] = array("y"=>number_format(($v/$total_days)*100,2),"label"=>$vendor_details[$k]);
                }
              //  print_r($percent);
              //  exit;
         //       $percent=$this->getVendorpercentage($doctrine,$total);
                
                
                
                return $this->render('admin/adminhomepage.html.twig',['admininfo' => 'Welcome to Admin Homepage','vendorcount'=>$vendorcount,'emparray'=>$emparray,'selectionarray'=>$selectionarray,'snackname'=>$sname,'percent'=>json_encode($percent)]);
                
            //    return $this->render('admin/adminhomepage.html.twig',['admininfo' => 'Welcome to Admin Homepage','employeecount'=>$employeecount,'vendorcount'=>$vendorcount,'admincount'=>$admincount,'usercount'=>$usercount,'snackname'=>$sname]);
                
            }
            else
            {
                return $this->redirectToRoute('snacks_info');
            }
        }
        else {
            if($sessmem ==1)
            {
                return $this->redirectToRoute('app_snackassignment');
            }
            else {
             //   echo "Today Snack is Not Yet Assigned";
             //   exit;
                $employeename=$this->get('session')->get('employeename');
                
                return $this->render('choose_snack/nosnack.html.twig',['emp_name'=>$employeename,'error' => 'Today Snack is Not Yet Assigned']);
             //  return $this->render('choose_snack/choosesnack.html.twig', ['presentdate' => '', 'snackinfo'=>'', 'error'=>'Today Snack is Not Yet Assigned']);
                
            }
        }
        
    }
    /**
     * @Route("/assignmenttable", name="app_assignment_table")
     */
    public function displayUsertable():Response
    {
        $logged=$this->get('session')->get('logged');
        
        $roleid=$this->get('session')->get('roleid');
        
        if($logged!=true && $roleid!=1)
        {
            return $this->redirectToRoute('dssnacker_login');
            
        }
        $repository=$this->getDoctrine()->getRepository(Snackassignment::class);
        
      //  $assignments=$repository->findAll();
      
        $assignments=$repository->findBy(array(),array('presentdate'=>'DESC'));
        
        // dd($assignments);
        
        return $this->render('assignment/assignmenttable.html.twig',['assignments'=>$assignments]);
        
    }
    
    public function findPurchasedvendorcount ($doctrine)
    {
        $startdate=date('Y-m-01');
        $enddate=date('Y-m-d');
        
        $assignmentlist = $doctrine->getRepository(Snackassignment::class)->getAssignmentlist($startdate,$enddate);
        
        $assignedvendors=array();
        
        foreach ($assignmentlist as $k=>$v)
        {
            $val=$v[1];
            array_push($assignedvendors,$val);
        }
        // dd($assignedvendors);
        $new_array = array_count_values($assignedvendors);
        return $new_array;
        
     /*   sort($assignedvendors);
        
        $vendorcount=array();
        $i=0;
        $total=0;
         while($i<count($assignedvendors))
        {
            $x=$assignedvendors[$i];
            $j=$i;
            while($j<count($assignedvendors))
            {
                if($x==$assignedvendors[$j])
                {
                    $total++;
                    $j++;
                }
                else
                {
                    $vendorcount[$x]=$total;
                    break;
                }
                
            }
            $i=$j;
            $total=0;
        } */
    }
    public function getDsquaddetails ($doctrine)
    {
        $ecount=$doctrine->getRepository(Users::class)->findAll();
        
        $employeecount=count($ecount);
        
        $admcount=$doctrine->getRepository(Users::class)->getAlladminuser();
        
        $admincount=count($admcount);
        
        $usercount=$employeecount-$admincount;
        
        $emparray = array(
            'employeecount'=>$employeecount,
            'usercount'=>$usercount,
            'admincount'=>$admincount
        );
        
        return $emparray;
        
    }
    
    public function getTodayselectionresponse (ManagerRegistry $doctrine,$emparray)
    {
        $currentdate1=date("Y-m-d 00:00:00");
        $currentdate2=date("Y-m-d 23:59:59");
        
        $employeecount=$emparray['employeecount'];
        
        $selectionresult=$doctrine->getRepository(Selection::class)->getTodayselection($currentdate1,$currentdate2);
        
        $yescount=0;
        $nocount=0;
        
        foreach($selectionresult as $key=>$values)
        {
            if($values['isselected']==true)
            {
                $yescount++;
            }
            else {
                $nocount++;
            }
        }
        
        $noresponsecount=$employeecount - count($selectionresult);
        
        $selectionarray=array('yescount'=>$yescount,'nocount'=>$nocount,'noresponsecount'=>$noresponsecount);
        
        return $selectionarray;
    }
    
    public function getTodayassignedsnackname($doctrine)
    {
        $assignment = $doctrine->getRepository(Snackassignment::class)->findAssignmentdetails();
        
        foreach($assignment as $k=>$v){
            
            $assignmentid=$v->getId();
            
            $snackid=$v->getSnack()->getId();
            
            $sname=$v->getSnack()->getSnackname();
            
            break;
        } 
        return $sname;
    }
    public function getVendorpercentage($doctrine,$total)
    {
        
        
        $startdate=date('Y-m-01');
        $enddate=date('Y-m-d');
        
        $sdate = strtotime($startdate);
        $edate = strtotime($enddate);
        
        $datediff=$edate-$sdate;
        
        $totaldays = round($datediff / (60 * 60 * 24));
        
        echo $totaldays;
        exit;
        
        
        /* $earlier = new DateTime($startddate);
        $later = new DateTime($enddate);
        
        $totaldays = $later->diff($later)->format("%a");
        
        $abs_diff = $later->diff($earlier)->format("%a"); */ //3
        
        
    }
   
}
