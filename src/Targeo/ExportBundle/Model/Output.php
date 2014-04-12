<?php

namespace Targeo\ExportBundle\Model;

/**
 * Nr_Klienta

Kod_Produktu
Kod_Marketingowy
Typ_Zamowienia
Kod_Podpisu
Ilosc_Rekordow
Data_Zamowienia
Reason
DNP
Kontakt
DNPLine
Opcje
Source
ID_Klienta
ID_Klienta2
ID_Zamowienia
Ilosc_Sztuk
ID_Channel
IP_Address
Memo

 */
class Output
{

    protected static $fields = array(
        "Kod_Produktu",
        "Kod_Marketingowy",
        "Typ_Zamowienia",
        "Kod_Podpisu",
        "Ilosc_Rekordow",
        "Data_Zamowienia",
        "Reason",
        "DNP",
        "Kontakt",
        "DNPLine",
        "Opcje",
        "Source",
        "ID_Klienta",
        "ID_Klienta2",
        "ID_Zamowienia",
        "Ilosc_Sztuk",
        "ID_Channel",
        "IP_Address",
        "Memo",
    );
    
    protected $constraints = array(
        "Nr_Klienta" => 9,
        "Kod_Produktu" => 8,
        "Kod_Marketingowy" => 6,
        "Typ_Zamowienia" => 2,
        "Kod_Podpisu" => 1,
        "Ilosc_Rekordow" => 1,
        "Data_Zamowienia" => "YYYYMMDD",
        "Reason" => 2,
        "DNP" => 1,
        "Kontakt" => 1,
        "DNPLine" => 5,
        "Opcje" => 12,
        "Source" => 2,
        "ID_Klienta" => 7,
        "ID_Klienta2" => 7,
        "ID_Zamowienia" => 9,
        "Ilosc_Sztuk" => 3,
        "ID_Channel" => 15,
        "IP_Address" => 15,
        "Memo" => 255,
    );
    
    public static function getFields()
    {
        return self::$fields;
    }

}
