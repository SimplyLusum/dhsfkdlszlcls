<? 

class Testimonial {

	var $db;
	var $info;
	var $dir="upload/testimonial/";
	

	/* Construction */
	function __construct($id=0) { 
		$this->db = DB::getInstance();

		if ($id!=0) {
			$this->id=$id;
			$this->info = $this->db->get('tbl_testimonial_t','t_id=?',$this->id);		
		}
	}
	
	function get_list() {
		$sql="Select * from tbl_testimonial_t where t_status=1 order by t_order";
		return $this->db->getAll($sql);	
	}
	
	function get_player() {
		$tmp=explode("/",$this->info['t_video']);
		$code=$tmp[3];
		
		$str='<object width="421" height="318"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" />';
		$str.='<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=' . $code . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />';
		$str.='<embed src="http://vimeo.com/moogaloop.swf?clip_id=' . $code . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="421" height="318"></embed></object>';
		
		return $str;		
	
	}	
	
	function get_dir() { return $this->dir; }
	function get() { return $this->info; }
	
	
	
}