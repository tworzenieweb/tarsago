<?php

namespace Targeo\ExportBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\ORM\Mapping as ORM;

/**
 * Export
 */
class Export
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $filenameContent;

    /**
     * @var \DateTime
     */
    private $createdAt;
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private $file;


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
     * Set filename
     *
     * @param string $filename
     * @return Export
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filenameContent
     *
     * @param string $filenameContent
     * @return Export
     */
    public function setFilenameContent($filenameContent)
    {
        $this->filenameContent = $filenameContent;

        return $this;
    }

    /**
     * Get filenameContent
     *
     * @return string 
     */
    public function getFilenameContent()
    {
        return $this->filenameContent;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Export
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function upload()
    {
        if($this->file === null)
        {
            return;
        }
        
        $this->filename = $this->file->getClientOriginalName();
        $this->filenameContent = file_get_contents($this->file->getPathname());
        $this->file = null;
    }
    
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }
}
