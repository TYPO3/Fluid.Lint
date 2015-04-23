<?php
namespace TYPO3\Fluid\Lint;
use TYPO3\Fluid\View\TemplateView;
use TYPO3\Fluid\Core\Parser\TemplateParser;

/**
 * Class FluidTemplateLinter
 */
class FluidTemplateLinter {

	/**
	 * @param string $filesPath
	 * @return string
	 */
	public function lint($filesPath) {
		$files = $this->resolveFiles($filesPath);
		$total = count($files);
		$linted = array_combine($files, array_map(array($this, 'lintFile'), $files));
		$errors = array();
		foreach ($linted as $file => $result) {
			if ($result) {
				$errors[$file] = $result;
			}
		}
		$errorCount = count($errors);
		$returnCode = (integer) ($errorCount > 0);
		$summary = ($returnCode > 0 ? sprintf('%d errors detected!', $errorCount) : 'Everything is awesome!');
		$message = ($total - $errorCount) . ' valid out of ' . $total . ' total files. ' . $summary . PHP_EOL;
		if ($errorCount) {
			$message .= PHP_EOL;
			$errors = $this->reduceArrayKeyLength($errors);
			foreach ($errors as $file => $error) {
				$message .= sprintf("\t%s:\n\t\t%s (%d)", $file, $error->getMessage(), $error->getCode()) . PHP_EOL;
			}
		}
		return array($message, $returnCode);
	}

	/**
	 * Reduces the (string) length of array keys to remove
	 * repeated strings like path prefixes.
	 *
	 * @param array $array
	 * @return array
	 */
	protected function reduceArrayKeyLength(array $array) {
		while ($this->allKeysStartWithSamePathSegment($array)) {
			$array = $this->removeSegmentFromArrayKeys($array);
		}
		return $array;
	}

	/**
	 * @param array $array
	 * @return array
	 */
	protected function removeSegmentFromArrayKeys(array $array) {
		$keys = array_map(
			function($item) {
				return substr($item, strpos($item, '/', 1) + 1);
			},
			array_keys($array)
		);
		return array_combine($keys, $array);
	}

	/**
	 * @param array $array
	 * @return boolean
	 */
	protected function allKeysStartWithSamePathSegment(array $array) {
		$keys = array_keys($array);
		$compare = array_shift($keys);
		if (strpos($compare, '/') === FALSE) {
			return FALSE;
		}
		$compare = substr($compare, 0, strpos($compare, '/', 1) + 1);
		foreach ($keys as $key) {
			$key = substr($key, 0, strpos($key, '/', 1) + 1);
			if ($key !== $compare) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * @param string $filePath
	 * @return boolean
	 */
	protected function lintFile($filePath) {
		try {
			$templateParser = new TemplateParser();
			$templateParser->parse(file_get_contents($filePath));
			return FALSE;
		} catch (\RuntimeException $error) {
			return $error;
		}
	}

	/**
	 * @param string $filesPath
	 * @return array
	 */
	protected function resolveFiles($filesPath) {
		if (is_file($filesPath)) {
			return array($filesPath);
		} elseif (strpos($filesPath, '*') !== FALSE) {
			return glob($filesPath);
		} else {
			$iterator = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($filesPath, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::SELF_FIRST
			);
			$files = array();
			foreach (array_values(array_map('strval', iterator_to_array($iterator))) as $file) {
				if (is_file($file)) {
					$files[] = $file;
				}
			}
			return $files;
		}
	}

}
