<?php

namespace Targeo\ExportBundle\Helper;

use Targeo\ExportBundle\Model\OutputFactory;

class GridHelper
{
    
    protected $engine;
    protected $cached;
    
    public function __construct(\Symfony\Component\Templating\EngineInterface $engine)
    {
        $this->engine = $engine;
    }
    
    public function options()
    {
        
        if($this->cached === null)
        {
            $fields = OutputFactory::getAvailableFields();
            $this->cached = $this->engine->render('TargeoExportBundle:Grid:selectbox.html.twig', array(
                'fields' => $fields));
        }
        
        return $this->cached;
        
    }
    
}