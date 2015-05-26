<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishSolrSearchEngineBundle\Tests\DependencyInjection;

use eZ\Bundle\EzPublishSolrSearchEngineBundle\DependencyInjection\EzPublishSolrSearchEngineExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\Yaml\Yaml;

class EzPublishSolrSearchEngineExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @var \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->extension = new EzPublishSolrSearchEngineExtension();

        parent::setUp();
    }

    protected function getContainerExtensions()
    {
        return array( $this->extension );
    }

    protected function getMinimalConfiguration()
    {
        return Yaml::parse(
            file_get_contents( __DIR__ . "/Fixtures/minimal.yml" )
        );
    }

    public function testEmpty()
    {
        $this->load( array() );
    }

    public function dataProviderForTestEndpoint()
    {
        return array(
            array(
                "endpoint_dsn",
                array(
                    "dsn" => "https://jura:pura@10.10.10.10:5434/jolr",
                    "core" => "core0",
                ),
                array(
                    "scheme" => "https",
                    "host" => "10.10.10.10",
                    "port" => 5434,
                    "user" => "jura",
                    "pass" => "pura",
                    "path" => "/jolr",
                    "core" => "core0",
                ),
            ),
            array(
                "endpoint_standalone",
                array(
                    "scheme" => "https",
                    "host" => "22.22.22.22",
                    "port" => 1232,
                    "user" => "jura",
                    "pass" => "pura",
                    "path" => "/holr",
                    "core" => "core1",
                ),
                array(
                    "scheme" => "https",
                    "host" => "22.22.22.22",
                    "port" => 1232,
                    "user" => "jura",
                    "pass" => "pura",
                    "path" => "/holr",
                    "core" => "core1",
                ),
            ),
            array(
                "endpoint_override",
                array(
                    "dsn" => "https://miles:teg@257.258.259.400:5555/noship",
                    "scheme" => "http",
                    "host" => "farm.com",
                    "port" => 1234,
                    "core" => "core2",
                    "user" => "darwi",
                    "pass" => "odrade",
                    "path" => "/dunr",
                ),
                array(
                    "scheme" => "https",
                    "host" => "257.258.259.400",
                    "port" => 5555,
                    "user" => "miles",
                    "pass" => "teg",
                    "path" => "/noship",
                    "core" => "core2",
                ),
            ),
            array(
                "endpoint_defaults",
                array(
                    "core" => "core3"
                ),
                array(
                    "scheme" => "http",
                    "host" => "127.0.0.1",
                    "port" => 8983,
                    "user" => null,
                    "pass" => null,
                    "path" => "/solr",
                    "core" => "core3",
                ),
            ),
        );
    }

    /**
     * @dataProvider dataProviderForTestEndpoint
     *
     * @param string $endpointName
     * @param array $endpointValues
     * @param array $expectedArgument
     */
    public function testEndpoint( $endpointName, $endpointValues, $expectedArgument )
    {
        $this->load( array( "endpoints" => array( $endpointName => $endpointValues ) ) );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            "ez_search_engine_solr.endpoints.{$endpointName}",
            "ezpublish.search.solr.endpoint",
            array( "alias" => $endpointName )
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            "ez_search_engine_solr.endpoints.{$endpointName}",
            0,
            $expectedArgument
        );
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testEndpointCoreRequired()
    {
        $this->load(
            array(
                "endpoints" => array(
                    "endpoint0" => array(
                        "dsn" => "https://12.13.14.15:4444/solr"
                    ),
                ),
            )
        );
    }
}
