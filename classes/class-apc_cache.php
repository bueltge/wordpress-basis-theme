<?php
/**
 * @package    WordPress
 * @subpackage WP-Basis Theme
 * @template   class for use APC cache in WP
 * @since      0.0.1
 */
/*
 * How to use it
 * <?php
 * require 'class.apc_cache.php';
 * $cache = new Wp_Basis_Apc_Cache();
 * $cache -> capturePage();
 *
 * // Here goes the rest of your code :)
 *
 */
class Wp_Basis_Apc_Cache {
	/**
	 * The default time-to-live value, 5 seconds.
	 */
	protected $_ttl = 5;
	/**
	 * The random factor value.
	 * Increment this value if you expect random page results.
	 */
	protected $_randomFactor = 0;
	/**
	 * Cache stack.
	 */
	protected $_stack = array();
	public function __construct() {
		if (!extension_loaded('apc'))
			throw new Exception('APC extension is not loaded!');
	}

	/**
	 * Set time-to-live value. The bigger the value the bigger time interval
	 * between page refreshes.
	 * @param int $ttl
	 */
	public function setTtl($ttl) {
		$this -> _ttl = $ttl;
	}

	/**
	 * Get time-to-live value.
	 * @return int
	 */
	public function getTtl() {
		return $this -> _ttl;
	}

	/**
	 * Set random factor, the bigger the value the more pages will be cached.
	 * Use this if you expect random page results.
	 * @param int $randomFactor
	 */
	public function setRandomFactor($randomFactor) {
		$this -> _randomFactor = $randomFactor;
	}

	/**
	 * Get random factor.
	 * @param int $randomFactor
	 */
	public function getRandomFactor($randomFactor) {
		return $this -> _randomFactor;
	}

	/**
	 * Save data to cache.
	 * @param string $cacheId
	 * @param mixed $data
	 * @return boolean
	 */
	public function save($cacheId, $data) {
		return apc_store($cacheId, $data, $this -> _ttl);
	}

	/**
	 * Load data from cache.
	 * @param string $cacheId
	 * @return mixed
	 */
	public function load($cacheId) {
		$success = false;
		$data = apc_fetch($cacheId, $success);
		return ($success ? $data : null);
	}

	/**
	 * Remove cache.
	 * @param string $cacheId
	 * @return boolean
	 */
	public function remove($cacheId) {
		return apc_delete($cacheId);
	}

	/**
	 * Start capturing the output.
	 *
	 * If more than one request will try to capture the output in the same time,
	 * the first one should lock a mutex and the other will wait up to 5 seconds
	 * before unlock or die.
	 *
	 * @param string $cacheId
	 */
	public function capture($cacheId) {
		if ($this -> _randomFactor > 0)
			$cacheId .= (rand() % $this -> _randomFactor);
		$this -> _loadCacheAndExit($cacheId);
		$this -> _waitForLockOrDie($cacheId);
		$this -> _loadCacheAndExit($cacheId);
		$this -> save($cacheId . 'lock', true);
		ob_start(array($this, '_flush'));
		ob_implicit_flush(false);
		array_push($this -> _stack, $cacheId);
	}

	/**
	 * Flush output and save it in cache when a request is finished.
	 * This method is being used by output buffering feature in php.
	 * @param string $data
	 * @return string
	 */
	public function _flush($data) {
		$cacheId = array_pop($this -> _stack);
		$this -> save($cacheId, $data);
		$this -> remove($cacheId . 'lock');
		return $data;
	}

	/**
	 * Start capturing the page output.
	 * Generate an unique cache id based on the current request uri,
	 * get and post arrays.
	 * If more than one request will try to capture the output in the same time,
	 * the first one should lock a mutex and the other will wait up to 5 seconds
	 * before unlock or die.
	 */
	public function capturePage() {
		$cacheId = md5(serialize(array($_SERVER['REQUEST_URI'], $_GET, $_POST)));
		if ($this -> _randomFactor > 0)
			$cacheId .= (rand() % $this -> _randomFactor);
		$this -> _loadPageCacheAndExit($cacheId);
		$this -> _waitForLockOrDie($cacheId);
		$this -> _loadPageCacheAndExit($cacheId);
		$this -> save($cacheId . 'lock', true);
		ob_start(array($this, '_flushPage'));
		ob_implicit_flush(false);
		array_push($this -> _stack, $cacheId);
	}

	/**
	 * Flush output and save it in cache when a request is finished.
	 * This method is being used by output buffering feature in php.
	 *
	 * This method remembers sent headers.
	 *
	 * @param string $data
	 * @return string
	 */
	public function _flushPage($data) {
		$cacheId = array_pop($this -> _stack);
		$this -> save($cacheId, array($data, headers_list()));
		$this -> remove($cacheId . 'lock');
		return $data;
	}

	/**
	 * Load and flush cache.
	 * @param string $cacheId
	 */
	protected function _loadCacheAndExit($cacheId) {
		$data = $this -> load($cacheId);
		if ($data !== null) {
			echo $data;
			exit ;
		}
	}

	/**
	 * Load page cache, restore headers and flush response.
	 * @param string $cacheId
	 */
	protected function _loadPageCacheAndExit($cacheId) {
		$pack = $this -> load($cacheId);
		if (is_array($pack)) {
			$headers = $pack[1];
			foreach ($headers as $header)
				header($header);
			echo $pack[0];
			exit ;
		}
	}

	/**
	 * Wait up to 5 seconds for unlock or die.
	 * @param string $cacheId
	 */
	protected function _waitForLockOrDie($cacheId) {
		$cacheId .= 'lock';
		$i = 500;
		while ($i > 0) {
			if (!$this -> load($cacheId))
				return;
			$i--;
			usleep(10000);
		}
		exit();
	}

}
