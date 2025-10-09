/*
*  Scutum Admin_template
*  @version v1.0.1
*  @author tzd
*  @license http://themeforest.net/licenses

*  used in: pages-mailbox.html
*/

scutum.pages.mailbox = {};
scutum.pages.mailbox.init = function () {
	// messages actions
	scutum.pages.mailbox.message.show();
	scutum.pages.mailbox.message.close();
	scutum.pages.mailbox.message.remove();
	scutum.pages.mailbox.message.filter();
};
scutum.pages.mailbox.message = {};
scutum.pages.mailbox.message.show = function () {
	$('.sc-js-message-expand').find('.sc-message-card').each(function () {
		$(this).on('click', function (e) {
			var $content = scutum.$pageContent.find('.optiscroll-content').length ? scutum.$pageContent.find('.optiscroll-content') : scutum.$pageContent;
			if (!$(e.target).closest('.sc-js-item-check').length && !$(e.target).closest('.sc-message-actions').length) {
				var $message = $(this);
				var height = $message.outerHeight();
				if ($message.hasClass('sc-message-expanded')) {
					return;
				}
				scutum.$pageContent
					.addClass('sc-page-overflow')
					.css({'padding-right': scutum.helpers.scrollbar.width()});
				scutum.helpers.overlay.add($content, true, scutum.helpers.mq.mediumMax() ? 0 : $content.scrollTop());
				if (scutum.helpers.mq.mediumMax()) {
					scutum.helpers.scrollbar.disable(true);
				}
				$message.addClass('sc-message-expanded').velocity({
					top: $content.scrollTop() + 12,
					height: '100%'
				}, {
					duration: 560,
					easing: scutum.easingSwiftOut,
					begin: function () {
						$message.closest('li').css({
							height: height,
							overflow: 'hidden'
						});
						scutum.helpers.hideDuringTransform($message, 560, $message.find('.sc-message-head'));
						$message.find('.sc-js-el-hide').hide();
						$message.find('.sc-js-el-show').show().velocity("transition.slideUpBigIn", { stagger: 280 });
						scutum.$pageWrapper.addClass('sc-message-single');
					}
				});
			}
		})
	})
};
scutum.pages.mailbox.message.close = function () {
	scutum.$doc.on('click', '.sc-js-message-close', function (e) {
		e.preventDefault();
		console.log(!$(e.target).closest('.sc-js-item-check').length && !$(e.target).closest('.sc-message-actions').length);
		if (!$(e.target).closest('.sc-js-item-check').length && !$(e.target).closest('.sc-message-actions').length) {
			var $content = scutum.$pageContent.find('.optiscroll-content').length ? scutum.$pageContent.find('.optiscroll-content') : scutum.$pageContent;
			var $message = $('.sc-message-card.sc-message-expanded');

			$message.find('.sc-js-el-hide').show();
			$message.find('.sc-js-el-show').hide();

			$message.velocity('reverse', {
				begin: function () {
					scutum.$pageWrapper.removeClass('sc-message-single');
					if (scutum.helpers.mq.mediumMax()) {
						scutum.helpers.scrollbar.enable(true);
					}
				},
				complete: function () {
					scutum.helpers.overlay.remove($content, true);
					$message
						.removeClass('sc-message-expanded')
						.attr('style', '');
					setTimeout(function () {
						scutum.$pageContent
							.removeClass('sc-page-overflow')
							.css({'padding-right': ''});
						$message.closest('li').css({
							height: '',
							overflow: ''
						});
					}, 320);
				}
			});
		}
	})
};
scutum.pages.mailbox.message.remove = function () {
	$('.sc-js-message-remove').on('click', function (e) {
		e.preventDefault();
		var $message = $(this).closest('li');
		$message.velocity(
			{
				'translateX': '100%',
				'opacity': 0
			},
			{
				duration: 280,
				easing: scutum.easingSwiftOut,
				complete: function () {
					$message.remove();
				}
			}
		)
	})
};
scutum.pages.mailbox.message.filter = function () {
	var $filterEl = scutum.$pageWrapper;
	var filter = UIkit.filter($filterEl, {
		'target': '.sc-js-message-filter'
	});
	var $filterClear = $('.sc-js-filter-clear');
	if ($filterClear.length) {
		// fires after the filter has been applied
		scutum.$pageWrapper.on('afterFilter', function () {
			var state = filter.getState();
			if (typeof state.filter[""] === 'undefined') {
				$filterEl.removeClass('sc-filtered-items');
			} else {
				$filterEl.addClass('sc-filtered-items');
			}
		});
	}
	$('.sc-js-offcanvas-filters').find('li').on('click', function () {
		var filter = $(this).attr('data-uk-filter-control');
		$('.sc-js-offcanvas-filter')
			.attr('data-uk-filter-control', filter)
			.click();
	});
};
