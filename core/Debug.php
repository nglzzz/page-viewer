<?php

declare(strict_types=1);

namespace Core;

class Debug
{
    private bool $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
        $this->configure();
    }

    public function configure(): void
    {
        if ($this->debug) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        }
    }

    public function printDebug()
    {
        if (!$this->debug) {
            return;
        }

        ?>
        <p><strong>Execution time:</strong> <?= START_TIME - microtime(true) ?></p>
        <?php
    }
}
