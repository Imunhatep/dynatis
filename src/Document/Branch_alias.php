<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Branch_alias implements \JsonSerializable
{
    use Serializable;

    /**
     * @ODM\Id
     */
    protected $id;


    /**
     * @ODM\Field(type="string")
     */
    protected $dev_master;

    function __construct()
    {

    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDev_master(): ?string
    {
        return $this->dev_master;
    }

    public function setDev_master(string $dev_master): Branch_alias
    {
        $this->dev_master = $dev_master;

        return $this;
    }
}