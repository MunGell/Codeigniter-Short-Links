<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/**
 * Linker class for generating short links
 * @author  Shmavon Gazanchyan <munhell@gmail.com>
 * @since   01.01.2012 - 00:00:00
 */

class Shortlinker
{
	/**
	 * Codeigniter instance
	 * @var object
	 */
	private $CI;

	/**
	 * The domain for short links
	 * @var string
	 */
	private $short_domain;

	/**
	 * The length of short "hash"
	 * @var string
	 */
	private $hash_length;
	
	function __construct()
	{
		$this->CI =& get_instance();
		if(!isset($this->CI->db))
		{
			$this->CI->load->library('database');
		}
		$this->CI->config->load('short_linker');
		$this->short_domain = $this->CI->config->item('linker_domain');
		$this->hash_length	= $this->CI->config->item('linker_hash_length');
	}

	/**
	 * Returns shorten link, generates it if does not exist.
	 * @param $link
	 * @param null $customHash
	 * @return string
	 */
	public function getLink($link, $customHash = null)
	{
		$link = trim($link);
		$generated = $this->chechExistance($link);
		if($generated)
		{
			return $generated;
		}
		if(is_null($customHash))
		{
			$hash = $this->getUniqueHash($this->hash_length);
		}
		elseif(!$this->checkUniqueness($customHash))
		{
			$hash = $this->getUniqueHash($this->hash_length, $customHash);
		}
		else
		{
			$hash = $customHash;
		}
		$generated = $this->short_domain . $hash;
		$this->saveLink($link, $generated, $hash);
		return $generated;
	}

	/**
	 * Returns the original link for given short link or hash.
	 * @param string $link
	 * @return string
	 */
	public function getOriginalLink($link)
	{
		$link = trim($link);
		if(strpos($link, 'http') === 0)
		{
			// Link received
			$hash = str_replace($this->short_domain, '', $link);
			$original = $this->CI->db->select('link_original')->from('linker')->where('link_hash', $hash['path'])->get()->row_array();
		}
		else
		{
			// Hash received
			$original = $this->CI->db->select('link_original')->from('linker')->where('link_hash', $link)->get()->row_array();
		}
		return $original['link_original'];
	}

	/**
	 * Generates a unique hash for short link.
	 * @param int $length
	 * @param null $keystr
	 * @return string
	 */
	private function getUniqueHash($length = 6, $keystr = null)
	{
		if(is_null($keystr))
		{
			$hash = substr(md5(uniqid(rand(), true)), 0, $length);
		}
		else
		{
			$hash = substr(md5($keystr.$keystr), 0, $length);
		}
		if(!$this->checkUniqueness($hash))
		{
			$this->getUniqueHash($length, srand($keystr));
		}
		else
		{
			return $hash;
		}
	}

	/**
	 * Checks uniqueness of the hash
	 * @param string $hash
	 * @return bool
	 */
	private function checkUniqueness($hash)
	{
		$exists = $this->CI->db->select('count(*) as count')->from('linker')->where('link_hash', $hash)->get()->row_array();
		if(isset($exists['count']) && $exists['count'] > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Checks existance of the original link in the database. If it already there, returns generated link.
	 * @param string $link
	 * @return string|bool
	 */
	private function chechExistance($link)
	{
		$exists = $this->CI->db->from('linker')->where('link_original', $link)->get()->row_array();
		if(isset($exists['link_id']))
		{
			return $exists['link_generated'];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Saves the link to the database
	 * @param string $original
	 * @param string $generated
	 * @param string $hash
	 */
	private function saveLink($original, $generated, $hash)
	{
		$data = array(
			'link_original'		=> $original,
			'link_generated'	=> $generated,
			'link_hash'			=> $hash
		);
		$this->CI->db->insert('linker', $data);
	}
}