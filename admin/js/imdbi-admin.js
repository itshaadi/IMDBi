(function( $ ) {
	'use strict';

	/**
	 * from this point every thing related to imdbi-metabox-view.php and omdb_view.php
	 */
	 $('document').ready(function(){
	 	var tempResult = null;
	 	var tempID = null;
	 	var imdbID = null;


		/**
		* display warning message for once.
		*/
		if(!localStorage.getItem('imdbi_warning')) {
			$(".imdbi-warning").fadeIn("fast");
		}


		if( $("input[name=imdbi-imdbid-value]").val() != '' ){
			$(".imdbi-metabox-slide1").hide();
			$(".imdbi-tab-wrapper").show("fast");
			$(".imdbi-metabox-common-fields").show("fast");
		}

	 	$(".nav-tab").click(function(e){
	 		e.preventDefault();
	 	});

		$(".crawler-error-btn").live("click", function(e){
			$(".crawler-error").fadeOut("fast");
			$(".imdbi-metabox-slide2").fadeIn("fast");
			e.preventDefault();
		});

	 	$("#imdbi-bytitle").click(function(){

	 		$(".imdbi-metabox-slide1").hide();
	 		$(".imdbi-metabox-slide2").show();
	 	});


	 	$("#imdbi-byid").click(function(){

	 		$(".imdbi-metabox-slide1").hide();
	 		$(".imdbi-metabox-slide3").show();
	 	});


	 	$("#goto1").click(function(e){
	 		$(".imdbi-metabox-slide2").hide();
	 		$(".imdbi-metabox-slide1").show();
	 		e.preventDefault();
	 	});

	 	$("#goto2").click(function(e){
	 		$(".imdbi-metabox-slide3").hide();
	 		$(".imdbi-metabox-slide1").show();
	 		e.preventDefault();
	 	});

	 	$(".imdbi-try-btn").live("click", function(e){
		 		$(".imdbi-metabox-slide4").hide();
		 		$(".imdbi-metabox-slide2").show();
		 		e.preventDefault();
		 	});
		$(".imdbi-alt-api").live("click", function(e){
			$(".imdbi-metabox-slide4").fadeOut("fast");
			$(".imdbi-metabox-loader").fadeIn("fast");
			$.ajax({
				type: "POST",
				url: document.URL,
				data:{
					'imdbID': imdbID,
				},
				success: function(html){
					$(".imdbi-metabox-loader").fadeOut("fast");
					$(".imdbi-metabox-slide4 .inside").html(html);
					$(".imdbi-metabox-slide4").fadeIn("fast");
				}
			})
			e.preventDefault();
		});

	 	$(".omdb-result-link").live("click",function(e){
	 		imdbID = $(this).attr("title");
	 		if(tempID == imdbID){
	 			$(".omdb-temp-results").hide();
	 			$(".imdbi-metabox-slide4").show();
	 		}
	 		else{
	 			tempID = $(this).attr("title");
	 			$(".omdb-search-results").fadeOut("fast");
	 			$(".imdbi-metabox-loader").fadeIn("fast");
		 		$.ajax({
		 			type: "POST",
		 			url:  document.URL,
		 			data: {
		 				'imdbID':imdbID,
		 			},

		 			success: function(html){
		 				$(".imdbi-metabox-loader").fadeOut("fast");
		 				$(".imdbi-metabox-slide4 .inside").html(html);
		 				$(".imdbi-metabox-slide4").fadeIn("fast");
		 			}
		 		}) //End ajax

				$("#back2").live("click", function(e){
					$(".imdbi-metabox-slide4").hide();
					$(".omdb-temp-results").show();
					e.preventDefault();
				});

				$("#back1").live("click",function(e){
					$(".imdbi-metabox-slide4").hide();
					$(".omdb-temp-results").hide();
					$(".imdbi-metabox-slide2").show();
					e.preventDefault();
				});
	 		}
	 		e.preventDefault();
	 	});

	 	$("#imdbi-search-submit").live("click", function(){

	 		var target = document.URL;
	 		var query = $("#imdbi-query").val();
	 		var year = $("#imdbi-year").val();

	 		if(query == ''){
	 			$(".imdbi-empty-title").fadeIn("fast").delay(1500).fadeOut("fast");
	 		}
	 		else{

		 		$(".imdbi-metabox-slide2").fadeOut("fast");
		 		$(".imdbi-metabox-loader").fadeIn("fast");

		 		$.ajax({

		 			type: "POST",
		 			url:  target,
		 			data: {
						'imdbQuery':query,
						'imdbYear':year,
		 			},

		 			success: function(html){
		 				$(".imdbi-metabox-loader").fadeOut("fast");
		 				$(".imdbi-metabox-slide4 .inside").html(html);
		 				$(".imdbi-metabox-slide4").fadeIn("fast");

		 				$("#back1").on("click", function(e){
		 					$(".imdbi-metabox-slide4").hide();
		 					$(".imdbi-metabox-slide2").show();
		 					e.preventDefault();
		 				});

		 				tempResult = $(".omdb-search-results").html();
		 				$(".omdb-temp-results").addClass("omdb-search-results");
		 				$(".omdb-temp-results").html(tempResult);
		 			}
		 		})
	 		}

	 	}); //End imdbi-search-submit

	 	$("#imdbi-id-submit").click(function(){
	 		var target = document.URL;
	 		var imdbID = $("#imdbi-id").val();
	 		var pattern = /tt(\d)/;

	 		if(imdbID == ''){
	 			$(".imdbi-empty-id").fadeIn("fast").delay(1500).fadeOut("fast");
	 		}
	 		else if(!pattern.test(imdbID)){
	 			$(".imdbi-invalid-id").fadeIn("fast").delay(1500).fadeOut("fast");
	 		}
	 		else{

		 		$(".imdbi-metabox-slide3").fadeOut('fast');
		 		$(".imdbi-metabox-loader").fadeIn('fast');

				$.ajax({
					type: "POST",
					url:target,
					data: {

						'imdbID':imdbID,
					},
					success: function(html){
					$(".imdbi-metabox-loader").fadeOut('fast');
					$(".imdbi-metabox-slide4 .inside").html(html);
					$('.imdbi-metabox-slide4').fadeIn('fast');

				 	$("#back2").click(function(e){
				 		$(".imdbi-metabox-slide4").hide();
				 		$(".imdbi-metabox-slide3").show();
				 		e.preventDefault();
				 	});

					}

				});
			}
	 	});

		// pretend to submiting information

		$(".imdbi-submit-info").live("click", function(){

			var target = document.URL;

			$(".omdb-search-results").fadeOut("fast");

			$(".imdbi-metabox-loader").fadeIn("fast");

			/**
			* so when user confirm this results, next step is saving poster
			*
			*/

			if($("#imdbi-crawler-poster").text() !== "N/A"){

				var poster = $("#imdbi-crawler-poster").text();

				$.post(
						target,
						{
							poster_url:poster,
						},
						function(response){

							$("textarea[name=imdbi-poster-value]").text(response);

							$(".imdbi-tab-wrapper").fadeIn("fast");
							$(".imdbi-metabox-common-fields").fadeIn("fast");

							$(".imdbi-metabox-loader").fadeOut("fast");
						}
					);

			}
			else{

				$("textarea[name=imdbi-poster-value]").text($("#imdbi-crawler-poster").text());

			}


			/**
			* Pasting results into metabox fields
			* Section: common fields
			*/

			$("input[name=imdbi-genre-value]").val($("#imdbi-crawler-genre").text());
			$("input[name=imdbi-country-value]").val($("#imdbi-crawler-country").text());
			$("input[name=imdbi-language-value]").val($("#imdbi-crawler-language").text());
			$("input[name=imdbi-awards-value]").val($("#imdbi-crawler-awards").text());
			$("textarea[name=imdbi-plot-value]").text($("#imdbi-crawler-plot").text());



			/**
			* Pasting results into metabox fields
			* Section: other fields
			*/


			$("input[name=imdbi-rated-value]").val($("#imdbi-crawler-rated").text());
			$("input[name=imdbi-released-value]").val($("#imdbi-crawler-released").text());
			$("input[name=imdbi-runtime-value]").val($("#imdbi-crawler-runtime").text());
			$("input[name=imdbi-director-value]").val($("#imdbi-crawler-director").text());
			$("input[name=imdbi-writer-value]").val($("#imdbi-crawler-writer").text());
			$("input[name=imdbi-actors-value]").val($("#imdbi-crawler-actors").text());
			$("input[name=imdbi-metascore-value]").val($("#imdbi-crawler-metascore").text());
			$("input[name=imdbi-imdbrating-value]").val($("#imdbi-crawler-imdbrating").text());
			$("input[name=imdbi-imdbvotes-value]").val($("#imdbi-crawler-imdbvotes").text());
			$("input[name=imdbi-gross-value]").val($("#imdbi-crawler-gross").text());
			$("input[name=imdbi-budget-value]").val($("#imdbi-crawler-budget").text());
			$("textarea[name=imdbi-trailer-value]").text($("#imdbi-crawler-trailer").text());

			/**
			* these fields are not editable and hidden from user view.
			*/

			$("input[name=imdbi-imdbid-value]").val($("#imdbi-crawler-imdbid").text());
			$("input[name=imdbi-title-value]").val($("#imdbi-crawler-title").text());
			$("input[name=imdbi-year-value]").val($("#imdbi-crawler-year").text());
			$("input[name=imdbi-type-value]").val($("#imdbi-crawler-type").text());

			$(".imdbi-tab-wrapper").fadeIn("fast");
			$(".imdbi-metabox-common-fields").fadeIn("fast");

			$(".imdbi-metabox-loader").fadeOut("fast").delay(1500);


			/**
			* Clearing search track to prevent crashing.
			*/

			$("#imdbi-query").val("");
			$("#imdbi-year").val("");
			$("#imdbi-id").val("");

		});

		// switch between tabs

		$(".imdbi-tab1").on("click", function(){
			$(".imdbi-tab2").removeClass("nav-tab-active");
			$(".imdbi-tab1").addClass("nav-tab-active");
			$(".imdbi-metabox-common-fields").fadeIn("fast");
			$(".imdbi-metabox-other-fields").fadeOut("fast");
		});

		$(".imdbi-tab2").on("click", function(){
			$(".imdbi-tab1").removeClass("nav-tab-active");
			$(".imdbi-tab2").addClass("nav-tab-active");
			$(".imdbi-metabox-other-fields").fadeIn("fast");
			$(".imdbi-metabox-common-fields").fadeOut("fast");
		});


		$("#imdbi-query").keypress(function (e) {
				if (e.which == '13') {
					e.preventDefault();
						$("#imdbi-search-submit").click();
				}
		});

		$("#imdbi-id").keypress(function (e) {
				if (e.which == '13') {
					e.preventDefault();
						$("#imdbi-id-submit").click();
				}
		});

		$("#imdbi-warning-close").click(function(e){

			if(!localStorage.getItem('imdbi_warning')) {
				localStorage.setItem('imdbi_warning','true');
				$(".imdbi-warning").fadeOut("fast");
			}

			e.preventDefault();

		});

	 });

})( jQuery );
