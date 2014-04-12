<?php

namespace Targeo\ExportBundle\Renderer;

use Targeo\ExportBundle\Model\CSV;

class Grid
{
    
    /**
     *
     * @var \Symfony\Bundle\TwigBundle\TwigEngine
     */
    protected $engine;
    protected $helper;
    
    protected $template = 'TargeoExportBundle:Grid:template.html.twig';
    
    
    public function render(CSV $csv)
    {
        return $this->engine->render($this->template, array('csv' => $csv, 'helper' => $this->helper));
    }
    
    public function __construct(\Symfony\Component\Templating\EngineInterface $engine, \Targeo\ExportBundle\Helper\GridHelper $helper)
    {
        $this->engine = $engine;
        $this->helper = $helper;
    }
    
}