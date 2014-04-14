<?php

namespace Targeo\ExportBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Targeo\ExportBundle\Entity\Export;

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

        $entities = $em->getRepository('TargeoExportBundle:Export')->findAll();

        return $this->render('TargeoExportBundle:Export:index.html.twig', array(
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

        $entity = $em->getRepository('TargeoExportBundle:Export')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Export entity.');
        }

        return $this->render('TargeoExportBundle:Export:show.html.twig', array(
            'entity'      => $entity,
        ));
    }
}
