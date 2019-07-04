<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Author implements \JsonSerializable
{
    use Serializable;

    /**
     * @ODM\Id
     */
    protected $id;


    /**
     * @ODM\Field(type="string")
     */
    protected $name;

    /**
     * @ODM\Field(type="string")
     */
    protected $email;

    /**
     * @ODM\Field(type="string")
     */
    protected $homepage;

    function __construct()
    {

    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Author
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): Author
    {
        $this->email = $email;

        return $this;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function setHomepage(string $homepage): Author
    {
        $this->homepage = $homepage;

        return $this;
    }
}