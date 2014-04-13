<?php

namespace Targeo\ExportBundle\Manager;

use Targeo\ExportBundle\Entity\Export;
use Targeo\ExportBundle\Model\CSV;
use Doctrine\ORM\EntityManager;
use Targeo\ExportBundle\Model\OutputFactory;
use Targeo\ExportBundle\Entity\IM;
use Symfony\Component\Validator\Validator;

class ExportManager
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     *
     * @var \Targeo\ExportBundle\Model\OutputFactory
     */
    private $outputFactory;
    
    /**
     *
     * @var Symfony\Component\Validator\Validator
     */
    private $validator;
    
    public function __construct(EntityManager $em, OutputFactory $outputFactory, Validator $validator)
    {
        $this->em = $em;
        $this->outputFactory = $outputFactory;
        $this->validator = $validator;
    }
    
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
            while (($data = fgetcsv($fp, 1000000, $export->getDelimeter())) !== FALSE)
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
    
    public function export($selectedHeaders, CSV $csv)
    {
        $filteredSelectedHeaders = $selectedHeaders['field'];
        
        $filteredSelectedHeaders = array_filter($filteredSelectedHeaders, 'strlen');
        
        if(count($filteredSelectedHeaders) === 0)
        {
            throw new \Exception('Musisz wybrać przynajmniej jedną kolumnę');
        }
        
        


        foreach($csv->getRows() as $row)
        {
            $outputRow = array();

            foreach($filteredSelectedHeaders as $inputIndex => $outputIndex) 
            {
                
                $outputRow[$this->outputFactory->getFieldNameForIndex($inputIndex)] = $row[$inputIndex];
                
                
            }

            $im = $this->outputFactory->create($outputRow);

            $this->applyOutputFormatting($im);
            
            $errors = $this->validator->validate($im);
            
            var_dump($errors); exit;

            $this->em->persist($im);

        }
        
        $this->em->flush();
        
    }
    
    protected function applyOutputFormatting(IM $im)
    {
        $cmf = $this->em->getMetadataFactory();
        
        $class = $cmf->getMetadataFor(get_class($im));
        
        foreach(OutputFactory::getFields() as $field)
        {
            $mapping = $class->getFieldMapping($field);
            
            if(!empty($mapping['length']))
            {
                $length = $mapping['length'];
                
                $value = $this->outputFactory->getValue($field, $im);
                
                if(is_numeric($value) && $value < 9)
                {
                    $paddingString = "0";
                    $dir = STR_PAD_LEFT;
                }
                else
                {
                    $paddingString = " ";
                    $dir = STR_PAD_RIGHT;
                }
                
                $value = str_pad($value, $length, $paddingString, $dir);
                
                $this->outputFactory->setValue($field, $value, $im);
            }
            
        }
        
        return $im;
    }
    
}