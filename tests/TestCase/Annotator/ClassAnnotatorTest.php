<?php

namespace IdeHelper\Test\TestCase\Annotator;

use Cake\Console\ConsoleIo;
use IdeHelper\Annotator\AbstractAnnotator;
use IdeHelper\Annotator\ClassAnnotator;
use IdeHelper\Console\Io;
use Tools\TestSuite\ConsoleOutput;
use Tools\TestSuite\TestCase;

class ClassAnnotatorTest extends TestCase {

	/**
	 * @var \Tools\TestSuite\ConsoleOutput
	 */
	protected $out;

	/**
	 * @var \Tools\TestSuite\ConsoleOutput
	 */
	protected $err;

	/**
	 * @var \IdeHelper\Console\Io
	 */
	protected $io;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->out = new ConsoleOutput();
		$this->err = new ConsoleOutput();
		$consoleIo = new ConsoleIo($this->out, $this->err);
		$this->io = new Io($consoleIo);
	}

	/**
	 * @return void
	 */
	public function testAnnotate() {
		$annotator = $this->_getAnnotatorMock([]);

		$path = APP . 'Custom/CustomClass.php';
		$execPath = TMP . 'CustomClass.php';
		copy($path, $execPath);

		$annotator->annotate($execPath);

		$content = file_get_contents($execPath);

		$testPath = TEST_FILES . 'Custom/CustomClass.php';
		$expectedContent = file_get_contents($testPath);
		$this->assertTextEquals($expectedContent, $content);

		$output = (string)$this->out->output();

		$this->assertTextContains('  -> 1 annotation added.', $output);
	}

	/**
	 * @param array $params
	 * @return \IdeHelper\Annotator\ClassAnnotator
	 */
	protected function _getAnnotatorMock(array $params) {
		$params += [
			//AbstractAnnotator::CONFIG_REMOVE => true,
			AbstractAnnotator::CONFIG_VERBOSE => true,
		];
		return new ClassAnnotator($this->io, $params);
	}

}
