<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vimeo_ft extends EE_Fieldtype {

	var $info = array(
		'name'		=> 'Vimeo',
		'version'	=> '1.0'
	);

	function install() {
		return array(
			'vimeo_width'		=> '100',
			'vimeo_height'	=> '100'
		);
	}

	function display_global_settings() {
		$val = array_merge($this->settings, $_POST);

		$form = '<p>'.form_label('Default Width', 'width').'<br >'.form_input('width', $this->settings['vimeo_width'], 'style="width:100px;"').'</p>';
		$form .= '<p>'.form_label('Default Height', 'height').'<br >'.form_input('height', $this->settings['vimeo_height'], 'style="width:100px;"').'</p>';

		return $form;
	}

	function save_global_settings() {
		return array_merge($this->settings, $_POST);
	}

	function display_settings($data) {
		if(isset($data['vimeo_width']) && isset($data['vimeo_height'])) {
			$_width = $data['vimeo_width'];
			$_height = $data['vimeo_height'];
		} else {
			$_width = $this->settings['vimeo_width'];
			$_height = $this->settings['vimeo_height'];
		}

		$this->EE->table->add_row(
			lang('Width', 'width'),
			form_input(array('id'=>'vimeo_width', 'name'=>'vimeo_width', 'size'=>4,'value'=>$_width))
		);

		$this->EE->table->add_row(
			lang('Height', 'height'),
			form_input(array('id'=>'vimeo_height', 'name'=>'vimeo_height', 'size'=>4,'value'=>$_height))
		);
	}

	function save_settings($data) {
		return array(
			'vimeo_width'		=> $this->EE->input->post('vimeo_width'),
			'vimeo_height'	=> $this->EE->input->post('vimeo_height')
		);
	}

	function display_field($data) {
		return form_input($this->field_name, $data);
	}

	function replace_tag($data, $params = array(), $tagdata = false) {
		//tagdata not working without an extension - see Pixel and Tonic's implementation of Matrix
		/*
		if($tagdata !== false) {
			$return_data = array();
			$return_data[] = array(
				'embed_id' => $data,
				'embed_width' => (isset($params['width'])) ? $params['width'] : $this->settings['vimeo_width'],
				'embed_height' => (isset($params['height'])) ? $params['height'] : $this->settings['vimeo_height']
			);
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $return_data);
		}
		*/

		// Look for url_params to add to the url, construct the full embed url.
		$froog = (isset($params['froog'])) ? $params['froog'] : false ;
		$player_id = (isset($params['player_id'])) ? $params['player_id'] : false ;

		if ($froog) {
			if (!$player_id) {
				$player_id = str_replace(' ','_',microtime());
			}
		}

		$url_params = (isset($params['url_params'])) ? $params['url_params'] : false ;
		if ($froog && $url_params) {
			$url_params .= "&api=1&player_id=$player_id";
		} elseif ($froog && !$url_params) {
			$url_params .= "api=1&player_id=$player_id";
		}
		$url = 'http://player.vimeo.com/video/'.$data.(($url_params !== false) ? '?'.$url_params : '' );

		if(empty($params) || !isset($params['display'])) {
			return $url;
		}

		switch(strtolower($params['display'])) {
			case 'id':
			case 'id_only':
				return $data;
				break;
			case 'embed':
				$_width = (isset($params['width'])) ? $params['width'] : $this->settings['vimeo_width'];
				$_height = (isset($params['height'])) ? $params['height'] : $this->settings['vimeo_height'];
				return '<iframe title="Vimeo video player" width="'.$_width.'" height="'.$_height.'" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
				break;
			case 'url':
			default:
				return $url;
				break;
		}

		return $data;
	}

	function validate($data) {
		return true; //or error message
	}

	function save($data) {
		//We only want to save the vimeo ID
		//Get a URL from input entered (User might enter in iframe code, a standard url, a share url, etc)
		preg_match('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $data, $matches);
		if(count($matches) > 0) {
			$fixcount = strpos($matches[0], '"');
			if($fixcount > 0) {
				$url = substr($matches[0], 0, $fixcount);
			} else {
				$url = $matches[0];
			}


			$parsed = parse_url($url);

			//vimeo.com/VIDEOID
			if(($parsed['host'] == 'vimeo.com' || $parsed['host'] == 'www.vimeo.com') && $parsed['path'] != '') {
				return str_replace('/', '', $parsed['path']);

			//else Grab 'v' parameter from URL -- (wr) no params ... for now.
			} /*elseif(isset($parsed['query'])) {
				parse_str($parsed['query'], $parse_s);

				// '?v=VIDEOID' is present in URL
				if(isset($parse_s['v'])) {
					return $parse_s['v'];
				}
			}*/

			// '/video/VIDEOID' is present in URL (such as for embed codes)
			$parampos = strpos($url, '/video/');
			if($parampos > 0) {
				$endpos = strpos($url, '?');
				return substr($url, ($parampos+7), ($endpos - ($parampos+7)));
			}

			// '/embed/VIDEOID' is present in URL
			/*$paramembedpos = strpos($url, '/embed/');
			if($paramembedpos > 0) {
				return substr($url, ($paramembedpos+7), (strlen($url)-1));
			}*/
		}

		//If no match, assume they entered a valid vimeo ID (better solution?)
		return $data;
	}

	/* USE IF NEEDED
	function post_save($data) {}
	function delete($ids) {}
	*/
}
// END vimeo_ft class

/* End of file ft.vimeo.php */
/* Location: ./system/expressionengine/third_party/vimeo/ft.vimeo.php */
