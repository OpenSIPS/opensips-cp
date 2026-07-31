var MI = (function () {
	'use strict';

	// Collapse the raw "which" output (an array of parameter-name arrays) into
	// readable flavors: the signatures go into a prefix tree and every leaf path
	// becomes one flavor, so branching signatures split instead of colliding. A
	// parameter is optional when its parent node is itself a valid signature.
	// [], [a], [a,b], [a,b,c], [d], [d,c]  ->  {} , {a, b?, c?} , {d, c?}
	function computeFlavors(sigs) {
		var root = { children: {}, order: [], terminal: false };
		sigs.forEach(function (sig) {
			if (!Array.isArray(sig)) return;
			if (sig.length === 0) { root.terminal = true; return; }
			var node = root;
			sig.forEach(function (name) {
				if (!node.children[name]) {
					node.children[name] = { children: {}, order: [], terminal: false };
					node.order.push(name);
				}
				node = node.children[name];
			});
			node.terminal = true;
		});

		var flavors = [];
		if (root.terminal) flavors.push([]);
		(function walk(node, isRoot, path) {
			node.order.forEach(function (name) {
				var child = node.children[name];
				var next = path.concat([{ name: name, optional: isRoot ? false : node.terminal }]);
				if (child.order.length === 0) flavors.push(next);
				else walk(child, false, next);
			});
		})(root, true, []);

		return flavors;
	}

	// Runnable only when the filled parameters form a valid prefix of the flavor:
	// no pending "name=", no gaps, and the first empty slot is optional.
	function flavorReady(parts) {
		if (parts.some(function (p) { return p.pending; })) return false;
		var firstEmpty = -1;
		for (var i = 0; i < parts.length; i++)
			if (!parts[i].filled) { firstEmpty = i; break; }
		if (firstEmpty === -1) return true;
		for (var j = firstEmpty + 1; j < parts.length; j++)
			if (parts[j].filled) return false;
		return parts[firstEmpty].optional;
	}

	/*
	 * Whitespace separates arguments, except inside a [ ] list -- the same rule
	 * the server-side parser applies, so the two agree on where an argument
	 * ends. "open" reports a list the user has not closed yet.
	 */
	function splitArgs(text) {
		var toks = [], depth = 0, cur = '';

		for (var i = 0; i < text.length; i++) {
			var c = text.charAt(i);
			if (c === '[') depth++;
			else if (c === ']' && depth) depth--;

			if (depth === 0 && /\s/.test(c)) {
				if (cur !== '') { toks.push(cur); cur = ''; }
			} else {
				cur += c;
			}
		}
		if (cur !== '') toks.push(cur);

		return { toks: toks, open: depth > 0 };
	}

	// argText is everything typed after the command name. Returns one line per
	// matching flavor: [{ name, value, filled, optional, pending }]
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
					return { name: p.name, value: '', filled: false, optional: p.optional };
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
					var hasVal = present && used[p.name] !== '';
					return {
						name: p.name, value: hasVal ? used[p.name] : '', filled: hasVal,
						optional: p.optional, pending: present && !hasVal
					};
				}));
			});
			return lines;
		}

		// bare values, assigned in order
		flavors.forEach(function (fl) {
			if (fl.length < toks.length) return;
			lines.push(fl.map(function (p, i) {
				return { name: p.name, value: i < toks.length ? toks[i] : '', filled: i < toks.length, optional: p.optional };
			}));
		});

		return lines;
	}

	return {
		computeFlavors: computeFlavors,
		flavorReady: flavorReady,
		buildLines: buildLines,
		splitArgs: splitArgs
	};
})();
