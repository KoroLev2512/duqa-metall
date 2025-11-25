function Modal() {
	this.config = {
		modal: document.querySelector('#modal-description'),
		content: document.querySelector('.description-modal__text'),
		openTag: document.querySelectorAll('.webcomp-settings__field-description'),
		closeTag: document.querySelector('.description-modal__close'),
		activeClass: 'active',
	};
}

Modal.prototype.init = function () {
	if(this.config.modal) {
		if(this.config.openTag.length) {
			this.config.openTag.forEach(elem => {
				elem.addEventListener('click', () => {
					const content = elem.nextElementSibling.innerHTML;
					this.open(content);
				});
			});
		}

		this.config.closeTag.addEventListener('click', () => {
			this.close();
		});
	}

	this.open = function (content) {
		this.config.content.innerHTML = content;
		this.config.modal.classList.add(this.config.activeClass);
	}

	this.close = function () {
		this.config.modal.classList.remove(this.config.activeClass);
		this.config.content.innerHTML = "";
	}
}


function Tab() {
	this.config = {
		tabList: document.querySelectorAll('.webcomp-settings__item'),
		contentList: document.querySelectorAll('.webcomp-settings__content'),
		activeClass: 'active',
		activeTab: null,
		activeContent: null,
		dataTabId: "tab",
		dataContentId: "content"
	};
}

Tab.prototype.init = function () {
	if(this.config.tabList.length) {
		this.config.tabList.forEach(elem => elem.addEventListener('click', () => {
			this.changeTab(elem);
		}));
	}

	this.setTab = function (currentElem) {
		this.config.tabList.forEach(elem => {
			this.removeClass(elem);
		});

		currentElem.classList.add(this.config.activeClass);
		this.activeTab = currentElem;
	}

	this.setContent = function (activeTab) {
		const target = activeTab.dataset[this.config.dataTabId];
		this.config.contentList.forEach(elem => {
			this.removeClass(elem);

			if(elem.dataset[this.config.dataContentId] === target) {
				elem.classList.add(this.config.activeClass);
				this.activeContent = elem;
			}
		});
	}

	this.removeClass = function (elem) {
		if(elem.classList.contains(this.config.activeClass)) {
			elem.classList.remove(this.config.activeClass);
		}
	}

	this.changeTab = function (elem) {
		if(this.config.contentList.length) {
			this.setTab(elem);
			this.setContent(elem);
		}
	}
}


$(function () {
	const addNewLine = function () {
		let _this = $(this),
			parent = _this.closest("tr"),
			container = parent.prev().find("td").last(),
			target = parent.prev().find(".adm-detail-input-row").last();

		let newElem = target.clone(true);

		let newInput = newElem.find("input");

		newInput.attr("value", "");

		// append new element
		newElem.appendTo(container);
	};

	const removeLine = function () {
		let _this = $(this),
			target = _this.parent(".adm-detail-input-row");

		target.remove();
	};

	$("body").on("click", ".js-add-newLine", addNewLine);
	$("body").on("click", ".adm-multiple_remove", removeLine);

	const btnsUp = document.querySelectorAll('.admin-section__btn--up');
	const btnsDown = document.querySelectorAll('.admin-section__btn--down');

	btnsUp.forEach(function (i) {
		i.addEventListener('click', function (e) {
			const item = this.closest('.admin-section');
			const prevItem = item.previousElementSibling;
			if (prevItem) {
				prevItem.before(item);
			}
		});
	});

	btnsDown.forEach(function (i) {
		i.addEventListener('click', function (e) {
			const item = this.closest('.admin-section');
			const nextItem = item.nextElementSibling;
			if (nextItem) {
				nextItem.after(item);
			}
		});
	});

	const seoCheck =  document.querySelector('.seo-check input');
	const seoCheckFields =  document.querySelectorAll('.seo-check__hidden');

	if(seoCheckFields.length) {
		seoCheckFields.forEach((i)=>setVisibility(!seoCheck.checked,i))

		seoCheck.addEventListener('change', function (e){
			seoCheckFields.forEach((i)=>setVisibility(!this.checked,i))
		});
	}

	function setVisibility(hidden, item){
		if (hidden){
			item.classList.add("hidden");
		} else{
			item.classList.remove("hidden");
		}
	}

	// init Tab function
	new Tab().init();
	new Modal().init();
});

