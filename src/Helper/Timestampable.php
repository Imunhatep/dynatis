<?php
declare(strict_types=1);

namespace App\Helper;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait Timestampable
{
    /**
     * @ODM\Field(type="date")
     *
     * @var \DateTime
     */
    protected $created;

    /**
     * @ODM\Field(type="date")
     *
     * @var \DateTime
     */
    protected $updated;

    /**
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return Timestampable
     */
    public function setCreated(\DateTime $created): Timestampable
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Timestampable
     */
    public function setUpdated(\DateTime $updated): Timestampable
    {
        $this->updated = $updated;

        return $this;
    }
}