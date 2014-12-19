<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\options;

use FFMpeg\Options\OptionInterface;

class OptionsCollection implements \Countable, \IteratorAggregate
{
    private $sorted;
    private $options = array();

    /**
     * @param OptionInterface $option
     *
     * @return optionsCollection
     */
    public function add(OptionInterface $option)
    {
        $this->options[$option->getPriority()][] = $option;
        $this->sorted = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if (0 === count($this->options)) {
            return 0;
        }

        return count(call_user_func_array('array_merge', $this->options));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (null === $this->sorted) {
            if (0 === count($this->options)) {
                $this->sorted = $this->options;
            } else {
                krsort($this->options);
                $this->sorted = call_user_func_array('array_merge', $this->options);
            }
        }

        return new \ArrayIterator($this->sorted);
    }
}
