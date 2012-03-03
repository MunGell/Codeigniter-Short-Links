<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sample controller
 * Short URL: s.to/go/{$hash}
 */
class Go extends CI_Controller
{
	public function __construct()
	{
		$this->load->library('shortlinker');
		$this->load->helper('url');
		$original = $this->shortlinker->getOriginalLink(current_url());
		redirect($original);
	}
}

/* End of file go.php */
/* Location: ./application/controllers/go.php */