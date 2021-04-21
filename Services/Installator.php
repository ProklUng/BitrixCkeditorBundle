<?php

namespace Local\Bundles\CkEditorBundle\Services;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Installator
 * @package Local\Bundles\CkEditorBundle\Services
 *
 * @since 21.04.2021
 */
class Installator
{
    /**
     * @var string[] $dirs
     */
    private $dirs = [
         '/src/build/' => '/local/assets/ck5editor-bundle/build',
         '/Assets/' => '/local/assets/ck5editor-bundle/assets'
    ];

    /**
     * Скопировать при необходимости ассеты.
     *
     * @return void
     */
    public function installAssets() : void
    {
        $filesystem = new Filesystem();

        foreach ($this->dirs as $src => $target) {
            if (!$filesystem->exists($_SERVER['DOCUMENT_ROOT'] . $target)) {
                $filesystem->mkdir($_SERVER['DOCUMENT_ROOT'] . $target);
                $filesystem->mirror(
                    __DIR__ . '/..' . $src,
                    $_SERVER['DOCUMENT_ROOT'] . $target
                );
            }
        }
    }
}
