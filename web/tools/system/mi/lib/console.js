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
	var lookupTimer = null;
	var cycle = null;
	var recalling = false;

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

	/* ---- recall history ----
	 * only the command lines are kept, like a shell's .bash_history; results
	 * stay in the page because a single MI reply can be megabytes.
	 */

	function loadHistory() {
		try {
			var raw = JSON.parse(localStorage.getItem('mi.history'));
			if (Array.isArray(raw)) history = raw;
		} catch (e) {}
	}

	function remember(line) {
		if (history[history.length - 1] !== line) history.push(line);
		history = history.slice(-cfg.historySize);
		histPos = -1;
		try {
			localStorage.setItem('mi.history', JSON.stringify(history));
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
		input.value = histPos === history.length ? '' : history[histPos];
		recalling = true;
		updateSuggest();
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
				sessionStorage.setItem('mi.results', JSON.stringify(results));
				return;
			} catch (e) {
				results.shift();
			}
		}
		try {
			sessionStorage.removeItem('mi.results');
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
			var raw = JSON.parse(sessionStorage.getItem('mi.results'));
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

	function preferred() {
		return localStorage.getItem('mi.view') === 'json' ? 'json' : 'table';
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

	function addToggle(slot, table, body) {
		var seg = el('div', 'mi-seg');
		var tBtn = el('button', 'mi-seg-btn', 'Table');
		var jBtn = el('button', 'mi-seg-btn', 'JSON');
		tBtn.type = jBtn.type = 'button';

		function show(asTable) {
			table.style.display = asTable ? '' : 'none';
			body.style.display = asTable ? 'none' : '';
			tBtn.classList.toggle('mi-seg-on', asTable);
			jBtn.classList.toggle('mi-seg-on', !asTable);
			// selecting a table yields the rendered grid rather than the reply,
			// so Copy is only offered over the JSON it can reproduce exactly
			slot.copy.disabled = asTable;
			slot.copy.title = asTable ? 'Switch to JSON to copy the reply' : '';
		}

		function pick(asTable) {
			show(asTable);
			try {
				localStorage.setItem('mi.view', asTable ? 'table' : 'json');
			} catch (e) {}
		}

		tBtn.addEventListener('click', function () { pick(true); });
		jBtn.addEventListener('click', function () { pick(false); });

		seg.appendChild(tBtn);
		seg.appendChild(jBtn);
		slot.meta.insertBefore(seg, slot.copy);

		show(preferred() === 'table');
	}

	function render(slot, payload) {
		var body = el('pre', 'mi-card-body');
		var table = null;
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
			addToggle(slot, view, body);
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

	function run(line) {
		var at = now();
		var on = boxInfo(box);
		var slot = addCard(line, at, on);
		var body = new URLSearchParams();
		body.set('line', line);
		body.set('csrf', cfg.csrf);

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
		if (!line || (!ready && !cfg.unlocked)) return;
		input.value = '';
		closeSuggest();
		// the line the complaint was about is gone with it -- reachable only when
		// Run is unlocked, which is the one way a refused line can be sent
		argErr = '';
		clearNameError();
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
			.then(function (j) { cmdCache[box] = j.ok ? j.commands.slice().sort() : []; })
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
	 * "optional" marks a point where the command may stop, not a parameter that
	 * can be dropped on its own: tracer:start takes id uri, or id uri filter, or
	 * all five, but never type without scope. So the brackets nest -- everything
	 * past a stopping point is part of that optional tail --
	 * "id uri [filter [scope type]]" rather than "id uri [filter] [scope] type".
	 */
	function hintRow(parts) {
		var row = el('div', 'mi-hintrow');

		if (!parts.length) {
			row.appendChild(el('span', 'mi-hint-opt', '(no parameters)'));
		} else {
			var depth = 0, last = null;
			parts.forEach(function (p) {
				var cls, text;
				if (p.filled) {
					cls = 'mi-hint-filled';
					// an empty string was asked for by name, so it is shown the
					// way it was written rather than as nothing at all
					text = p.name + '=' + (p.value === '' ? '""' : p.value);
				} else if (p.pending) {
					cls = 'mi-hint-req';
					text = p.name + '=?';
				} else {
					if (p.optional) depth++;
					cls = depth ? 'mi-hint-opt' : 'mi-hint-req';
					text = (p.optional ? '[' : '') + p.name;
				}
				last = el('span', cls, text);
				row.appendChild(last);
			});
			if (depth) last.textContent += new Array(depth + 1).join(']');
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

	// "Always allow Run" gives up the lock entirely, for boxes whose MI metadata
	// is too thin to judge a command by. The hints still say what they think.
	function setReady(state) {
		if (cfg.readOnly) return;
		ready = state;
		runBtn.disabled = !(ready || cfg.unlocked);
	}

	/* ---- dropdown ---- */

	// before the first space the input names a command; after it, parameters.
	// "silent" refreshes the Run lock without popping the dropdown open, which
	// is what history recall needs so the arrow keys keep walking the history.
	function updateSuggest(silent) {
		clearTimeout(lookupTimer);

		var v = input.value;
		argErr = '';
		if (v === '') {
			showError();
			setReady(true);
			return closeSuggest();
		}

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
		setReady(info ? known && !bad && lines.some(MI.flavorReady) : true);

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
			// a token still being typed reads as a value, but it is as likely
			// the start of a parameter name -- either way the slot goes back
			// to empty rather than trapping "ind" as the value of callid
			return ' ' + p.name + '=' + (p.filled && !p.partial ? p.raw : '');
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

	// straight into the first empty slot -- an "=" with nothing behind it -- so
	// the value can just be typed
	function caretToFirstSlot() {
		var at = input.value.search(/=(\s|$)/);
		var pos = at === -1 ? input.value.length : at + 1;
		input.setSelectionRange(pos, pos);
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

	input.addEventListener('keydown', function (e) {
		// any key other than the history arrows means the user is composing
		// again, so the command list is welcome back
		if (e.key !== 'ArrowUp' && e.key !== 'ArrowDown') recalling = false;

		if (e.key === 'Escape') return closeSuggest();

		if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
			e.preventDefault();
			// arrows walk the completion list while it is open, the recall
			// history otherwise -- the hint rows do not take part, they are
			// picked with the mouse
			if (matches.length) {
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

	if (!loadResults()) showEmpty();
	showUrl();
	setReady(true);
	loadHistory();
	loadCommands();
	input.focus();
})();
