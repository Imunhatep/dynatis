<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(repositoryClass="App\Repository\PackageRepository")
 */
class Package implements \JsonSerializable
{
    use Serializable, Timestampable;

    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @ODM\UniqueIndex(order="asc")
     *
     * @var string
     */
    protected $namespace;

    /**
     * @ODM\Field(type="string")
     *
     * @var string
     */
    protected $version;

    /**
     * @ODM\Field(type="integer")
     *
     * @var integer
     */
    protected $status;

    /**
     * @ODM\Field(type="date")
     *
     * @var \DateTime
     */
    protected $touched;

    function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     *
     * @return Package
     */
    public function setNamespace(string $namespace): Package
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     *
     * @return Package
     */
    public function setVersion(string $version): Package
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Package
     */
    public function setStatus(int $status): Package
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTouched(): ?int
    {
        return $this->touched;
    }

    /**
     * @return mixed
     */
    public function getTouchedDT(): ?\DateTime
    {
        return \DateTime::createFromFormat('U', $this->touched);
    }

    /**
     * @param mixed $touched
     *
     * @return Package
     */
    public function setTouched(\DateTime $touched): Package
    {
        $this->touched = $touched->format('U');

        return $this;
    }
}