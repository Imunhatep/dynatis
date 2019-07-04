<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="requirement", repositoryClass="App\Repository\RequirementRepository")
 */
class Requirement implements \JsonSerializable
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
     * @return Requirement
     */
    public function setNamespace(string $namespace): Requirement
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
     * @return Requirement
     */
    public function setVersion(string $version): Requirement
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
     * @return Requirement
     */
    public function setStatus(int $status): Requirement
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
     * @return Requirement
     */
    public function setTouched(\DateTime $touched): Requirement
    {
        $this->touched = $touched->format('U');

        return $this;
    }
}