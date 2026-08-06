(function () {
	'use strict';

	var cfg = JSON.parse(document.getElementById('mi_config').textContent);
	var log = document.getElementById('log');
	var input = document.getElementById('line');
	var suggest = document.getElementById('suggest');
	var runBtn = document.getElementById('run');
	var clearBtn = document.getElementById('clear');
	var cmdErr = document.getElementById('cmderr');
	var boxsel = document.getElementById('boxsel');
	var miurl = document.getElementById('miurl');
	var force = document.getElementById('force');

	var box = 0;
	var cmdCache = {};
	var flavorCache = {};
	var inFlight = {};
	var matches = [];
	var allMatches = [];
	var hints = [];
	var active = -1;
	var ready = true;
	var history = [];
	var histPos = -1;
	var draft = '';
	var lookupTimer = null;
	var cycle = null;
	var recalling = false;
	// the list is standing open over an empty field rather than filtering
	// something typed, which is the one state where the arrows are still the
	// history's -- see the keydown handler
	var browsing = false;

	function el(tag, cls, text) {
		var node = document.createElement(tag);
		if (cls) node.className = cls;
		if (text !== undefined) node.textContent = text;
		return node;
	}

	// navigator.clipboard only exists on https or localhost, and the panel is
	// usually served over plain http on a LAN address -- fall back to the old
	// select-and-copy there rather than leaving a dead button
	function copyText(text) {
		if (window.isSecureContext && navigator.clipboard)
			return navigator.clipboard.writeText(text);

		var ta = el('textarea');
		ta.value = text;
		ta.readOnly = true;
		ta.style.position = 'fixed';
		ta.style.opacity = '0';
		document.body.appendChild(ta);
		ta.select();
		var ok = false;
		try { ok = document.execCommand('copy'); } catch (e) {}
		ta.remove();
		return ok ? Promise.resolve() : Promise.reject();
	}

	function now() {
		var d = new Date();
		return ('0' + d.getHours()).slice(-2) + ':' +
			('0' + d.getMinutes()).slice(-2) + ':' +
			('0' + d.getSeconds()).slice(-2);
	}

	/* ---- per-login storage ----
	 * Both stores below outlive the page, so both are named after the login
	 * rather than the origin: cfg.store is minted from the PHP session, which a
	 * logout empties, so a second user on the same browser reads under a key of
	 * their own. Entries under any older key belonged to a session that has
	 * ended -- sweep them at startup instead of leaving MI replies on disk.
	 *
	 * The sweep is what actually ends them, and it only runs when this tool is
	 * opened; a logout alone leaves the old entries in place, unread, until
	 * someone loads the console again. Clearing them at logout would take JS on
	 * a page that only redirects.
	 */

	var HKEY = 'mi.history.' + cfg.store;
	var RKEY = 'mi.results.' + cfg.store;

	// backwards: removeItem renumbers everything above the key it drops
	function sweep(store, prefix, keep) {
		for (var i = store.length - 1; i >= 0; i--) {
			var k = store.key(i);
			if (k !== keep && k.lastIndexOf(prefix, 0) === 0)
				store.removeItem(k);
		}
	}

	/* ---- recall history ----
	 * only the command lines are kept, like a shell's .bash_history; results
	 * stay in the page because a single MI reply can be megabytes.
	 */

	function loadHistory() {
		try {
			var raw = JSON.parse(localStorage.getItem(HKEY));
			if (Array.isArray(raw)) history = raw;
		} catch (e) {}
	}

	function remember(line) {
		if (history[history.length - 1] !== line) history.push(line);
		history = history.slice(-cfg.historySize);
		histPos = -1;
		// the line that was being written has just been sent, so there is no
		// draft left to come back to
		draft = '';
		try {
			localStorage.setItem(HKEY, JSON.stringify(history));
		} catch (e) {}
	}

	/*
	 * Walking the history is not the same as typing a command, so the matching-
	 * command list stays shut until the next keystroke. The flag has to outlive
	 * this call: the parameter lookup lands a moment later and re-renders, and
	 * would otherwise pop the list open over a recalled line -- taking the arrow
	 * keys with it, since they drive the list whenever it is open.
	 */
	function recall(step) {
		if (!history.length) return;
		if (histPos === -1) histPos = history.length;
		histPos = Math.min(history.length, Math.max(0, histPos + step));
		// past the newest entry sits the line that was being written when the
		// walk started -- walking back down returns to it, not to an empty field
		input.value = histPos === history.length ? draft : history[histPos];
		recalling = true;
		updateSuggest();
	}

	// the draft is whatever is in the field while the walk is at its own end, so
	// it follows the typing until an arrow steps off it and freezes it there
	function keepDraft() {
		if (histPos === -1 || histPos === history.length) draft = input.value;
	}

	/* ---- stored results ----
	 * Results live in sessionStorage, so they survive a reload but go when the
	 * tab does. The quota varies by browser -- 25MB in Chromium, nearer 5 in
	 * others -- so rather than assume a budget, an over-quota write sheds the
	 * oldest entries and retries until it fits.
	 */

	var results = [];
	var MAX_RESULTS = 50;
	var MAX_ENTRY = 256 * 1024;

	function saveResults() {
		while (results.length) {
			try {
				sessionStorage.setItem(RKEY, JSON.stringify(results));
				return;
			} catch (e) {
				results.shift();
			}
		}
		try {
			sessionStorage.removeItem(RKEY);
		} catch (e) {}
	}

	function keepResult(line, time, where, payload) {
		// one huge reply would otherwise evict every other entry trying to fit
		var big = JSON.stringify(payload).length > MAX_ENTRY;
		results.push({
			line: line, time: time, box: where,
			payload: big ? { dropped: true } : payload
		});
		if (results.length > MAX_RESULTS) results = results.slice(-MAX_RESULTS);
		saveResults();
	}

	function loadResults() {
		try {
			var raw = JSON.parse(sessionStorage.getItem(RKEY));
			if (Array.isArray(raw)) results = raw;
		} catch (e) {}

		// oldest first: each card is inserted at the top, so newest ends up there
		results.forEach(function (r) {
			// entries stored before the target was recorded by name hold an index
			var where = typeof r.box === 'number' ? boxInfo(r.box) : r.box;
			render(addCard(r.line, r.time, where), r.payload);
		});
		return results.length;
	}

	/* ---- result cards ---- */

	function showEmpty() {
		log.textContent = '';
		log.appendChild(el('div', 'mi-empty', 'No commands run yet.'));
	}

	function clearLog() {
		results = [];
		saveResults();
		showEmpty();
	}

	// which box a command went to, resolved at the time it runs -- an index
	// would be reinterpreted if an administrator reordered the boxes later
	function boxInfo(i) {
		return { name: cfg.boxes[i], url: cfg.urls[i] };
	}

	// time and where are passed when replaying a stored card, so it keeps the
	// stamp and target it was run with, not the state of the page now
	function addCard(line, time, where) {
		var placeholder = log.querySelector('.mi-empty');
		if (placeholder) placeholder.remove();

		var card = el('div', 'mi-card');

		// the bubble replaces the native title: it says the same thing, in the
		// same voice as the rest of the page, and waits half a second so it is
		// a nudge for something clickable rather than a flash on every pass
		var cmd = el('div', 'mi-card-cmd', line);
		cmd.appendChild(el('span', 'mi-help-text mi-card-hint', 'Run this again'));
		cmd.addEventListener('click', function () {
			input.value = line;
			input.focus();
			updateSuggest();
		});
		card.appendChild(cmd);

		var pill = el('span', 'mi-pill mi-pill-run', 'running');
		var meta = el('div', 'mi-card-meta');
		meta.appendChild(pill);
		meta.appendChild(el('span', null, time === undefined ? now() : time));
		// the box a command ran on, as a hover mark like the syntax "?" -- the
		// name is what matters, the address is one hover (or Tab) away
		var on = where || boxInfo(box);
		var target = el('span', 'mi-card-box');
		target.tabIndex = 0;
		target.setAttribute('aria-label', 'MI address of ' + on.name);
		target.appendChild(el('span', 'mi-card-boxname', on.name));
		if (on.url) target.appendChild(el('span', 'mi-help-text mi-card-url', on.url));
		meta.appendChild(target);

		meta.appendChild(el('span', 'mi-spacer'));

		var copy = el('button', 'mi-copy', 'Copy');
		copy.type = 'button';
		meta.appendChild(copy);
		card.appendChild(meta);

		log.insertBefore(card, log.firstChild);

		return { card: card, meta: meta, pill: pill, copy: copy };
	}

	/* ---- table view ---- */

	// the drawn view is what a card opens with, unless the switch was last left
	// on JSON -- and a box too old to be asked is answered the same way
	function preferred(key) {
		try {
			return localStorage.getItem(key) !== 'json';
		} catch (e) {
			return true;
		}
	}

	function cell(v, cls) {
		var td = el('td', cls);
		td.textContent = v === null || v === undefined ? '' : String(v);
		return td;
	}

	function tableView(t) {
		var wrap = el('div', 'mi-table-wrap');

		var caption = t.title ? t.title + ' · ' : '';
		wrap.appendChild(el('div', 'mi-table-cap',
			caption + t.rows.length + (t.rows.length === 1 ? ' row' : ' rows')));

		/*
		 * Columns are sized to their content and a blank one on the end takes
		 * the slack, so they stay packed together instead of being stretched
		 * apart to fill the card. Anything too wide scrolls sideways.
		 */
		var table = el('table', 'mi-table');
		var cls = t.numeric.map(function (n) { return n ? 'mi-num' : null; });

		var head = el('tr');
		t.columns.forEach(function (c, i) { head.appendChild(el('th', cls[i], c)); });
		head.appendChild(el('th', 'mi-pad'));
		var thead = el('thead');
		thead.appendChild(head);
		table.appendChild(thead);

		var tbody = el('tbody');
		t.rows.forEach(function (row) {
			var tr = el('tr');
			row.forEach(function (v, i) { tr.appendChild(cell(v, cls[i])); });
			tr.appendChild(el('td', 'mi-pad'));
			tbody.appendChild(tr);
		});
		table.appendChild(tbody);

		var scroll = el('div', 'mi-table-scroll');
		scroll.appendChild(table);
		wrap.appendChild(scroll);
		return wrap;
	}

	/* ---- tree view ---- */

	function count(v) {
		return Array.isArray(v) ? '[' + v.length + ']'
			: '{' + Object.keys(v).length + '}';
	}

	// How much of a reply may be laid open at once. Everything open means
	// everything built, and ul_dump on a busy box runs to tens of thousands of
	// leaves -- past this, only the first level opens and the rest waits to be
	// asked for.
	var TREE_OPEN_MAX = 2000;

	// what is left of the budget, which stops as soon as it runs out: the
	// question is only whether the whole reply fits, not by how far it misses
	function budget(value, left) {
		if (value === null || typeof value !== 'object') return left - 1;
		var keys = Object.keys(value);
		for (var i = 0; i < keys.length && left > 0; i++)
			left = budget(value[keys[i]], left);
		return left;
	}

	/*
	 * A leaf is a row, a branch a <details> -- disclosure, keyboard and
	 * find-in-page come with the element and cost nothing to write. The children
	 * of a closed branch are built the first time it opens, so the half of a big
	 * reply nobody looks at is never in the document at all.
	 */
	function treeNode(key, value, depth, openAll) {
		if (value === null || typeof value !== 'object') {
			var row = el('div', 'mi-tree-row');
			row.appendChild(el('span', 'mi-tree-key', key));
			row.appendChild(el('span',
				'mi-tree-val' + (typeof value === 'number' ? ' mi-tree-num' : ''),
				value === null ? 'null' : String(value)));
			return row;
		}

		var branch = el('details', 'mi-tree-branch');
		var head = el('summary');
		head.appendChild(el('span', 'mi-tree-key', key));
		head.appendChild(el('span', 'mi-tree-count', count(value)));
		branch.appendChild(head);
		// a reply small enough to hold open is read, not navigated; a big one
		// still shows its first level, which is the shape of the answer
		branch.open = openAll || depth === 0;

		var built = false;
		function build() {
			if (built) return;
			built = true;
			var kids = el('div', 'mi-tree-kids');
			// a position in a list is not a name, and reads as one without the
			// brackets: "[0]" among the dialogs, "callid" inside one of them
			var list = Array.isArray(value);
			Object.keys(value).forEach(function (k) {
				kids.appendChild(treeNode(list ? '[' + k + ']' : k, value[k],
					depth + 1, openAll));
			});
			branch.appendChild(kids);
		}

		if (branch.open) build();
		else branch.addEventListener('toggle', build);

		return branch;
	}

	function treeView(data) {
		var wrap = el('div', 'mi-tree');
		var openAll = budget(data, TREE_OPEN_MAX) > 0;
		var list = Array.isArray(data);
		Object.keys(data).forEach(function (k) {
			wrap.appendChild(treeNode(list ? '[' + k + ']' : k, data[k], 0, openAll));
		});
		return wrap;
	}

	/* ---- view switch ---- */

	// "alt" is whichever rendering the reply earned -- a table, or the tree that
	// stands in for one when the reply does not fit a grid -- against the JSON
	// it was built from. Each remembers its own side of the switch.
	function addToggle(slot, alt, body, label, key) {
		var seg = el('div', 'mi-seg');
		var aBtn = el('button', 'mi-seg-btn', label);
		var jBtn = el('button', 'mi-seg-btn', 'JSON');
		aBtn.type = jBtn.type = 'button';

		function show(asAlt) {
			alt.style.display = asAlt ? '' : 'none';
			body.style.display = asAlt ? 'none' : '';
			aBtn.classList.toggle('mi-seg-on', asAlt);
			jBtn.classList.toggle('mi-seg-on', !asAlt);
			// selecting a rendering yields what is drawn rather than the reply, so
			// Copy is only offered over the JSON it can reproduce exactly
			slot.copy.disabled = asAlt;
			slot.copy.title = asAlt ? 'Switch to JSON to copy the reply' : '';
		}

		function pick(asAlt) {
			show(asAlt);
			try {
				localStorage.setItem(key, asAlt ? 'alt' : 'json');
			} catch (e) {}
		}

		aBtn.addEventListener('click', function () { pick(true); });
		jBtn.addEventListener('click', function () { pick(false); });

		seg.appendChild(aBtn);
		seg.appendChild(jBtn);
		slot.meta.insertBefore(seg, slot.copy);

		show(preferred(key));
	}

	function render(slot, payload) {
		var body = el('pre', 'mi-card-body');
		var table = null;
		var tree = null;
		var text = '';

		if (payload.dropped) {
			// succeeded when it ran, but the reply was too big to keep
			slot.pill.className = 'mi-pill mi-pill-ok';
			slot.pill.textContent = 'OK';
			body.classList.add('mi-muted');
			text = body.textContent = 'Output was too large to keep across a reload — run it again to see it.';
		} else if (payload.ok) {
			var d = payload.data;
			var empty = d === null || d === undefined ||
				(typeof d === 'object' && Object.keys(d).length === 0);
			slot.pill.className = 'mi-pill mi-pill-ok';
			slot.pill.textContent = 'OK';
			if (empty) {
				body.classList.add('mi-muted');
				text = body.textContent = 'Executed, no output returned.';
			} else {
				text = body.textContent = JSON.stringify(d, null, 2);
				table = MITable.build(d);
				// nesting is what keeps a reply out of a grid, and nesting is
				// exactly what the tree is for
				if (!table && typeof d === 'object') tree = treeView(d);
			}
		} else {
			slot.pill.className = 'mi-pill mi-pill-err';
			slot.pill.textContent = 'Error';
			body.classList.add('mi-err');
			text = body.textContent = payload.error;
		}

		if (table) {
			var view = tableView(table);
			slot.card.appendChild(view);
			addToggle(slot, view, body, 'Table', 'mi.view');
		} else if (tree) {
			slot.card.appendChild(tree);
			addToggle(slot, tree, body, 'Tree', 'mi.treeview');
		}
		slot.card.appendChild(body);

		slot.copy.addEventListener('click', function () {
			function flash(word) {
				slot.copy.textContent = word;
				setTimeout(function () { slot.copy.textContent = 'Copy'; }, 1200);
			}
			copyText(text).then(function () { flash('Copied'); },
				function () { flash('Failed'); });
		});
	}

	/*
	 * A command that ends in a list takes any number of values there --
	 * "statistics:get shmem: net:" is two names for one parameter, not two
	 * parameters -- and the signature cannot say which parameter that is, only
	 * how many there are. So values past the last one of the widest signature
	 * are folded back into it, and the position is what the server is told.
	 *
	 * Only positional lines fold. A named value is written as a list the way it
	 * always was, in brackets, which is unambiguous and needs no signature.
	 */
	function groupIndex(line) {
		var sp = line.indexOf(' ');
		if (sp === -1) return -1;

		var info = lookup(line.substring(0, sp), false);
		if (!info || !info.known) return -1;

		var toks = MI.splitArgs(line.substring(sp)).toks;
		if (!toks.length || toks.some(isNamed)) return -1;

		var widest = info.flavors.reduce(function (m, fl) {
			return Math.max(m, fl.length);
		}, 0);

		return widest > 0 && toks.length > widest ? widest - 1 : -1;
	}

	function run(line) {
		var at = now();
		var on = boxInfo(box);
		var slot = addCard(line, at, on);
		var body = new URLSearchParams();
		body.set('line', line);
		body.set('csrf', cfg.csrf);

		var group = groupIndex(line);
		if (group >= 0) body.set('group', group);

		function done(payload) {
			render(slot, payload);
			keepResult(line, at, on, payload);
		}

		fetch('api.php?op=run&box=' + box, { method: 'POST', body: body })
			.then(function (r) { return r.json(); })
			.then(done)
			.catch(function (e) { done({ ok: false, error: 'Request failed: ' + e }); });
	}

	function submit() {
		// slots the user never filled in are not empty values -- they never
		// happened, so neither the history nor OpenSIPS hears about them
		var line = MI.dropPlaceholders(input.value.trim());
		if (!line || !(ready || forced())) return;
		input.value = '';
		closeSuggest();
		// the line the complaint was about is gone with it -- reachable only by
		// running anyway, which is the one way a refused line can be sent
		argErr = '';
		clearNameError();
		// the override was for the line just sent, not for the next one
		if (force) force.checked = false;
		setReady(true);
		remember(line);
		run(line);
	}

	/* ---- command completion ---- */

	function loadCommands() {
		if (cmdCache[box]) return;
		cmdCache[box] = [];
		fetch('api.php?op=commands&box=' + box)
			.then(function (r) { return r.json(); })
			.then(function (j) {
				cmdCache[box] = j.ok ? j.commands.slice().sort() : [];
				// a list already standing open was drawn from whatever had
				// arrived by then -- redraw it now the rest is here
				if (browsing) updateSuggest();
			})
			.catch(function () {});
	}

	function commandMatches(v) {
		var q = v.toLowerCase();
		return (cmdCache[box] || []).filter(function (c) {
			return c.toLowerCase().indexOf(q) !== -1;
		});
	}

	function closeSuggest() {
		suggest.style.display = 'none';
		suggest.textContent = '';
		matches = [];
		allMatches = [];
		hints = [];
		active = -1;
		browsing = false;
	}

	function drawSuggest() {
		suggest.textContent = '';
		matches.forEach(function (m, i) {
			var row = el('div', 'mi-opt' + (i === active ? ' mi-active' : ''), m);
			row.addEventListener('mousedown', function (e) {
				e.preventDefault();
				accept(i);
			});
			suggest.appendChild(row);
		});
		suggest.style.display = 'block';
		if (active >= 0) suggest.children[active].scrollIntoView({ block: 'nearest' });
	}

	/* ---- parameter hints ---- */

	// { known, flavors } once "which <cmd>" has answered, null until then, so a
	// slow or failed lookup never locks the console
	function lookup(name, allowFetch) {
		var key = box + '|' + name;
		if (flavorCache[key]) return flavorCache[key];
		if (!allowFetch || inFlight[key]) return null;

		inFlight[key] = true;
		fetch('api.php?op=params&box=' + box + '&command=' + encodeURIComponent(name))
			.then(function (r) { return r.json(); })
			.then(function (j) {
				flavorCache[key] = {
					known: !!j.known,
					flavors: MI.computeFlavors(j.signatures || [])
				};
				inFlight[key] = false;
				updateSuggest();
				refreshNameError();
			})
			.catch(function () { inFlight[key] = false; });

		return null;
	}

	/*
	 * Two things can be wrong with a line and one place says so, the argument
	 * complaint first because it is the one holding Run down.
	 *
	 * An unknown command is never called out while it is still being typed --
	 * the matching-command list is the useful thing at that point -- so that
	 * complaint waits for the field to be left alone. The argument one is said
	 * as it happens: it is nearly always a space that should have been quoted,
	 * and the only other sign of it is a Run button that quietly stays off.
	 */
	var nameErr = '';
	var argErr = '';

	function showError() {
		cmdErr.textContent = argErr || nameErr;
		cmdErr.style.display = cmdErr.textContent ? 'block' : 'none';
	}

	function clearNameError() {
		nameErr = '';
		showError();
	}

	function refreshNameError() {
		var v = input.value.trim();
		if (!v || document.activeElement === input) return clearNameError();

		var sp = v.indexOf(' ');
		var name = sp === -1 ? v : v.substring(0, sp);
		var info = lookup(name, true);
		if (!info || info.known) return clearNameError();

		nameErr = '"' + name + '" is not an MI command on this box';
		showError();
	}

	/*
	 * One row per signature the box reported, so every parameter on a row is a
	 * parameter that call takes: tracer:start reads as "id uri", "id uri filter"
	 * and "id uri filter scope type", three rows, rather than one row with the
	 * shorter calls written into it as an optional tail.
	 */
	function hintRow(parts) {
		var row = el('div', 'mi-hintrow');

		if (!parts.length) {
			row.appendChild(el('span', 'mi-hint-opt', '(no parameters)'));
		} else {
			parts.forEach(function (p) {
				var cls, text;
				if (p.filled) {
					cls = 'mi-hint-filled';
					// values are shown as they were typed, quotes and all: they are
					// what holds a value with a space in it together, and a row
					// that dropped them would read as two arguments. Folded ones
					// are shown as the list they will be sent as, where the comma
					// separates and nothing needs quoting.
					text = p.list ? p.name + '=[' + p.items.join(',') + ']'
						: p.name + '=' + p.raw;
				} else {
					cls = 'mi-hint-req';
					text = p.pending ? p.name + '=?' : p.name;
				}
				row.appendChild(el('span', cls, text));
			});
		}

		if (MI.flavorReady(parts)) {
			row.classList.add('mi-hint-ready');
			row.appendChild(el('span', 'mi-hint-check', '✓'));
		}

		return row;
	}

	// The hint rows are pickable, but with the mouse only: the keyboard is
	// already spoken for -- Enter runs the line and the arrows walk the history,
	// both of which are worth more here than picking a flavor.
	function drawHints() {
		suggest.textContent = '';
		hints.forEach(function (parts, i) {
			var row = hintRow(parts);
			row.addEventListener('mousedown', function (e) {
				e.preventDefault();
				acceptHint(i);
			});
			suggest.appendChild(row);
		});
		suggest.style.display = 'block';
	}

	// "Force" lifts the lock for one line, for a module whose MI metadata is too
	// thin to judge a command by. It says nothing about the hints, which go on
	// reporting what they think of the line.
	function forced() {
		return !!force && force.checked;
	}

	function setReady(state) {
		if (cfg.readOnly) return;
		ready = state;
		runBtn.disabled = !(ready || forced());
	}

	/* ---- dropdown ---- */

	// before the first space the input names a command; after it, parameters.
	// "silent" refreshes the Run lock without popping the dropdown open, which
	// is what history recall needs so the arrow keys keep walking the history.
	function updateSuggest(silent) {
		clearTimeout(lookupTimer);

		// every change to the line comes through here, typed or written by a
		// pick, a completion or a card, so this is where the draft is taken --
		// what a walk comes back down to is the line as it stood, not the part
		// of it that happened to be typed by hand
		keepDraft();

		var v = input.value;
		argErr = '';
		if (v === '') {
			showError();
			setReady(true);
			/*
			 * An empty field with the caret in it is a place to start looking,
			 * so it stands the whole command list open -- the same rows a typed
			 * fragment filters, and the same list Tab has always cycled from
			 * here. Only when the field is actually being used: a recall walking
			 * back to an empty draft, or a programmatic pass with the focus
			 * elsewhere, leaves the dropdown shut as before.
			 */
			if (silent || recalling || document.activeElement !== input)
				return closeSuggest();
			// every command, not the 50 a typed fragment stops at: this list is
			// the one being read rather than narrowed, and it is what Tab then
			// cycles, so the rows on screen and the rows Tab walks are the same
			allMatches = cmdCache[box] || [];
			matches = allMatches.slice();
			if (!matches.length) return closeSuggest();
			hints = [];
			// nothing is preselected: Enter on an empty line has nothing to run
			// and must not pick a command the user only meant to look at
			active = -1;
			browsing = true;
			return drawSuggest();
		}
		browsing = false;

		var sp = v.indexOf(' ');
		var name = sp === -1 ? v : v.substring(0, sp);

		// a typed space means the name is final, so resolve it at once; while it
		// is still being typed, wait for a pause before spending a request
		var info = lookup(name, sp !== -1);
		if (sp === -1 && !info)
			lookupTimer = setTimeout(function () { lookup(name, true); }, 250);

		matches = [];
		active = -1;

		// unified rule: runnable when the command exists and the arguments typed
		// so far satisfy at least one of its signatures. An unclosed list and a
		// named/positional mixture are both refused by the parser outright, so
		// neither ever counts as ready.
		var known = !info || info.known;
		var tail = sp === -1 ? '' : v.substring(sp);
		var args = MI.splitArgs(tail);
		var bad = args.open || isMixed(args.toks);
		var lines = known && info ? MI.buildLines(info.flavors, tail) : [];
		// judged on the line as it will be sent, with the slots a template wrote
		// and nobody filled taken out of it: a five-parameter row with three left
		// empty is the two-parameter call, and that is a signature of its own
		var sent = known && info ? MI.buildLines(info.flavors, MI.dropPlaceholders(tail)) : [];
		setReady(info ? known && !bad && sent.some(MI.flavorReady) : true);

		// while a quote is still open the line is mid-value and says nothing yet
		if (!args.open && isMixed(settled(args.toks, tail)))
			argErr = 'Named and positional parameters cannot be mixed -- ' +
				'a value with a space in it has to be quoted';
		showError();
		if (silent || recalling) return closeSuggest();

		if (sp === -1) {
			/*
			 * A cycle in progress keeps the candidates it started with. The name
			 * Tab just wrote matches only itself, so recomputing here would
			 * collapse the list under the user -- and the parameter lookup
			 * lands a moment later and would do exactly that.
			 */
			if (cycle && cycle.command && cycle.applied === v) {
				matches = allMatches = cycle.names;
				active = cycle.idx;
				return drawSuggest();
			}

			allMatches = commandMatches(v);
			matches = allMatches.slice(0, 50);
			if (matches.length) {
				active = 0;
				return drawSuggest();
			}
		}

		if (!lines.length) return closeSuggest();

		hints = lines;
		drawHints();
	}

	function accept(i) {
		input.value = matches[i] + ' ';
		closeSuggest();
		input.focus();
		updateSuggest();
	}

	/*
	 * Picking a flavor writes the whole branch out: every parameter it takes,
	 * optional ones included, each as an empty slot ready for its value.
	 *
	 *   dialog:list  ->  dialog:list index= counter=
	 *
	 * A slot stops at the "=" so it reads as a value owed rather than a value
	 * given -- name="" would be the empty string, which is a thing a command can
	 * legitimately be sent. Values already on the line are carried over as typed.
	 * Slots left untouched are dropped on the way out (MI.dropPlaceholders), so
	 * the optional tail costs nothing but is there to be filled if it is wanted.
	 */
	function acceptHint(i) {
		var v = input.value.trim();
		var sp = v.indexOf(' ');

		var slots = hints[i].map(function (p) {
			// values folded into a list only stay one argument if the brackets
			// are written out, which is what the named form needs anyway
			if (p.list) return ' ' + p.name + '=[' + p.items.join(',') + ']';
			// a token still being typed only reads as the start of a parameter
			// name when it is the start of one this flavor offers: "ind" would
			// be trapped as the value of callid, so the slot goes back to empty,
			// while "sip:1.2.3.4" prefixes nothing and is the value it looks like
			var namish = p.partial && hints[i].some(function (q) {
				return q.name.toLowerCase().indexOf(p.raw.toLowerCase()) === 0;
			});
			return ' ' + p.name + '=' + (p.filled && !namish ? p.raw : '');
		}).join('');

		// a flavor that takes nothing still ends the name, so it keeps the space:
		// a line without one reads as a command still being typed, and the
		// dropdown would answer with the matching-command list all over again
		var line = (sp === -1 ? v : v.substring(0, sp)) + (slots || ' ');

		// a second click on a row that is written out with every parameter filled
		// in, optional ones included, has nothing left to give, so it is read as
		// done with the list and dismisses it. A row with a slot still empty keeps
		// the list up: it is the thing saying what may still go there. Either way
		// the Run lock is refreshed, silently in the first case, since the line is
		// what it judges.
		var again = line === input.value && hints[i].every(function (p) {
			return p.filled;
		});
		input.value = line;

		closeSuggest();
		input.focus();
		caretToFirstSlot();
		updateSuggest(again);
	}

	// every empty slot on the line -- an "=" with nothing behind it -- as the
	// caret position that fills it
	function slots() {
		var at = [];
		var re = /=(?=\s|$)/g;
		var m;
		while ((m = re.exec(input.value)) !== null) at.push(m.index + 1);
		return at;
	}

	// straight into the first empty slot, so the value can just be typed
	function caretToFirstSlot() {
		var at = slots();
		var pos = at.length ? at[0] : input.value.length;
		input.setSelectionRange(pos, pos);
	}

	/*
	 * A picked flavor leaves the line dotted with empty slots, and Tab walks
	 * them in the order they are written, wrapping round at the end -- so a
	 * whole command is filled in without reaching for the mouse or the arrows.
	 * Slots that already hold a value are done with and are not stopped at.
	 */
	function tabToSlot(back) {
		var at = slots();
		if (!at.length) return false;

		var here = input.selectionStart;
		var next = back ? at[at.length - 1] : at[0];

		for (var i = 0; i < at.length; i++) {
			if (!back && at[i] > here) { next = at[i]; break; }
			if (back && at[i] < here) next = at[i];
		}

		input.setSelectionRange(next, next);
		return true;
	}

	/* ---- tab completion ---- */

	function commonPrefix(list) {
		return list.reduce(function (p, s) {
			var i = 0;
			while (i < p.length && i < s.length && p[i] === s[i]) i++;
			return p.slice(0, i);
		});
	}

	/*
	 * Candidate names for the token being typed. Signatures branch -- dialog:list
	 * accepts {callid, from_tag?} or {index, counter} and never a mixture -- so
	 * only flavors that still admit every name already on the line contribute,
	 * and a name already supplied is not offered twice.
	 */
	function paramCandidates(info, tail) {
		var parsed = MI.splitArgs(tail);
		var toks = parsed.toks;
		var partial = /\s$/.test(tail) || !toks.length ? '' : toks[toks.length - 1];
		if (partial.indexOf('=') !== -1) return null;

		/*
		 * The parser takes either all-named or all-positional arguments and
		 * refuses a mixture, so once a bare value has been typed there is no
		 * parameter name left to offer -- completing one would only build a
		 * command the server is bound to reject.
		 */
		var done = partial === '' ? toks : toks.slice(0, -1);
		if (!done.every(isNamed)) return null;

		var used = done.map(function (t) {
			return t.substring(0, t.indexOf('='));
		});

		var names = [];
		info.flavors.forEach(function (fl) {
			var offered = fl.map(function (p) { return p.name; });
			if (!used.every(function (n) { return offered.indexOf(n) !== -1; })) return;
			offered.forEach(function (n) {
				if (used.indexOf(n) === -1 && n.indexOf(partial) === 0 && names.indexOf(n) === -1)
					names.push(n);
			});
		});

		return names.length ? { partial: partial, names: names } : null;
	}

	// mirrors parse_command: a leading "[" makes it a list value, so "[a=b]" is
	// a positional argument rather than a named one
	function isNamed(tok) {
		return tok.indexOf('=') > 0 && tok.charAt(0) !== '[';
	}

	function isMixed(toks) {
		return toks.some(isNamed) && !toks.every(isNamed);
	}

	// a bare token still being typed is as likely the start of a parameter name
	// as it is a value, so it only counts as positional once a space ends it
	function settled(toks, tail) {
		return /\s$/.test(tail) ? toks : toks.slice(0, -1);
	}

	/*
	 * Where the last argument stands:
	 *   open      inside a list the user has not closed -- "filter=[ip,"
	 *   awaiting  a name whose value is still owed      -- "node="
	 *   complete  a finished pair                       -- "node=abc"
	 *   none      anything else, including a trailing space
	 */
	function argState(v) {
		var sp = v.indexOf(' ');
		if (sp === -1) return 'none';

		var parsed = MI.splitArgs(v.substring(sp + 1));
		if (parsed.open) return 'open';
		if (/\s$/.test(v)) return 'none';

		var last = parsed.toks.length ? parsed.toks[parsed.toks.length - 1] : '';
		var eq = last.indexOf('=');
		if (eq <= 0) return 'none';

		return eq === last.length - 1 ? 'awaiting' : 'complete';
	}

	function applyCycle() {
		input.value = cycle.applied = cycle.head + cycle.names[cycle.idx] + cycle.suffix;
		updateSuggest();
		return true;
	}

	// shell semantics: extend to the longest common prefix, and only commit to a
	// full name when it is unambiguous. Where there is no prefix left to extend
	// -- after a space, or among names sharing nothing -- repeated Tab cycles
	// the candidates instead, for command names and parameters alike.
	function tabComplete(back) {
		var v = input.value;

		// still on the same cycle: step to the next candidate
		if (cycle && cycle.applied === v) {
			var n = cycle.names.length;
			cycle.idx = (cycle.idx + (back ? n - 1 : 1)) % n;
			return applyCycle();
		}

		var sp = v.indexOf(' ');

		if (sp === -1) {
			// nothing is on screen straight after a history recall, so the
			// candidates have to be rebuilt before Tab can complete from them
			if (!allMatches.length) {
				allMatches = commandMatches(v);
				matches = allMatches.slice(0, 50);
			}
			if (!allMatches.length) return false;
			if (allMatches.length === 1) {
				input.value = allMatches[0] + ' ';
				closeSuggest();
				updateSuggest();
				return true;
			}
			var pre = commonPrefix(allMatches);
			if (pre.length > v.length) {
				input.value = pre;
				updateSuggest();
				return true;
			}
			// nothing left to extend, so walk the matches one at a time
			cycle = { command: true, head: '', names: matches, suffix: '',
			          idx: back ? matches.length - 1 : 0 };
			return applyCycle();
		}

		var info = lookup(v.substring(0, sp), false);
		if (!info || !info.known) return false;

		/*
		 * Tab pressed straight after a value, with no separating space, is
		 * asking for the next parameter -- so carry on as though the space were
		 * there. It is only ever written back as part of a completion, so a Tab
		 * that finds nothing still leaves the line exactly as it was.
		 */
		var line = argState(v) === 'complete' ? v + ' ' : v;

		var cand = paramCandidates(info, line.substring(sp + 1));
		if (!cand) return false;

		var head = line.substring(0, line.length - cand.partial.length);
		cycle = null;

		if (cand.names.length > 1 && commonPrefix(cand.names).length <= cand.partial.length) {
			cycle = { command: false, head: head, names: cand.names, suffix: '=',
			          idx: back ? cand.names.length - 1 : 0 };
			return applyCycle();
		}

		input.value = cand.names.length === 1
			? head + cand.names[0] + '='
			: head + commonPrefix(cand.names);
		updateSuggest();

		return true;
	}

	/* ---- wiring ---- */

	// wrapped, so the InputEvent is not passed through as "silent"
	input.addEventListener('input', function () {
		cycle = null;
		recalling = false;
		clearNameError();
		updateSuggest();
	});

	input.addEventListener('blur', refreshNameError);

	input.addEventListener('focus', clearNameError);

	// a click on an empty field asks for the command list. The focus is not the
	// trigger: the console takes it on load, and arriving at the tool is not
	// asking to be shown everything.
	input.addEventListener('click', function () {
		if (input.value === '' && !browsing) updateSuggest();
	});

	input.addEventListener('keydown', function (e) {
		// any key other than the history arrows means the user is composing
		// again, so the command list is welcome back
		if (e.key !== 'ArrowUp' && e.key !== 'ArrowDown') recalling = false;

		if (e.key === 'Escape') return closeSuggest();

		if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
			e.preventDefault();
			// arrows walk the completion list while it is open, the recall
			// history otherwise -- the hint rows do not take part, they are
			// picked with the mouse. The list standing open over an empty field
			// is browsing, not completing, and the field being empty is exactly
			// where the history is reached for, so it keeps the arrows.
			if (matches.length && !browsing) {
				active = (active + (e.key === 'ArrowDown' ? 1 : matches.length - 1)) % matches.length;
				drawSuggest();
			} else {
				recall(e.key === 'ArrowDown' ? 1 : -1);
			}
			return;
		}

		/*
		 * Tab completes where it can. Where it cannot it gets out of the way
		 * and moves the focus on, the one exception being a name still waiting
		 * for its value -- "node=" offers nothing to complete yet, but the
		 * argument is plainly half written, so the field is held.
		 */
		if (e.key === 'Tab') {
			// completion comes first: a name just offered by Tab reads as an
			// unfinished value, and testing that ahead of tabComplete would
			// stop the cycle dead on its second press
			if (tabComplete(e.shiftKey)) return e.preventDefault();
			// nothing to complete: the empty slots of a picked flavor are the
			// next thing Tab is for, each in turn
			if (tabToSlot(e.shiftKey)) return e.preventDefault();
			// nothing to complete -- hold the field anyway while a value is
			// still owed or a list is still open, rather than moving the focus
			var st = argState(input.value);
			if (st === 'open' || st === 'awaiting') e.preventDefault();
			return;
		}

		if (e.key !== 'Enter') return;
		e.preventDefault();
		if (matches.length && active >= 0) return accept(active);
		submit();
	});

	/*
	 * Close on an outside click, but not while reaching for anything in the
	 * card. Judged on the way down: picking a row redraws the dropdown, and by
	 * the time the event bubbled back up here the row that was clicked would be
	 * a detached node with no card above it -- an inside click reported as an
	 * outside one, closing what the pick had just reopened.
	 */
	document.addEventListener('mousedown', function (e) {
		if (!e.target.closest('.mi-runner')) closeSuggest();
	}, true);

	runBtn.addEventListener('click', submit);
	clearBtn.addEventListener('click', clearLog);

	// ticking it releases Run there and then, rather than at the next keystroke
	if (force) force.addEventListener('change', function () { setReady(ready); });

	// shown for reference only -- a command is still addressed by box index, so
	// the address never travels back to the server
	function showUrl() {
		if (miurl) miurl.textContent = cfg.urls[box] || '';
	}

	if (boxsel) {
		var saved = parseInt(localStorage.getItem('mi.box'), 10);
		if (saved >= 0 && saved < cfg.boxes.length) box = saved;
		boxsel.value = box;
		boxsel.addEventListener('change', function () {
			box = parseInt(boxsel.value, 10);
			localStorage.setItem('mi.box', box);
			showUrl();
			closeSuggest();
			loadCommands();
		});
	}

	sweep(localStorage, 'mi.history', HKEY);
	sweep(sessionStorage, 'mi.results', RKEY);

	if (!loadResults()) showEmpty();
	showUrl();
	setReady(true);
	loadHistory();
	loadCommands();
	input.focus();
})();
