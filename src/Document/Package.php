<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
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
     */
    protected $name;

    /**
     * @ODM\Field(type="string")
     */
    protected $version;

    /**
     * @ODM\Field(type="string")
     */
    protected $version__normalized;

    /**
     * @ODM\Field(type="string")
     */
    protected $time;

    /**
     * @ODM\Field(type="string")
     */
    protected $type;

    /**
     * @ODM\Field(type="string")
     */
    protected $notification_url;

    /**
     * @ODM\Field(type="string")
     */
    protected $description;

    /**
     * @ODM\Field(type="string")
     */
    protected $homepage;

    /**
     * @ODM\Field(type="string")
     */
    protected $abandoned;

    /**
     * @ODM\Field(type="string")
     */
    protected $target_dir;

    /**
     * @ODM\Field(type="collection")
     */
    protected $license;

    /**
     * @ODM\Field(type="collection")
     */
    protected $keywords;

    /**
     * @ODM\Field(type="hash")
     */
    protected $require;

    /**
     * @ODM\Field(type="hash")
     */
    protected $require_dev;

    /**
     * @ODM\Field(type="hash")
     */
    protected $suggest;

    /**
     * @ODM\Field(type="hash")
     */
    protected $replace;

    /**
     * @ODM\Field(type="hash")
     */
    protected $conflict;

    /**
     * @ODM\Field(type="hash")
     */
    protected $provide;

    /**
     * @ODM\EmbedOne(targetDocument=Source::class)
     */
    protected $source;

    /**
     * @ODM\EmbedOne(targetDocument=Dist::class)
     */
    protected $dist;

    /**
     * @ODM\EmbedOne(targetDocument=Autoload::class)
     */
    protected $autoload;

    /**
     * @ODM\EmbedOne(targetDocument=Extra::class)
     */
    protected $extra;

    /**
     * @ODM\EmbedMany(targetDocument=Authors::class)
     */
    protected $authors;

    function __construct()
    {
        $this->license = [];
        $this->keywords = [];
        $this->require = [];
        $this->require_dev = [];
        $this->suggest = [];
        $this->replace = [];
        $this->conflict = [];
        $this->provide = [];

        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Package
    {
        $this->name = $name;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): Package
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion__normalized(): ?string
    {
        return $this->version__normalized;
    }

    public function setVersion__normalized(string $version__normalized): Package
    {
        $this->version__normalized = $version__normalized;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): Package
    {
        $this->time = $time;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): Package
    {
        $this->type = $type;

        return $this;
    }

    public function getNotification_url(): ?string
    {
        return $this->notification_url;
    }

    public function setNotification_url(string $notification_url): Package
    {
        $this->notification_url = $notification_url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): Package
    {
        $this->description = $description;

        return $this;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function setHomepage(string $homepage): Package
    {
        $this->homepage = $homepage;

        return $this;
    }

    public function getAbandoned(): ?string
    {
        return $this->abandoned;
    }

    public function setAbandoned(string $abandoned): Package
    {
        $this->abandoned = $abandoned;

        return $this;
    }

    public function getTarget_dir(): ?string
    {
        return $this->target_dir;
    }

    public function setTarget_dir(string $target_dir): Package
    {
        $this->target_dir = $target_dir;

        return $this;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(Source $source): Package
    {
        $this->source = $source;

        return $this;
    }

    public function getDist(): ?Dist
    {
        return $this->dist;
    }

    public function setDist(Dist $dist): Package
    {
        $this->dist = $dist;

        return $this;
    }

    public function getAutoload(): ?Autoload
    {
        return $this->autoload;
    }

    public function setAutoload(Autoload $autoload): Package
    {
        $this->autoload = $autoload;

        return $this;
    }

    public function getExtra(): ?Extra
    {
        return $this->extra;
    }

    public function setExtra(Extra $extra): Package
    {
        $this->extra = $extra;

        return $this;
    }

    public function getRequire(): array
    {
        return $this->require;
    }

    public function addRequire(array $require): Package
    {
        $this->require = array_merge( $this->require, $require );

        return $this;
    }

    public function removeRequire(array $require): Package
    {
        $this->require = array_diff_key( $this->require, $require );

        return $this;
    }

    public function getRequire_dev(): array
    {
        return $this->require_dev;
    }

    public function addRequire_dev(array $require_dev): Package
    {
        $this->require_dev = array_merge( $this->require_dev, $require_dev );

        return $this;
    }

    public function removeRequire_dev(array $require_dev): Package
    {
        $this->require_dev = array_diff_key( $this->require_dev, $require_dev );

        return $this;
    }

    public function getSuggest(): array
    {
        return $this->suggest;
    }

    public function addSuggest(array $suggest): Package
    {
        $this->suggest = array_merge( $this->suggest, $suggest );

        return $this;
    }

    public function removeSuggest(array $suggest): Package
    {
        $this->suggest = array_diff_key( $this->suggest, $suggest );

        return $this;
    }

    public function getReplace(): array
    {
        return $this->replace;
    }

    public function addReplace(array $replace): Package
    {
        $this->replace = array_merge( $this->replace, $replace );

        return $this;
    }

    public function removeReplace(array $replace): Package
    {
        $this->replace = array_diff_key( $this->replace, $replace );

        return $this;
    }

    public function getConflict(): array
    {
        return $this->conflict;
    }

    public function addConflict(array $conflict): Package
    {
        $this->conflict = array_merge( $this->conflict, $conflict );

        return $this;
    }

    public function removeConflict(array $conflict): Package
    {
        $this->conflict = array_diff_key( $this->conflict, $conflict );

        return $this;
    }

    public function getProvide(): array
    {
        return $this->provide;
    }

    public function addProvide(array $provide): Package
    {
        $this->provide = array_merge( $this->provide, $provide );

        return $this;
    }

    public function removeProvide(array $provide): Package
    {
        $this->provide = array_diff_key( $this->provide, $provide );

        return $this;
    }

    public function getLicense(): array
    {
        return $this->license;
    }

    public function addLicense(string $license): Package
    {
        $this->license[$license] = $license;

        return $this;
    }

    public function removeLicense(string $license): Package
    {
        unset($this->license[$license]);

        return $this;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function addKeywords(string $keywords): Package
    {
        $this->keywords[$keywords] = $keywords;

        return $this;
    }

    public function removeKeywords(string $keywords): Package
    {
        unset($this->keywords[$keywords]);

        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function addAuthors(Author $authors): Package
    {
        $this->authors[spl_object_hash($authors)] = $authors;

        return $this;
    }

    public function removeAuthors(Author $authors): Package
    {
        unset($this->authors[spl_object_hash($authors)]);

        return $this;
    }
}