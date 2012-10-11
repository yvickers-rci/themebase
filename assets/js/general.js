$.fn.dropdown = function() {
	$(this).hover(function(){
		$(this).addClass("hover");
		$('> .menu-item',this).addClass("open");
		$('ul:first',this).css('display', 'block');
	},function(){
		$(this).removeClass("hover");
		$('.open',this).removeClass("open");
		$('ul:first',this).css('display', 'none');
	});
};

$(document).ready(function(){
	// Start Dropdown Menu
	$(".nav-header .menu li").dropdown();

	// File Types
	$("a[href$='.pdf']").addClass("pdf");
	$("a[href$='.doc'], a[href$='.docx']").addClass("doc");
	$("a[href$='.xls'], a[href$='.xlsx']").addClass("xls");
	$("a[href$='.ppt']").addClass("ppt");
 
	// Detect external links and add appropriate class
	$('a').filter(function() {
		//Compare the anchor tag's host name with location's host name
		return this.hostname && this.hostname !== location.hostname;
	 }).addClass("external").attr("target", "_blank");

	// Gravity Forms disabled / readonly inputs
	$(".gform_wrapper .disabled").attr('disabled','disabled');
	$(".gform_wrapper .readonly").attr('readonly','readonly');
});