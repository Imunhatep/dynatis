<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Autoload implements \JsonSerializable
{
    use Serializable;

    /**
     * @ODM\Id
     */
    protected $id;


    /**
     * @ODM\Field(type="collection")
     */
    protected $files;

    /**
     * @ODM\Field(type="collection")
     */
    protected $exclude_from_classmap;

    /**
     * @ODM\Field(type="collection")
     */
    protected $classmap;

    /**
     * @ODM\Field(type="hash")
     */
    protected $psr_0;

    /**
     * @ODM\Field(type="hash")
     */
    protected $psr_4;

    function __construct()
    {
        $this->files = [];
        $this->exclude_from_classmap = [];
        $this->classmap = [];
        $this->psr_0 = [];
        $this->psr_4 = [];

    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPsr_0(): array
    {
        return $this->psr_0;
    }

    public function addPsr_0(array $psr_0): Autoload
    {
        $this->psr_0 = array_merge( $this->psr_0, $psr_0 );

        return $this;
    }

    public function removePsr_0(array $psr_0): Autoload
    {
        $this->psr_0 = array_diff_key( $this->psr_0, $psr_0 );

        return $this;
    }

    public function getPsr_4(): array
    {
        return $this->psr_4;
    }

    public function addPsr_4(array $psr_4): Autoload
    {
        $this->psr_4 = array_merge( $this->psr_4, $psr_4 );

        return $this;
    }

    public function removePsr_4(array $psr_4): Autoload
    {
        $this->psr_4 = array_diff_key( $this->psr_4, $psr_4 );

        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function addFiles(string $files): Autoload
    {
        $this->files[$files] = $files;

        return $this;
    }

    public function removeFiles(string $files): Autoload
    {
        unset($this->files[$files]);

        return $this;
    }

    public function getExclude_from_classmap(): array
    {
        return $this->exclude_from_classmap;
    }

    public function addExclude_from_classmap(string $exclude_from_classmap): Autoload
    {
        $this->exclude_from_classmap[$exclude_from_classmap] = $exclude_from_classmap;

        return $this;
    }

    public function removeExclude_from_classmap(string $exclude_from_classmap): Autoload
    {
        unset($this->exclude_from_classmap[$exclude_from_classmap]);

        return $this;
    }

    public function getClassmap(): array
    {
        return $this->classmap;
    }

    public function addClassmap(string $classmap): Autoload
    {
        $this->classmap[$classmap] = $classmap;

        return $this;
    }

    public function removeClassmap(string $classmap): Autoload
    {
        unset($this->classmap[$classmap]);

        return $this;
    }
}