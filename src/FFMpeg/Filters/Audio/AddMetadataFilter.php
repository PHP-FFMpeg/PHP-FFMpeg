<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\Audio\AudioFilterInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

class AddMetadataFilter implements AudioFilterInterface
{
	/** @var Array */
	private $metaArr;
	/** @var Integer */
	private $priority;

	function __construct($metaArr = null, $priority = 9)
	{
		$this->metaArr = $metaArr;
		$this->priority = $priority;
	}

	public function getPriority()
	{
		//must be of high priority in case theres a second input stream (artwork) to register with audio
		return $this->priority;
	}

	public function apply(Audio $audio, AudioInterface $format)
	{
		$meta = $this->metaArr;

		if (is_null($meta)) {
			return ['-map_metadata', '-1', '-vn'];
		}

		$metadata = [];

		if (array_key_exists("artwork", $meta)) {
			array_push($metadata, "-i", $meta['artwork'], "-map", "0", "-map", "1");
			unset($meta['artwork']);
		}

		foreach ($meta as $k => $v) {
			array_push($metadata, "-metadata", "$k=$v");
		}

		return $metadata;
	}
}
