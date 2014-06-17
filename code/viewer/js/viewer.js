var Pages = function() { };
	Pages.prototype = [];
	Pages.prototype.push = function() {
		//console.log('Before push: '+this);
		for(var i = 0; i < arguments.length; i++ ) {
			Array.prototype.push.call(this, arguments[i]);
		}
		//console.log('After push: '+this);		
	};
	Pages.prototype.pop = function() {
		//console.log('Before pop: '+this);
		for(var i = 0; i < arguments.length; i++ ) {
			this.splice( $.inArray(arguments[i], this), 1 );
		}
		//console.log('After pop: '+this);
	};
	

var viewer;
var annotate;

var color = '#000000';
var activeannotate = '';
var session = '';
var globalinput;

var numpages;
var layout;
var download = 1;
var fullscreen = 1;
var default_div = "";
var lastLoadPage = 1;
var currRequestedPage = 1;
//var availablePages = new Array();
var availablePages = new Pages();
var requestedPages = new Array(1,2,3);
var lazyOffset = 2;
var pagesQueue = new Array();
var ajaxQueue = new Array();
var pageMetaData = new Array();
var fullscreentoggle = false;

$(document).ready(function () {
	var adjustwindow = function(){
        if(typeof(DPembeddable) === 'undefined'){
            $("#demo-viewer").height($(window).height() - $('.DP-controls').height());
        }
    }
    
    if(typeof(DPembeddable) === 'undefined'){
        adjustwindow();

        $(window).resize(function(){
            adjustwindow();
        });
    }
    
    session = getSession(window.location.pathname);
    
   $('.DP-annotate').click(function(){
        if($('.DP-annotate-controls').is(':hidden')){
            $('.DP-annotate-controls').slideDown(500, function(){
                adjustwindow();
            });
        }
        else{
            $('.DP-annotate-controls').slideUp(500, function(){
                adjustwindow();
            });
            $('.DP-corebtns').each(function(){
                iconsreplace($(this), activeannotate, 1);
            });
            annotate["DP.annotate"].controls.resetAllAnnotations();
        }
    });
	
	viewer = $("#demo-viewer").DPviewer(undefined, {
		layout: 'DP_DOCUMENT',
		loaderPath:   baseUrl +'viewer/img/DPPreloader.gif',
		localURLFlag: 1,
		scaleStep: 0.1,
		responsive: 1,
		errorElement: '.DP-errorinfo',
		resizeOffsetWidth: 100,
		resizeOffsetHeight: 20,
		enableProxyFetch: true,
		documentURL: '', // only use when enableProxyFetch is false
        fullscreenTriggerTime: 1500,
		
		proxyServerInfo: {
			fetchMetaDataURL: baseUrl+'viewer/server/v1/fetchMetaData.php?session_id=' + session_id,
			customMetaDataCheck: function (data) {
				return true;
			},
			fetchMainURL: baseUrl+'viewer/server/v1/fetchMain.php?session_id=' + session_id,
			customMainCheck: function (data) {
				return true;
			},
			// the parameter for the page is 'pageNo' and its a get request
			fetchPageURL: baseUrl+'viewer/server/v1/fetchPage.php?session_id=' + session_id,
			customPageCheck: function (data, pageNo) {
				// example fetch page custom response check
				//handle page level permission validation returned by server something like below (Chapter rentals)
				if (data === 'page not allowed for viewing'){
					data = '<div style="text-align: center;margin-top: 100px;">' + data + '</div>';
					viewer["DP.viewer"].controls.setPageHTML(pageNo, data, true);
					return false;
				} else {
					return true;
				}
			}
		},
		
		progressiveLoadInfo: {
			startPage:1,
			offset: lazyOffset,
			loadPages: 'ONPAGENAV' //'ALL' loads all pages 'ONPAGENAV' loads pages only when user scrolls or navigates to a particular page
		},
		
		callbacks: {
			zoomChange: function (scaleRatio) {
				//console.log("zoomChange"); 
				annotate["DP.annotate"].controls.zoomChangeSetup(scaleRatio);
			},
			pageChange: function (params) {//params[currLoadPage,total number of pages]
				currLoadPage = params[0];
				numpages = params[1];
				$('.DP-pagenav').val(currLoadPage + ' of ' + numpages);	
				//console.log("Requested Page Change: "+ currLoadPage);
				removeOutOfBounds(currLoadPage);
			},
			onReady: function (params) {
				// params[0] = total number of pages
				// setting for viewer page info
				$('.DP-pagenav').val(1 + ' of ' + params[0]);
				
				//annotate related code
				 annotate = $("#demo-viewer").DPannotate(undefined, {
                    scaleRatio: viewer["DP.viewer"].controls.getScaleRatio(),
					sessionId: session_id,
					user: customer_id
				});
                
                annotate = annotate.data();
                
                //annotate
                $('.DP-highlight').click(function(){
                    var ref = $(this);
                    $('.DP-corebtns').each(function(){
                        if($(this)[0] != ref[0])
                            iconsreplace($(this), 'highlight', 1);
                    });
                    
                    iconsreplace(ref, 'highlight');
                    
                    annotate["DP.annotate"].controls.highlight();
                });
                
                $('.DP-strike').click(function(){
                    var ref = $(this);
                    $('.DP-corebtns').each(function(){
                        if($(this)[0] != ref[0])
                            iconsreplace($(this), 'strike', 1);
                    });
                    
                    iconsreplace(ref, 'strike');
                    
                    annotate["DP.annotate"].controls.strike();
                });
                
                $('.DP-hyperlink').click(function(){
                    var ref = $(this);
                    $('.DP-corebtns').each(function(){
                        if($(this)[0] != ref[0])
                            iconsreplace($(this), 'hyperlink', 1);
                    });
                    
                    iconsreplace(ref, 'hyperlink');
                    
                    annotate["DP.annotate"].controls.hyperlink();
                    
                });
                
                $('.DP-draw').click(function(){
                    var ref = $(this);
                    $('.DP-corebtns').each(function(){
                        if($(this)[0] != ref[0])
                            iconsreplace($(this), 'draw', 1);
                    });
                    
                    iconsreplace(ref, 'draw');
                    
                    annotate["DP.annotate"].controls.draw();
                });
				
				$('.DP-comment').click(function(){
					var ref = $(this);
					$('.DP-corebtns').each(function(){
						if($(this)[0] != ref[0])
							iconsreplace($(this), 'pointcomment', 1);
					});
					
					iconsreplace(ref, 'pointcomment');
					
					annotate["DP.annotate"].controls.pointComment();
				});
                
                $('.DP-colors').click(function(){
                    var col = $(this).css('background-color');
                    color = rgbToHex(col);
                    
                    if(activeannotate == 'highlight')
                        annotate["DP.annotate"].controls.changeHighlightColor(color);
                    else if(activeannotate == 'draw')
                        annotate["DP.annotate"].controls.changeDrawColor(color);
                });
			},
			
			fullScreenChange: function (params, eventtype) {
                fullscreentoggle = params;
                
                if(eventtype === 'before' & fullscreentoggle){
                    $('#demo-viewer').hide();
                    $('.DP-controls').addClass('fullscreenmode');
                    
                }else if(eventtype === 'after' & fullscreentoggle){
                    setTimeout(function(){
                        $('#demo-viewer').fadeIn(500);
                    }, 100);
                }
            },
			
			onPageLoad: function (params) {	// params[0] = currLoadPage
				// on page load request complete
				 annotate["DP.annotate"].controls.pageLoadSetup(params);
				 availablePages.push(params[0]);
			},
            
            pageRemoved: function(pageNo, html){
                annotate["DP.annotate"].controls.pageUnloadSetup(pageNo, html);
                //availablePages.pop(pageNo);
            }
		}
	});
	
	// get hold of viewer Plugin
	viewer = viewer.data();
	
	$('body').on('swipeleft', '#'+viewer["DP.viewer"].$el.attr('id'), function(){
        viewer["DP.viewer"].controls.previous();
    });

    $('body').on('swiperight', '#'+viewer["DP.viewer"].$el.attr('id'), function(){
        viewer["DP.viewer"].controls.next();
    });

    // keyboard navigation
	$(document).keydown(function(e){
		if (e.keyCode === 37){
			viewer["DP.viewer"].controls.previous();
		} else if (e.keyCode === 39){
			viewer["DP.viewer"].controls.next();
		}
	})

    $('body').on('click tap', '.DP-pagenav', function(){
        $(this).val(globalinput);
    });

    $('.DP-pagenav').change(function(){
       var pg = $(this).val();
		try {
			viewer["DP.viewer"].controls.gotoPage(parseInt(pg));
		} catch (err) {
			$(this).val(globalinput + ' of ' + numpages);
		}
    });

    $('body').on('click tap', '.DP-zoomIn', function(){
        viewer["DP.viewer"].controls.zoomIn();
    });

    $('body').on('click tap', '.DP-zoomOut', function(){
        viewer["DP.viewer"].controls.zoomOut();
    });

    $('.DP-nextpage').click(function(e){
		viewer["DP.viewer"].controls.next();
	});
	$('.DP-previouspage').click(function(e){
		viewer["DP.viewer"].controls.previous();
	});
	if (fullscreen){
		viewer["DP.viewer"].controls.subscribeToFullScreen();
		$('body').on('click tap', '.DP-fullscreen', function(){
			if(!fullscreentoggle)
                viewer["DP.viewer"].controls.enterFullScreen($('.DP-demo')[0]);
            else
                viewer["DP.viewer"].controls.exitFullScreen();
		});
	 }
	$("#demo-viewer").on('contextmenu', function(e){
		return false;
	}).addClass('unselect');
	
	function getSession(url){
		url = url.split('/');
		var len = url.length;
		if(url[len - 1] == 'index.html' || url[len - 1] == '')
			return url[len - 2];
		else
			return url[len - 1];       
	}

	function iconsreplace(element, type, val){
		var pos = element.css('background-position');
		var arr = pos.split(' ');
		pos = parseFloat(arr[1].replace('px',''));
		if(val == undefined){
			pos = (pos == 0)?18:0;
		}
		else{
			pos = 0;
		}
		
		activeannotate = type;
		
		if(type == 'highlight' || type == 'draw'){
			if(pos == 0){
				$('.DP-colors').hide();
			}else{
				$('.DP-colors').show();
			}
		}else{
			$('.DP-colors').hide();
		}
		
		element.css('background-position', arr[0] + ' ' + pos + 'px');
	}

	function componentToHex(c) {
		var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}

	function rgbToHex(val) {
		val = val.replace('rgb(', '').replace(')','');
		val = val.split(', ');
		return "#" + componentToHex(parseInt(val[0])) + componentToHex(parseInt(val[1])) + componentToHex(parseInt(val[2]));
	}


	/******* Copy paste protection Starts here ********/
	var ctrlDown = false;
    var ctrlKey = 17, vKey = 86, cKey = 67, aKey=18, cmdKey = 224;

    $(document).keydown(function(e){
		//console.log(e.keyCode);
        if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
	}).keyup(function(e){
        if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = false;
    });

    $(document).keydown(function(e){
		//console.log("ctrl: "+ctrlDown+"; key: "+e.keyCode);
		
        if (ctrlDown && (e.keyCode == vKey || e.keyCode == cKey || e.keyCode == aKey)){
			return false;
		}
    });
	
	/******* Copy paste protection Ends here ********/
	
	function removeOutOfBounds(requestedPage){
		$(availablePages).each(function(i,e){
			if(e<(requestedPage-lazyOffset) || e>(requestedPage+lazyOffset)){	
				//console.log("Removing page: "+e);
				//remove from available pages
				availablePages.pop(e);
				//remove out of boundary pages
				//$(".pf").eq(e-1).html('<div class="DP-loader"><img src="img/DPPreloader.gif"></div>');				
				viewer["DP.viewer"].controls.setPageHTML(e, '');
			}
		});
	}
});