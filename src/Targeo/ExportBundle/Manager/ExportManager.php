<?php

namespace Targeo\ExportBundle\Manager;

use Targeo\ExportBundle\Entity\Export;
use Targeo\ExportBundle\Model\CSV;

class ExportManager
{
    
    public function process(Export $export)
    {
        if(!is_resource($export->getFilenameContent()))
        {
            throw new \Exception('Błąd odczytu pliku wejściowego. Podany plik nie jest prawidłowym źródłem');
        }
        
        $content = stream_get_contents($export->getFilenameContent());
        
        $path = 'php://memory';
    
        $fp = fopen($path, "r+");
        fwrite($fp, $content);
        rewind($fp);
        
        
        if ($fp !== FALSE)
        {
            
            $csv = new CSV();
            
            $isHeader = true;
            while (($data = fgetcsv($fp, 1000000, ",")) !== FALSE)
            {
                
                $this->processRow($csv, $data, $isHeader);
                
                if($isHeader)
                {
                    $isHeader = false;
                }
                
            }
            fclose($fp);
            
            return $csv;
        }
        
        throw new \Exception('Nie można załadować pliku wejściowego');


        
    }
    
    protected function processRow(CSV $csv, array $data, $isHeader)
    {
        if($isHeader)
        {
            $csv->setHeader($data);
        }
        else {
            $csv->addRow($data);
            
        }

        return $this;
        
    }
    
}