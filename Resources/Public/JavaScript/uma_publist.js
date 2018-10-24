$.expr[":"].contains = $.expr.createPseudo(function(arg) {
	return function(elem) {
		return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
	};
});

$(document).ready(function() {
	var updateUmaPublist = function(content, searchWord) {
		searchWord = searchWord.replace(/'/g, '');
		$(this).find('li').addClass('hidden').filter(":contains('" + searchWord + "')").removeClass('hidden');
	};
	$('.uma-publist-filter').on('keyup', function() {
		updateFilter($('#' + $(this).attr('data-publist-content')), $(this).val());
	});
	$('.uma-publist-hidden').hide();
	$('.uma-publist-expand').on('click', function() {
		var publist = $(this).closest('.uma-publist');
		publist.find('.uma-publist-hidden').show();
		publist.find('.uma-publist-expand').remove();
	});
});
