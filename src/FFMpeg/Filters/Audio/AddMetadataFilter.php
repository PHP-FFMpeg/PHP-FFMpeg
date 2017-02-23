<?php
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
		if (is_null($this->metaArr))
			return ['-map_metadata', '-1', '-vn'];

		$metadata = [];

		if (array_key_exists("artwork", $this->metaArr)) {
			array_push($metadata, "-i", $this->metaArr['artwork'], "-map", "0", "-map", "1");
			unset($this->metaArr['artwork']);
		}

		foreach ($this->metaArr as $k => $v) {
			array_push($metadata, "-metadata", "$k=$v");
		}

		return $metadata;
	}
}
