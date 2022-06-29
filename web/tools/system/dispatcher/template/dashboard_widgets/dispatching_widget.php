<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class dispatching_widget extends widget
{
    public $active = 0;
	public $inactive = 0;
	public $probing = 0;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
        $this->set_dispatching();
        $this->color = "rgb(207, 207, 207)";
    }


    function get_name() {
        return "Dispatching destinations";
    }
    function display_test() {
		echo ('
			<table class="ttable" style="table-layout: fixed;
			width: 110px; height:15px; margin: auto;" cellspacing="0" cellpadding="0" border="0">
			');
		echo ('
			<tr><td class="rowEven">Available: <span style="color:green;">'.$this->active.'</span></td></tr>
			<tr><td class="rowEven">Inactive: <span style="color:red;">'.$this->inactive.'</span></td></tr>
			<tr><td class="rowEven">Probing: <span style="color:yellow;">'.$this->probing.'</span></td>
			</tr>  ');
		echo('</table>');
	
	}

	function set_dispatching() {
		$stat_res = mi_command("ds_list", array(), $_SESSION['boxes'][0]['mi_conn'], $errors);
		
		foreach($stat_res["PARTITIONS"] as $key => $partition) {
			foreach($partition["SETS"] as $key => $set) {
				foreach($set['Destinations'] as $destination) {
					switch ($destination['state']) {
						case "Active":
							$this->active ++;
							break;
						case "Inactive": 
							$this->inactive ++;
							break;
						case "probing":
							$this->probing ++;
							break;
						default:
							error_log("Bug");
					}

				}
			} 
		}
	}


    function echo_content() {
        $this->display_test();
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) {  
        form_generate_input_text("Name", "", "widget_name", null, $params['widget_name'], 20,null);
    }

}

?>