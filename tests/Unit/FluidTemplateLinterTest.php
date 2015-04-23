<?php
namespace TYPO3\Fluid\Lint\Tests\Unit;

use TYPO3\Fluid\Lint\FluidTemplateLinter;

/**
 * Class FluidTemplateLinterTest
 */
class FluidTemplateLinterTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider getGoodPaths
	 * @param string $path
	 * @test
	 */
	public function lintReturnsCodeZeroWhenFilesAreOkay($path) {
		$instance = new FluidTemplateLinter();
		list ($message, $returnCode) = $instance->lint($path);
		$this->assertEquals(0, $returnCode);
	}

	/**
	 * @return array
	 */
	public function getGoodPaths() {
		$base = realpath('tests/Fixtures/Templates/Good/');
		return array(
			array($base),
			array($base . '/*'),
			array($base . '/Good.html'),
		);
	}

	/**
	 * @dataProvider getBadPaths
	 * @param string $path
	 * @test
	 */
	public function lintReturnsCodeOneWhenFilesAreNotOkay($path) {
		$instance = new FluidTemplateLinter();
		list ($message, $returnCode) = $instance->lint($path);
		$this->assertEquals(1, $returnCode);
	}

	/**
	 * @return array
	 */
	public function getBadPaths() {
		$base = realpath('tests/Fixtures/Templates/Bad/');
		return array(
			array($base),
			array($base . '/*'),
			array($base . '/Bad.html'),
		);
	}

}
