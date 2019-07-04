<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Extra implements \JsonSerializable
{
    use Serializable;

    /**
     * @ODM\Id
     */
    protected $id;


    /**
     * @ODM\EmbedOne(targetDocument=Branch_alias::class)
     */
    protected $branch_alias;

    function __construct()
    {

    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBranch_alias(): ?Branch_alias
    {
        return $this->branch_alias;
    }

    public function setBranch_alias(Branch_alias $branch_alias): Extra
    {
        $this->branch_alias = $branch_alias;

        return $this;
    }
}