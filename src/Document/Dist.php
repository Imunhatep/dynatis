<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Dist implements \JsonSerializable
{
    use Serializable;

    /**
     * @ODM\Id
     */
    protected $id;


    /**
     * @ODM\Field(type="string")
     */
    protected $type;

    /**
     * @ODM\Field(type="string")
     */
    protected $url;

    /**
     * @ODM\Field(type="string")
     */
    protected $reference;

    /**
     * @ODM\Field(type="string")
     */
    protected $shasum;

    function __construct()
    {

    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): Dist
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): Dist
    {
        $this->url = $url;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): Dist
    {
        $this->reference = $reference;

        return $this;
    }

    public function getShasum(): ?string
    {
        return $this->shasum;
    }

    public function setShasum(string $shasum): Dist
    {
        $this->shasum = $shasum;

        return $this;
    }
}