<?php

namespace Targeo\ExportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IM
 */
class IM
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nrKlienta;

    /**
     * @var string
     */
    private $kodProduktu;

    /**
     * @var string
     */
    private $kodMarketingowy;

    /**
     * @var string
     */
    private $typZamowienia;

    /**
     * @var string
     */
    private $kodPodpisu;

    /**
     * @var string
     */
    private $iloscRekordow;

    /**
     * @var \DateTime
     */
    private $dataZamowienia;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var string
     */
    private $dnp;

    /**
     * @var string
     */
    private $kontakt;

    /**
     * @var string
     */
    private $DNPLine;

    /**
     * @var string
     */
    private $opcje;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $idKlienta;

    /**
     * @var string
     */
    private $idKlienta2;

    /**
     * @var string
     */
    private $idZamowienia;

    /**
     * @var string
     */
    private $iloscSztuk;

    /**
     * @var string
     */
    private $idChannel;

    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @var string
     */
    private $memo;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nrKlienta
     *
     * @param string $nrKlienta
     * @return IM
     */
    public function setNrKlienta($nrKlienta)
    {
        $this->nrKlienta = $nrKlienta;

        return $this;
    }

    /**
     * Get nrKlienta
     *
     * @return string 
     */
    public function getNrKlienta()
    {
        return $this->nrKlienta;
    }

    /**
     * Set kodProduktu
     *
     * @param string $kodProduktu
     * @return IM
     */
    public function setKodProduktu($kodProduktu)
    {
        $this->kodProduktu = $kodProduktu;

        return $this;
    }

    /**
     * Get kodProduktu
     *
     * @return string 
     */
    public function getKodProduktu()
    {
        return $this->kodProduktu;
    }

    /**
     * Set kodMarketingowy
     *
     * @param string $kodMarketingowy
     * @return IM
     */
    public function setKodMarketingowy($kodMarketingowy)
    {
        $this->kodMarketingowy = $kodMarketingowy;

        return $this;
    }

    /**
     * Get kodMarketingowy
     *
     * @return string 
     */
    public function getKodMarketingowy()
    {
        return $this->kodMarketingowy;
    }

    /**
     * Set typZamowienia
     *
     * @param string $typZamowienia
     * @return IM
     */
    public function setTypZamowienia($typZamowienia)
    {
        $this->typZamowienia = $typZamowienia;

        return $this;
    }

    /**
     * Get typZamowienia
     *
     * @return string 
     */
    public function getTypZamowienia()
    {
        return $this->typZamowienia;
    }

    /**
     * Set kodPodpisu
     *
     * @param string $kodPodpisu
     * @return IM
     */
    public function setKodPodpisu($kodPodpisu)
    {
        $this->kodPodpisu = $kodPodpisu;

        return $this;
    }

    /**
     * Get kodPodpisu
     *
     * @return string 
     */
    public function getKodPodpisu()
    {
        return $this->kodPodpisu;
    }

    /**
     * Set iloscRekordow
     *
     * @param string $iloscRekordow
     * @return IM
     */
    public function setIloscRekordow($iloscRekordow)
    {
        $this->iloscRekordow = $iloscRekordow;

        return $this;
    }

    /**
     * Get iloscRekordow
     *
     * @return string 
     */
    public function getIloscRekordow()
    {
        return $this->iloscRekordow;
    }

    /**
     * Set dataZamowienia
     *
     * @param \DateTime $dataZamowienia
     * @return IM
     */
    public function setDataZamowienia($dataZamowienia)
    {
        $this->dataZamowienia = $dataZamowienia;

        return $this;
    }

    /**
     * Get dataZamowienia
     *
     * @return \DateTime 
     */
    public function getDataZamowienia()
    {
        return $this->dataZamowienia;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return IM
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set dnp
     *
     * @param string $dnp
     * @return IM
     */
    public function setDnp($dnp)
    {
        $this->dnp = $dnp;

        return $this;
    }

    /**
     * Get dnp
     *
     * @return string 
     */
    public function getDnp()
    {
        return $this->dnp;
    }

    /**
     * Set kontakt
     *
     * @param string $kontakt
     * @return IM
     */
    public function setKontakt($kontakt)
    {
        $this->kontakt = $kontakt;

        return $this;
    }

    /**
     * Get kontakt
     *
     * @return string 
     */
    public function getKontakt()
    {
        return $this->kontakt;
    }

    /**
     * Set dnpline
     *
     * @param string $dnpline
     * @return IM
     */
    public function setDnpline($dnpline)
    {
        $this->DNPLine = $dnpline;

        return $this;
    }

    /**
     * Get dnpline
     *
     * @return string 
     */
    public function getDnpline()
    {
        return $this->DNPLine;
    }

    /**
     * Set opcje
     *
     * @param string $opcje
     * @return IM
     */
    public function setOpcje($opcje)
    {
        $this->opcje = $opcje;

        return $this;
    }

    /**
     * Get opcje
     *
     * @return string 
     */
    public function getOpcje()
    {
        return $this->opcje;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return IM
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set idKlienta
     *
     * @param string $idKlienta
     * @return IM
     */
    public function setIdKlienta($idKlienta)
    {
        $this->idKlienta = $idKlienta;

        return $this;
    }

    /**
     * Get idKlienta
     *
     * @return string 
     */
    public function getIdKlienta()
    {
        return $this->idKlienta;
    }

    /**
     * Set idKlienta2
     *
     * @param string $idKlienta2
     * @return IM
     */
    public function setIdKlienta2($idKlienta2)
    {
        $this->idKlienta2 = $idKlienta2;

        return $this;
    }

    /**
     * Get idKlienta2
     *
     * @return string 
     */
    public function getIdKlienta2()
    {
        return $this->idKlienta2;
    }

    /**
     * Set idZamowienia
     *
     * @param string $idZamowienia
     * @return IM
     */
    public function setIdZamowienia($idZamowienia)
    {
        $this->idZamowienia = $idZamowienia;

        return $this;
    }

    /**
     * Get idZamowienia
     *
     * @return string 
     */
    public function getIdZamowienia()
    {
        return $this->idZamowienia;
    }

    /**
     * Set iloscSztuk
     *
     * @param string $iloscSztuk
     * @return IM
     */
    public function setIloscSztuk($iloscSztuk)
    {
        $this->iloscSztuk = $iloscSztuk;

        return $this;
    }

    /**
     * Get iloscSztuk
     *
     * @return string 
     */
    public function getIloscSztuk()
    {
        return $this->iloscSztuk;
    }

    /**
     * Set idChannel
     *
     * @param string $idChannel
     * @return IM
     */
    public function setIdChannel($idChannel)
    {
        $this->idChannel = $idChannel;

        return $this;
    }

    /**
     * Get idChannel
     *
     * @return string 
     */
    public function getIdChannel()
    {
        return $this->idChannel;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return IM
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set memo
     *
     * @param string $memo
     * @return IM
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * Get memo
     *
     * @return string 
     */
    public function getMemo()
    {
        return $this->memo;
    }
}