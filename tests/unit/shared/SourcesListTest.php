<?php
namespace PharIo\Phive;

/**
 * @covers PharIo\Phive\SourcesList
 */
class SourcesListTest extends \PHPUnit_Framework_TestCase {

    public function testReturnsEmptyArrayIfFileDoesNotExist() {
        $list = new SourcesList(
            new XmlFile(
                new Filename('php://memory/foo.xml'),
                'https://phar.io/repository-list',
                'repositories'
            )
        );
        $this->assertEquals(
            [],
            $list->getSourceForAlias(
                new PharAlias('bar', new AnyVersionConstraint(), new AnyVersionConstraint())
            )
        );
    }

    public function testReturnsEmptyArrayForUnknownAlias() {
        $list = new SourcesList(
            new XmlFile(
                new Filename(__DIR__ . '/../../data/repositories.xml'),
                'https://phar.io/repository-list',
                'repositories'
            )
        );
        $this->assertEquals(
            [],
            $list->getSourceForAlias(
                new PharAlias('foo', new AnyVersionConstraint(), new AnyVersionConstraint()
                )
            )
        );
    }

    public function testReturnsExpectedArrayOfSources() {
        $list = new SourcesList(
            new XmlFile(
                new Filename(__DIR__ . '/../../data/repositories.xml'),
                'https://phar.io/repository-list',
                'repositories'
            )
        );

        $expected = [
            new Source('phar.io', new Url('https://phar.phpunit.de')),
            new Source('phar.io', new Url('https://phar.io'))
        ];
        $this->assertEquals(
            $expected,
            $list->getSourceForAlias(
                new PharAlias('phpunit', new AnyVersionConstraint(), new AnyVersionConstraint())
            )
        );

        $expected = [
            new Source('phar.io', new Url('https://phar.io'))
        ];
        $this->assertEquals(
            $expected,
            $list->getSourceForAlias(
                new PharAlias('phpab', new AnyVersionConstraint(), new AnyVersionConstraint())
            )
        );
    }

}



