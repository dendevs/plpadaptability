<?php
namespace DenDev\Plpadaptability\Test;
use DenDev\Plpadaptability\NoKernel;


class NoKernelTest extends \PHPUnit_Framework_TestCase 
{
	public function test_instanciate()
	{
		$object = new NoKernel();
		$this->assertInstanceOf( 'DenDev\Plpadaptability\NoKernel', $object );
	}

	public function test_get_kernel_service()
	{
		$object = new NoKernel();
		$this->assertFalse( $object->get_kernel_service( 'jkljkl' ) );
	}
}
