/*
*  Scutum Admin_template
*  @version v1.0.1
*  @author tzd
*  @license http://themeforest.net/licenses

*  used in: components-buttons.html
*/

scutum.components.buttons = function () {
	$('.sc-js-button-replace-text').on('click', function (e) {
		e.preventDefault();
		scutum.helpers.button.replaceContent($(this), 'New replaced text <i class="mdi mdi-emoticon-happy uk-margin-small-left"></i>', true, 'sc-button-flex');
	});
	$('.sc-js-button-loading').on('click', function (e) {
		e.preventDefault();
		var $this = $(this);
		scutum.helpers.button.showLoading($this);
		setTimeout(function () {
			scutum.helpers.button.hideLoading($this);
		}, 2000)
	})
};
