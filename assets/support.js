$(document).ready(function(){
	
	/**
	*	init visible classes array
	**/
	var filteredBy = new Object();
	$(".sort-toggle").each(function(i){
		var tmp = $(this).attr("id");
		filteredBy[tmp] = false;
	});
	
	//make category array for each grid object
	var allPostFilters = new Object();
	$('.grid-wrapper').each(function(i,val){
		var classArray = new Object();
		var classes = $(this).attr('class').split(/\s+/);
		classArray['dom'] = $(this);
		for(var j = 0 ; j < classes.length ; j++){
			if(classes[j] == 'grid-wrapper') j++; //skip the grid-wrapper class
			classArray[classes[j]] = $(this).hasClass(classes[j]);
		}
		allPostFilters[i] = classArray;
		//console.log(val);
	});
	
	
	//init bold sidebar
	refreshFilterStates();
	
//image grid rollovers	
	var rolloverDim = 200;
	$('.grid-wrapper').mouseover(function() {
		var dom = $(this).find("a");
		var imgURL = dom.attr("data-image-url");
		var title = dom.attr("data-title");
		var excerpt = dom.attr("data-excerpt");
		var mediaCount = dom.attr("data-media-count")
		
		$("#rollover-image").find("img").attr("src", imgURL)
		$("#rollover #project-name").empty().append(title);
		$("#rollover h4").empty().append(mediaCount+" media objects");
		$("#rollover p").empty().append(excerpt);
		
	});

//deep linking
	
	$('.sort-only').address();
	
	$.address.change(function(event){
		var uri = event.value;
		$.getJSON(uri,function(data){
			console.log(uri);
		});
	});




//search box stuff
	$('#media-toggle').toggle(
		function(){
			$(this).text('hide media links');
			$('#hidden-links').show('fast');
		},
		function(){
			$(this).text('show media links');
			$('#hidden-links').hide('fast');		
	});
		
	//add 'search to input box automatically
	//if($( "input:text" ).val() == ""){
		$("input:text").val('search').attr('style',"color:#ccc");
	//}
	//when field is clicked, clear any text already inside
	$( "input:text" ).focus(function(){
		if ($( "input:text" ).val() == "search") $("input:text").val("").attr('style',"color:#333");
	});
	//when field loses focus add 'search' only if field is empty
	$( "input:text" ).focusout(function(){
		if ($( "input:text" ).val() == "") $("input:text").val("search").attr('style',"color:#ccc") ;
	});
	
	/**
	*	dynamic post filtering
	**/
	$(".sort-toggle").click(function(){
			var sortID = $(this).attr('id');
		
			filteredBy[sortID] = !filteredBy[sortID];
		
			refactor();
			refreshFilterStates();
	});
	
	
	/**
	*	when you click on the category show all related posts (hide others)
	**/	
	$(".sort-only").click(function(){
		var sortID = $(this).attr("id").split(/sort-only-/)[1];
		
		$.each(filteredBy, function(k,v){
			filteredBy[k] = false;
		});
		filteredBy[sortID] = true;
		
		refactor();
		refreshFilterStates();
	});
	
	/**
	*	when you click on the show all button show all  posts
	**/	
	$("#sort-all-on").click(function(){
		
		$.each(filteredBy,function(key,value){
			filteredBy[key]=false;
		});
		
		refactor();
		refreshFilterStates();
	});
	/**
	*	when you click on the category hide all posts
	**/
	$("#sort-all-off").click(function(){
		jQuery.each(filteredBy,function(key,val){
			filteredBy[key] = true;
		});
		
		refactor();
		refreshFilterStates();
	});
	
	$(".sort-only").hover(
		function(){
			var activeClass = $(this).attr("id").split(/sort-only-/)[1];
			$('.grid-wrapper:not(.'+activeClass+')').css('opacity',"0.15");
		},
		function(){
			var activeClass = $(this).attr("id").split(/sort-only-/)[1];
			$('.grid-wrapper:not(.'+activeClass+')').css('opacity',"1");
		}
	);
	
	//maintains the sidebar list bold states
	function refreshFilterStates(){
		$.each(filteredBy,function(key,val){
			if(filteredBy[key]){
				$("#sort-only-"+key).css("font-weight","bold");
				$("#sort-only-"+key).css("color","");
				$("#"+key).text("[–]");
			}else{
				$("#sort-only-"+key).css("font-weight","normal");	
				$("#sort-only-"+key).css("color","#aaa");
				$("#"+key).text("[+]");
			}
		});
	}
	
	//determines which posts should be showing
	function refactor(){
		$.each(allPostFilters,function(i, val){
			var flag = true;
			$.each(filteredBy,function(j,v){
				if(v != "" && allPostFilters[i][j] != v){
					flag = false;
					return false;
				}
			})
			//console.log(flag);
			if(flag){
				allPostFilters[i]['dom'].show();
			}else{
				allPostFilters[i]['dom'].hide();
			}
		})
	}
	
});