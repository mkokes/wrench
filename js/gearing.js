jQuery(document).ready(function($) {
	$.getJSON('/wp-content/themes/wrench/js/gearing.json', function(data) {

		var uniq_man = [];
		
		$.each(data, function() {
			if ($.inArray(data, uniq_man)==-1) {
				uniq_man.push(this.manufacturer);
			}
			return uniq_man;
		});
		
		
		var options = $("#manufacturer");
		$.each(data, function() {
			options.append($("<option />").val(this.manufacturer).text(this.manufacturer));
		});
	});
});