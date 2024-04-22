<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Selection;
use App\Repository\SelectionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Users;
use App\Repository\UsersRepository;



class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="app_report")
     */
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }
    /**
     * @Route("/reportform", name="app_report_form")
     */
    public function createReportform(Request $request)
    {
        $logged=$this->get('session')->get('logged');
        
        $roleid=$this->get('session')->get('roleid');
        
        
        if($logged!=true && $roleid!=1)
        {
            return $this->redirectToRoute('dssnacker_login');     
        }
        
       // echo $_GET['shootdate'];
       // $reportdate=date('d-m-Y');
      // dd($request->request->get('shootdate'));
      
         if($request->request->get('shootdate') != ''){
            $daterange=$request->request->get('shootdate');
            $datearray=explode(" - ", $daterange);
           // dd($datearray[0]);
           $date1=$datearray[0];
           $date2=$datearray[1];  
    } 
    return $this->render('report/reportform.html.twig');
    }
    /**
     * @Route("/report/ajax")
     */
    public function ajaxAction (Request $request)
    {   
        
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

           
            $daterange=$request->request->get('daterange');
            $datearray=explode(" - ", $daterange);
            $date1=date("Y-m-d 00:00:00",strtotime($datearray[0]));
            $date2=date("Y-m-d 23:59:59",strtotime($datearray[1]));
            
          //  $selectedusers = $this->getDoctrine()->getRepository(Selection::class)->fetchYesChoiceusers($date1);
            
            $selectedusers = $this->getDoctrine()->getRepository(Selection::class)->findSelectedusers($date1, $date2);
            
            
            //dd($selectedusers);
            
            
            $jsonData = array();
            $idx = 0;
            foreach($selectedusers as $key=>$values) {
                $date = $values['createdtime'];
                $seldate=$date->format('d-m-Y');
                
              // echo  $literalTime    =   \DateTime::createFromFormat("d/m/Y H:i",strtotime($date));
                
             //  echo $time_input = strtotime( $values['createdtime']);
             //  exit;
               
             //   print_r($values);
           //   echo  $dates = new DateTime($values['createdtime']);
             // echo (new \DateTime($values['createdtime']))->format('d.m.Y');
              
              //  echo $dates->format('Y-m-d H:i:s');
                $temp = array(
                    'id' => $values['id'],
                    'employeename' => $values['employeename'],
                    'isselected' =>$values['isselected'],
                    'createdtime'=>$seldate
                );
                $jsonData[$idx++] = $temp;
            }  
            //exit;
            
          //  var_dump($jsonData);
          //  exit;
          return new JsonResponse($jsonData);
        } else { 
            return $this->render('report/reportform.html.twig');
        }
            
    }
    /**
     * @Route("/noresponsereport/ajax")
     */
    public function secondajaxAction (Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            
            
            $daterange=$request->request->get('daterange');
            $datearray=explode(" - ", $daterange);
            $date1=date("Y-m-d 00:00:00",strtotime($datearray[0]));
            $date2=date("Y-m-d 23:59:59",strtotime($datearray[1]));
            
          //  $noresponseusers = $this->getDoctrine()->getRepository(Selection::class)->fetchNoresponseusers($date1);
            
            $selectedusers = $this->getDoctrine()->getRepository(Selection::class)->findSelectedusers($date1,$date2);
            $allusers = $this->getDoctrine()->getRepository(Users::class)->getAllUser();
            
            
            // dd($selectedusers);
            $user_list = array();
            foreach($allusers as $key=>$values) {
                $user_list[$values['id']] = $values['employeename'];
            }
           
            $jsonData = array();
            $idx = 0;
          //  print_r($noresponseusers);
            $temp_details = array();
           // print_r($selectedusers);
            foreach($selectedusers as $key=>$values) {
                $date = $values['createdtime'];
                $seldate=$date->format('d-m-Y');
                if(!array_key_exists($seldate, $temp_details)){
                    $temp_details[$seldate][] = $values['id'];
                }
                else {
                    $temp_details[$seldate][] = $values['id'];
                }
            }
           // print_r($temp_details);
            $final_array = array();
            foreach($temp_details as $k1=>$v){
               // print_r($user_list);
                foreach($user_list as $k=>$values){
                    if(!in_array($k, $v)){
                     //  $final_array[$k1][] = array($k,$user_list[$k],$k1);
                     
                        $temp = array(
                            'id' => $k,
                            'employeename' => $user_list[$k],
                            'createdtime'=>$k1
                        );
                        $jsonData[$idx++] = $temp;
                        
                     //  $jsonData[$idx++] = array($k,$user_list[$k],$k1);
                        
                     /*   $empname=$user_list[$k];
                        $empid=$k;
                        $sdate=$seldate;*/
                        
                        
                      //  $user_list[$values['id']] = $values['employeename'];
                      
                        //$final_array[$k1][]=$empname;
                    }
                }
               // echo "<br>";
               // echo "<br>";
            }
           // print_r($final_array);
           // exit;
            
            return new JsonResponse($jsonData);
            
        } else {
            return $this->render('report/reportform.html.twig');
        }
        
        
    }
}
