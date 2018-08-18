<?php
 require_once("base_connector.php"); class TreeDataItem extends DataItem{ private $im0; private $im1; private $im2; private $check; private $kids=-1; private $attrs; function __construct($data,$config,$index){ parent::__construct($data,$config,$index); $this->im0=false; $this->im1=false; $this->im2=false; $this->check=false; $this->attrs = array(); } function get_parent_id(){ return $this->data[$this->config->relation_id["name"]]; } function get_check_state(){ return $this->check; } function set_check_state($value){ $this->check=$value; } function has_kids(){ return $this->kids; } function set_kids($value){ $this->kids=$value; } function set_attribute($name, $value){ switch($name){ case "id": $this->set_id($value); break; case "text": $this->data[$this->config->text[0]["name"]]=$value; break; case "checked": $this->set_check_state($value); break; case "im0": $this->im0=$value; break; case "im1": $this->im1=$value; break; case "im2": $this->im2=$value; break; case "child": $this->set_kids($value); break; default: $this->attrs[$name]=$value; } } function set_image($img_folder_closed,$img_folder_open=false,$img_leaf=false){ $this->im0=$img_folder_closed; $this->im1=$img_folder_open?$img_folder_open:$img_folder_closed; $this->im2=$img_leaf?$img_leaf:$img_folder_closed; } function to_xml_start(){ if ($this->skip) return ""; $str1="<item id='".$this->get_id()."' text='".$this->xmlentities($this->data[$this->config->text[0]["name"]])."' "; if ($this->has_kids()==true) $str1.="child='".$this->has_kids()."' "; if ($this->im0) $str1.="im0='".$this->im0."' "; if ($this->im1) $str1.="im1='".$this->im1."' "; if ($this->im2) $str1.="im2='".$this->im2."' "; if ($this->check) $str1.="checked='".$this->check."' "; foreach ($this->attrs as $key => $value) $str1.=$key."='".$this->xmlentities($value)."' "; $str1.=">"; if ($this->userdata !== false) foreach ($this->userdata as $key => $value) $str1.="<userdata name='".$key."'><![CDATA[".$value."]]></userdata>"; return $str1; } function to_xml_end(){ if ($this->skip) return ""; return "</item>"; } } require_once("filesystem_item.php"); class TreeConnector extends Connector{ protected $parent_name = 'id'; public function __construct($res,$type=false,$item_type=false,$data_type=false, $render_type=false){ if (!$item_type) $item_type="TreeDataItem"; if (!$data_type) $data_type="TreeDataProcessor"; if (!$render_type) $render_type="TreeRenderStrategy"; parent::__construct($res,$type,$item_type,$data_type,$render_type); } public function parse_request(){ parent::parse_request(); if (isset($_GET[$this->parent_name])) $this->request->set_relation($_GET[$this->parent_name]); else $this->request->set_relation("0"); $this->request->set_limit(0,0); } public function xml_start(){ $attributes = ""; foreach($this->attributes as $k=>$v) $attributes .= " ".$k."='".$v."'"; return "<tree id='".$this->request->get_relation()."'".$attributes.">"; } public function xml_end(){ return "</tree>"; } } class TreeDataProcessor extends DataProcessor{ function __construct($connector,$config,$request){ parent::__construct($connector,$config,$request); $request->set_relation(false); } function name_data($data){ if ($data=="tr_pid") return $this->config->relation_id["db_name"]; if ($data=="tr_text") return $this->config->text[0]["db_name"]; return $data; } } ?>