/*
*  Scutum Admin_template
*  @version v1.0.1
*  @author tzd
*  @license http://themeforest.net/licenses

*  used in: pages-contact_list.html
*/

scutum.pages.contactList = {};
scutum.pages.contactList.init = function () {
	var $list = $("#sc-contact-list");
	scutum.require(['listnav'], function () {
		$list.listnav({
			noMatchText: 'No matching contacts',
			onAfterFilter: function (letter) {
				console.log('Letter changed to ' + letter);
			}
		});
	});
};
