<?php

namespace Targeo\ExportBundle\Model;

use Targeo\ExportBundle\Entity\IM;

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
    );
    
//    nr_klienta,
//kod_produktu,
//kod_marketingowy,
//typ_zamowienia,
//kod_podpisu,
//ilosc_rekordow,
//Data_Zamowienia,
//Reason,
//DNP,
//Kontakt,
//DNPLine,
//Opcje,
//Source,
//ID_Klienta,
//ID_Klienta2,
//ID_Zamowienia,
//Ilosc_Sztuk,
//ID_Channel,
//IP_Address,
//Memo
    
    protected static $defaults = array(
        "kodPodpisu" => 'B',
        "iloscRekordow" => '1',
        "dnp" => '',
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
     * @param type $data
     * @return \Targeo\ExportBundle\Entity\IM
     */
    public function create($data)
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
        
                
        foreach(self::$fields as $key)
        {
            
            if(isset($data[$key]))
            {
                
                $this->setValue($key, $data[$key], $im);
            }
            
        }
        
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
