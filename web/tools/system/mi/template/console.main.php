<?php
/*
 * Copyright (C) 2026 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
?>
<div class="mi-runner">
 <div class="mi-head">
  <label class="mi-label" for="line">Run a MI command</label>
  <span class="mi-help" tabindex="0" aria-label="Command syntax">?
   <span class="mi-help-text">
    Parameters are positional (<code>cmd a b</code>) or named
    (<code>cmd x=1 y=2</code>), never both.<br>
    Brackets make one argument a list:<br>
    <code>statistics:get [all]</code><br>
    <code>tracer:start filter=[ip,1.2.3.4]</code><br>
    <b>Tab</b> completes, and repeats to cycle the candidates.
    <b>↑</b> walks the history.
   </span>
  </span>
 </div>
 <div class="mi-inputwrap">
  <input id="line" autocomplete="off" spellcheck="false"
   placeholder="<?=htmlspecialchars($_SESSION['read_only'] ? "Read-only access" : "ex: core:uptime - use <tab> completion")?>"
   <?php if ($_SESSION['read_only']) echo 'disabled'; ?>>
  <div id="suggest" class="mi-suggest" style="display:none"></div>
 </div>
 <div id="cmderr" class="mi-cmderr" style="display:none"></div>
 <div class="mi-controls">
  <select id="boxsel" class="mi-select">
<?php foreach (mi_boxes() as $i => $b) { ?>
   <option value="<?=$i?>"><?=htmlspecialchars($b['name'])?></option>
<?php } ?>
  </select>
  <span id="miurl" class="mi-url" title="MI address of the selected box"></span>

  <label class="mi-force" for="force"
   title="Send the line even if the checks refuse it. Clears itself once the command has run.">
   <input id="force" type="checkbox"
    <?php if ($_SESSION['read_only']) echo 'disabled'; ?>>Force</label>

  <button id="run" type="button" class="mi-btn"
   <?php if ($_SESSION['read_only']) echo 'disabled'; ?>>Run</button>
 </div>
</div>

<div class="mi-results-head">
 <span class="mi-results-title">History</span>
 <button id="clear" type="button" class="mi-btn-ghost">Clear</button>
</div>
<div id="log"></div>

<script type="application/json" id="mi_config"><?php
	echo json_encode(array(
		"boxes"       => array_column(mi_boxes(), 'name'),
		"urls"        => array_column(mi_boxes(), 'url'),
		"csrf"        => mi_csrf_token(),
		"store"       => mi_store_key(),
		"readOnly"    => (bool)$_SESSION['read_only'],
		"historySize" => max(1, (int)get_settings_value("history_size"))
	), JSON_HEX_TAG);
?></script>
<script>
<?php require("lib/autocomplete.js"); ?>
<?php require("lib/table.js"); ?>
<?php require("lib/console.js"); ?>
</script>
