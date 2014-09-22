<?php

/**
 * This file is part of Bldr.io
 *
 * (c) Aaron Scherer <aequasi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE
 */

namespace Bldr\Test\DependencyInjection;

use Bldr\DependencyInjection\ContainerBuilder;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContent;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

/**
 * @author Luis Cordova <cordoval@gmail.com>
 */
class ContainerBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $containerBuilder;
    protected $application;
    protected $input;
    protected $output;
    protected $root;

    public function setUp()
    {
        $this->application = $this->getMockBuilder('Bldr\Application')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->input = $this->getMockBuilder('Symfony\Component\Console\Input\InputInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->root = vfsStream::setup();
    }

    public function testGetThirdPartyBlocks()
    {
        $embeddedComposer = $this->getMockBuilder('Dflydev\EmbeddedComposer\Core\EmbeddedComposer')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $config = $this->getMockBuilder('Composer\Config')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $config->expects($this->once())
            ->method('has')
            ->with('block-loader')
            ->willReturn(true)
        ;
        $config->expects($this->once())
            ->method('get')
            ->with('block-loader')
            ->willReturn('build/blocks.yml')
        ;
        $embeddedComposer
            ->expects($this->once())
            ->method('getExternalComposerConfig')
            ->willReturn($config)
        ;
        $embeddedComposer
            ->expects($this->once())
            ->method('getExternalRootDirectory')
            ->willReturn('/my-project')
        ;
        $this->application
            ->expects($this->once())
            ->method('getEmbeddedComposer')
            ->willReturn($embeddedComposer)
        ;

        $myProjectFolder = vfsStream::newDirectory('my-project')->at($this->root);
        $bldrFolder = vfsStream::newDirectory('build')->at($myProjectFolder);
        vfsStream::newFile('blocks.yml')
            ->withContent('- Block\Miscellaneous\MiscellaneousBlock')
            ->at($bldrFolder)
        ;

        $this->assertTrue(file_exists('my-project/build/blocks.yml'));

        $this->containerBuilder = new ContainerBuilder(
            $this->application,
            $this->input,
            $this->output
        );

        $this->assertCount(1, $this->containerBuilder->getThirdPartyBlocks());
    }
}
