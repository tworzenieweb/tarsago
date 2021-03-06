<?php

namespace Tarsago\ExportBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tarsago\ExportBundle\Entity\Export;

/**
 * Export controller.
 *
 */
class ExportController extends Controller
{

    /**
     * Lists all Export entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TarsagoExportBundle:Export')->findAll();

        return $this->render('TarsagoExportBundle:Export:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Export entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TarsagoExportBundle:Export')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Export entity.');
        }

        return $this->render('TarsagoExportBundle:Export:show.html.twig', array(
            'entity'      => $entity,
        ));
    }
    
    public function publishAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $export = $em->find('TarsagoExportBundle:Export', $id);
        
        if (!$export) {
            throw $this->createNotFoundException('Unable to find Export entity.');
        }
        
        if($export)
        {
            $this->get('tarsago_export.exporter')->publish($export);
            
            $this->get('session')->getFlashBag()->add(
                    'success',
                    'Pliki zostały załadowane na serwer FTP prawidłowo'
            );
                
            return $this->redirect($this->generateUrl('export', array('id' => $export->getId())));
        }
        
    }
}
