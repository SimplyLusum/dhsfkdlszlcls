<? 

class Gallery {

	var $db;
	var $info;
	var $dir="upload/gallery_images/";
	var $totalpage=0;
	var $page_size=15;
	var $id=0;
	

	/* Construction */
	function __construct($id=0) { 
		$this->db = DB::getInstance();

		if ($id!=0) {
			$this->id=$id;
			$this->info = $this->db->get('tbl_gallery_gal','gal_id=?',$this->id);		
		}
	}
	
	function get_list($page,$tag) {
		
		if ($tag=="") {
			$tags=$this->get_tags();
			$tag=$tags[0]['tag'];
		}
		
		$sql="Select * from tbl_gallery_gal where gal_tag='$tag' and gal_status=1 order by gal_order";
		$rs=$this->db->Execute($sql);

		$this->totalpage = ceil($rs->RecordCount() / $this->page_size);
		$start = ($page-1) * $this->page_size;

		return $this->db->SelectLimit($sql,$this->page_size,$start);					
	}
	
	function get_tags() {
		return $this->db->getAll("Select distinct(gal_tag) as tag from tbl_gallery_gal order by gal_tag");
	}
	
	function get() { return $this->info; }
	function get_totalpage() { return $this->totalpage; }
	

}