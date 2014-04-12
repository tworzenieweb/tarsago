<?php

namespace Targeo\ExportBundle\Model;

class CSV
{
    
    protected $rows;
    protected $header;

    function __construct()
    {
        $this->rows = array();
    }
    
    /**
     * 
     * @param type $header
     * @return \Targeo\ExportBundle\Model\CSV
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }
    
    /**
     * 
     * @param array $row
     * @return \Targeo\ExportBundle\Model\CSV
     */
    public function addRow(array $row)
    {
        array_push($this->rows, $row);
        
        return $this;
    }
    
    /**
     * 
     * @param type $offset
     * @return \Targeo\ExportBundle\Model\CSV
     */
    public function removeRow($offset)
    {
        if(isset($this->rows[$offset]))
        {
            unset($this->rows[$offset]);
        }
        else
        {
            throw new Exception('Ten wiersz nie istnieje');
        }
        
        return $this;
    }
    
    public function getColumn($offset)
    {
        if(is_numeric($offset))
        {
            $key = $offset;
        }
        else {
            $key = array_search($offset, $this->header);
            
            if(false === $key)
            {
                throw new \Exception('Nie ma takiej kolumny');
            }
        }
        
        return array_column($this->rows, $key);
    }
    
    public function getHeader()
    {
        return $this->header;
    }
    
    public function getRows()
    {
        return $this->rows;
    }

}