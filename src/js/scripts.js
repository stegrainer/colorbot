function colorbot() {
	var convertButton = document.querySelector("[name='convert']");
	convertButton.disabled = true;

	var loadImg = function(src,alt,id) {
		var image = document.createElement('img');
		var frame = document.getElementById(id);
		image.setAttribute('alt',alt);
		image.setAttribute('src',src);
		frame.appendChild(image);
		frame.className += 'loaded';
	}

	var share = document.getElementById('share');
	if(share) {
		share.onclick = function() {
			var link = this.getAttribute("href");
			window.prompt("Copy to clipboard: Ctrl or Cmd + C, then Enter", link);
			return false;
		};
	}

	var hasClass = function(elem,c) {
		return (" " + elem.className + " " ).indexOf( " "+c+" " ) > -1;
	}
	
	var picker = document.getElementsByClassName('picker');
	var logo = '';
	if(hasClass(picker[0],'white')) {
		logo = 'colorbot-w.svg';
	} else {
		logo = 'colorbot-b.svg';
	}
	loadImg('-/img/'+logo,'Hello, I&rsquo;m ColorBot','logo');
	
	var clrLinks = document.getElementsByClassName('warn');
	for(var i=0; i<clrLinks.length; i++) {
		clrLinks[i].onclick = function() {
			if(hasClass(this,'remove')) {
				var q = 'Are you sure you want to remove this color from your palette?\nClicking OK will remove it.';
			} else {
				var q = 'Are you sure you want to clear your entire palette?\nClicking OK will clear it.';
			}
			return confirm(q);
		};
	}
	
	var webfont = document.querySelector("[rel='preload']");
	webfont.rel = 'stylesheet';
	
	var colorInputs = document.querySelectorAll("#picker input[type='text']");
	for(i=0; i<colorInputs.length; i++) {
		colorInputs[i].onchange = function() {
			var current = this.value;
			var mode = this.id;
			var original = document.querySelector("[name='"+mode+"-o']").value;
			if(current != original) {
				this.className += " changed";
				convertButton.disabled = false;
			}
		}
	}
}

window.onload = colorbot;