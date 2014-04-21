<?php

namespace Tarsago\ExportBundle\Model;

use Tarsago\ExportBundle\Entity\IM;

class OutputFactory
{

    protected static $fields = array(
        "nrKlienta",
        "kodProduktu",
        "kodMarketingowy",
        "typZamowienia",
        "kodPodpisu",
        "iloscRekordow",
        "dataZamowienia",
        "reason",
        "dnp",
        "kontakt",
        "DNPLine",
        "opcje",
        "source",
        "idKlienta",
        "idKlienta2",
        "idZamowienia",
        "iloscSztuk",
        "idChannel",
        "ipAddress",
        "memo",
        
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
    );
    
    protected static $riFields = array(
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
    );
    

    protected static $defaults = array(
        "kodPodpisu" => 'B',
        "iloscRekordow" => '1',
        "dnp" => ' ',
        "DNPLine" => '0',
        'opcje' => 'KU3;',
        "source" => 'BP',
        "idKlienta" => '',
        "idKlienta2" => '',
        "DNPLine" => '0',
        'iloscSztuk' => '1',
        'idChannel' => '',
        'ipAddress' => '',
        'typZamowienia' => 'PA',
        
        // te pola będą brane z normalnego IM
        'dataTrans' => '',
        'dataImp' => '',
        'dataExp' => '',
        'mrktcd' => '',
        'produkt' => '',
        'prodect' => '',
        'stan' => '1',
        'ftype' => '',
        'vendor' => 'BUUSIPERF_',
        'region' => '01',
        'blank' => '',
        'blank2' => '',
        'country' => 'PL',
        'account' => '',
        'symbol' => 'CHADD',
        'blank3' => '',
        'blank4' => '',
        
        
    );
    
    protected static $available;
    
    private static function flip_isset_diff($b, $a)
    {
        $at = array_flip($a);
        $d = array();
        foreach ($b as $key => $i)
            if (!isset($at[$i]))
                $d[$key] = $i;
        return $d;
    }

    public static function getAvailableFields()
    {
        if(self::$available === null)
        {
            self::$available = self::flip_isset_diff(self::$fields, array_keys(self::$defaults));
        }
        
        return self::$available;
    }

    public static function getFields()
    {
        return self::$fields;
    }
    
    public static function getRIFields()
    {
        return self::$riFields;
    }
    
    public function setValue($key, $value, IM $im)
    {
        $method = sprintf('set%s', ucfirst($key));
        $im->$method($value);
    }
    
    public function getValue($key, IM $im)
    {
        $method = sprintf('get%s', ucfirst($key));
        return $im->$method();
    }
    
    protected function initDefault(IM $im)
    {
        foreach(self::$defaults as $key => $value)
        {
            $this->setValue($key, $value, $im);
        }
        
        return $im;
    }
    
    /**
     * 
     * @param array $data
     * @return \Tarsago\ExportBundle\Entity\IM
     */
    public function create(array $data)
    {
        
        $im = new IM();
        
        $this->initDefault($im);
        
        if($data['reason'] > 0 && $data['reason'] < 81)
        {
            
            $data['idZamowienia'] = $data['kodProduktu'] = $data['opcje'] = '';
            $data['iloscSztuk'] = 0;
            
            if($data['reason'] >= 46)
            {
                $data['typZamowienia'] = 'NO';
            }
            else if($data['reason'] < 46) {
                
                $data['typZamowienia'] = 'SA';
                $data['kontakt'] = 'N';
            }
        }
        else {
            $data['reason'] = 81;
        }
        
        $data['dataTrans'] = $data['dataImp'] = $data['dataZamowienia'];
        $data['mrktcd'] = $data['kodMarketingowy'];
        $data['produkt'] = substr($data['kodProduktu'],0,7);
        $data['prodect'] = strlen($data['kodProduktu'] > 7) ? substr($data['kodProduktu'],7,1) : "0";
        $data['account'] = $data['nrKlienta'];
        $data['ftype'] = !empty($data['address1']) ? 'CHADD' : 'CLEAR';
        
        $data['name'] = empty($data['name']) ? $data['company'] : $data['name'];
        $data['sex'] = $data['sex'] == 'F' ? 92 : ($data['sex'] == 'M' ? '91' : '00');
        
        if($data['company'])
        {
           $data['sex'] = '00';
        }
        
        
                
        foreach(self::$fields as $key)
        {
            
            if(isset($data[$key]))
            {
                
                $this->setValue($key, strtoupper($data[$key]), $im);
            }
            
        }
        
        return $im;
        
    }
    
    public function prePersist(IM $im)
    {
        $im->setDataExp($im->getExport()->getCreatedAt());
        
        $cmpField = $im->getDataTrans() . $im->getDataImp() . $im->getDataExp() 
                . $im->getKampania() . $im->getMrktcd() . $im->getProdukt() . $im->getProdect()
                . $im->getStan() . $im->getFtype() . $im->getVendor();
        
        $im->setCmpField($cmpField);
        
        return $im;
        
    }
    
    public function getFieldNameForIndex($index)
    {
        if(!isset(self::$fields[$index]))
        {
            throw new \Exception(sprintf('Kolumna %s nie istnieje', $index));
        }
        
        return self::$fields[$index];
    }

}
