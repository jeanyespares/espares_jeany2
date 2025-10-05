<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Io {

	private $_enable_csrf = FALSE;
	private $security;
	private $status_code;
	private $headers = [];
	private $content;

	public function __construct() {
		$this->security =& load_class('Security', 'kernel');
		$this->_enable_csrf = (config_item('csrf_protection') === TRUE);

		if ($this->_enable_csrf === TRUE) {
			$this->security->csrf_validate();
		}
	}

	// ===================== INPUT METHODS =====================

	public function post($index = NULL) {
		if ($index === NULL && !empty($_POST)) {
			$post = [];
			foreach ($_POST as $key => $value) {
				$post[$key] = $value;
			}
			return $post;
		}
		return $_POST[$index] ?? null;
	}

	public function get($index = NULL) {
		if ($index === NULL && !empty($_GET)) {
			$get = [];
			foreach ($_GET as $key => $value) {
				$get[$key] = $value;
			}
			return $get;
		}
		return $_GET[$index] ?? null;
	}

	public function post_get($index = NULL) {
		$output = $this->post($index);
		return isset($output) ? $output : $this->get($index);
	}

	public function get_post($index = NULL) {
		$output = $this->get($index);
		return isset($output) ? $output : $this->post($index);
	}

	public function cookie($index = NULL) {
		if ($index === NULL && !empty($_COOKIE)) {
			$cookie = [];
			foreach ($_COOKIE as $key => $value) {
				$cookie[$key] = $value;
			}
			return $cookie;
		}
		return $_COOKIE[$index] ?? null;
	}

	public function server($index = NULL) {
		if ($index === NULL && !empty($_SERVER)) {
			$server = [];
			foreach ($_SERVER as $key => $value) {
				$server[$key] = $value;
			}
			return $server;
		}
		return $_SERVER[$index] ?? null;
	}

	public function method($upper = FALSE) {
		return $upper
			? strtoupper($this->server('REQUEST_METHOD'))
			: strtolower($this->server('REQUEST_METHOD'));
	}

	public function ip_address() {
		$trustedHeaders = ['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_REAL_IP'];
		foreach ($trustedHeaders as $header) {
			if (isset($_SERVER[$header]) && filter_var($_SERVER[$header], FILTER_VALIDATE_IP)) {
				return $_SERVER[$header];
			}
		}
		return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
	}

	public function valid_ip($ip, $which = '') {
		switch (strtolower($which)) {
			case 'ipv4': $which = FILTER_FLAG_IPV4; break;
			case 'ipv6': $which = FILTER_FLAG_IPV6; break;
			default: $which = 0; break;
		}
		return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
	}

	public function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}

	// ===================== COOKIE SETTER =====================

	public function set_cookie($name, $value = '', $expiration = 0, $options = []) {
		$defaults = ['prefix', 'path', 'domain', 'secure', 'httponly', 'samesite'];
		$arr = [];

		foreach ($defaults as $key) {
			$arr[$key] = $options[$key] ?? config_item('cookie_' . $key);
		}

		$arr['expiration'] = ($expiration > 0) ? time() + $expiration : 0;

		setcookie($arr['prefix'] . $name, $value, [
			'expires' => $arr['expiration'],
			'path' => $arr['path'],
			'domain' => $arr['domain'],
			'secure' => (bool) $arr['secure'],
			'httponly' => (bool) $arr['httponly'],
			'samesite' => $arr['samesite']
		]);
	}

	// ===================== RESPONSE METHODS =====================

	public function set_status_code($status_code) {
		$this->status_code = $status_code;
	}

	public function add_header($name, $value) {
		if (is_array($name)) {
			foreach ($name as $key => $val) {
				$this->headers[$key] = $val;
			}
		} else {
			$this->headers[$name] = $value;
		}
	}

	public function set_content($content) {
		$this->content = $content;
	}

	public function set_html_content($content) {
		$this->add_header('Content-Type', 'text/html');
		return $this->set_content($content);
	}

	public function send() {
		http_response_code($this->status_code);
		foreach ($this->headers as $name => $value) {
			header("$name: $value");
		}
		echo $this->content;
	}

	public function send_json($data) {
		$this->add_header('Content-Type', 'application/json');
		$this->set_content(json_encode($data));
		$this->send();
	}
}