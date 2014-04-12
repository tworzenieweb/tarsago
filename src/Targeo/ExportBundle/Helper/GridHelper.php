<?php

namespace Targeo\ExportBundle\Helper;

use Targeo\ExportBundle\Model\Output;

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
            $this->cached = $this->engine->render('TargeoExportBundle:Grid:selectbox.html.twig', array(
                'fields' => Output::getFields()));
        }
        
        return $this->cached;
        
    }
    
}