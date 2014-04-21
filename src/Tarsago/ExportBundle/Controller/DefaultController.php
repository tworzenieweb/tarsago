<?php

namespace Tarsago\ExportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tarsago\ExportBundle\Entity\Export;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        
        $export = new Export();
        $form = $this->createForm(new \Tarsago\ExportBundle\Form\ExportType(), $export);
        
        
        if($request->isMethod('post'))
        {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $export->upload();
                $em->persist($export);
                
                $em->flush();
                
                $request->getSession()->set('exportId', $export->getId());
                
                return $this->redirect($this->generateUrl('tarsago_export_process', array('id' => $export->getId())));
                
                
            }
        }
        
        return $this->render('TarsagoExportBundle:Default:index.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function processAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $export = $em->find('TarsagoExportBundle:Export', $id);
        
        if($export)
        {
            $csv = $this->get('tarsago_export.exporter')->process($export);
            $grid = $this->get('tarsago_export.grid')->render($csv);
            
            return $this->render('TarsagoExportBundle:Default:process.html.twig', array(
                'grid' => $grid,
                'export' => $export
            ));
            
        }
        
        return $this->redirect($this->generateUrl('tarsago_export_homepage'));
    }
    
    public function exportAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $exportEntity = $em->find('TarsagoExportBundle:Export', $id);
        
        if($exportEntity && !$exportEntity->getIsCompleted())
        {
            $csv = $this->get('tarsago_export.exporter')->process($exportEntity);
            $errors = $this->get('tarsago_export.exporter')->exportIM($this->getRequest()->request->all(), $csv, $exportEntity);
            
            if(is_object($errors))
            {   
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $errors
                );
                
                return $this->redirect($this->generateUrl('tarsago_export_process', array('id' => $exportEntity->getId())));
            }
            
        }
        
        return $this->outputFile($exportEntity);
    }
    
    protected function outputFile(Export $exportEntity)
    {
        $content = $this->get('tarsago_export.exporter')->getFile($exportEntity);

        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=export.zip');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($content));
        ob_clean();
        flush();
        readfile($content);
        
        exit;
    }
}
