<?php
declare(strict_types=1);

namespace App\Document;

use App\Helper\Serializable;
use App\Helper\Timestampable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="repository", repositoryClass="App\Repository\RepositoryRepository")
 */
class Repository implements \JsonSerializable
{
    use Serializable, Timestampable;

    const TYPE_COMPOSER = 'composer';
    const TYPE_PACKAGE = 'package';
    const TYPE_PEAR = 'pear';
    const TYPE_VCS = 'vcs';

    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @var string
     */
    protected $url;

    /**
     * @ODM\Field(type="string")
     * @var string
     */
    protected $type;

    /**
     * @ODM\Field(type="integer")
     * @var integer
     */
    protected $status;

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
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return Repository
     */
    public function setUrl(string $url): Repository
    {
        $this->url = rtrim($url, '/');

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return Repository
     */
    public function setType(string $type): Repository
    {
        $type = strtolower($type);

        if (!in_array($type, [self::TYPE_COMPOSER, self::TYPE_PACKAGE, self::TYPE_PEAR, self::TYPE_VCS])) {
            throw new \InvalidArgumentException('Repository type is not supported');
        }

        $this->type = $type;

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
     * @return Repository
     */
    public function setStatus(int $status): Repository
    {
        $this->status = $status;

        return $this;
    }
}
