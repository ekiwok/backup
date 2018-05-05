<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

final class Output
{
    /**
     * @var Directory
     */
    private $destination;

    /**
     * @var string
     */
    private $name;

    public function __construct(Directory $destination, string $name)
    {
        $this->destination = $destination;
        $this->name = $name;
    }

    public function __toString()
    {
        return sprintf("%s/%s", $this->destination, $this->name);
    }
}
