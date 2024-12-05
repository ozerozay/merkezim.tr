<?php

namespace App\Traits;

trait ClientProfilModalHandler
{
    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function closeAndEmit()
    {
        $this->close(andEmit: ['spotlight.toggle']);
    }
}
