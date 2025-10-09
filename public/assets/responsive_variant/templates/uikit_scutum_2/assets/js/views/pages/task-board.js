/*
*  Scutum Admin_template
*  @version v1.0.1
*  @author tzd
*  @license http://themeforest.net/licenses

*  used in: pages-task-board.html
*/

var $taskBoard = $('#sc-task-board');

scutum.pages.taskBoard = {};
scutum.pages.taskBoard.init = function () {
	// lists drag&drop
	scutum.pages.taskBoard.listsDragDrop();
	// cards drag&drop
	scutum.pages.taskBoard.cardsDragDrop();
	// task modal
	scutum.pages.taskBoard.taskModal();
	// list shrink
	scutum.pages.taskBoard.list.shrink();
	// list expand
	scutum.pages.taskBoard.list.expand();
};
scutum.pages.taskBoard.cardsDragDrop = function () {
	scutum.require(['dragula'], function () {
		var drake = dragula($('.sc-task-list-cards').toArray());

		var containers = drake.containers;
		var length = containers.length;

		for (var i = 0; i < length; i++) {
			$(containers[i]).addClass('dragula dragula-vertical');
		}

		drake.on('drag', function (el, source) {
			$(el).addClass('sc-mirror-el');
		});
		drake.on('dragend', function (el) {
			$(el).removeClass('sc-mirror-el');
		});

		drake.on('drop', function (el, target, source, sibling) {
			console.log(el);
			console.log(target);
			console.log(source);
		})
	});
};
scutum.pages.taskBoard.listsDragDrop = function () {
	scutum.require(['dragula'], function () {
		var drake = dragula($('.sc-task-board').toArray(), {
			direction: 'horizontal',
			moves: function (el, container, handle) {
				return $(handle).closest('.dragula-handle-el').length;
			}
		});

		var containers = drake.containers;
		var length = containers.length;

		for (var i = 0; i < length; i++) {
			$(containers[i]).addClass('dragula dragula-handle');
		}

		drake.on('drop', function (el, target, source, sibling) {
			console.log(el);
			console.log(target);
			console.log(source);
		})
	});
};
scutum.pages.taskBoard.list = {
	'shrink': function () {
		$taskBoard.on('click', '.sc-js-list-collapse', function (e) {
			e.preventDefault();
			var $list = $(this).closest('.sc-task-list');
			$list.addClass('sc-task-list-collapsed');
			scutum.helpers.hideDuringTransform($list);
		})
	},
	'expand': function () {
		$taskBoard.on('click', '.sc-js-list-expand', function (e) {
			e.preventDefault();
			var $list = $(this).closest('.sc-task-list');
			$list.removeClass('sc-task-list-collapsed');
			scutum.helpers.hideDuringTransform($list);
		})
	}
};
scutum.pages.taskBoard.taskModal = function () {
	scutum.require(['handlebars'], function () {
		var $modal = $('#sc-task-modal');
		var $modal_hb_content = $('#sc-hb-task-modal');
		var tasks = [];
		// init modal component
		var task_modal = UIkit.modal($modal);
		// get tasks
		scutum.helpers.ajax('data/pages/tasks.json', 'GET', 'json').then(function (result) {
			tasks = result;
		}).catch(function (error) { console.log('There has been a error: ' + error.message); throw error; });
		scutum.$body.on('click', '.sc-task-card', function () {
			var task_id = $(this).attr('data-task-id');
			if (task_id) {
				scutum.helpers.findObjectByKey(tasks, 'id', parseInt(task_id)).then(function (response, reject) {
					if (response) {
						// modal content
						$modal_hb_content.one('render.handlebars', function (template, data) {
							task_modal.show();
						}).hbRender('task-modal', {
							title: response.title,
							date: response.date,
							list: response.list_long,
							description: response.description,
							tags: response.tags,
							assignee: response.assignee,
							progress: response.progress
						});
					}
				});
			}
		});
	}, false);
};
