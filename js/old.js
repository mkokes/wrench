

jQuery(function($) {
$(document).ready(function () {
	$(calculate());
    $('.math').keyup(calculate);
});

function calculate() {
    var intratio = parseInt($('#intgear1').val()) / parseInt($('#intgear2').val());
    var extratio = parseInt($('#extgear1').val()) / parseInt($('#extgear2').val());
    var wheel = parseInt($('#wheel').val()) * 3.14;
    var mph60 = ((intratio * extratio * 6000 * wheel) / 63360) * 60;
	var mph65 = ((intratio * extratio * 6500 * wheel) / 63360) * 60;
	var mph70 = ((intratio * extratio * 7000 * wheel) / 63360) * 60;
	var mph75 = ((intratio * extratio * 7500 * wheel) / 63360) * 60;
	var mph80 = ((intratio * extratio * 8000 * wheel) / 63360) * 60;
	var mph85 = ((intratio * extratio * 8500 * wheel) / 63360) * 60;
	var mph90 = ((intratio * extratio * 9000 * wheel) / 63360) * 60;
	var mph95 = ((intratio * extratio * 9500 * wheel) / 63360) * 60;
	var mph100 = ((intratio * extratio * 10000 * wheel) / 63360) * 60;
	var mph105 = ((intratio * extratio * 10500 * wheel) / 63360) * 60;
	var mph110 = ((intratio * extratio * 11000 * wheel) / 63360) * 60;
	var mph115 = ((intratio * extratio * 11500 * wheel) / 63360) * 60;
	var mph120 = ((intratio * extratio * 12000 * wheel) / 63360) * 60;
	var mph125 = ((intratio * extratio * 12500 * wheel) / 63360) * 60;
	
    $('#intratio').text('Internal Ratio = ' + intratio.toFixed(5));
    $('#extratio').text('External Ratio = ' + extratio.toFixed(5));
    $('#radius').text('Tire Radius = ' + wheel.toFixed(2) + ' Inches');
    $('#mph60').text(' = ' + mph60.toFixed(2) + ' MPH');
    $('#mph65').text(' = ' + mph65.toFixed(2) + ' MPH');
    $('#mph70').text(' = ' + mph70.toFixed(2) + ' MPH');
    $('#mph75').text(' = ' + mph75.toFixed(2) + ' MPH');
    $('#mph80').text(' = ' + mph80.toFixed(2) + ' MPH');
    $('#mph85').text(' = ' + mph85.toFixed(2) + ' MPH');
    $('#mph90').text(' = ' + mph90.toFixed(2) + ' MPH');
    $('#mph95').text(' = ' + mph95.toFixed(2) + ' MPH');
    $('#mph100').text(' = ' + mph100.toFixed(2) + ' MPH');
    $('#mph105').text(' = ' + mph105.toFixed(2) + ' MPH');
    $('#mph110').text(' = ' + mph110.toFixed(2) + ' MPH');
    $('#mph115').text(' = ' + mph115.toFixed(2) + ' MPH');
    $('#mph120').text(' = ' + mph120.toFixed(2) + ' MPH');
}


