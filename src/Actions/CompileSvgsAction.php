<?php

namespace OwenVoke\BladeFontAwesome\Actions;

use DirectoryIterator;
use RuntimeException;

class CompileSvgsAction
{
    /** @var string */
    private $svgDirectory;

    /** @var string */
    private $svgOutputDirectory;

    public function __construct(string $svgDirectory, string $svgOutputDirectory)
    {
        $this->svgDirectory = $svgDirectory;
        $this->svgOutputDirectory = $svgOutputDirectory;
    }

    public function execute(): void
    {
        foreach (new DirectoryIterator($this->svgDirectory) as $svg) {
            if (! $svg->isFile() || $svg->getExtension() !== 'svg') {
                continue;
            }

            /** @var string $svgContent */
            $svgContent = file_get_contents($svg->getPathname());

            if ($svgContent === false) {
                throw new RuntimeException("Failed to read file: {$svg->getPathname()}");
            }

            $svgContent = str_replace('<svg ', '<svg fill="currentColor" ', $svgContent);
            $svgContent = str_replace('height="1em" ', ' ', $svgContent);

            $ret = file_put_contents("{$this->svgOutputDirectory}/{$svg->getFilename()}", $svgContent);

            if ($ret === false) {
                throw new RuntimeException("Failed to write file: {$svg->getFilename()}");
            }
        }
    }
}
