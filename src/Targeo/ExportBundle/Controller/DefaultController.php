<?php

namespace Targeo\ExportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Targeo\ExportBundle\Entity\Export;
use Symfony\Component\HttpFoundation\Response;


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
                'grid' => $grid,
                'export' => $export
            ));
            
        }
        
        return $this->redirect($this->generateUrl('targeo_export_homepage'));
    }
    
    public function exportAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $exportEntity = $em->find('TargeoExportBundle:Export', $id);
        
        if($exportEntity && !$exportEntity->getIsCompleted())
        {
            $csv = $this->get('targeo_export.exporter')->process($exportEntity);
            $errors = $this->get('targeo_export.exporter')->export($this->getRequest()->request->all(), $csv, $exportEntity);
            
            if(is_object($errors))
            {   
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $errors
                );
                
                return $this->redirect($this->generateUrl('targeo_export_process', array('id' => $exportEntity->getId())));
            }
            
        }
        
        return $this->outputFile($exportEntity);
    }
    
    protected function outputFile(Export $exportEntity)
    {
        $content = $this->get('targeo_export.exporter')->getFile($exportEntity);

        $headers = array(
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="'. $exportEntity->getFilename() .'"'
        );  

        return new Response($content, 200, $headers);
    }
}
