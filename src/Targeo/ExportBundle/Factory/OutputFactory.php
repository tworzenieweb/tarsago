<?php

namespace Targeo\ExportBundle\Factory;

use Targeo\ExportBundle\Model\Input;
use Targeo\ExportBundle\Model\Output;
use Targeo\ExportBundle\Model\CSV;

class OutputFactory
{

    function __construct()
    {
        
    }
    
    public function getOutput(Input $input)
    {
        $inp;
    }
    
    public function getInput(CSV $csv)
    {
        
        $input = new Input($csv);
        
    }
            

}