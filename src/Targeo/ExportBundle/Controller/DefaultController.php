<?php

namespace Targeo\ExportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Targeo\ExportBundle\Entity\Export;



class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        
        $export = new Export();
        $form = $this->createForm(new \Targeo\ExportBundle\Form\ExportType(), $export);
        
        
        if($request->isMethod('post'))
        {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $export->upload();
                $em->persist($export);
                $em->flush();
                
                $request->getSession()->set('exportId', $export->getId());
                
                return $this->redirect($this->generateUrl('targeo_export_process', array('id' => $export->getId())));
                
                
            }
        }
        
        return $this->render('TargeoExportBundle:Default:index.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function processAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $export = $em->find('TargeoExportBundle:Export', $id);
        
        if($export)
        {
            $csv = $this->get('targeo_export.exporter')->process($export);
            $grid = $this->get('targeo_export.grid')->render($csv);
            
            return $this->render('TargeoExportBundle:Default:process.html.twig', array(
                'grid' => $grid
            ));
            
        }
        
        return $this->redirect($this->generateUrl('targeo_export_homepage'));
    }
}
