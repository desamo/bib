/**
 * 
 */
 (function() {
    function checkCondition(v1, operator, v2) {
        switch(operator) {
            case '==':
                return (v1 == v2);
            case '===':
                return (v1 === v2);
            case '!==':
                return (v1 !== v2);
            case '<':
                return (v1 < v2);
            case '<=':
                return (v1 <= v2);
            case '>':
                return (v1 > v2);
            case '>=':
                return (v1 >= v2);
            case '&&':
                return (v1 && v2);
            case '||':
                return (v1 || v2);
            default:
                return false;
        }
    }

    Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
        return checkCondition(v1, operator, v2)
                    ? options.fn(this)
                    : options.inverse(this);
    });
}());
 function show_leser(leserid){
	
 	
 	$('.leser').remove() ;
 	

	 	 $.ajax({
					type: 'GET',
					url: "./api/leser/"+leserid,
					dataType: "json",
					
					success: function(response){
						
											
 			   			var template = Handlebars.compile($('#leser_template').html());
					    var html = template(response);
					    
					    $('#Inhalt').append(html);
					    show_books_from_leser(leserid)
						
											
					},
					error: function(jqXHR, textStatus, errorThrown){
						alert(jqXHR.responseText);
					}


				});


 	};
 	function show_books_from_leser(leserid){
	
 	console.log(leserid);
 	$('.buch').remove() ;
 	 $(".showpages").remove();
 	 $(".Trefferanzeige").remove();

	 	 $.ajax({
					type: 'GET',
					url: "./api/books_from_leser/"+leserid,
					dataType: "json",
					
					success: function(response){
						
									
						
						 anzahl = parseInt(response.info.anzahl) ;
						 
								
								
						 $('#Inhalt').append('<a class="Trefferanzeige"> Ausgeliehene Bücher  - <span id="anzahl_buecher_'+ leserid + '">' + anzahl +'</span>  - </a>');
			
		 	     			var $template = $('#buch_template').html() ;
	          						
      						var template = Handlebars.compile($template);
								
						 	$.each(response.data, function(i, item){
									
						 			if (item.Ausleihdatum) item.Ausleihdatum =$.date(Date.parse(item.Ausleihdatum));
						 			if (item.Rückgabedatum) item.Rückgabedatum =$.date(Date.parse(item.Rückgabedatum));
			 					
			 							    					      		
					     		var html = template(item);
					      		
					     		$('#Inhalt').append(html);


			 				
							 })		

											
							
						console.log(response) ;



						
					},
					error: function(jqXHR, textStatus, errorThrown){
						alert(jqXHR.responseText);
					}


				});


 	};
 jQuery.fn.extend({
/**
* Returns get parameters.
*
* If the desired param does not exist, null will be returned
*
* To get the document params:
* @example value = $(document).getUrlParam("paramName");
* 
* To get the params of a html-attribut (uses src attribute)
* @example value = $('#imgLink').getUrlParam("paramName");
*/ 

 getUrlParam: function(strParamName){
	  strParamName = escape(unescape(strParamName));
	  
	  var returnVal = new Array();
	  var qString = null;
	  
	  if ($(this).attr("nodeName")=="#document") {
	  	//document-handler
		
		if (window.location.search.search(strParamName) > -1 ){
			
			qString = window.location.search.substr(1,window.location.search.length).split("&");
		}
			
	  } else if ($(this).attr("src")!="undefined") {
	  	
	  	var strHref = $(this).attr("src")
	  	if ( strHref.indexOf("?") > -1 ){
	    	var strQueryString = strHref.substr(strHref.indexOf("?")+1);
	  		qString = strQueryString.split("&");
	  	}
	  } else if ($(this).attr("href")!="undefined") {
	  	
	  	var strHref = $(this).attr("href")
	  	if ( strHref.indexOf("?") > -1 ){
	    	var strQueryString = strHref.substr(strHref.indexOf("?")+1);
	  		qString = strQueryString.split("&");
	  	}
	  } else {
	  	return null;
	  }
	  	
	  
	  if (qString==null) return null;
	  
	  
	  for (var i=0;i<qString.length; i++){
			if (escape(unescape(qString[i].split("=")[0])) == strParamName){
				returnVal.push(qString[i].split("=")[1]);
			}
			
	  }
	  
	  
	  if (returnVal.length==0) return null;
	  else if (returnVal.length==1) return returnVal[0];
	  else return returnVal;
	}
});
 function ajax_suche(page){
		var search = {
  				cat : $('#categorie').val(),
  				term : $('#suchenach').val(),
  				page: page,
  			}
  			
  			  			
			var $template = $('#buch_template').html() ;
  		
  			//search = JSON.stringify(search) ;
  			console.log(search);

  			$.ajax({
					type: 'GET',
					url: "./api/search",
					dataType: "json",
					data: search,
					
					success: function(response){
							
							
  			

				  			var $Inhalt = $('#Inhalt');

									anzahl = parseInt(response.info.anzahl) ;
									$(".showpages").remove();
							
									$Inhalt.append('<div class="showpages"><div>')
									show_pages(anzahl, page)
							
  									$('.Trefferanzeige').remove()  ;

									$Inhalt.append('<a class="Trefferanzeige"> Die Suche lieferte ' + anzahl + ' Teffer.</a>');
			
							
          							
	          						if (search.cat == "Name" || search.cat == "Kuerzel" || search.cat == "Klasse") { 
	          							var $template = $('#leser_template').html() ;
	          						   $('.leser').remove()  ;	
	          						} else {

	          						var $template = $('#buch_template').html() ;
	          						  $('.buch').remove()  ;

	          						}
        						
								var template = Handlebars.compile($template);
							$.each(response.data, function(i, item){
									
									if (item.Ausleihdatum) item.Ausleihdatum =$.date(Date.parse(item.Ausleihdatum));
									if (item.Rückgabedatum) item.Rückgabedatum =$.date(Date.parse(item.Rückgabedatum));
			 						
			 						
					    					      		
					    			var html = template(item);
					      		
					    			$Inhalt.append(html);


			 						//$Inhalt.append(Mustache.render($template , item));
							})		

							$Inhalt.append('<div class="showpages"><div>')
							show_pages(anzahl, page)
							
							console.log(response) ;
					},
					error: function(jqXHR, textStatus, errorThrown){
									
						
						alert(jqXHR.responseText);
											
					}
				});


};
 function show_pages(anzahl, page){
			
	
	var akt_page_nr = parseInt(page) ;
	var $Inhalt = $('#Inhalt') ;
	var url = "/bib/start.php";
	var anzahl_pro_seite = 10 ;
	var seitenanzahl = Math.ceil(anzahl / anzahl_pro_seite) ;
	console.log("seitenzahl= "+ seitenanzahl + " page= " + akt_page_nr );
	
			var $showpages = $('.showpages')
			$showpages.empty();
		if (anzahl > anzahl_pro_seite) {
			
			
	 		
			if (seitenanzahl < 6) {
			for(i=1;i<seitenanzahl+1;i++){
				if (i <= seitenanzahl) {
					if (i == akt_page_nr) { $showpages.append("<a>"+ i +"</a>");	} else 	$showpages.append("<a class='page' data-value=" + i + " href="+ url+ "&Page="+ i + ">"+i+"</a>");

					
				}
				if (i < seitenanzahl) $showpages.append("  |  ") ;
			}
		} else {
			if (akt_page_nr == 1 ) {$showpages.append("<a>1</a> ");}	else { $showpages.append("<a class=page data-value=1 href="+ url+ "&Page=1>1</a> ");}
			if (akt_page_nr > 4 && akt_page_nr < seitenanzahl - 3) {
				$showpages.append("... ");
				for(i=akt_page_nr -2 ;i<akt_page_nr+3;i++){
					if (i < seitenanzahl) {
						if (i == akt_page_nr) { $showpages.append ("<a>"+ i +"</a>");	} else 	{$showpages.append ("<a class='page' data-value=" + i + " href="+ url+ "&Page="+ i + ">"+i+"</a>");}
					}

					if (i < akt_page_nr+2) $showpages.append("  |  ") ;
				}
				$showpages.append ("  ...  ");
			} else if (akt_page_nr <= 4) {
				$showpages.append( "| ");
			
				for(i=2 ;i< akt_page_nr + 3;i++){
					
					if (i < seitenanzahl) {
						if (i == akt_page_nr) { $showpages.append("<a>"+ i+ "</a>");	} else 	{$showpages.append("<a class=page data-value="+ i + " href="+ url + "&Page="+ i+ ">"+i+"</a>");}
					}


					  if (i < (akt_page_nr + 2)) $showpages.append("  |  ");
				}
				$showpages.append ("  ...  ");
			} else if (akt_page_nr > 4 && akt_page_nr >= seitenanzahl - 3) {
				$showpages.append ("  ...  ");
				for(i= akt_page_nr -2  ;i<seitenanzahl+1;i++){

					if (i < seitenanzahl) {
						if (i == akt_page_nr) { $showpages.append("<a>"+i+"</a> "); } else  {$showpages.append("<a class=page data-value="+ i + " href="+ url + "&Page="+ i+ ">"+i+"</a>");}
					}

					if (i < seitenanzahl) $showpages.append("  |  ");
				}
			}
			if (akt_page_nr == seitenanzahl ) {$showpages.append("<a>"+ seitenanzahl + "</a> ");}	else {$showpages.append("<a class=page data-value="+ seitenanzahl + " href="+ url+ "&Page="+seitenanzahl+ ">"+ seitenanzahl+ "</a> ");	}

		}
		//console.log(seitenanzahl + " " + $akt_page_nr) ;

    	}
 };
 $.date = function(dateObject) {
    var d = new Date(dateObject);
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    var date = day + "." + month + "." + year;

    return date;
};
 
 	
	$(document).delegate(".page", 'click' ,function(event){
		event.preventDefault();
		var a = $(this).attr('data-value') ;
		
		ajax_suche(a )
		console.log(this) ;
	});
	
	$(document).delegate(".info_symbol", "click", function(){
		$.colorbox({href:this.href, title:this.title, initialWidth:'300',initialHeight:'150',		// anzeigen der info box 

		
		onComplete:function(){ $("#com textarea").focus(); },
		
		
		onCleanup:function(){ 
			
			var action = "./api/save_info"; 
			var com = $("#comment") 
			var form_data = {
				//leserid: $("#com input").val(),
				id: com.attr( 'data-id' ),
				buch: com.attr( 'data-buch' ),
				comment: com.val(),
			};

			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "GET",
				url: action,
				data: form_data,
			
			});
				var $bild = $('#info_'+ form_data.id);
								
			 if (!$.trim(form_data.comment) ){
			 	$bild.attr('src' , "./img/info_grau.png");
			 } else {
			 	$bild.attr('src' , "./img/info.png") ;
			 }	

			
		}});
		return false;
	});
	
	$(document).delegate(".leser_symbol", "click", function(){
		$.colorbox({href:this.href});
		return false;
	});
	$(document).delegate(".book_symbol", "click", function(){
		$.colorbox({href:this.href});
		return false;
	});
	

	// $('#Inhalt').delegate(".leser_symbol",'click', function() { 
		

	// 	event.preventDefault();
	// 	console.log(this);
	// 	alert("ifdafsf");
	// 	$(this).colorbox({})
	// });
	
		
	$(document).delegate(".ausleih_button",'click', function() {
		var antwort = confirm('Wirklich ausleihen?');
		
		if (antwort == true) {
			
			var	buchid =  $(this).attr( 'data-buchid' );
			var	leserid =  $(this).attr( 'data-leserid' );

			var action = "./api/ausleihen" ;
			
						
			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "GET",
				url: action,
				dataType:"json",
				data: { buchid : buchid,
						leserid : leserid
					 },
				
				
				success: function(response)
				{
					console.log(response) ;
									
					show_books_from_leser(leserid);
					// if(response != 'success') { alert(response);}
					// window.location.reload();
				},
				error: function(jqXHR, textStatus, errorThrown){
					
						 alert(jqXHR.responseText);
						 console.log(jqXHR);

											
				}
			});
		}
	});
	$(document).delegate(".delete_button",'click', function() {
		var antwort = confirm('Buch wirklich löschen?');
		
		if (antwort == true) {
			
			var	buchid =  $(this).attr( 'data-buchid' );
			

			var action = "./api/book/"+buchid ;
			
						
			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "DELETE",
				url: action,
				dataType:"json",
				data: { buchid : buchid },
				
				
				success: function(response)
				{
					console.log(response) ;
					
					if (response == true) $('#buch_contain'+ buchid).fadeOut('250');
					
				},
				error: function(jqXHR, textStatus, errorThrown){
					
						 alert(jqXHR.responseText);
						 console.log(jqXHR);

											
				}
			});
		}
	});
	$(".vormerk_button").click(function() {
	
		
		var antwort = confirm('Wirklich vormerken?');
		
		if (antwort == true) {
			var action = "./inc/buch_vormerken.php";
			var lid = $(this).attr( 'data-leserid' );
			var bid = $(this).attr( 'data-buchid' );
			var form_data = {
					leserid: lid,
					buchid: bid,
				};
			
			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "POST",
				url: action,
				data: form_data,
				success: function(response)
				{
					if(response != '1') { alert(response);} 
					window.location.href = "./leser.php?Leserid="+lid
				}
			});
		}
	});
$(".del_vormerken").click(function() {
	
		
		var antwort = confirm('Vormerkung wirklich vormerken?');
		
		if (antwort == true) {
			var action = "./inc/vormerkung_loeschen.php";
			var bid = $(this).attr( 'data-buchid' );
			var lid = $(this).attr( 'data-leserid' );
			var form_data = {
					buchid: bid,
					leserid: lid,
				};
			
			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "POST",
				url: action,
				data: form_data,
				success: function(response)
				{
					if(response != '1') { alert(response);} 
					window.location.reload();
				}
			});
		}
	});
	
	
	$(document).delegate(".ver_button",'click', function() {
		
		
		var antwort = confirm('Wirklich verlängern?');
		
		if (antwort == true) {
			
			var	buchid =  $(this).attr( 'data-buchid' );
			var action = "./api/verlaengern" ;
			
						
			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "GET",
				url: action,
				dataType:"json",
				data: { buchid : buchid },
				
				
				success: function(response)
				{
					console.log(response) ;
				
					$('#rdatum_'+buchid).text(response) ;
					// if(response != 'success') { alert(response);}
					// window.location.reload();
				},
				error: function(jqXHR, textStatus, errorThrown){
					
						 alert(jqXHR.responseText);
						 console.log(jqXHR);

											
				}
			});
		}
	});
	$(document).delegate('.schloss_symbol', 'dblclick', function() {

		console.log("hakllo");
		var action = "./inc/leser_sperren.php";
		var lid = $(this).attr( 'data-leserid' );
		
		var form_data = {
			leserid: lid,
		};
		// var leserid = $(this).attr('id');
			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "POST",
				url: action,
				data: form_data,
				success: function(response)
				{
					
				
					 // var bild = document.getElementById("info"+lid) ;
	 				 var $bild = $('#schloss_'+ form_data.leserid);
								
					// var bild = $(this) document.getElementById("info"+lid) ;
					
					if(response == '0'){
						$bild.attr('src', "./img/nicht_gesperrt.png");
					} else
						$bild.attr('src' ,'./img/gesperrt.png') ;	
				}
					
			});
	
	
	
	});
	$(document).delegate(".back_button",'click', function() {
		
		
		var antwort = confirm('Wirklich zurückgeben?');
		
		if (antwort == true) {
			
			var	buchid =  $(this).attr( 'data-buchid' );
			var	leserid =  $(this).attr( 'data-leserid' );
			var action = "./api/zurueckgeben" ;
			
						
			$.ajax({							// aufrufen des kommentar speicherscripts
				type: "GET",
				url: action,
				dataType:"json",
				data: { buchid : buchid },
				
				
				success: function(response)
				{
					console.log(response) ;
					var bez = '#buch_contain'+response.buchid ;
					$(bez).fadeOut('250', function() {});
					
					var $anzahl_buecher = $('#anzahl_buecher_'+leserid) ;
					console.log($anzahl_buecher);
					var anzahl = $anzahl_buecher.text() ;
					var anzahl = parseInt(anzahl) ;
					$anzahl_buecher.text(anzahl - 1) ;

					// if(response != 'success') { alert(response);}
					// window.location.reload();
				},
				error: function(jqXHR, textStatus, errorThrown){
					
						 alert(jqXHR.responseText);
						 console.log(jqXHR);

											
				}
			});
		}
	});

 	jQuery.fn.putCursorAtEnd = function() {

  return this.each(function() {

    $(this).focus()

    // If this function exists...
    if (this.setSelectionRange) {
      // ... then use it (Doesn't work in IE)

      // Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
      var len = $(this).val().length * 2;

      this.setSelectionRange(len, len);
    
    } else {
    // ... otherwise replace the contents with itself
    // (Doesn't work in Google Chrome)

      $(this).val($(this).val());
      
    }

    // Scroll to the bottom, in case we're in a tall textarea
    // (Necessary for Firefox and Google Chrome)
    this.scrollTop = 999999;

  });

};
jQuery(document).ready(function(){
	$("#leserlink").colorbox({});	// anzeigen der info box 
	$("#buch_eingabe").colorbox({});
	$("#user_edit").colorbox({});	// anzeigen der info box 
	
	$("#suchenach").autocomplete({
		 select: function( a, b ) {
	  	$(this).val(b.item.value);
		$("#suchen").click();}
	});
	
	


	
	 function activate_autocomplete() {
	 	
	 	if ( ($('#categorie').val()) == 'Buch' || ($('#categorie').val()) == 'Name') {
			 		
					$("#suchenach").autocomplete({
       					source: function(request, response) {
            				$.ajax({
                				url: './api/quick_search',
                				dataType: "json",
                				data: {
                    				cat : $("#categorie").val(),
                    				term : request.term
                    			}, 
                					success: function(data) {
                    				response(data);
                				}
            				});
        				},
        					disabled: false,
				    	    min_length: 3,
        					delay: 300
    				});
				
		} else 	$("#suchenach").autocomplete({ disabled: true });

	};

	$('#categorie').change(function(){activate_autocomplete() });

	activate_autocomplete()

	

    $("#reminder").click(function() {

		var antwort = confirm('Erinnerungen erstellen?');
		
		if (antwort == true) {
			
			var action = "./inc/erinnerungen_erstellen.php";
			var form_data = {};
			
				$.ajax({							// aufrufen des kommentar speicherscripts
					type: "POST",
					url: action,
					data: form_data,
					success: function(response)	{window.location.href = response ;}
				});
		}
		
		
	});
	
});
