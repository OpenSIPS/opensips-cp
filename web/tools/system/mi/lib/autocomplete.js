var MI = (function () {
	'use strict';

	// One flavor per signature, in the order "which" reports them. OpenSIPS
	// already lists every combination it accepts -- [], [a], [a,b] are three
	// answers, not one answer with two optional tails -- so each is shown as
	// the command it is. Nothing here is optional: a row says exactly what it
	// takes, and a shorter call is the shorter row.
	// [], [a], [a,b], [a,b,c], [d], [d,c]  ->  {} , {a} , {a,b} , {a,b,c} , {d} , {d,c}
	function computeFlavors(sigs) {
		return sigs.filter(function (sig) {
			return Array.isArray(sig);
		}).map(function (sig) {
			return sig.map(function (name) {
				return { name: name, optional: false };
			});
		});
	}

	// Runnable only when the filled parameters form a valid prefix of the flavor:
	// no gaps, and the first empty slot is optional. An empty "name=" slot is not
	// counted as a value owed -- a template writes one for every optional
	// parameter too, and those must not hold the command back.
	function flavorReady(parts) {
		var firstEmpty = -1;
		for (var i = 0; i < parts.length; i++)
			if (!parts[i].filled) { firstEmpty = i; break; }
		if (firstEmpty === -1) return true;
		for (var j = firstEmpty + 1; j < parts.length; j++)
			if (parts[j].filled) return false;
		return parts[firstEmpty].optional;
	}

	/*
	 * Whitespace separates arguments, except inside a [ ] list or a " " quote --
	 * the same rule the server-side parser applies, so the two agree on where an
	 * argument ends. "open" reports a list or a quote the user has not closed.
	 */
	function splitArgs(text) {
		var toks = [], depth = 0, quoted = false, cur = '';

		for (var i = 0; i < text.length; i++) {
			var c = text.charAt(i);
			if (c === '"') quoted = !quoted;
			else if (!quoted) {
				if (c === '[') depth++;
				else if (c === ']' && depth) depth--;
			}

			if (!quoted && depth === 0 && /\s/.test(c)) {
				if (cur !== '') { toks.push(cur); cur = ''; }
			} else {
				cur += c;
			}
		}
		if (cur !== '') toks.push(cur);

		return { toks: toks, open: depth > 0 || quoted };
	}

	// quotes wrap a value and are not part of it, so they come off before
	// anything reads it -- mi_value() does the same on the way to OpenSIPS
	function unquote(raw) {
		return raw.length >= 2 && raw.charAt(0) === '"' && raw.charAt(raw.length - 1) === '"'
			? raw.slice(1, -1) : raw;
	}

	// A name with nothing after the "=" is the slot a flavor template left behind,
	// not a value. Dropping the untouched ones here is what lets a template carry
	// the optional parameters too: leave one empty and it simply does not go.
	// Quotes are a value, so name="" still goes, as the empty string it says.
	function dropPlaceholders(line) {
		return splitArgs(line).toks.filter(function (t) {
			var eq = t.indexOf('=');
			return !(eq > 0 && t.charAt(0) !== '[' && t.substring(eq + 1) === '');
		}).join(' ');
	}

	// argText is everything typed after the command name. Returns one line per
	// matching flavor: [{ name, value, raw, filled, optional, pending, partial }]
	// -- "value" is what the user meant, "raw" what they typed.
	function buildLines(flavors, argText) {
		var parsed = splitArgs(argText);
		var toks = parsed.toks;
		// inside an unclosed list the trailing text is part of a value, never
		// the start of a new parameter name
		var endsWithSpace = parsed.open || /\s$/.test(argText);
		var lines = [];

		if (toks.length === 0) {
			flavors.forEach(function (fl) {
				lines.push(fl.map(function (p) {
					return { name: p.name, value: '', raw: '', filled: false, optional: p.optional };
				}));
			});
			return lines;
		}

		if (toks[0].indexOf('=') !== -1) {
			var used = {}, partialName = null, lastIdx = toks.length - 1;
			toks.forEach(function (t, idx) {
				var eq = t.indexOf('=');
				if (eq >= 0) used[t.substring(0, eq)] = t.substring(eq + 1);
				else if (idx === lastIdx && !endsWithSpace) partialName = t.toLowerCase();
			});
			var usedNames = Object.keys(used);
			flavors.forEach(function (fl) {
				var flNames = fl.map(function (p) { return p.name; });
				// keep only flavors that offer every name already typed
				if (!usedNames.every(function (n) { return flNames.indexOf(n) !== -1; })) return;
				// and, while a new name is being typed, one that still matches it
				if (partialName && !fl.some(function (p) {
					return !used.hasOwnProperty(p.name) && p.name.toLowerCase().indexOf(partialName) === 0;
				})) return;
				lines.push(fl.map(function (p) {
					var present = used.hasOwnProperty(p.name);
					var raw = present ? used[p.name] : '';
					// anything after the "=" is a value, quotes included, so name=""
					// counts as supplied -- an empty string is what it asks for.
					// "pending" marks a bare name= for the hint row to point at; it
					// is only ever a slot waiting to be filled, never a value.
					return {
						name: p.name, value: unquote(raw), raw: raw, filled: raw !== '',
						optional: p.optional, pending: present && raw === ''
					};
				}));
			});
			return lines;
		}

		// bare values, assigned in order
		var widest = flavors.reduce(function (m, fl) { return Math.max(m, fl.length); }, 0);

		flavors.forEach(function (fl) {
			/*
			 * Values past the last parameter of the widest signature are taken as
			 * more of that parameter: a command only accepts them at all when it
			 * ends in a list, the way statistics:get takes any number of names.
			 * groupIndex() sends the position along so the server folds the same
			 * way, and a shorter signature stays out of it -- one fold per line.
			 */
			var fold = fl.length === widest && fl.length > 0 && toks.length > fl.length;
			if (fl.length < toks.length && !fold) return;

			lines.push(fl.map(function (p, i) {
				var last = fold && i === fl.length - 1;
				var items = last ? toks.slice(i).map(unquote) : null;
				var raw = last ? toks.slice(i).join(' ') : (i < toks.length ? toks[i] : '');
				return {
					name: p.name, value: unquote(raw), raw: raw, filled: i < toks.length,
					optional: p.optional, list: last, items: items,
					// the token still being typed reads as a value here, but it may
					// as easily be the start of a name -- see acceptHint()
					partial: !endsWithSpace && i === toks.length - 1
				};
			}));
		});

		return lines;
	}

	return {
		computeFlavors: computeFlavors,
		flavorReady: flavorReady,
		buildLines: buildLines,
		splitArgs: splitArgs,
		dropPlaceholders: dropPlaceholders
	};
})();
