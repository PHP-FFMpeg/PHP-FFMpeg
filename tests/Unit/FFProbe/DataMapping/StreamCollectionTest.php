<?php
declare (strict_types = 1);

namespace Tests\FFMpeg\Unit\FFProbe\DataMapping;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\DataMapping\StreamCollection;

class StreamCollectionTest extends TestCase
{
    public function testUniqueness()
    {
        $stream = $this->getStreamMock();

        $collection = new StreamCollection();

        $collection->add($stream);
        $collection->add($stream);

        $this->assertEquals([$stream], $collection->getUniqueStreams()->getAllStreams());
    }

    public function testAdd()
    {
        $stream = $this->getStreamMock();

        $collection = new StreamCollection;
        $this->assertEquals([], $collection->getAllStreams());
        $collection->add($stream);
        $this->assertEquals([$stream], $collection->getAllStreams());
        $collection->add($stream);
        $this->assertEquals([$stream, $stream], $collection->getAllStreams());
    }

    public function testVideos()
    {
        $audio = $this->getStreamMock();
        $audio->expects($this->once())
            ->method('isVideo')
            ->will($this->returnValue(false));

        $video = $this->getStreamMock();
        $video->expects($this->once())
            ->method('isVideo')
            ->will($this->returnValue(true));

        $collection = new StreamCollection([$audio, $video]);
        $videos = $collection->getVideoStreams();

        $this->assertInstanceOf(StreamCollection::class, $videos);
        $this->assertCount(1, $videos);
        $this->assertEquals([$video], $videos->getAllStreams());
    }

    public function testAudios()
    {
        $audio = $this->getStreamMock();
        $audio->expects($this->once())
            ->method('isAudio')
            ->will($this->returnValue(true));

        $video = $this->getStreamMock();
        $video->expects($this->once())
            ->method('isAudio')
            ->will($this->returnValue(false));

        $collection = new StreamCollection([$audio, $video]);
        $audios = $collection->getAudioStreams();

        $this->assertInstanceOf(StreamCollection::class, $audios);
        $this->assertCount(1, $audios);
        $this->assertEquals([$audio], $audios->getAllStreams());
    }

    public function testCount()
    {
        $stream = $this->getStreamMock();

        $collection = new StreamCollection([$stream]);
        $this->assertCount(1, $collection);
    }

    public function testGetIterator()
    {
        $audio = $this->getStreamMock();
        $video = $this->getStreamMock();

        $collection = new StreamCollection([$audio, $video]);
        $this->assertInstanceOf(\Iterator::class, $collection->getIterator());
        $this->assertCount(2, $collection->getIterator());
    }

    public function testEmptyCollectionIsStillWorking()
    {
        $collection = new StreamCollection([]);

        foreach (['getAudioStreams', 'getUniqueStreams', 'getVideoStreams'] as $method) {
            $emptyCollection = $collection->$method();

            $this->assertInstanceOf(StreamCollection::class, $emptyCollection);
            $this->assertInstanceOf(\Iterator::class, $emptyCollection->getIterator());
            $this->assertCount(0, $emptyCollection);
            $this->assertEmpty($collection->getAllStreams());
        }
    }

    public function testFirst()
    {
        $stream1 = $this->getStreamMock();
        $stream2 = $this->getStreamMock();

        $coll = new StreamCollection([$stream1, $stream2]);

        $this->assertSame($stream1, $coll->getFirstStream());
    }

    public function testFirstIsNullWhenCollectionIsEmpty()
    {
        $collection = new StreamCollection([]);

        $this->assertNull($collection->getFirstStream());
    }
}
