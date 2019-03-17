<?php

namespace IndieHD\FilenameSanitizer;

interface FilenameSanitizerInterface
{
    public function getIllegalCharacters();

    public function setFilename($filename);

    public function getFilename();

    public function stripPhp();

    public function stripRiskyCharacters();

    public function stripIllegalFilesystemCharacters();
}
