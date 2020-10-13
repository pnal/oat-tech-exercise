<?php declare(strict_types=1);

namespace App\Storage\Question;

use App\Repository\Question\StorageInterface;
use RuntimeException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Base class for any physical-disk-based repository implementations
 * @package App\Infrastructure\Repository
 */
class FileStorage implements StorageInterface
{
    /** @var string */
    protected $dataDirPath;
    /** * @var Filesystem */
    private $filesystem;
    /** @var string */
    private $filename;

    /**
     * @param string $dataDirPath
     */
    public function __construct(string $dataDirPath)
    {
        $this->filesystem = new Filesystem();
        $this->dataDirPath = $dataDirPath;
        $this->initStorage();
    }

    public function setFilename(string $filenameWithExtension): void
    {
        $this->filename = $filenameWithExtension;
        $this->initStorage();
    }

    /**
     * @return string|null
     */
    public function get(): ?string
    {
        $data = file_get_contents($this->getDataFilePath());

        return $data ?: null;
    }

    /**
     * @param string $data
     */
    public function save(string $data): void
    {
        $this->filesystem->dumpFile($this->getDataFilePath(), $data);
        //file_put_contents($this->getDataFilePath(), $data, LOCK_EX);
    }

    /**
     * Check storage is available and can be used
     */
    private function initStorage(): void
    {
        if (!$this->filesystem->exists($this->dataDirPath)) {
            try {
                $this->filesystem->mkdir($this->dataDirPath, 0777);
            } catch (IOExceptionInterface $e) {
                $err = 'An error occurred while creating data directory %s: %s';
                throw new RuntimeException(sprintf($err, $this->dataDirPath, $e->getMessage()));
            }
        }

        if (!$this->filesystem->exists($this->getDataFilePath())) {
            try {
                $this->filesystem->touch($this->getDataFilePath());
            } catch (IOExceptionInterface $e) {
                $err = 'An error occurred while creating data file %s: %s';
                throw new RuntimeException(sprintf($err, $this->getDataFilePath(), $e->getMessage()));
            }
        }
    }

    /**
     * @return string
     */
    private function getDataFilePath(): string
    {
        return $this->dataDirPath . DIRECTORY_SEPARATOR . $this->filename;
    }
}
