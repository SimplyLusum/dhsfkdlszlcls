<? 

class Homepage {

	var $db;
	var $info;
	
	

	/* Construction */
	function __construct() { 
		$this->db = DB::getInstance();
		$this->info = $this->db->get('tbl_homepage_hm','hm_id=?',1);		
	}
	
	function get_player() {
		$tmp=explode("/",$this->info['hm_video']);
		$code=$tmp[3];
		
		$str='<object width="503" height="315"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="wmode" value="transparent" />';
		$str.='<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=' . $code . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />';
		$str.='<embed src="http://vimeo.com/moogaloop.swf?clip_id=' . $code . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" wmode="transparent" allowscriptaccess="always" width="503" height="315"></embed></object>';
		
		return $str;		
	
	}		
	
	

	function get_title() { return $this->info['hm_title']; }
	function get_subtitle() { return $this->info['hm_subtitle']; }
	function get_content() { return $this->info['hm_desc']; }
	function get_video() { return $this->info['hm_video']; }
	

}