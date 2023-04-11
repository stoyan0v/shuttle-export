<?php
namespace ShuttleExport;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ExecutableFinder;

/**
 * Shell abstraction; It's just a handy proxy for Symfony Process component. 
 */
class Shell {
	/**
	 * @var ExecutableFinder
	 */
	public $executable_finder;

	function __construct() {
		$this->executable_finder = new ExecutableFinder();
	}

	function is_enabled() {
		if (!function_exists('proc_open')) {
			return false;
		}

		return stripos(ini_get('disable_functions'), 'proc_open') === false;
	}

	function has_command($command) {
		return $this->executable_finder->find($command) !== null;
	}
}

