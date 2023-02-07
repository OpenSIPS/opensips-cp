<?php
require_once(__DIR__."/../widget/widget.php");

class gauge_widget extends widget
{
	public static $ignore = 1;
	public $box_id;
	public $widget_box;
	public $warning;
	public $critical;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 2, 3, $array['widget_title']);
        $this->color = 'rgb(219,255,244)';
	$this->warning = $array['widget_warning'];
	$this->critical = $array['widget_critical'];
	$this->box_id = $array['widget_box'];
	$this->widget_box = null;
	foreach ($_SESSION['boxes'] as $box) {
		if ($box['id'] == $this->box_id)
			$this->widget_box = $box;
	}
	if ($this->widget_box == null)
		$this->set_status(widget::STATUS_CRIT);
	else
		$this->set_status(widget::STATUS_OK);
	$this->refresh = (isset($array['widget_refresh'])?$array['widget_refresh'] * 1000:null);
    }

    function display_chart($title, $value, $valueMax = 100) {
        $_SESSION['gauge_id'] = $this->id;
        $_SESSION['gauge_value'] = $value;
	$_SESSION['gauge_max'] = $valueMax;
	$_SESSION['warning'] = $this->warning;
	$_SESSION['critical'] = $this->critical;
	if ($this->refresh != null)
		$_SESSION['refreshInterval'] = $this->refresh;
	else
		$_SESSION['refreshInterval'] = null;
	if ($value / $valueMax * 100 > $this->critical)
		$this->set_status(widget::STATUS_CRIT);
	else if ($value / $valueMax * 100 > $this->warning)
		$this->set_status(widget::STATUS_WARN);
        require(__DIR__."/../../../../../common/charting/percent_d3js.php");
    }
}
