/*
*  Scutum Admin_template
*  @version v1.0.1
*  @author tzd
*  @license http://themeforest.net/licenses

*  used in: pages-chat.html
*/

var $chat = $('#sc-chat');
var $submit = $('#sc-chat-message-submit');
var $input = $('#sc-chat-message-input');
var messages = [];

scutum.pages.chat = {};
scutum.pages.chat.init = function () {
	$chat.addClass('sc-light');
	scutum.pages.chat.message.fillChat();
	scutum.pages.chat.message.send();
	scutum.pages.chat.users();
};
scutum.pages.chat.message = {
	'fillChat': function () {
		scutum.require(['handlebars'], function () {
			$.when(
				scutum.handlebars.getPartial('chat-message'),
				scutum.helpers.ajax('data/pages/chat_messages.json', 'GET', 'json')
			).done(function (p, result) {
				// resgister partial
				Handlebars.registerPartial('chat-message', p);
				// get messages
				var delayedMessages = [];
				$.each(result, function (index, item) {
					if (index < 4) {
						messages.push(item);
					} else {
						delayedMessages.push(item);
					}
				});

				$chat
					.one('render.handlebars', function (template, data) {
						scutum.helpers.hiResImagesProcess();
					}).hbRender('chat-messages', {
						messages: messages
					});

				// update chat
				setTimeout(function () {
					$chat
						.one('render.handlebars', function (template, data) {
							$chat.find('.sc-sequence-show.sc-chat-messages-wrapper').each(function (index) {
								var $this = $(this);
								var delay = 200 * (index + 1);
								setTimeout(function () {
									scutum.helpers.sequenceShow.activate($this, 'uk-animation-slide-left');
									scutum.helpers.hiResImagesProcess();
								}, delay);
							})
						}).hbRender('chat-messages', {
							messages: delayedMessages,
							delayed: true
						}, true);
				}, 1000);
			});
		}, false)
	},
	'send': function () {
		function sendMessage () {
			var message = scutum.helpers.escapeHTML($input.val());
			var $last = $chat.find('.sc-chat-messages-wrapper').last();
			var callback = function () {
				$input.val('');
				if ($chat.parent('.optiscroll-content').length) {
					// $chat.parent('.optiscroll-content').scrollTop($chat[0].scrollHeight);
					$chat.closest('[data-sc-scrollbar]').data('optiscroll').scrollIntoView($last.find('.sc-chat-messages').children('li:last'), 280, 20);
				} else {
					$chat.closest('.uk-card-body').scrollTop($chat[0].scrollHeight);
				}
			};
			if (message !== '') {
				if ($last.hasClass('my')) {
					var last_message_time = $last.find('[data-message-time]:last').find('time').text();
					var time = moment().format('HH:mm');
					$last.find('.sc-chat-messages')
						.one('render.handlebars', function (template, data) {
							scutum.helpers.sequenceShow.activate($last.find('.sc-sequence-show').last(), 'uk-animation-slide-right');
							callback();
						})
						.hbRender('chat-message', {
							"content": message,
							"time": last_message_time !== time ? moment().format('HH:mm') : "",
							animated: true
						}, true);
				} else {
					$chat
						.one('render.handlebars', function (template, data) {
							scutum.helpers.sequenceShow.activate($chat.find('.sc-sequence-show').last(), 'uk-animation-slide-right');

							callback();
						}).hbRender('chat-messages', {
							messages: [
								{
									"id": scutum.helpers.uniqueID(),
									"own": true,
									"messages": [
										{
											"content": message,
											"time": moment().format('HH:mm')
										}
									]
								}
							],
							delayed: true
						}, true);
				}
			}
		}

		$submit.on('click', function (e) {
			e.preventDefault();
			sendMessage()
		});

		$input.on('keyup', function (e) {
			var char = event.which || event.keyCode;
			if (char === 13) {
				sendMessage();
			}
		});
	}
};
scutum.pages.chat.users = function () {
	var $list = $('.sc-js-chat-users-list');
	if ($list.length) {
		$list.children('li').on('click', function () {
			console.log('new chat window');
		});
	}
};
