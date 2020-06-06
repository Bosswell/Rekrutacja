<?php


namespace IShopClient\Http;

use Psr\Http\Message\StreamInterface;


class Stream implements StreamInterface
{
    /**
     * @see https://www.php.net/manual/en/function.fopen.php
     */
    private const READABLE_MODES = '/r|a\+|ab\+|w\+|wb\+|x\+|xb\+|c\+|cb\+/';
    private const WRITABLE_MODES = '/a|w|r\+|rb\+|rw|x|c/';


    /** @var resource */
    private $stream;

    private ?int $size = null;
    private bool $seekable;
    private bool $writable;
    private bool $readable;

    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Stream must be instance of resource');
        }

        $this->stream = $stream;
        $this->seekable = $this->getMetadata('seekable');
        $mode = $this->getMetadata('mode');

        $this->readable = (bool)preg_match(self::READABLE_MODES, $mode);
        $this->writable = (bool)preg_match(self::WRITABLE_MODES, $mode);
    }

    public function __destruct()
    {
        $this->detach();
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        if ($this->isSeekable()) {
            $this->seek(0);
        }

        return $this->getContents();
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        if (!isset($this->stream)) {
            return;
        }

        fclose($this->stream);
        $this->detach();
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $result = $this->stream;
        unset($this->stream);

        $this->size = null;
        $this->readable = $this->writable = $this->seekable = false;

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        if (!isset($this->stream)) {
            return null;
        }

        return fstat($this->stream)['size'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        if ($position = ftell($this->stream)) {
            return $position;
        }

        throw new \RuntimeException('Can`t resolve stream position');
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        return feof($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        return $this->seekable;
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        if (!$this->seekable) {
            throw new \RuntimeException('Stream is not seekable');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new \RuntimeException('Unable to seek to stream position');
        }
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        rewind($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        if (!$this->writable) {
            throw new \RuntimeException('Stream is not writable');
        }

        $this->size = null;

        if ($bytes = fwrite($this->stream, $string)) {
            return $bytes;
        }

        return new \RuntimeException('An error occurred while writing to stream');
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        if (!$this->readable) {
            throw new \RuntimeException('Stream is not readable');
        }

        if ($length < 0) {
            throw new \RuntimeException('Length parameter cannot be negative');
        }

        if ($string = fread($this->stream, $length)) {
            return $string;
        }

        throw new \RuntimeException('Unable to read from stream');
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        if (!$this->readable) {
            throw new \RuntimeException('Stream is not readable');
        }

        return stream_get_contents($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        $metadata = stream_get_meta_data($this->stream);

        if (is_null($key)) {
            return $metadata;
        }

        return $metadata[$key] ?? null;
    }
}
