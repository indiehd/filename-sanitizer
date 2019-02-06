<?php

namespace IndieHD\FilenameSanitizer;

use PHPUnit\Framework\TestCase;

class FilenameSanitizerTest extends TestCase
{
    public function setUp()
    {
        $this->sanitizer = new FilenameSanitizer();
    }

    /**
     * Ensure that PHP tags and any code between them is stripped.
     *
     * @return void
     */
    public function testPhpCodeIsStripped()
    {
        $this->assertEquals(
            '',
            $this->sanitizer
                ->setFilename('<?php echo("foo"); ?>')
                ->stripPhp()
                ->getFilename()
        );
    }

    /**
     * Ensure that characters that might be considered risky, due to their
     * potential to be abused in injection-style attacks, are stripped.
     *
     * @return void
     */
    public function testRiskyCharactersAreStripped()
    {
        // The backtick is considered risky because it's a shell command alias.

        $riskyCharacters = '`';

        // 0-31 (ASCII control characters)

        for ($i = 0; $i < 32; $i++) {
            $riskyCharacters .= chr($i);
        }

        $this->assertEquals(
            '',
            $this->sanitizer
                ->setFilename($riskyCharacters)
                ->stripRiskyCharacters()
                ->getFilename()
        );
    }

    /**
     * Ensure that characters that would cause an error or failure if used
     * in a filename on UNIX-like platforms are stripped.
     *
     * @return void
     */
    public function testIllegalCharactersOnUnixAreStripped()
    {
        $forbiddenOnLinux = [
            '/',
            chr(0),
        ];

        $this->assertEquals(
            '',
            $this->sanitizer
                ->setFilename(implode('', $forbiddenOnLinux))
                ->stripIllegalFilesystemCharacters()->getFilename()
        );
    }

    /**
     * Ensure that characters that would cause an error or failure if used
     * in a filename on Windows are stripped.
     *
     * @return void
     */
    public function testIllegalCharactersOnWindowsAreStripped()
    {
        $forbiddenOnWindows = [
            '<',
            '>',
            ':',
            '"',
            '/',
            '\\',
            '|',
            '?',
            '*',
        ];

        // 0-31 (ASCII control characters)

        for ($i = 0; $i < 32; $i++) {
            $forbiddenOnWindows[chr($i)] = '';
        }

        $this->assertEquals(
            '',
            $this->sanitizer
                ->setFilename(implode('', $forbiddenOnWindows))
                ->stripIllegalFilesystemCharacters()
                ->getFilename()
        );
    }

    /**
     * Ensure that characters that would cause an error or failure if used
     * in a filename on MacOS are stripped.
     *
     * @return void
     */
    public function testIllegalCharactersOnMacAreStripped()
    {
        $forbiddenOnMac = [
            ':'
        ];

        $this->assertEquals(
            '',
            $this->sanitizer
                ->setFilename(implode('', $forbiddenOnMac))
                ->stripIllegalFilesystemCharacters()
                ->getFilename()
        );
    }
}
