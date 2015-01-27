<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name'		=> 'Instagrizzle',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Jason Varga',
	'pi_author_url'	=> 'http://pixelfear.com',
	'pi_description'=> 'An EE port of Jack McDade\'s Statamic Instagram Plugin',
	'pi_usage'		=> Instagrizzle::usage()
);

class Instagrizzle
{

	public function __construct()
	{
		$this->EE =& get_instance();

		$username = $this->EE->TMPL->fetch_param('username');
		$limit    = (int) $this->EE->TMPL->fetch_param('limit');
		$offset   = (int) $this->EE->TMPL->fetch_param('offset');
		$debug    = (bool) preg_match('/1|on|yes|y|true/i', $this->EE->TMPL->fetch_param('debug'));

		$media    = $this->getMedia($username, $limit, $offset);

		$vars = array();
		foreach ($media as $val) {
			$vars[] = $this->flattenArray($val);
		}

		if ($debug) {
			die(var_dump($vars));
		}

		$this->return_data = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
	}


	private function getMedia($username, $limit = false, $offset = 0)
	{
		$media = $this->scrape($username);

		// offset results
		if ($offset > 0) {
			$media = array_splice($media, $offset);
		}

		// limit results
		if ($limit) {
			$media = array_slice($media, 0, $limit);
		}

		return $media;
	}


	// Quick and dirty Instagram scraper
	// Based on https://gist.github.com/cosmocatalano/4544576
	private function scrape($username)
	{
		$source = file_get_contents('http://instagram.com/' . $username);
		$shards = explode('window._sharedData = ', $source);
		$json_response = explode('"}};', $shards[1]);
		$response_array = json_decode($json_response[0].'"}}', TRUE);
		
		$media = $response_array['entry_data']['UserProfile'][0]['userMedia'];

		return $media;
	}	


	// Change multidimensional array to dot notation
	// Based on http://stackoverflow.com/a/10424516/1569621
	private function flattenArray($array)
	{
		$ritit = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
		$result = array();

		foreach ($ritit as $leafValue) {
			$keys = array();
			foreach (range(0, $ritit->getDepth()) as $depth) {
				$keys[] = $ritit->getSubIterator($depth)->key();
			}
			$result[ join(':', $keys) ] = $leafValue;
		}

		return $result;
	}


	// ----------------------------------------------------------------

	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
View docs on Github: https://github.com/pixelfear/EE-Instagrizzle
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.instagrizzle.php */
/* Location: /system/expressionengine/third_party/instagrizzle/pi.instagrizzle.php */
