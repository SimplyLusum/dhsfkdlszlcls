<? 

class Video {
	
	var $id;
	var $db;
	var $info;

	

	/* Construction */
	function __construct($id=0) { 
		$this->db = DB::getInstance();
		if ($id!=0) {
			$this->id=$id;
			$this->info = $this->db->get('tbl_video_vdo','vdo_id=?',$this->id);		
		}		
	}
	

	
	function get_list() {
		$sql="Select * from tbl_video_vdo where vdo_status=1 order by vdo_order desc";
		return $this->db->getAll($sql);
	}
	
	function get_player() {
		$tmp=explode("/",$this->info['vdo_file']);
		$code=$tmp[3];
		
		$str='<object width="421" height="318"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="wmode" value="transparent" />';
		$str.='<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=' . $code . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />';
		$str.='<embed src="http://vimeo.com/moogaloop.swf?clip_id=' . $code . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" wmode="transparent" allowscriptaccess="always" width="421" height="318"></embed></object>';
		
		return $str;		
	
	}
	
	function get() { return $this->info; }
	

}