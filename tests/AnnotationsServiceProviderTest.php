<?php

use Mockery as m;
use Collective\Annotations\AnnotationsServiceProvider;

class AnnotationsServiceProviderTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->app = m::mock('Illuminate\Contracts\Foundation\Application');
		$this->provider = new AnnotationsServiceProvider( $this->app );
	}

	public function tearDown()
	{
		m::close();
	}

	public function testConvertNamespaceToPath()
	{
		$this->provider = new AnnotationsServiceProviderAppNamespaceStub( $this->app );
		$class = 'App\\Foo';

		$result = $this->provider->convertNamespaceToPath($class);

		$this->assertEquals('Foo', $result);
	}

	public function testConvertNamespaceToPathWithoutRootNamespace()
	{
		$this->provider = new AnnotationsServiceProviderAppNamespaceStub( $this->app );
		$this->provider->appNamespace = 'Foo';
		$class = 'App\\Foo';

		$result = $this->provider->convertNamespaceToPath($class);

		$this->assertEquals('App/Foo', $result);
	}

	public function testGetClassesFromNamespace()
	{
		$this->provider = new AnnotationsServiceProviderAppNamespaceStub( $this->app );
		$this->provider->appNamespace = 'App';

		$this->app->shouldReceive( 'make' )
			->with( 'Illuminate\Filesystem\ClassFinder' )->once()
			->andReturn( $classFinder = m::mock() );

		$classFinder->shouldReceive('findClasses')
			->with('path/to/app/Base')->once()
			->andReturn( ['classes'] );

		$results = $this->provider->getClassesFromNamespace( 'App\\Base', 'path/to/app' );

		$this->assertEquals( ['classes'], $results );
	}
}


class AnnotationsServiceProviderAppNamespaceStub extends AnnotationsServiceProvider {
	public $appNamespace = 'App';

	public function getAppNamespace()
	{
		return $this->appNamespace;
	}
}
