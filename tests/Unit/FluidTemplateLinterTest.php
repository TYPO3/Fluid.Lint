<?php
namespace TYPO3Fluid\FluidLint\Tests\Unit;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3Fluid\FluidLint\FluidTemplateLinter;

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
		$instance = new FluidTemplateLinter(new RenderingContext(new TemplateView()));
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
		$instance = new FluidTemplateLinter(new RenderingContext(new TemplateView()));
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
