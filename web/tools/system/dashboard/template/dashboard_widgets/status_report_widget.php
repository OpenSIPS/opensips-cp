<?php
require_once(__DIR__."/../widget/widget.php");

class status_report_widget extends widget
{
    public $mi_group;
	public $mi_id;
	public $status;
	public $box_id;
	public $widget_box;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
		$this->set_warning(1);
		$this->box_id = $array['widget_box'];
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$this->widget_box = $box;
		}
        $group_identifier = explode("/", $array["widget_identifier"]);
		$this->mi_group = $group_identifier[0];
		$this->mi_id = $group_identifier[1];
		$this->set_params();
    }

    function get_name() {
        return "Status report widget";
    }

    function display_test() {
		if (!$this->status['Readiness'])
			$this->set_warning(3);
		//echo ('<span style= "font-size:13px;">Status report for '.$this->mi_group.' '.$this->mi_id.':</span><br><br>');
		echo('
			<table style="table-layout: fixed;
				width: 180px; height:20px; margin: auto; margin-left: 10px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
		');
		echo('<tr><td class="rowEven">Details: </td><td>'.$this->status['Details'].' ('.$this->status['Status'].')</td></tr>');
		echo('<tr><td class="rowEven">Readiness: </td><td>'.(($this->status['Readiness'])?"<span style=\"font-weight: 900; color:green;\">True</span>":"<span style=\"font-weight: 900; color:red;\">False</span>").'</td></tr>');
		echo('</table>');
	}

	function set_params() {
		$group = $this->mi_group;
		$identifier = $this->mi_id;
		$params = [];
		$params["group"] = $group;
		if ($identifier)
			$params["identifier"] = $identifier;
		$stat_res = mi_command("sr_get_status", $params, $this->widget_box['mi_conn'], $errors);
		$this->status = $stat_res;
	}

    public static function box_selection($identifiers_list, $init) {
        $ilist = json_encode($identifiers_list);
        echo ('
            <script>
            var ilist = '.$ilist.';
			var init = '.$init.'
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_options();}, false);
            function update_options() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var identifier_select = document.getElementById("widget_identifier"); 
				identifier_select.options.length = 0;
                if (ilist[selected_box])  {
                    ilist[selected_box].forEach(element => {
                        var opt = document.createElement("option");
                        opt.value = element;
                        opt.textContent = element;
                        identifier_select.appendChild(opt);
                    });
                }
            } if (init == 1) update_options();
            </script>
        ');
    }

    static function get_identifiers_options() {
        foreach($_SESSION['boxes'] as $id => $box) {
            $stat_res = mi_command("sr_list_identifiers", array(), $box['mi_conn'], $errors);
            foreach($stat_res as $group) {
                foreach($group["Identifiers"] as $identifier)
                        $identifiers[$box['id']][] = $group["Group"]."/".$identifier;
            }
        }
        return $identifiers;
    }

    function echo_content() {
       $this->display_test();
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) {
		$boxes_info = self::get_boxes();
        if (is_null($params))
			$init = 1;
		else $init = 0;
        if (!$params['widget_name'])
            $params['widget_name'] = "Status report";
        $identifiers_list = self::get_identifiers_options();
		$options = (!$init)?$identifiers_list[$params['widget_box']]:$identifiers_list[0];
        form_generate_input_text("Name", "", "widget_name", "n", $params['widget_name'], 20,null);
        form_generate_select("Group/ID", "", "widget_identifier", null, $params['widget_identifier'], $options);
    	form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
        self::box_selection($identifiers_list, $init);
	}

	static function get_description() {
		return "
Display status reports";
	}

}

?>
