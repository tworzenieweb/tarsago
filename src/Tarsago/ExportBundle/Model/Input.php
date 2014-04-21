<?php

namespace Tarsago\ExportBundle\Model;

use Tarsago\ExportBundle\Model\CSV;

/**
 * field_name	field_type	field_len
  ZIP_CODE	Character	5
  STATE	Character	2
  ACCOUNT_NO	Character	9
  KLIENT_ID	Character	7
  MARKET_COD	Character	6
  NAME	Character	30
  COMPANY	Character	27
  ADDR_LINE1	Character	27
  CITY	Character	19
  ADDR_LINE2	Character	27
  PROD_NUM	Character	8
  CAMPAIGN	Character	6
  SEX	Character	1
  TELEFON1	Character	15
  RODZAJ1	Character	6
  TELEFON2	Character	15
  RODZAJ2	Character	6
  P2011712	Character	1
  P2010725	Character	1
  P2040387	Character	1
  P2010126	Character	1
  P2010947	Character	1
  P2040385	Character	1
  P20741041	Character	1
  P2011522	Character	1
  P2011528	Character	1
  P2040239	Character	1
  P2040270	Character	1
  P2040329	Character	1
  P2040370	Character	1
  P2019397	Character	1
  P2019455	Character	1
  P2011518	Character	1
  P2040220	Character	1
  P20701021	Character	1
  TYTUL	Character	60
  CARRIER	Character	4
  EMAIL	Character	50

 */
class Input extends CSV
{

    protected $fields = array(
        "ZIP_CODE",
        "STATE",
        "ACCOUNT_NO",
        "KLIENT_ID",
        "MARKET_COD",
        "NAME",
        "COMPANY",
        "ADDR_LINE1",
        "CITY",
        "ADDR_LINE2",
        "PROD_NUM",
        "CAMPAIGN",
        "SEX",
        "TELEFON1",
        "RODZAJ1",
        "TELEFON2",
        "RODZAJ2",
        "P2011712",
        "P2010725",
        "P2040387",
        "P2010126",
        "P2010947",
        "P2040385",
        "P20741041",
        "P2011522",
        "P2011528",
        "P2040239",
        "P2040270",
        "P2040329",
        "P2040370",
        "P2019397",
        "P2019455",
        "P2011518",
        "P2040220",
        "P20701021",
        "TYTUL",
        "CARRIER",
        "EMAIL",
    );

    

}
