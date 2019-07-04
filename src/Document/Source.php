<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Source implements \JsonSerializable
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

    public function setType(string $type): Source
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): Source
    {
        $this->url = $url;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): Source
    {
        $this->reference = $reference;

        return $this;
    }
}