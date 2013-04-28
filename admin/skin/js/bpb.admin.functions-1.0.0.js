// BPB CMS Admin Core JS

$(document).ready(function() {
	
	// Set up easy alternating row colours for tables
	$(".cms-table tbody tr:even").addClass("even");
	$(".cms-table tbody tr:odd").addClass("odd");

	// Easy table rollovers										
	$(".cms-table tbody tr").hover(
      function () {
		$(this).addClass("hover");
      }, 
      function () {
		$(this).removeClass("hover");
      }
    );

	
	
});