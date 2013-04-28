<?php 



class dataface {



	var $db;

	var $field=array();

	var $page_size;

	var $total_page;

	var $icon=array();

	var $table;

	var $prefix;

	var $form=array();

	var $dir="";

	var $sort=true;

	var $sort_field="";	

	var $display_order="asc";

	var $resize=0;



	/* Construction */

	function __construct($table) { 

		$this->db = DB::getInstance();

		$this->table=$table;

		$tmp=explode("_",$this->table);

		$this->prefix=$tmp[2];

		//$this->icon['hot']=0; $this->icon['status']=0;$this->icon['sort']=0; $this->icon['edit']=0;$this->icon['delete']=0;

	}



	function set_page_size($page) { $this->page_size=$page; }	

	function set_dir($dir) { $this->dir=$dir; }	

	function set_order($opt,$field="",$order="asc") { $this->sort=$opt; $this->sort_field=$field; $this->display_order=$order; }	

	function set_resize($size) { $this->resize=$size; }	

	

	function set_icon($icon) {

		foreach ($icon as $row=>$value) {

			if (!is_array($value)) {

				$this->icon[$value]=1;

			} else {

				$this->icon[$value[0]]=$value[1] . "|" . $value[2];

			}

		}

		

	

	}

	

	/* Field  0-Label, 1-Field, 2-width */

	function set_field($field) { 

		$this->field[]=$field; 

	}

	

	function set_form($type,$label,$field_name,$value,$class,$content="") { 

		$tmp=array();

		$tmp['label']=$label;

		$tmp['type']=$type;

		$tmp['field']=$field_name;

		$tmp['value']=$value;

		$tmp['class']=$class;

		$tmp['content']=$content;

		

		$this->form[]=$tmp; 

	}

	

	function process_keyword_search ($label,$keyword,$_GET) {



		echo "<form action=\"\" method=\"get\" class=\"searchbox\">";

		

		// Prepare for all variables

		foreach($_GET as $var => $value) { 

			if ($var=="action") { 

				echo "<input type=\"hidden\" name=\"" . $var . "\" value=\"" . $value . "-action\" />";

			} else { 

				echo "<input type=\"hidden\" name=\"" . $var . "\" value=\"" . $value . "\" />";

			} 

		} 	

		

		echo "<label>" . $label . "</label>";

		echo "<input type=\"text\" name=\"keyword\" value=\"$keyword\" class=\"textbox\">";

		echo "<button class=\"button\" type=\"submit\">Search</button>";

		echo "</form>";

	

	}

	

	

	function process_gallery_list($pid,$width) {

		$sql="select * from " . $this->table . " where " . $this->prefix . "_pid='" . $pid . "'";

		$rs = $this->db->Execute($sql);

		

		echo "<div class=\"uploadbox\">";

		echo "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" class=\"form\">";

		echo "<input type=\"hidden\" name=\"" . $this->prefix . "_pid\" value=\"$pid\">";

		echo "<input type=\"hidden\" name=\"url\" value=\"" . $_SERVER['QUERY_STRING'] . "\">";

		echo "<input type=\"hidden\" name=\"pg\" value=\"$pg\">";

		echo "<input type=\"hidden\" name=\"action\" value=\"imgupload-action\">";

		echo "<label>Please select file to upload</label>";

		echo "<input type=\"file\" name=\"file\" size=\"20\">";

		echo "<button type=\"submit\" class=\"button\">Upload</button>";

		echo "</form>";

		echo "</div>";

		

		echo "<ul class=\"imglist\">";

		while (!$rs->EOF) {

			echo "<li><a href=\"?" . $_SERVER['QUERY_STRING'] . "&imgid=" . $rs->Fields($this->prefix . "_id") . "&action=imgdel-action\" class=\"delete\">X</a>";

			echo "<img class=\"pic\" src=\"crop.php?f=" . $this->dir . $rs->Fields($this->prefix . "_pic") . "&w=$width&h=$width\">";

			echo "</li>";

		$rs->MoveNext(); }

		

		echo "</ul>";

	

	}



	

	

	function process_form($_GET) {

		



		echo "<form action=\"\" method=\"post\"  class=\"editor-form clearfix\" enctype=\"multipart/form-data\">";

		

		// Prepare for all variables

		foreach($_GET as $var => $value) { 

			if ($var=="action") { 

				echo "<input type=\"hidden\" name=\"" . $var . "\" value=\"" . $value . "-action\" />";

			} else { 

				echo "<input type=\"hidden\" name=\"" . $var . "\" value=\"" . $value . "\" />";

			} 

		} 

		

		foreach ($this->form as $row) {

		

			echo "<label>" . $row['label'] . "</label>";

			

			switch ($row['type']) {

				case "text":

					echo "<input type=\"text\" name=\"" . $row['field'] . "\" value=\"" . $row['value'] . "\" " . $row['class'] . ">";

					break;
					
				case "checkbox":
					
					echo '<span style="float:left"><input type="checkbox" name="' . $row['field'] . '" value="1"' . $row['class'] . '> '  . $row['value'] . '</span>';
					
					break;
					

				case "listbox-lookup":

					echo "<select name=\"" . $row['field'] . "\" " . $row['class'] . ">";

					$sql=$row['content']; $rs = $this->db->Execute($sql);

					while (!$rs->EOF) {

						echo "<option value=\"" . $rs->fields[0] . "\"";

						if ($rs->fields[0]==$row['value']) { echo "selected"; } 

						echo ">" . $rs->fields[1] . "</option>";

					$rs->MoveNext(); }

					echo "</select>";

					break;

				case "listbox":

					$tmp=$row['content'];

					echo "<select name=\"" . $row['field'] . "\" " . $row['class'] . ">";

					for ($i=0; $i<count($tmp); $i++) {

						echo "<option value=\"" . $tmp[$i] . "\"";

						if ($tmp[$i]==$row['value']) { echo "selected"; } 

						echo ">" . $tmp[$i] . "</option>";

					}

					echo "</select>";

					break;
                
                case "listbox_custom":

					$tmp=$row['content'];

					echo "<select name=\"" . $row['field'] . "\" " . $row['class'] . ">";

					foreach ($tmp as $k => $v) {

						echo "<option value=\"" . $k . "\"";

						if ($k==$row['value']) { echo "selected"; } 

						echo ">" . $v . "</option>";

					}

					echo "</select>";

					break;

				case "tag":

					echo "<input type=\"text\" name=\"" . $row['field'] . "\" value=\"" . $row['value'] . "\" " . $row['class'] . ">";

					$sql=$row['content']; $rs = $this->db->Execute($sql);



					echo "<select name=\"select_" . $row['field'] . "-select\" class=\"tag-select\" onchange=\"this.form." . $row['field'] . ".value=this.value\" >";

					echo "<option value=\"\">Please select...</option>";

					while (!$rs->EOF) {

						echo "<option value=\"" . $rs->fields[0] . "\">" . $rs->fields[0] . "</option>";

					$rs->MoveNext(); }

					echo "</select>";				

					break;					

				case "file":

					echo '<div id="upload-file-wrapper">';

					echo "<input type=\"file\" name=\"" . $row['field'] . "\" " . $row['class'] . ">";

					if ($row['value']!="") {

						echo '<div id="upload-file-links-wrapper">';

						echo "<a href=\"" . $this->dir . $row['value'] . "\" target=\"_blank\">view file</a> |  ";

						echo "<input type=\"checkbox\" name=\"" . $row['field'] . "-delete\" value=\"1\"><span>Remove File</span>"; 

						echo '</div>';

					}

					echo'</div><div class="clearIt"></div>';

					break;

				case "date":

					echo "<select name=\"" . $row['field'] . "_d\" " . $row['class'] . ">";

					for ($i=1; $i<=31; $i++) {

						echo "<option value=\"$i\"";

						if ($i==date("d",$row['value'])) { echo " selected"; }

						echo ">$i</option>";

					}

					echo "</select>";



					echo "<select name=\"" . $row['field'] . "_m\" " . $row['class'] . ">";

					for ($i=1; $i<=12; $i++) {

						echo "<option value=\"$i\"";

						if ($i==date("m",$row['value'])) { echo " selected"; }

						echo ">$i</option>";

					}

					echo "</select>";



					echo "<select name=\"" . $row['field'] . "_y\" " . $row['class'] . ">";

					for ($i=2009; $i<=2030; $i++) {

						echo "<option value=\"$i\"";

						if ($i==date("Y",$row['value'])) { echo " selected"; }

						echo ">$i</option>";

					}

					echo "</select>";

					

					break;					

				case "textarea":

					echo "<div class=\"textarea-container\"><textarea name=\"" . $row['field'] . "\" " . $row['class'] . ">" . $row['value'] . "</textarea></div>";

					break;

					

				case "htmleditor":

					echo "<div class=\"textarea-container\"><textarea name=\"" . $row['field'] . "\" " . $row['class'] . ">" . $row['value'] . "</textarea></div>";

					echo "<script type=\"text/javascript\"> CKEDITOR.replace('" . $row['field'] . "' );";

					//echo "CKFinder.SetupCKEditor( editor, '../lib/ckfinder/' ); " ;

					echo "</script>";

					break;	

				case "image-list":

					echo "<iframe " . $row['class'] . " name=\"" . $row['field'] . "\" src=\"image.php?dir=" . $row['value'] . "\"></iframe>";

					break;

					

								

					

			} // end switch

			

		} // end foreach ($this->form as $row) {

		

		echo "<div class=\"submit-button\"><button type=\"submit\" class=\"button\">Submit</button></div>";

		

		echo "</form>";

		

	}	

	

	



	function process_list($sql,$sort,$way, $page, $table_attach, $url) {

		

		if ($this->sort==true) {

			$row=$this->field[$sort]; // Get Sort Option

			if ($way=="up") { $order=" order by " . $row[1]; } else { $order=" order by " . $row[1] . " desc"; } 

			$sql.=$order; 

		} else {

			$sql.=" order by " . $this->sort_field . " " . $this->display_order;

		}

		

		$rs = $this->db->Execute($sql);

		

		

		// Page Split

		$this->total_page = ceil($rs->RecordCount() / $this->page_size);

		$start = ($page-1) * $this->page_size;

		

		$rs=$this->db->SelectLimit($sql,$this->page_size,$start);	

		

		// Build header */

		echo "<table $table_attach>";

		echo "<thead><tr>"; 

		$col=0;

		foreach ($this->field as $row) {

			echo "<td style=\"width:" . $row[2] . "px\" class=\"col" . $col . "\">";

			

			if ($this->sort==true) {  // use column sort option

				if ($way=="up") { $go="down"; } else { $go="up"; }

				

				echo "<a href=\"" . $url . "&page=$page&sort=$col&way=$go\">" . $row[0] . "</a>";

				if ($sort==$col) { echo "<img src=\"images/$go.gif\">";  } 

			} else {

				echo $row[0];

			}

			

			echo "</td>";

		$col++; }		

		echo "<td class=\"tool\"></td>";

		echo "</tr></thead><tbody>";

		

		// build list

		$i=0; while (!$rs->EOF) {

			echo "<tr class=\"row" . $i++%2 . "\">";

			foreach ($this->field as $row) {

				switch ($row[3]) { 

					case "date": echo "<td>" . date("d/m/Y",$rs->Fields($row[1])) . "</td>"; break;

					case "datetime": echo "<td>" . date("d/m/Y H:i",$rs->Fields($row[1])) . "</td>"; break;

					default: echo "<td>" . $rs->Fields($row[1]) . "</td>";	break;

				}

			}

			

			echo "<td class=\"tool\">";



			// Build Tool button		

			foreach ($this->icon as $ico => $key) {

				

				

					

				switch ($ico) {

					case "order":

						if ($this->display_order=="asc") {

							echo "<a class=\"btn\" href=\"" . $url . "&action=" . $ico . "-action&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "&order=" . $rs->Fields($this->prefix . "_order") . "&move=up\"><img src=\"skin/images/icon/up.png\"></a>";

							echo "<a class=\"btn\" href=\"" . $url . "&action=" . $ico . "-action&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "&order=" . $rs->Fields($this->prefix . "_order") . "&move=down\"><img src=\"skin/images/icon/down.png\"></a>";

						} else {

							echo "<a class=\"btn\" href=\"" . $url . "&action=" . $ico . "-action&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "&order=" . $rs->Fields($this->prefix . "_order") . "&move=down\"><img src=\"skin/images/icon/up.png\"></a>";

							echo "<a class=\"btn\" href=\"" . $url . "&action=" . $ico . "-action&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "&order=" . $rs->Fields($this->prefix . "_order") . "&move=up\"><img src=\"skin/images/icon/down.png\"></a>";

						}

						break;

					case "status":

						echo "<a class=\"btn\" href=\"" . $url . "&action=" . $ico . "-action&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "\"><img src=\"skin/images/icon/eye" . $rs->Fields($this->prefix . "_status") . ".png\"></a>";

						break;

					case "hot":

						echo "<a class=\"btn\" href=\"" . $url . "&action=" . $ico . "-action&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "\"><img src=\"images/btn-hot" . $rs->Fields($this->prefix . "_hot") . ".gif\"></a>";

						break;

					case "delete":

						echo "<a class=\"btn\" href=\"javascript:DoDel('" . $url . "&action=" . $ico . "-action&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "')\"><img src=\"skin/images/icon/" . $ico . ".png\"></a>";

						break;	

					case "external":

						$tmp=explode("|",$key);

						echo "<a class=\"btn\" href=\"" . $tmp[1] . "&page=$page&sort=$sort&way=$way&parent_id=" . $rs->Fields($this->prefix . "_id") . "\"><img src=\"images/btn-" . $tmp[0] . ".gif\"></a>";

						break;														

					default:

						echo "<a class=\"btn\" href=\"" . $url . "&action=" . $ico . "&page=$page&sort=$sort&way=$way&id=" . $rs->Fields($this->prefix . "_id") . "\"><img src=\"skin/images/icon/" . $ico . ".png\"></a>";

						break;

						

				} // end switch

			

			

			

			} // end foreach ($this->icon as $ico => $key) {

			

			

			echo "</tr>";

		

		$rs->MoveNext(); }

		

		echo "</tbody></table>";

	

	}

	

	/***************************************************************************************

	/ Name: save

	/ Used: Save data to database with file upload support

	/ Para In: $go - add or save

	/          $vars - $_POST array

	/          $more - additional array in case required, it will be merged   

	/ Para Out: Insert ID for after add record

	/ Note: File upload handling also provided, include re-name system for duplicate file

	/***************************************************************************************/	

	function save($go,$vars, $more) {

		

		$vars=array_merge($vars,$more);

		

		// Upload Files

		foreach ($_FILES as $row => $key) {

			

			if ($key['tmp_name']!='none') {

				$filename=$key['name'];

				if (file_exists($this->dir . $key['name'])) { $filename=rand(11111,99999) . $filename; } // Avoid duplicate file name

				if  (move_uploaded_file($key['tmp_name'], $this->dir . $filename)) { 

					$vars[$row]=$filename;

					if ($this->resize!=0) {

						$thumb=new Thumbnail($this->dir . $filename);

						$thumb->resize($this->resize);

						$thumb->save($this->dir . $filename);	

					}				

					

				} else { unset($vars[$row]); }				

			}

			

			if (!empty($vars[$row . "-delete"])) { @unlink($this->dir . $filename); $vars[$row]=""; }

			

		}

		

		// Check File to be deleted

		

		//$this->db->debug=true;

		switch ($go) {

			case "add":

				$tmp=$this->db->MetaColumns($this->table);

				if (array_key_exists(strtoupper($this->prefix . "_order"),$tmp)) { $vars[$this->prefix . "_order"]=$this->get_order(); }			

				

				return $this->db->insert($this->table,$vars); 

				

				break;

			case "save":

				$this->db->update($this->table,$vars,$this->prefix . "_id=?",$vars['id']); 

				break;			

		}	

		

		

	} 

	

	/***************************************************************************************

	/ Name: get_record 

	/ Used: Get db row by given id

	/ Para In: $id (Parmary Key)

	/ Para Out: Recordset / Array Type

	/***************************************************************************************/

	function get_record($id) {

		return $this->db->get($this->table, $this->prefix . "_id=?",$id);		

	}

	

	/***************************************************************************************

	/ Name: delete 

	/ Used: Delete row by given id

	/ Para In: $id (Parmary Key)

	/ Para Out: None

	/***************************************************************************************/

	function delete($id) {

		$sql="delete from " . $this->table . " where " . $this->prefix . "_id='$id'";

		$this->db->Execute($sql);		

	}	



	/***************************************************************************************

	/ Name: status 

	/ Used: Toggle status by given id (the status field must be $prefix_status, TinyInt)

	/ Para In: $id (Parmary Key)

	/ Para Out: None

	/***************************************************************************************/

	function status($id) {

		$text=$this->get_record($id);

		if ($text[$this->prefix . "_status"]==1) { $status=0; } else { $status=1; }

		$sql="update " . $this->table . " set " . $this->prefix . "_status='$status' where " . $this->prefix . "_id='$id'";

		$this->db->Execute($sql);	

		

		return 	$status;

	}	

	

	/***************************************************************************************

	/ Name: hot 

	/ Used: Toggle hot by given id (the status field must be $prefix_hot, TinyInt)

	/ Para In: $id (Parmary Key)

	/ Para Out: None

	/***************************************************************************************/

	function hot($id) {

		$text=$this->get_record($id);

		if ($text[$this->prefix . "_hot"]==1) { $hot=0; } else { $hot=1; }

		$sql="update " . $this->table . " set " . $this->prefix . "_hot='$hot' where " . $this->prefix . "_id='$id'";

		$this->db->Execute($sql);		

	}		

	

	/***************************************************************************************

	/ Name: get_order 

	/ Used: Generate unique display order number (the order field must be $prefix_order, Int

	        The method will be called in save method if the order field exist in given table

	/ Para In: None

	/ Para Out: Unique order number

	/***************************************************************************************/	

	function get_order() {

	

		$sql="Select count(" . $this->prefix . "_order) as num from " . $this->table;

		$rs_max=$this->db->Execute($sql);



		if (is_null($rs_max->Fields("num"))) { $order=1; } else { $order=$rs_max->Fields("num")+1; }



		return $order;



	}

	

	/***************************************************************************************

	/ Name: page_split 

	/ Used: Generate page split. will quck first/last page access. if the total page numbder

	        greater then 10 then it will automatically display middle 10 pages

	/ Para In: Param - The url attached to each link (no page parametre)

	           current - current page number		

	/ Para Out: Page split html in <ul><li> format, class name "pages"

	/***************************************************************************************/		

	function page_split($param,$current) { 

	

		$con="&page=";

		echo "<ul class=\"paging\">";

		if (($current!=1) && ($this->total_page!=0)) {  

			echo '<li><a href="' . $param . $con . '1">&lt;</a></li>'; 

		}

	

		if ($this->total_page>10 && $current>5) {

			$start=$current-4;

			$end=$current+5;

			if ($end > $this->total_page) { $end=$this->total_page; }

			if ($end-$start<10) { $start=$end-10; }

			for ($i=$start; $i<=$end; $i++) {

				echo '<li><a href="' . $param . $con . $i . '"';

				if ($i==$current) { echo ' class="current"'; }

				echo '>' . $i . '</a></li>';

			}

	

		} else {

			if ($this->total_page<10) { $limit=$this->total_page; } else { $limit=10; }

			for ($i=1; $i<=$limit; $i++) {

				echo '<li><a href="' . $param . $con . $i . '"';

				if ($i==$current) { echo ' class="selected"'; }

				echo '>' . $i . '</a></li>';

			}

		}

		

		if (($current!=$this->total_page) && ($this->total_page!=0)) {

			echo '<li><a href="' . $param . $con . $this->total_page . '">&gt;</a></li>'; 

		}	

		

		echo "</ul>";

	

	}

	

	/***************************************************************************************

	/ Name: save_gallery 

	/ Used: Upload single image file for gallery

	/ Para In: vars - file upload POST data

	           resize - width/height if require. 0-no resize	

	/ Para Out: None

	/***************************************************************************************/			

	

	function save_gallery($vars,$resize=0) {

		foreach ($_FILES as $row => $key) {

			if ($key['tmp_name']!='none') {

				$filename=$key['name'];

				if (file_exists($this->dir . $key['name'])) { $filename=rand(11111,99999) . $filename; } // Avoid duplicate file name

				if  (move_uploaded_file($key['tmp_name'], $this->dir . $filename)) { 

					$vars[$this->prefix . "_pic"]=$filename;

				} else { unset($vars[$this->prefix . "_pic"]); }				

			}

		}

		

		$this->db->insert($this->table,$vars); 

	

	}

	

	/***************************************************************************************

	/ Name: del_gallery 

	/ Used: Upload single image file for gallery

	/ Para In: vars - file upload POST data

	           resize - width/height if require. 0-no resize	

	/ Para Out: None

	/***************************************************************************************/			

	

	function del_gallery($vars) {

		

		$tmp=$this->db->get($this->table,$this->prefix . '_id=?',$vars['imgid']);	

		@unlink($this->dir . $tmp[$this->prefix . "_pic"]);

		

		$sql="Delete from " . $this->table . " where " . $this->prefix . "_id='" . $vars['imgid'] . "'";

		

		$this->db->Execute($sql);	

	

	}	

	

	/***************************************************************************************

	/ Name: update_order 

	/ Used: Sort data by set swap order number

	/ Para In: source_id - Record PK

	           source_order -  Record current sort number

			   way -"up" or "down", set where to swap order number

			   where - additional sql

	/ Para Out: None

	/***************************************************************************************/		

	function update_order($source_id,$source_order,$way,$where="") {

		

		

		if ($where=="") { $connect="where"; } else { $connect="and"; }

	

		if ($way=="up") {

			$sql="Select * from " . $this->table . " $where $connect " . $this->prefix . "_order<$source_order order by " . $this->prefix . "_order desc limit 0,1";

		} else {	

			$sql="Select * from " . $this->table . " $where $connect " . $this->prefix . "_order>$source_order order by " . $this->prefix . "_order limit 0,1";

		}

		

		$rs_target=$this->db->Execute($sql);

		

		if (!$rs_target->EOF) {

			$target_id=$rs_target->Fields($this->prefix . "_id");

			$target_order=$rs_target->Fields($this->prefix . "_order");

			

			if ($source_id!=$target_id) {

				$sql="update " . $this->table . " set " . $this->prefix . "_order=$target_order where " . $this->prefix . "_id=" . $source_id;

				$this->db->Execute($sql);

				

				$sql="update " . $this->table . " set " . $this->prefix . "_order=$source_order where " . $this->prefix . "_id=" . $target_id;

				$this->db->Execute($sql);

			}			

			

		}

		

	}	

	

	/***************************************************************************************

	/ Name: get_hidden_field 

	/ Used: Convert URL parametres to hidden fields

	/ Para In: url - "?pg=report&sort=1&page=2"

	/ Para Out: hidden fields

	/***************************************************************************************/		

	function get_hidden_field($url) {

		$tmp=explode("&",$url);

		$str="";

		foreach ($tmp as $row) {

			$field=explode("=",$row);

			$str.="<input type=\"hidden\" name=\"" . $field[0] . "\" value=\"" . $field[1] . "\">";

		}	

		

		return $str;

	}

	

	/***************************************************************************************

	/ Name: get_row_data

	/ Used: General use for sql statement, get 1 row back

	/ Para In: url - "?pg=report&sort=1&page=2"

	/ Para Out: result in table fields

	/***************************************************************************************/		

	function get_row_data($table,$where) {

		return $this->db->get($table,$where,"");	

	}	

	

	/***************************************************************************************

	/ Name: get_row_result

	/ Used: General use for sql statement, get multiple row back

	/ Para In: url - "?pg=report&sort=1&page=2"

	/ Para Out: result in table fields

	/***************************************************************************************/		

	function get_row_result($sql) {

		return $this->db->getAll($sql);	

	}	

	

	

	/***************************************************************************************

	/ Name: Login

	/ Used: User validation, work with specified user table

	/ Para In: login, password

	/ Para Out: user id is validate or false

	/***************************************************************************************/		

	function login($login,$password) {

		$db = DB::getInstance();				

		

		$sql="Select * from tbl_user_u where u_login='$login' and u_password='$password'";

		$rs=$db->Execute($sql);	

		

		if ($rs->EOF) { return false; } else { return $rs->Fields("u_id"); }



	}	



}