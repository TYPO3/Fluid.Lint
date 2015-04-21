<?php
namespace TYPO3\Fluid\Lint\Tests\Unit;

use TYPO3\Fluid\Lint\FluidTemplateLinter;

/**
 * Class FluidTemplateLinterTest
 */
class FluidTemplateLinterTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function lintReturnsTrue() {
		$instance = new FluidTemplateLinter();
		$result = $instance->lint('test');
		$this->assertEquals(TRUE, $result);
	}

}
