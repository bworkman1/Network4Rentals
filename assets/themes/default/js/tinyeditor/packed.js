TINY = {};

function T$(i) {
	return document.getElementById(i)
}
function T$$$() {
	return document.all ? 1 : 0
}
TINY.editor = function () {
	var c = [],
		offset = -30;
		c['cut'] = [1, 'Cut', 'a', 'cut', 1];
		c['copy'] = [2, 'Copy', 'a', 'copy', 1];
		c['paste'] = [3, 'Paste', 'a', 'paste', 1];
		c['bold'] = [4, 'Bold', 'a', 'bold'];
		c['italic'] = [5, 'Italic', 'a', 'italic'];
		c['underline'] = [6, 'Underline', 'a', 'underline'];
		c['strikethrough'] = [7, 'Strikethrough', 'a', 'strikethrough'];
		c['subscript'] = [8, 'Subscript', 'a', 'subscript'];
		c['superscript'] = [9, 'Superscript', 'a', 'superscript'];
		c['orderedlist'] = [10, 'Insert Ordered List', 'a', 'insertorderedlist'];
		c['unorderedlist'] = [11, 'Insert Unordered List', 'a', 'insertunorderedlist'];
		c['outdent'] = [12, 'Outdent', 'a', 'outdent'];
		c['indent'] = [13, 'Indent', 'a', 'indent'];
		c['leftalign'] = [14, 'Left Align', 'a', 'justifyleft'];
		c['centeralign'] = [15, 'Center Align', 'a', 'justifycenter'];
		c['rightalign'] = [16, 'Right Align', 'a', 'justifyright'];
		c['blockjustify'] = [17, 'Block Justify', 'a', 'justifyfull'];
		c['undo'] = [18, 'Undo', 'a', 'undo'];
		c['redo'] = [19, 'Redo', 'a', 'redo'];
		c['image'] = [20, 'Insert Image', 'i', 'insertimage', 'Enter Image URL:', 'http://'];
		c['hr'] = [21, 'Insert Horizontal Rule', 'a', 'inserthorizontalrule'];
		c['link'] = [22, 'Insert Hyperlink', 'i', 'createlink', 'Enter URL:', 'http://'];
		c['unlink'] = [23, 'Remove Hyperlink', 'a', 'unlink'];
		c['unformat'] = [24, 'Remove Formatting', 'a', 'removeformat'];
		c['print'] = [25, 'Print', 'as', 'print'];
		
		c['imgAlignLeft'] = [7, 'Image Align Left', 'a', 'left'];
		c['imgAlignRight'] = [7, 'Image Align Right', 'a', 'right'];
		

	function edit(n, a) {
		this.n = n;
		window[n] = this;
		this.t = T$(a.id);
		this.obj = a;
		this.xhtml = a.xhtml;
		var p = document.createElement('div'),
			w = document.createElement('div'),
			h = document.createElement('div'),
			l = a.controls.length,
			i = 0;
		this.i = document.createElement('iframe');
		this.i.frameBorder = 0;
		this.i.width = a.width || '500';
		this.i.height = a.height || '250';
		this.ie = T$$$();
		h.className = 'teheader';
		p.className = a.cssclass || 'te';
		p.style.width = this.i.width + 'px';
		p.appendChild(h);
		for (i; i < l; i++) {
			var b = a.controls[i];
			if (b == 'n') {
				h = document.createElement('div');
				h.className = a.rowclass || 'teheader';
				p.appendChild(h)
			} else if (b == '|') {
				var d = document.createElement('div');
				d.className = a.dividerclass || 'tedivider';
				h.appendChild(d)
			} else if (b == 'font') {
				var g = document.createElement('select'),
					fonts = a.fonts || ['Verdana', 'Arial', 'Georgia'],
					fl = fonts.length,
					x = 0;
				g.className = 'tefont';
				g.onchange = new Function(this.n + '.ddaction(this,"fontname")');
				g.options[0] = new Option('Font', '');
				for (x; x < fl; x++) {
					var j = fonts[x];
					g.options[x + 1] = new Option(j, j)
				}
				h.appendChild(g)
			} else if (b == 'size') {
				var g = document.createElement('select'),
					sizes = a.sizes || [1, 2, 3, 4, 5, 6, 7],
					sl = sizes.length,
					x = 0;
				g.className = 'tesize';
				g.onchange = new Function(this.n + '.ddaction(this,"fontsize")');
				for (x; x < sl; x++) {
					var k = sizes[x];
					g.options[x] = new Option(k, k)
				}
				h.appendChild(g)
			} else if (b == 'style') {
				var g = document.createElement('select'),
					styles = a.styles || [
						['Style', ''],
						['Paragraph', '<p>'],
						['Header 1', '<h1>'],
						['Header 2', '<h2>'],
						['Header 3', '<h3>'],
						['Header 4', '<h4>'],
						['Header 5', '<h5>'],
						['Header 6', '<h6>']
					],
					sl = styles.length,
					x = 0;
				g.className = 'testyle';
				g.onchange = new Function(this.n + '.ddaction(this,"formatblock")');
				for (x; x < sl; x++) {
					var o = styles[x];
					g.options[x] = new Option(o[0], o[1])
				}
				h.appendChild(g)
			} else if (c[b]) {
				
				var q = document.createElement('div'),
					x = c[b],
					func = x[2],
					ex, pos = x[0] * offset;
					console.log(x);
				q.className = a.controlclass;
				q.style.backgroundPosition = '0px ' + pos + 'px';
				q.title = x[1];
				ex = func == 'a' ? '.action("' + x[3] + '",0,' + (x[4] || 0) + ')' : '.insert("' + x[4] + '","' + x[5] + '","' + x[3] + '")';
				q.onclick = new Function(this.n + (b == 'print' ? '.print()' : ex));
				q.onmouseover = new Function(this.n + '.hover(this,' + pos + ',1)');
				q.onmouseout = new Function(this.n + '.hover(this,' + pos + ',0)');
				h.appendChild(q);
				if (this.ie) {
					q.unselectable = 'on'
				}
			}
		}
		
		this.t.parentNode.insertBefore(p, this.t);
		this.t.style.width = this.i.width + 'px';
		w.appendChild(this.t);
		w.appendChild(this.i);
		p.appendChild(w);
		this.t.style.display = 'none';
		if (a.footer) {
			var f = document.createElement('div');
			f.className = a.footerclass || 'tefooter';
			if (a.toggle) {
				var r = a.toggle,
					ts = document.createElement('div');
				ts.className = r.cssclass || 'toggle';
				ts.innerHTML = a.toggletext || 'source';
				ts.onclick = new Function(this.n + '.toggle(0,this);return false');
				f.appendChild(ts)
			}
			if (a.resize) {
				var s = a.resize,
					rs = document.createElement('div');
				rs.className = s.cssclass || 'resize';
				rs.onmousedown = new Function('event', this.n + '.resize(event);return false');
				rs.onselectstart = function () {
					return false
				};
				f.appendChild(rs)
			}
			p.appendChild(f)
		}
		this.e = this.i.contentWindow.document;
		this.e.open();
		var m = '<html><head>',
			bodyid = a.bodyid ? " id=\"" + a.bodyid + "\"" : "";
		if (a.cssfile) {
			m += '<link rel="stylesheet" href="' + a.cssfile + '" />'
		}
		if (a.css) {
			m += '<style type="text/css">' + a.css + '</style>'
		}
		m += '</head><body' + bodyid + '>' + (a.content || this.t.value);
		m += '</body></html>';
		this.e.write(m);
		this.e.close();
		this.e.designMode = 'on';
		this.d = 1;
		if (this.xhtml) {
			try {
				this.e.execCommand("styleWithCSS", 0, 0)
			} catch (e) {
				try {
					this.e.execCommand("useCSS", 0, 1)
				} catch (e) {}
			}
		}
	};
	edit.prototype.print = function () {
		this.i.contentWindow.print()
	}, edit.prototype.hover = function (a, b, c) {
		a.style.backgroundPosition = (c ? '34px ' : '0px ') + (b) + 'px'
	}, edit.prototype.ddaction = function (b, a) {
		var i = b.selectedIndex,
			v = b.options[i].value;
		this.action(a, v)
	}, edit.prototype.action = function (a, b, c) {
		if (c && !this.ie) {
			alert('Your browser does not support this function.')
		} else {
			this.e.execCommand(a, 0, b || null)
		}
	}, edit.prototype.insert = function (a, b, c) {
		var d = prompt(a, b);
		if (d != null && d != '') {
			this.e.execCommand(c, 0, d)
		}
	}, edit.prototype.setfont = function () {
		execCommand('formatblock', 0, hType)
	}, edit.prototype.resize = function (e) {
		if (this.mv) {
			this.freeze()
		}
		this.i.bcs = TINY.cursor.top(e);
		this.mv = new Function('event', this.n + '.move(event)');
		this.sr = new Function(this.n + '.freeze()');
		if (this.ie) {
			document.attachEvent('onmousemove', this.mv);
			document.attachEvent('onmouseup', this.sr)
		} else {
			document.addEventListener('mousemove', this.mv, 1);
			document.addEventListener('mouseup', this.sr, 1)
		}
	}, edit.prototype.move = function (e) {
		var a = TINY.cursor.top(e);
		this.i.height = parseInt(this.i.height) + a - this.i.bcs;
		this.i.bcs = a
	}, edit.prototype.freeze = function () {
		if (this.ie) {
			document.detachEvent('onmousemove', this.mv);
			document.detachEvent('onmouseup', this.sr)
		} else {
			document.removeEventListener('mousemove', this.mv, 1);
			document.removeEventListener('mouseup', this.sr, 1)
		}
	}, edit.prototype.toggle = function (b, c) {
		
		if (!this.d) {
			var v = this.t.value;
			if (c) {
				c.innerHTML = this.obj.toggletext || 'source'
			}
			if (this.xhtml && !this.ie) {
				v = v.replace(/<strong>(.*)<\/strong>/gi, '<span style="font-weight: bold;">$1</span>');
				v = v.replace(/<em>(.*)<\/em>/gi, '<span style="font-weight: italic;">$1</span>')
			}
			this.e.body.innerHTML = v;
			this.t.style.display = 'none';
			this.i.style.display = 'block';
			this.d = 1
		} else {
			var v = this.e.body.innerHTML;
			if (this.xhtml) {
				v = v.replace(/<[^>]*>/g, function (a) {
					return a.toLowerCase()
				});
				v = v.replace(/<span class="apple-style-span">(.*)<\/span>/g, '$1');
				v = v.replace(/ class="apple-style-span"/g, '');
				v = v.replace(/<span style="">/g, '');
				v = v.replace(/<br>/g, '<br />');
				v = v.replace(/<br ?\/?>$/g, '');
				v = v.replace(/^<br ?\/?>/g, '');
				v = v.replace(/(<img [^>]+[^\/])>/gi, '$1 />');
				v = v.replace(/<b\b[^>]*>(.*?)<\/b[^>]*>/g, '<strong>$1</strong>');
				v = v.replace(/<i\b[^>]*>(.*?)<\/i[^>]*>/g, '<em>$1</em>');
				v = v.replace(/<u\b[^>]*>(.*?)<\/u[^>]*>/g, '<span style="text-decoration:underline">$1</span>');
				v = v.replace(/<(b|strong|em|i|u) style="font-weight: normal;?">(.*)<\/(b|strong|em|i|u)>/g, '$2');
				v = v.replace(/<(b|strong|em|i|u) style="(.*)">(.*)<\/(b|strong|em|i|u)>/g, '<span style="$2"><$4>$3</$4></span>');
				v = v.replace(/<span style="font-weight: normal;?">(.*)<\/span>/g, '$1');
				v = v.replace(/<span style="font-weight: bold;?">(.*)<\/span>/g, '<strong>$1</strong>');
				v = v.replace(/<span style="font-style: italic;?">(.*)<\/span>/g, '<em>$1</em>');
				v = v.replace(/<span style="font-weight: bold;?">(.*)<\/span>|<b\b[^>]*>(.*?)<\/b[^>]*>/g, '<strong>$1</strong>')
			}
			if (c) {
				c.innerHTML = this.obj.toggletext || 'wysiwyg'
			}
			this.t.value = v;
			if (!b) {
				this.t.style.height = this.i.height + 'px';
				this.i.style.display = 'none';
				this.t.style.display = 'block';
				this.d = 0
			}
		}
	}, edit.prototype.post = function () {
		if (this.d) {
			this.toggle(1)
		}
	};
	return {
		edit: edit
	}
}();
TINY.cursor = function () {
	return {
		top: function (e) {
			return T$$$() ? window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop : e.clientY + window.scrollY
		}
	}
}();