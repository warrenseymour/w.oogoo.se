<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\integration\analysis;

use lithium\core\Libraries;
use lithium\analysis\Logger;
use lithium\util\Collection;
use lithium\util\collection\Filters;

/**
 * Logger adapter integration test cases
 */
class LoggerTest extends \lithium\test\Unit {

	public function testWriteFilter() {

		$base = Libraries::get(true, 'resources') . '/tmp/logs';
		$this->skipIf(!is_writable($base), "{$base} is not writable.");

		Filters::apply('lithium\analysis\Logger', 'write', function($self, $params, $chain) {
			$params['message'] = 'Filtered Message';
			return $chain->next($self, $params, $chain);
		});

		$config = array('default' => array('adapter' => 'File', 'timestamp' => false));
		Logger::config($config);

		$result = Logger::write('info', 'Original Message');
		$this->assertTrue(file_exists($base . '/info.log'));

		$expected = "Filtered Message\n";
		$result = file_get_contents($base . '/info.log');
		$this->assertEqual($expected, $result);

		unlink($base . '/info.log');
	}
}

?>