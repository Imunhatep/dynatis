<?php
declare(strict_types=1);

namespace App\Helper;

trait Serializable
{
    function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}