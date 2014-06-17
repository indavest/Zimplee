var viewer;
var annotate;

var globalinput;
var numpages;
var layout;
var download = 1;
var fullscreen = 1;
var default_div = "";
var lastLoadPage = 1;

$(document).ready(function () {
	$("#demo-viewer").height($(window).height() - 70);
	viewer = $("#demo-viewer").DPviewer(undefined, {	
		
		layout: 'DP_DOCUMENT',
		loaderPath: 'img/DPPreloader.gif',
		localURLFlag: 1,
		scaleStep: 0.1,
		responsive: 1,
		errorElement: '.DP-errorinfo',
		resizeOffsetWidth: 100,
		resizeOffsetHeight: 20,
		enableProxyFetch: true,
		documentURL: '', // only use when enableProxyFetch is false
		proxyServerInfo: {
			fetchMetaDataURL: 'server/v1/fetchMetaData.php?session_id=' + session_id,
			customMetaDataCheck: function (data) {
				return true;
			},
			fetchMainURL: 'server/v1/fetchMain.php?session_id=' + session_id,
			customMainCheck: function (data) {
				return true;
			},
			// the parameter for the page is 'pageNo' and its a get request
			fetchPageURL: 'server/v1/fetchPage.php?session_id=' + session_id,
			customPageCheck: function (data, pageNo) {
				// example fetch page custom response check
				//handle page level permission validation returned by server something like below (Chapter rentals)
				if (data === 'page not allowed for viewing'){
					data = '<div style="text-align: center;margin-top: 100px;">' + data + '</div>';
					viewer["DP.viewer"].controls.setPageHTML(pageNo, data);
					return false;
				} else {
					return true;
				}
			}
		},
		progressiveLoadInfo: {
			startPage: 1,
			offset: 1,
			loadPages: 'ONPAGENAV' //'ALL' loads all pages 'ONPAGENAV' loads pages only when user scrolls or navigates to a particular page
		},
		callbacks: {
			zoomChange: function (params) {},
			pageChange: function (params) {
				//params[currLoadPage,total number of pages]
				currLoadPage = params[0];
				numpages = params[1];
				$('.DP-pagenav').val(currLoadPage + ' of ' + numpages);
				// addtional code to perform on page Change
				console.log(currLoadPage +" - "+ numpages);
				
				//get boundries
				var leftBoundery = (currLoadPage-1>1)?(currLoadPage-1):1;
				var rightBoundery = (currLoadPage+1>numpages)?numpages:(currLoadPage+1);
				//check if its not empty (cur page -1 and curr page +1 divs child has DP-loader class or not
				
				customResetPageToDefaultBypageNumber(lastLoadPage,(Math.abs(lastLoadPage-currLoadPage)>1));
				lastLoadPage = currLoadPage;
				//remove page / reset div to defaults
				
			},
			onReady: function (params) {
				// params[0] = total number of pages
				// setting for viewer page info
				$('.DP-pagenav').val(1 + ' of ' + params[0]);
				// addtional code on viewer Ready
				lastLoadPage = 1;
			},
			fullScreenChange: function (params) {},
			onPageLoad: function (params) {
				// params[0] = currLoadPage
				// on page load request complete
			}
		}
	});
	
	// get hold of viewer Plugin
	viewer = viewer.data();
	
	// keyboard navigation
	$(document).keydown(function(e){
		if (e.keyCode === 37){
			viewer["DP.viewer"].controls.previous();
			customLoadPageByNumber(globalinput);
		} else if (e.keyCode === 39){
			viewer["DP.viewer"].controls.next();
			customLoadPageByNumber(globalinput);
		}
	})
	$('.DP-pagenav').click(function(e){
		$(this).val(globalinput);		
	});
	$('.DP-pagenav').change(function(e){
		var pg = $(this).val();
		try {
			viewer["DP.viewer"].controls.gotoPage(parseInt(pg));
			customLoadPageByNumber(globalinput);
		} catch (err) {
			console.log(globalinput);
			$(this).val(globalinput + ' of ' + numpages);
		}
	});
	$('.DP-zoomIn').click(function(e){
		viewer["DP.viewer"].controls.zoomIn();
	});
	$('.DP-zoomOut').click(function(e){
		viewer["DP.viewer"].controls.zoomOut();
	});
	$('.DP-nextpage').click(function(e){
		viewer["DP.viewer"].controls.next();
		customLoadPageByNumber(globalinput);
	});
	$('.DP-previouspage').click(function(e){
		viewer["DP.viewer"].controls.previous();
		customLoadPageByNumber(globalinput);
	});
	if (fullscreen){
		viewer["DP.viewer"].controls.subscribeToFullScreen();
		$('.DP-fullscreen').click(function(e){
			viewer["DP.viewer"].controls.enterFullScreen();
		});
	}
	$("#demo-viewer").on('contextmenu', function(e){
        return false;
	}).addClass('unselect');
});

function customLoadPageByNumber(currLoadPage){
	console.log("requesting to load page "+currLoadPage);
	
}
function customResetPageToDefaultBypageNumber(pageno,isPageJump){
	/*pageno-=1;
	
	if(isPageJump){
		//pagination by changing number (so may be from 4 to 14, so remove 3,4,5 
		if(pageno-1>=0){
			if(!$(".pf").eq(pageno-1).children("div:first-child").hasClass("DP-loader")){
				console.log("resetting page "+ pageno);
				$(".pf").eq(pageno-1).html('<div class="DP-loader"><img src="img/DPPreloader.gif"></div>');
			}
			$(".pf").eq(pageno).html('<div class="DP-loader"><img src="img/DPPreloader.gif"></div>');
			if(pageno+1<=numpages){
				if(!$(".pf").eq(pageno+1).children("div:first-child").hasClass("DP-loader")){
					console.log("resetting page "+ (pageno+1));
					$(".pf").eq(pageno+1).html('<div class="DP-loader"><img src="img/DPPreloader.gif"></div>');
				}
			}
		}	
	}else{
		//pagination by click on arrows (so may be from 4 to 5, so remove 3
		if(pageno-1>=0){
			if(!$(".pf").eq(pageno-1).children("div:first-child").hasClass("DP-loader")){
				console.log("resetting page "+ pageno);
				$(".pf").eq(pageno-1).html('<div class="DP-loader"><img src="img/DPPreloader.gif"></div>');
			}
		}
	}
	*/
	
	
}