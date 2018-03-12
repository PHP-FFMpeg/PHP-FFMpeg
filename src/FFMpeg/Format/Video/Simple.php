<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

/**
 * The X264 video format
 */
class Simple extends DefaultVideo {
    /** @var boolean */
    private $bframesSupport = true;

    /** @var integer */
    private $passes = 1;

    /**
     * {@inheritDoc}
     */
    public function supportBFrames() {
        return $this->bframesSupport;
    }

    /**
     * @param $support
     *
     * @return X264
     */
    public function setBFramesSupport($support) {
        $this->bframesSupport = $support;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs() {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableVideoCodecs() {
        return [];
    }

    /**
     * @param $passes
     *
     * @return X264
     */
    public function setPasses($passes) {
        $this->passes = $passes;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPasses() {
        return $this->passes;
    }

    /**
     * @return int
     */
    public function getModulus() {
        return 2;
    }
}