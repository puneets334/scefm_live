/*
*  Scutum Admin_template
*  @version v1.0.1
*  @author tzd
*  @license http://themeforest.net/licenses

*  used in: pages-settings.html
*/

scutum.pages.settings = {};
scutum.pages.settings.init = function () {
	// flags (select2 countries)
	scutum.require(['flagsCSS'], function () {});
	// colorpicker
	scutum.plugins.colorpicker.init();
};
