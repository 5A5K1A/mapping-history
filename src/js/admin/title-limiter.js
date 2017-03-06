/*
 * Based upon Limit Post Titles (by steve228uk)
 * GitHub: https://github.com/steve228uk/Limit-Post-Titles
 * Donate link: http://stephenradford.me/restricting-post-titles-in-wordpress-with-a-character-limit
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Limit posttitle for certain posttypes
 * Limit for: Theme
 */

(function(){
	var Limiter = function(){
		var limiter = {
			limit: 26,
			isAdmin: function() {
				// only limit posttitles for posttype theme
				this.admin = document.querySelector('.wp-admin.post-type-theme');
				if(this.admin) this.bootstrap();
			},
			bootstrap: function(){
				this.el = document.getElementById('titlewrap');
				this.submitBtn = document.getElementById('publish');
				this.postForm = document.getElementById('post');
				if(this.el) this.appendCounter();
			},
			appendCounter: function(){
				this.counter = document.createElement('span');
				this.counter.appendChild(document.createTextNode(''));
				this.counter.id = 'od-title-limiter';
				this.el.appendChild(this.counter);
				this.titleInput = this.el.getElementsByTagName('input')[0];
				this.titleInput.size = this.limit;
				this.run();
			},
			setCounter: function(){
				this.counter.childNodes[0].nodeValue = this.getLength();
				this.setClasses();
			},
			setClasses: function(){
				if(parseInt(this.getLength(), 10) < 0){
					this.counter.style.color = 'red';
					this.submitBtn.className = 'button button-primary-disabled button-large';
				} else {
					this.counter.style.color = '#999';
					this.submitBtn.className = 'button button-primary button-large';
				}
			},
			getLength: function(){
				return this.limit-this.titleInput.value.length;
			},
			checkLimit: function(e){
				if(this.getLength() < 0){
					window.alert('De titel kan niet langer zijn dan '+this.limit+' karakters.');
					e.stopImmediatePropagation();
					e.preventDefault();
					return false;
				}
			},
			run: function(){
				this.setCounter();
				this.titleInput.addEventListener('keyup', this.setCounter.bind(this));
				this.submitBtn.addEventListener('click', this.checkLimit.bind(this));
				this.postForm.addEventListener('submit', this.checkLimit.bind(this));
			}
		};
		this.init = function(){
			limiter.isAdmin();
		};
	};
	// push our script to the end of the callstack
	var limiter = new Limiter();
	window.setTimeout(limiter.init, 0);
})();