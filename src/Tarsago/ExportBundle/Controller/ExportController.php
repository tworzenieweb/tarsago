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
}
