var MITable = (function () {
	'use strict';

	function scalar(v) {
		return v === null || typeof v !== 'object';
	}

	function flatObject(v) {
		if (v === null || typeof v !== 'object' || Array.isArray(v)) return false;
		var keys = Object.keys(v);
		return keys.length > 0 && keys.every(function (k) { return scalar(v[k]); });
	}

	// rows need not agree on their keys, so the columns are the union in order
	// of first appearance and a row missing one gets a blank cell
	function fromObjects(arr, title) {
		var columns = [];
		arr.forEach(function (row) {
			Object.keys(row).forEach(function (k) {
				if (columns.indexOf(k) === -1) columns.push(k);
			});
		});

		return {
			title: title,
			columns: columns,
			rows: arr.map(function (row) {
				return columns.map(function (k) {
					return Object.prototype.hasOwnProperty.call(row, k) ? row[k] : null;
				});
			})
		};
	}

	function fromFlat(obj, title) {
		return {
			title: title,
			columns: ['Name', 'Value'],
			rows: Object.keys(obj).map(function (k) { return [k, obj[k]]; })
		};
	}

	function fromArray(arr, title) {
		if (!arr.length) return null;
		if (arr.every(flatObject)) return fromObjects(arr, title);
		if (arr.every(scalar))
			return {
				title: title,
				columns: [title || 'Value'],
				rows: arr.map(function (v) { return [v]; })
			};
		return null;
	}

	/*
	 * Most MI replies wrap their payload in a one-key envelope -- ps answers
	 * {"Processes": [...]}, dlg_list {"Dialogs": [...]} -- so that key names the
	 * table and what it holds makes the rows: an array is a row per element, a
	 * flat object a row per key, the way get_statistics answers a group.
	 *
	 * A reply is only tabulated when every cell is a scalar. Nesting is left to
	 * the JSON view rather than flattened or summarised, so a table never hides
	 * part of the answer.
	 */
	// a column of numbers is worth right-aligning and sizing to its digits, so
	// the values sit under their heading instead of stretched away from it
	function numericColumns(t) {
		t.numeric = t.columns.map(function (c, i) {
			var seen = false;
			return t.rows.every(function (row) {
				if (row[i] === null || row[i] === undefined) return true;
				seen = true;
				return typeof row[i] === 'number';
			}) && seen;
		});
		return t;
	}

	function build(data) {
		var t = null;

		if (Array.isArray(data)) {
			t = fromArray(data, null);
		} else if (data !== null && typeof data === 'object') {
			var keys = Object.keys(data);
			var inner = keys.length === 1 ? data[keys[0]] : null;
			if (Array.isArray(inner)) t = fromArray(inner, keys[0]);
			else if (flatObject(inner)) t = fromFlat(inner, keys[0]);
			else if (flatObject(data)) t = fromFlat(data, null);
		}

		return t && numericColumns(t);
	}

	return { build: build };
})();
