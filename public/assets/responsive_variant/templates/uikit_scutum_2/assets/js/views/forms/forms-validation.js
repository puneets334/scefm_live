/*
*  Scutum Admin_template
*  @version v1.0.1
*  @author tzd
*  @license http://themeforest.net/licenses

*  used in: forms-forms-validation.html
*/

scutum.forms.validation = {};
scutum.forms.validation.init = function () {
	var $form = $('#sc-js-form');
	scutum.require(['parsleyJS'], function () {
		$form.parsley().on('form:validated', function (ParsleyForm) {
			$form.find('[data-sc-input]').trigger('validationClassChanged');
		});
	})
};
