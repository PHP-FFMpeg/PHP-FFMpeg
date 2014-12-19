<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters;

class AbstractFilter implements FilterInterface
{
    protected $name;
    protected $args;

    public function __construct($name, $args = array())
    {
        $this->name = $name;
        $this->args = $args;
    }

    public function getName()
    {
        return $this->name;
    }

    private function sanitizeArgs()
    {
        return array_filter(array_map('strval', $this->args), function($arg){ return '' !== $arg;});
    }

    private function formatArgs()
    {
        $args = $this->sanitizeArgs();

        if(count($args) === 0) {
            return '';
        }

        return sprintf('=%s', implode(':', $args));
    }

    public function __toString()
    {
        return sprintf('%s%s',$this->name, $this->formatArgs());
    }
}
