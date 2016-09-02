<?php
namespace PharIo\Phive;

/**
 * @covers PharIo\Phive\AliasResolver
 */
class AliasResolverTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var RemoteSourcesListFileLoader
     */
    private $sourcesListFileLoader;

    public function testThrowsExceptionIfListReturnsEmptyArray() {
        $alias = new PharAlias('phpunit', new AnyVersionConstraint(), new AnyVersionConstraint());
        $resolver = new PharIoAliasResolver($this->sourcesListFileLoader->reveal());
        $this->expectException(ResolveException::class);
        $resolver->resolve($alias);
    }

    public function testReturnsExpectedArrayOfUrls() {
        $alias = new PharAlias('phpunit', new AnyVersionConstraint(), new AnyVersionConstraint());

        $sources = [
            new Source('phar.io', new Url('https://example.com/foo')),
            new Source('phar.io', new Url('https://example.com/bar')),
        ];

        $this->sourcesList->getSourcesForAlias($alias)
            ->shouldBeCalled()
            ->willReturn($sources);

        $resolver = new PharIoAliasResolver($this->sourcesListFileLoader->reveal());
        $this->assertEquals($sources, $resolver->resolve($alias));
    }

    protected function setUp() {
        $this->sourcesList = $this->prophesize(SourcesList::class);
        $this->sourcesListFileLoader = $this->prophesize(RemoteSourcesListFileLoader::class);
        $this->sourcesListFileLoader->load()->shouldBeCalled()->willReturn($this->sourcesList);

    }

}



