<?php

namespace Tarsago\ExportBundle\Manager;

use Tarsago\ExportBundle\Entity\Export;
use Tarsago\ExportBundle\Model\CSV;
use Doctrine\ORM\EntityManager;
use Tarsago\ExportBundle\Model\OutputFactory;
use Tarsago\ExportBundle\Entity\IM;
use Symfony\Component\Validator\Validator;
use Gaufrette\Filesystem;

class ExportManager
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     *
     * @var \Tarsago\ExportBundle\Model\OutputFactory
     */
    private $outputFactory;
    
    /**
     *
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;
    
    /**
     *
     * @var \Gaufrette\Filesystem;
     */
    private $filesystem;
    
    public function __construct(EntityManager $em, OutputFactory $outputFactory, Validator $validator)
    {
        $this->em = $em;
        $this->outputFactory = $outputFactory;
        $this->validator = $validator;
    }
    
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
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
    
    public function exportIM($selectedHeaders, CSV $csv, Export $export)
    {
        $filteredSelectedHeaders = $selectedHeaders['field'];
        
        $filteredSelectedHeaders = array_filter($filteredSelectedHeaders, 'strlen');
        
        if(count($filteredSelectedHeaders) === 0)
        {
            throw new \Exception('Musisz wybrać przynajmniej jedną kolumnę');
        }
        
        $errors = null;

        foreach($csv->getRows() as $row)
        {
            $outputRow = array();
            
            foreach($filteredSelectedHeaders as $inputIndex => $outputIndex) 
            {
                
                $outputRow[$this->outputFactory->getFieldNameForIndex($outputIndex)] = $row[$inputIndex];
                
            }
            
            $im = $this->outputFactory->create($outputRow, $export);

            $this->applyOutputFormatting($im);
            
             
            $errors = $this->validator->validate($im);
            
            if(count($errors) >= 1)
            {
                return $errors;
            }
            
            $im = $this->outputFactory->prePersist($im);
            
            $export->setIsCompleted(true);
            
            $this->em->persist($im);

        }
        
        $this->em->flush();
        
        
        return true;
        
    }
    
    protected function str_pad_unicode($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT) {
        $str_len = mb_strlen($str);
        $pad_str_len = mb_strlen($pad_str);
        if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
            $str_len = 1; // @debug
        }
        if (!$pad_len || !$pad_str_len || $pad_len <= $str_len) {
            return $str;
        }

        $result = null;
        if ($dir == STR_PAD_BOTH) {
            $length = ($pad_len - $str_len) / 2;
            $repeat = ceil($length / $pad_str_len);
            $result = mb_substr(str_repeat($pad_str, $repeat), 0, floor($length))
                    . $str
                    . mb_substr(str_repeat($pad_str, $repeat), 0, ceil($length));
        } else {
            $repeat = ceil($str_len - $pad_str_len + $pad_len);
            if ($dir == STR_PAD_RIGHT) {
                $result = $str . str_repeat($pad_str, $repeat);
                $result = mb_substr($result, 0, $pad_len);
            } else if ($dir == STR_PAD_LEFT) {
                $result = str_repeat($pad_str, $repeat);
                $result = mb_substr($result, 0, 
                            $pad_len - (($str_len - $pad_str_len) + $pad_str_len))
                        . $str;
            }
        }

        return $result;
    }

    
    protected function applyOutputFormatting(IM $im)
    {
        mb_internal_encoding("utf-8");
        $cmf = $this->em->getMetadataFactory();
        
        $class = $cmf->getMetadataFor(get_class($im));
        
        foreach(OutputFactory::getFields() as $field)
        {
            
            if(strstr($field, 'memo:') !== false)
            {
                continue;
            }
            
            $mapping = $class->getFieldMapping($field);
            
            if(!empty($mapping['length']))
            {
                $length = $mapping['length'];
                
                $value = $this->outputFactory->getValue($field, $im);
                
                if((is_numeric($value) && $value < 9) || $field == 'zip')
                {
                    $paddingString = "0";
                    $dir = STR_PAD_LEFT;
                }
                else
                {
                    $paddingString = " ";
                    $dir = STR_PAD_RIGHT;
                }
                
                $value = $this->str_pad_unicode($value, $length, $paddingString, $dir);
                
                $this->outputFactory->setValue($field, $value, $im);
            }
            
        }
        
        return $im;
    }
    
    public function getFile(Export $export)
    {
        $f = array();
        
        $filename = $export->getFilename();
        $filename2 = str_replace(array('IM', 'TXT'), array('RI', 'CSV'), $filename);
        $filename3 = str_replace(array('IM'), array('FP'), $filename);
        $filename4 = str_replace(array('IM'), array('CH'), $filename);
        
        $f[$filename] = $this->getIMFile($export);
        $f[$filename2] = $this->getRIFile($export);
        
        if($export->getChangeAddress())
        {
            $f[$filename4] = $this->getCHFile($export);
        }
        
        $f[$filename3] = $filename3;
        
        
        // files to include in FP file
        $list = sprintf("%s %s %s", $f[$filename], $f[$filename2], $f[$filename4]);
        
        $output = `stat -c "%y %s %n" {$list} | awk '{ printf "%s  %s %19s %s\\n", $1, substr($2,0,9), $4, $5 }'`;
        
        file_put_contents($f[$filename3], str_replace(array('/tmp/', "\n"), array('', "\r\n"), iconv('UTF-8', 'CP1250', $output)));
        
        
        $zip = new \ZipArchive();
        $f1 = tempnam(sys_get_temp_dir(), 'tarsago');

        if ($zip->open($f1, \ZipArchive::CREATE)!==TRUE) {
            throw new \Exception('Cannot create it');
        }
        $zip->addFile($f[$filename], $filename);
        $zip->addFile($f[$filename2], $filename2);
        $zip->addFile($f[$filename3], $filename3);
        
        if($export->getChangeAddress())
        {
            $zip->addFile($f[$filename4], $filename4);
        }
        
        $zip->close();
        
        return $f1;
        
    }
    
    protected function underscore($word)
    {
        return  strtolower(preg_replace('/[^A-Z^a-z^0-9]+/','_',
            preg_replace('/([a-zd])([A-Z])/','\1_\2',
            preg_replace('/([A-Z]+)([A-Z][a-z])/','\1_\2',$word)))
                );
    }
    
    public function getRIFile(Export $export)
    {
        
        $data = $this->em->getRepository('TarsagoExportBundle:IM')->getRI($export);
        
        $filename = str_replace(array('IM', 'TXT'), array('RI', 'CSV'), $export->getFilename());
        $filename = '/tmp/' . $filename;
        
        $csv = new \Keboola\Csv\CsvFile($filename);
        
        
        $header = array();
        foreach(OutputFactory::getRIFields() as $field)
        {
            $header[] = strtoupper($this->underscore($field));
        }
        
        $header[] = 'CNTRP';
        
        $csv->writeRow($header);
        foreach($data as $row)
        {
            
            $rowCSV = array();
            
            foreach(OutputFactory::getRIFields() as $fieldName)
            {
                $rowCSV[] = iconv('UTF-8', 'CP1250', $this->outputFactory->getValue($fieldName, $row[0]));
            }
            
            $rowCSV[] = (string) str_pad($row['CNTRP'], 3, 0, STR_PAD_LEFT);
            
            $csv->writeRow($rowCSV);
        }
        
        return $filename;
        
    }
    
    public function getIMFile(Export $export)
    {
        
        $filename = '/tmp/' . $export->getFilename();
        
        $str = '';
        
        $toIgnore = array(
            'dataTrans',
            'dataImp',
            'dataExp',
            'kampania',
            'mrktcd',
            'produkt',
            'prodect',
            'stan',
            'ftype',
            'vendor',
            'region',
            'blank',
            'zip',
            'blank2',
            'country',
            'account',
            'symbol',
            'name',
            'company',
            'address1',
            'city',
            'address2',
            'blank3',
            'sex',
            'blank4',
            "memo:typ_telefonu",
            "memo:nr_telefonu",
        );

        $fp = fopen($filename, 'w+');
        
        foreach($export->getRows() as $row)
        {
            foreach (OutputFactory::getFields() as $fieldName)
            {
                if(in_array($fieldName, $toIgnore))
                {
                    continue;
                }
                
                fputs($fp, iconv('UTF-8', 'CP1250', $this->outputFactory->getValue($fieldName, $row)));
            }
            
            fputs($fp, iconv('UTF-8', 'CP1250', "\r\n"));
        }
        
        fclose($fp);
        
        return $filename;
    }
    
    public function getCHFile(Export $export)
    {
        
        $filename = '/tmp/' . $export->getFilename();
        $filename = str_replace(array('IM'), array('CH'), $export->getFilename());
        
        $toInclude = array(
            'region',
            'blank',
            'zip',
            'blank2',
            'country',
            'account',
            'symbol',
            'name',
            'company',
            'address1',
            'city',
            'address2',
            'blank3',
            'sex',
            'blank4',
        );

        $fp = fopen($filename, 'w+');
        
        foreach($export->getRows() as $row)
        {
            foreach (OutputFactory::getFields() as $fieldName)
            {
                if(!in_array($fieldName, $toInclude))
                {
                    continue;
                }
                
                fputs($fp, iconv('UTF-8', 'CP1250', $this->outputFactory->getValue($fieldName, $row)));
            }
            
            fputs($fp, iconv('UTF-8', 'CP1250', "\r\n"));
        }
        
        fclose($fp);
        
        return $filename;
    }
    
    protected function getFilesArray($export)
    {
        
        $filesArray = array();
        
        $IMFilename = $export->getFilename();
        $RIFilename = str_replace(array('IM', 'TXT'), array('RI', 'CSV'), $IMFilename);
        $FPFilename = str_replace(array('IM'), array('FP'), $IMFilename);
        $CHFilename = str_replace(array('IM'), array('CH'), $IMFilename);
        
        $filesArray[$IMFilename] = $this->getIMFile($export);
        $filesArray[$RIFilename] = $this->getRIFile($export);
        
        if($export->getChangeAddress())
        {
            $filesArray[$CHFilename] = $this->getCHFile($export);
        }
        
        $filesArray[$FPFilename] = '/tmp/' . $FPFilename;
        
        $list = '';
        
        foreach($filesArray as $path)
        {
            $list .= $path . ' ';
        }
        
        $output = `stat -c "%y %s %n" {$list} | awk '{ printf "%s  %s %19s %s\\n", $1, substr($2,0,9), $4, $5 }'`;
        
        file_put_contents($filesArray[$FPFilename], str_replace(array('/tmp/', "\n"), array('', "\r\n"), iconv('UTF-8', 'CP1250', $output)));
        
        return $filesArray;
    }
    
    public function publish(Export $export)
    {
        $files = $this->getFilesArray($export);
        
        foreach($files as $filename => $filepath)
        {
            $this->filesystem->write($filename, file_get_contents($filepath), true);
        }
    }
    
}