/*!
 * jquery.yoxview
 * jQuery image gallery viewer
 * http://yoxigen.com/yoxview
 *
 * Copyright (c) 2010 Yossi Kolesnicov
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Date: 28th July, 2010
 * Version : 1.98
 */ 

var yoxviewApi;

(function($){
    var yoxviewPath;
    
    $.yoxviewUnload = function()
    {
        if (yoxviewApi)
        {
            yoxviewApi.unload();
            yoxviewApi = null;
        }
    }
    $(window).unload(function(){
        $.yoxviewUnload();
    });
    
    $.fn.extend({
        yoxview: function(opt) 
        {
            if (this.length == 0)
                return this;

            if (!yoxviewPath)
                yoxviewPath = typeof(_yoxviewPath) != "undefined" ? _yoxviewPath : Yox.getPath(/(.*)jquery.yoxview.*/i);

             // Load the language file if not already loaded:
            this.loadLanguage = function(lang, callBack)
            { 
                var self = this;
                if (!yoxviewLanguages[lang])
                {
                    yoxviewLanguages[lang] = {};
                    $.ajax({
                        url : yoxviewPath + "lang/" + lang + ".js",
                        async : false,
                        dataType : "json",
                        success: function(data){
                            yoxviewLanguages[lang] = data;
                            Yox.loadDataSource(options, callBack, self);
                        }
                    });
                }
                else
                    Yox.loadDataSource(options, callBack, self);
            }
            
            var defaults = {
                autoHideInfo: true, // If false, the info bar (with image count and title) is always displayed.
                autoPlay: false, // If true, slideshow mode starts when the popup opens
                backgroundColor: "#000",
                backgroundOpacity: 0.8,
                buttonsFadeTime: 300, // The time, in milliseconds, it takes the buttons to fade in/out when hovered on. Set to 0 to force the Prev/Next buttons to remain visible.
                cacheBuffer: 5, // The number of images to cache after the current image (directional, depends on the current viewing direction)
                cacheImagesInBackground: true, // If true, full-size images are cached even while the gallery hasn't been opened yet.
                controlsInitialFadeTime: 1500, // The time, in milliseconds, it takes the menu and prev/next buttons to fade in and out when the popup is opened.
                controlsInitialDisplayTime: 1000, // The time, in milliseconds, to display the menu and prev/next buttons when the popup is opened. Set to 0 to not display them by default
                dataFolder: yoxviewPath + "data/",
                imagesFolder: yoxviewPath + "images/",
                infoBackColor: "Black",
                infoBackOpacity: 0.5,
                isRTL : false, // Switch direction. For RTL languages such as Hebrew or Arabic, for example.
                lang: "en", // The language for texts. The relevant language file should exist in the lang folder.
                langFolder: yoxviewPath + "lang/",
                loopPlay: true, // If true, slideshow play starts over after the last image
                playDelay: 3000, // Time in milliseconds to display each image
                popupMargin: 20, // the minimum margin between the popup and the window
                popupResizeTime: 600, // The time in milliseconds it takes to make the resize transition from one image to the next.
                renderButtons: true, // Set to false if you want to implement your own Next/Prev buttons, using the API.
                renderMenu: true, // Set to false if you want to implement you own menu (Play/Help/Close).
                showBarsOnOpen: true, // If true, displays the top (help) bar and bottom (info) bar momentarily when the popup opens.
                showButtonsOnOpen: true, // If true, displays the Prev/Next buttons momentarily when the popup opens.
                titleAttribute: "title",
                titleDisplayDuration: 2000 // The time in ms to display the image's title, after which it fades out.
            };

            var options = $.extend(defaults, opt); 

            if (!yoxviewApi)
            {
                this.loadLanguage(options.lang, function(views){
                    yoxviewApi = new YoxView(views, options);
                });
            }
            else
            {
                this.loadLanguage(options.lang, function(views){
                    yoxviewApi.AddViews(views, options);
                });
            }   

            return this;
        }
    });
})(jQuery);

var yoxviewLanguages = new Array();

function YoxView(_views, _options)
{  
    var yoxviewApi = this;
    var $ = jQuery; // Ensure the dollar sign stands for jQuery, in case other JS libraries are loaded, that use it.
    
    var ajaxLoader;
    var cacheBufferLastIndex;
    var cacheComplete = false;
    var cachedImagesCount = 0;
    var cacheDirectionForward = true;
    var cacheImg = new Image();
    var countDisplay;
    var ctlButtons; // next and prev buttons
    var currentCacheImg = 0;
    var currentItemIndex = 0;
    var currentLanguage = {};
    var currentViewIndex = 0;
    var defaultOptions = _options;
    var disableInfo = false;
    var firstImage = true;
    var helpPanel;
    var hideInfoTimeout;
    var hideMenuTimeout;
    var image1;
    var image2;
    var images;
    var imagesCount = 0;
    var infoPanel;
    var infoPanelContent;
    var infoPanelLink;
    var infoPanelMinHeight = 30;
    var infoPanelWrap;
    var infoPinLink;
    var infoPinLinkImg;
    var infoText;
    this.isOpen = false;
    var isPlaying = false;
    var isResizing = false;
    var itemVar;
    var loading = false;
    var mediaButtonsSize = {width: 100, height: 100};
    var mediaLoader;
    var mediaProviderUrls = {
        vimeo: "http://vimeo.com/api/oembed.json",
        myspace: "http://vids.myspace.com/index.cfm?fuseaction=oembed"
    };
    var menuHidePosition = -42;
    var menuPanel;
    var nextBtn;
    var notifications = new Array();
    var onOpenCallback;
    var options = defaultOptions;
    var panel1;
	var panel2;
	var playBtnText;
    var popup;
    var popupBackground;
    var popupWrap;
    var prevBtn;
    var resumePlay = false;
    var tempImg = new Image();
    var thumbnail;
    var thumbnailImg;
    var thumbnailPos;
    var thumbnailProperties;
    var views = new Array();
    var windowDimensions;

    var keyCodes = {
	    40: 'DOWN',
	    35: 'END',
	    13: 'ENTER',
	    36: 'HOME',
	    37: 'LEFT',
	    39: 'RIGHT',
	    32: 'SPACE',
	    38: 'UP',
	    72: 'h',
	    27: 'ESCAPE'
    };
    var keyMappings = {
        RIGHT: options.isRTL ? 'prev' : 'next',
        DOWN: 'next',
        UP: 'prev',
        LEFT: options.isRTL ? 'next' : 'prev',
        ENTER: 'play',
        HOME: 'first',
        END: 'last',
        SPACE: 'next',
        h: 'help',
        ESCAPE: 'close'
    };
    var sprites = new Yox.Sprites({
        notifications: {
            width: 59,
            height: 59,
            sprites: [ 'empty', 'playRTL', 'play', 'pause', 'last', 'first' ]
        },
        icons: {
            width: 18,
            height: 18,
            sprites: ['close', 'help', 'play', 'link', 'pin', 'unpin']
        },
        menu: {
            height: 42,
            sprites: ['back']
        }
    }, options.imagesFolder + "sprites.png", options.imagesFolder + "empty.gif");

    this.AddViews = function(_views, options)
    {
        var popupIsCreated = this.firstViewWithImages != undefined;
        jQuery.each(_views, function(){
            setView(this, views.length, options);
            views.push(this);
            if (!yoxviewApi.firstViewWithImages)
            {
                var viewImages =  $(this).data("yoxview").images;   
                if (viewImages && viewImages.length != 0)
                    yoxviewApi.firstViewWithImages = this;
            }
        });

        if (!popupIsCreated && this.firstViewWithImages)
        {
            loadViewImages(this.firstViewWithImages);
            createPopup();

            if(options.cacheImagesInBackground && imagesCount != 0)
            {
                calculateCacheBuffer();
                cacheImages(0);
            }
            popupIsCreated = true;
        }
    }
    this.SetImages = function(images)
    {
        imagesCount = images.length;
    }
    function resetPopup()
    {
        if (popup)
        {
            popupWrap.remove();
            popup = undefined;
            prevBtn = undefined;
            nextBtn = undefined;
            image1 = undefined;
            image2 = undefined;
			panel1 = undefined;
			panel2 = undefined;
            currentItemIndex = 0;
            currentCacheImg = 0;
			yoxviewApi.infoButtons = undefined;
        }
        createPopup();
    }
    function loadViewImages(_view)
    {
        var viewData = $(_view).data("yoxview");
        if (!images || currentViewIndex != viewData.viewIndex)
        {
            images = viewData.images;
            imagesCount = images.length;
            currentViewIndex = viewData.viewIndex;

            var isResetPopup = false;

            if (viewData.options && !Yox.compare(options, viewData.options))
            {
                options = viewData.options;
                isResetPopup = true;
            }
            else if (!viewData.options && !Yox.compare(options, defaultOptions))
            {
                options = defaultOptions;
                isResetPopup = true;
            }
            else if ((prevBtn && imagesCount == 1) || (popup && !prevBtn && imagesCount > 0))
                isResetPopup = true;

            if (isResetPopup)
                resetPopup();
        }
    }
    
    function getImageDataFromThumbnail(thumbnail)
    {
        var isVideo = false;
        var imageData = {};
        var thumbnailHref = thumbnail.attr("href");
        var thumbImg = thumbnail.children("img:first");
        
        if (thumbnailHref.match(Yox.imageRegex))
        {
            $.extend(imageData, {
                media: {
                    src : thumbnail.attr("href"),
                    title : thumbImg.attr(options.titleAttribute),
                    alt : thumbImg.attr("alt")
                }
            });
        }
        else
        {
            for(videoProvider in Yox.videoRegex)
            {
                if (thumbnailHref.match(Yox.videoRegex[videoProvider]))
                {
                    $.extend(imageData, {
                        media: {
                            type: "video",
                            provider: videoProvider,
                            url: thumbnailHref
                        }
                    });
                    isVideo = true;
                    break;
                }
            }
            if (!isVideo)
                return null;
        }
        
        $.extend(imageData, {
            thumbnailImg : thumbImg,
            thumbnailSrc : thumbImg.attr("src")
        });
        return imageData;
    }
    
    function setView(view, viewIndex, _options)
    {
        var view = $(view);
        view.data("yoxview", {viewIndex : viewIndex});
        
        if (!Yox.compare(options, _options))
            view.data("yoxview").options = _options;
                    
        // First, get image data from thumbnails:
		_options.isSingleLink = view[0].tagName == "A";
        var thumbnails = _options.isSingleLink ? view : view.find("a:has(img)");
        var viewImages = new Array();

        thumbnails.each(function(i, thumbnail){
            var imageData = getImageDataFromThumbnail($(thumbnail));
            if (imageData != null)
                viewImages.push(imageData);
        });

        if (_options.images)
            viewImages = viewImages.concat(_options.images);

        if (_options.dataSource)
        {
            Yox.dataSources[_options.dataSource].getImagesData(_options, function(data){
                viewImages = viewImages.concat(data.images);
                view.data("yoxview").images = viewImages;

                var thumbnailsData = data.isGroup 
                    ? [$.extend(data, {
                        media: {
                            title: data.title + " (" + data.images.length + " images)",
                            alt: data.title
                        }
                    })]
                    : data.images;
					
                createThumbnails(view, _options.isSingleLink ? null : thumbnailsData, !data.createGroups ? null :
                    function(e){
                        var viewData = $(e.currentTarget).data("yoxview");
                        var thumbnail = $(e.currentTarget);
                        var thumbnailData = thumbnail.data("yoxthumbs");
                        if (!viewData.imagesAreSet)
                        {
                            thumbnail.css("cursor", "wait");
                            var newOptions = $.extend({}, options);
                            if (!newOptions.dataSourceOptions)
                                newOptions.dataSourceOptions = thumbnailData;
                            else
                                $.extend(newOptions.dataSourceOptions, thumbnailData);

                            Yox.dataSources[options.dataSource].getImagesData(newOptions, function(data){
                                viewData.images = data.images;
                                viewData.imagesAreSet = true;
                                thumbnail.css("cursor", "");
                                yoxviewApi.openGallery(viewData.viewIndex);
                            });
                        }
                        else
                        {
                            yoxviewApi.openGallery(viewData.viewIndex);
                        }
                    }
                );
				if (!_options.isSingleLink)
					$.each(view.yoxthumbs("thumbnails"), function(i, thumbnail){
						thumbnail.data("yoxview", {viewIndex: views.length});
						views.push(thumbnail);
					});
                if (!yoxviewApi.firstViewWithImages && data.images.length > 0)
                {
                    yoxviewApi.firstViewWithImages = view;
                    
                    if (_options.cacheImagesInBackground)
                        yoxviewApi.startCache();
                }
            });
        }
        else
		{
			view.data("yoxview").images = viewImages;
            createThumbnails(view);
		}
    }

    function createThumbnails(view, additionalImages, onClick)
    {
        view.yoxthumbs({ 
            images: additionalImages,
            enableOnlyMedia: true,
            onClick: onClick || function(e){
                e.preventDefault();
				if (options.thumbnailsOptions && options.thumbnailsOptions.onClick)
                    options.thumbnailsOptions.onClick(
                        $(e.currentTarget).data("yoxthumbs").imageIndex, 
                        $(e.currentTarget),
                        $(e.liveFired).data("yoxview").viewIndex);
                else
                    yoxviewApi.openGallery($(e.liveFired || e.currentTarget).data("yoxview").viewIndex,
                        $(e.currentTarget).data("yoxthumbs").imageIndex);

                return false;
            }
        });
    }
    function setThumbnail(setToPopupImage)
    {
        var currentView = $(views[currentViewIndex]);
        thumbnail = currentView[0].tagName == "A"
            ? currentView
            : currentView.yoxthumbs('thumbnails')[currentItemIndex];
        
        if (!thumbnail || thumbnail.length == 0)
            thumbnail = currentView.yoxthumbs('thumbnails')[0];
            
        var thumbnailImg = thumbnail.children("img:first");
        if (thumbnailImg)
        {
            if (setToPopupImage && image1)
                image1.attr("src", thumbnailImg.attr("src"));

            thumbnailPos = thumbnailImg.offset();
            thumbnailProperties = {
                width: thumbnailImg.width(), 
                height: thumbnailImg.height(), 
                top: thumbnailPos.top - $(window).scrollTop(), 
                left: thumbnailPos.left 
            };
        }
    }
    
//    Opens the viewer popup.
//    Arguments:
//    viewIndex: The 0-based index of the view to open, in case there are multiple instances of YoxView on the same page. Default is 0.
//    imageIndex: The 0-based index of the image to open, in the specified view. Default is 0.
//    callBack: A function to call after the gallery has opened.
    this.openGallery = function(viewIndex, initialItemIndex, callBack)
    {
        if (typeof(viewIndex) == 'function')
        {
            callBack = viewIndex;
            viewIndex = initialItemIndex = 0;
        }
        else if (typeof(initialItemIndex) == 'function')
        {
            callBack = initialItemIndex;
            initialItemIndex = 0;
        }
        viewIndex = viewIndex || 0;
        initialItemIndex = initialItemIndex || 0;

        loadViewImages(views[viewIndex]);

        if (!popup && imagesCount != 0)
            createPopup();

        this.selectImage(initialItemIndex);
        popupWrap.stop().css({ opacity: 1 }).fadeIn("slow", function(){ popupWrap.css("opacity", "") });

        if(options.cacheImagesInBackground)
            cacheImages(initialItemIndex);
            
        if (callBack)
            onOpenCallback = callBack;

        return false;
    }

    this.selectImage = function(itemIndex)
    {
        yoxviewApi.currentImage = images[itemIndex];
        currentItemIndex = itemIndex;
        
        setThumbnail(true);
        thumbnail.blur();

        panel1.css({
            "z-index" : "1",
            "width" : thumbnailProperties.width, 
            "height" : thumbnailProperties.height
        });
        panel2.css({
            "display" : "none",
            "z-index" : "2"
        });
        
        firstImage = true;

        popup.css({
            "width" : thumbnailProperties.width,
            "height" : thumbnailProperties.height,
            "top" : thumbnailProperties.top,
            "left" : thumbnailProperties.left
        });
        this.select(itemIndex);
    }
    this.refresh = function()
    {
        resumePlay = isPlaying;

        if (isPlaying)
            stopPlay();

        setImage(currentItemIndex);
        
        if (resumePlay)
            startPlay();
    }
    
//    Displays the specified image and shows the specified button, if specified. Use when the viewer is open.
//    Arguments:
//    imageIndex: The 0-based index of the image to display.
//    pressedBtn: a jQuery element of a button to display momentarily in the viewer. 
//                For example, if the image has been selected by pressing the Next button 
//                on the keyboard, specify the Next button. If no button should be display, leave blank.
    this.select = function(itemIndex, pressedBtn, viewIndex)
    {
        if (typeof pressedBtn === "number")
        {
            viewIndex = pressedBtn;
            pressedBtn = undefined;
        }
        viewIndex = viewIndex || 0;
        
        if (!isResizing)
        {
            if (itemIndex < 0)
                itemIndex = imagesCount - 1;
            else if (itemIndex == imagesCount)
                itemIndex = 0;

            if (!isPlaying && pressedBtn)
                flicker(pressedBtn);
                
            yoxviewApi.currentImage = images[itemIndex];
            currentItemIndex = itemIndex;
            setImage(currentItemIndex);
            
            // Set the cache buffer, if required:
            calculateCacheBuffer();
        
            // Handle event onSelect:
            if (options.onSelect)
                options.onSelect(itemIndex, images[itemIndex]);
        }
    }
    this.prev = function()
    {
        cacheDirectionForward = false;
        this.select(currentItemIndex - 1, prevBtn);
        return false;
    }
    this.next = function()
    {
        cacheDirectionForward = true;
        this.select(currentItemIndex + 1, nextBtn);
        return false;
    }
    this.first = function()
    {
        longFlicker(notifications["first"]);
        this.select(0);
        return false;
    }
    this.last = function()
    {
        longFlicker(notifications["last"]);
        this.select(imagesCount - 1);
        return false;
    }
    this.play = function()
    {
        if (imagesCount == 1)
            return;
            
        cacheDirectionForward = true;
        
        if (!isPlaying)
        {
            longFlicker(notifications["play"]);
            startPlay();
            playBtnText.text(currentLanguage.Pause);
        }
        else
        {
            longFlicker(notifications["pause"]);
            stopPlay();
            playBtnText.text(currentLanguage.Play);
        }
    }
    function flicker(button)
    {
        if (button.css("opacity") == 0)
            button.stop().animate({ opacity : 0 }, options.buttonsFadeTime, fadeOut(button));
    }
    function longFlicker(button)
    {
        button.stop().fadeIn(options.buttonsFadeTime, function(){ 
            $(this).delay(500)
            .fadeOut(options.buttonsFadeTime);
        });
    }
    function fadeIn(button)
    {
        $(button).stop().animate({ opacity : 0 }, options.buttonsFadeTime);
    }
    function fadeOut(button)
    {
        $(button).stop().animate({ opacity : 0.5 }, options.buttonsFadeTime);
    }

    this.close = function()
    {
        this.closeHelp();
        setThumbnail(false);
        resizePopup(thumbnailProperties.width, thumbnailProperties.height, thumbnailProperties.top, thumbnailProperties.left, function(){
            yoxviewApi.isOpen = false;
        });
        hideMenuPanel();
        
        if (infoPanel)
            hideInfoPanel(function(){
                infoText.html("");
            });

        newPanel.animate({
            width: thumbnailProperties.width,
            height: thumbnailProperties.height
        }, options.popupResizeTime, function(){
            newPanel.css("opacity", 1);
            popupBackground.css("opacity", options.backgroundOpacity);
        });
		
		popupWrap.stop().fadeOut(1000);
		 
		if (isPlaying)
			stopPlay();
			
        if (options.onClose)
            options.onClose();

        isResizing = false;
    }
    this.help = function()
    {
        if (this.isOpen)
        {
            if (helpPanel.css("display") == "none")
                helpPanel.css("display", "block").stop().animate({ opacity : 0.8 }, options.buttonsFadeTime);
            else
                this.closeHelp();
        }
    }
    this.closeHelp = function()
    {
        if (helpPanel.css("display") != "none")
        helpPanel.stop().animate({ opacity: 0 }, options.buttonsFadeTime, function(){
                helpPanel.css("display", "none");
            });
    }
    this.clickBtn = function(fn, stopPlaying)
    {
        if (stopPlaying && isPlaying)
            stopPlay();
            
        fn.call(this);
        return false;
    }
    
    function catchPress(e)
    {
        if (yoxviewApi && yoxviewApi.isOpen)
        {
            var pK = keyCodes[e.keyCode];
            var calledFunction = yoxviewApi[keyMappings[pK]];
            if (calledFunction)
            {
                e.preventDefault();
                calledFunction.apply(yoxviewApi);
                return false;
            }
            return true;
        }
        return true;
    }
    
    function createMenuButton(_title, btnFunction, stopPlay)
    {
        var btn = $("<a>", {
            href : "#",
            click : function(){
                return yoxviewApi.clickBtn(yoxviewApi[btnFunction], stopPlay);
            }         
        });
        var btnSpan = $("<span>" + _title + "</span>");
        btnSpan.css("opacity", "0")
        .appendTo(btn);

        btn.append(sprites.getSprite("icons", btnFunction));
        return btn;
    }

    // Prev and next buttons:
    function createNavButton(_function, _side)
    {      
        var navBtnImg = new Image();
        navBtnImg.src = options.imagesFolder + _side + ".png";
        var navBtn = $("<a>", {
            css : {
                "background" : "url(" + navBtnImg.src + ") no-repeat " + _side + " center",
                "opacity" : "0",
                "outline" : "0"
            },
            className : "yoxview_ctlBtn",
            href : "#",
            click : function(){
                this.blur();
                return yoxviewApi.clickBtn(_function, true);
            }
        });
        navBtn.css(_side, "0");
        return navBtn; 
    }
    
    // INIT:

    this.AddViews(_views, options);
    
    $(document).delegate('*', 'keydown', function(data){
        catchPress(data);
    });
    $(window).bind("resize.yoxview", function()
    {
        windowDimensions = getWindowDimensions();
        if (yoxviewApi.isOpen)
            yoxviewApi.resize();
    });
        
    function createPopup()
    {
        currentLanguage = yoxviewLanguages[options.lang];

        popupWrap = $("<div>", {
            id : "yoxview_popupWrap",
            css : {
                "position" : "fixed",
                "top" : "0",
                "left" : "0",
                "width" : "100%",
                "height" : "100%",
                "display" : "none",
                "z-index" : "100"
            }
        });
        
        popup = $("<div>", {
            id : 'yoxview'
        });
        popupWrap.appendTo($(parent.document.body)).append(popup);
        
		panel1 = $("<div>", {
			className: "yoxview_imgPanel",
			css: {
				"z-index": "2"
			}
		});
		panel2 = $("<div>", {
			className: "yoxview_imgPanel",
			css: {
				"z-index": "1",
				"display": "none"
			}
		});
        // the first image:
        image1 = $("<img />", {
            className : "yoxview_fadeImg",
            css : {
				"display" : "block",
				"width" : "100%",
				"height" : "100%"
			}
        });

        // the second image:
        image2 = $("<img />", {
            className : "yoxview_fadeImg",
            css : {
				"display" : "block",
				"width" : "100%",
				"height" : "100%"
			}
        });
        panel1.data("yoxviewPanel", {image: image1})
		.append(image1).appendTo(popup);
		panel2.data("yoxviewPanel", {image: image2})
		panel2.append(image2).appendTo(popup);
        var singleImage = imagesCount == 1;
        if (singleImage && !images[0].media[options.titleAttribute])
            options.renderInfo = false;
            
        // the menu:
        if (options.renderMenu !== false)
        {
            var menuPanelWrap = $("<div>", {
                className : "yoxview_popupBarPanel yoxview_top"
            });
            
            if (options.autoHideMenu !== false)
            {
                menuPanelWrap.mouseenter(function(){
                    if (yoxviewApi.isOpen)
                        showMenuPanel();
                })
                .mouseleave(function(){
                    if (yoxviewApi.isOpen)
                        hideMenuPanel();
                });
            }
            
            menuPanel = $("<div>", {
                id : "yoxview_menuPanel",
                css: { 
                    "opacity": "0.8",
                    "background-position": sprites.getBackgroundPosition("menu", "back")
                }
            });

            var helpBtn = createMenuButton(currentLanguage.Help, "help", false);
            var playBtn = createMenuButton(currentLanguage.Slideshow, "play", false);
            playBtnText = playBtn.children("span");
            
            menuPanel.append(
                createMenuButton(currentLanguage.Close, "close", true),
                helpBtn,
                playBtn
            );
            
            if (singleImage)
            {
                playBtn.css("display", "none");
                helpBtn.css("display", "none");
                menuPanel.css({
                    width: 58
                });
            }
            
            menuPanel.find("a:last-child").attr("class", "last");
            menuPanelWrap.append(menuPanel).appendTo(popup);
            menuPanel.delegate("a", "mouseenter", function(){
                $(this).stop().animate({ top : "8px" }, "fast").find("span").stop().animate({opacity:1}, "fast");
            })
            .delegate("a", "mouseleave", function(){
                $(this).stop().animate({ top : "0" }, "fast").find("span").stop().animate({opacity:0}, "fast");
            });
        }
        
        if (options.renderButtons !== false && !singleImage)
        {
            // prev and next buttons:            
            prevBtn = createNavButton(yoxviewApi.prev, options.isRTL ? "right" : "left");
            prevBtn.appendTo(popup);
            
            nextBtn = createNavButton(yoxviewApi.next, options.isRTL ? "left" : "right");
            nextBtn.appendTo(popup);
        }

        ctlButtons = popup.find(".yoxview_ctlBtn");

        // add the ajax loader:
        ajaxLoader = $("<div>", {
            id: "yoxview_ajaxLoader",
            className: "yoxview_notification",
            css: { 
                "display": "none"
            }
        });
        ajaxLoader.append($("<img>", {
            src: options.imagesFolder + "popup_ajax_loader.gif",
            alt: currentLanguage.Loading,
            css: {
                width: 32,
                height: 32,
                "background-image": "url(" + options.imagesFolder + "sprites.png)",
                "background-position": sprites.getBackgroundPosition("notifications", "empty")
            }
        }))
        .appendTo(popup);
        
        // notification images:
        var notificationsNames = ["play", "pause", "first", "last"];
        jQuery.each(notificationsNames, function(i, notificationName){
            var notification = sprites.getSprite("notifications", notificationName);

            notification.attr("className", "yoxview_notification")
            .css("display", "none")
            .appendTo(popup);
            notifications[notificationName] = notification;
        });
        
        // help:
        helpPanel = $("<div>", {
            id : "yoxview_helpPanel", 
            href : "#", 
            title : currentLanguage.CloseHelp,
            css : {
                "background" : "url(" + options.imagesFolder + "help_panel.png) no-repeat center top",
                "direction" : currentLanguage.Direction,
                "opacity" : "0"
            },
            click : function(){
                return yoxviewApi.clickBtn(yoxviewApi.help, false);
            }
        });
        
        var helpTitle = document.createElement("h1");
        helpTitle.innerHTML = currentLanguage.Help.toUpperCase();

        var helpText = document.createElement("p");
        helpText.innerHTML = currentLanguage.HelpText;
        
        var closeHelp = document.createElement("span");
        closeHelp.id = "yoxview_closeHelp";
        closeHelp.innerHTML = currentLanguage.CloseHelp;
        
        helpPanel.append(helpTitle).append(helpText).append(closeHelp).appendTo(popup);
        
        // popup info:
        if (options.renderInfo !== false)
        {
            infoPanelWrap = $("<div>", {
                className : "yoxview_popupBarPanel yoxview_bottom"
            });
            
            infoPanelWrap.mouseenter(function(){
                if (yoxviewApi.isOpen && !disableInfo && options.autoHideInfo !== false)
                    setInfoPanelHeight();
            })
            .mouseleave(function(){
                if (yoxviewApi.isOpen && !disableInfo && options.autoHideInfo !== false)
                    hideInfoPanel();
            });

            infoPanel = $("<div>", {
                id: "yoxview_infoPanel"
            });
            
            infoPanel.append(
                $("<div>", {
                    id : "yoxview_infoPanelBack",
                    css : {
                        "background" : options.infoBackColor,
                        "opacity" : options.infoBackOpacity
                    }
                })
            );
            infoPanelContent = $("<div>", {
                id: "yoxview_infoPanelContent"
            });
            
            countDisplay = $("<span>", {
                id: "yoxview_count"
            });
            
            infoText = $("<div>", {
                id: "yoxview_infoText"
            });
            if (singleImage)
            {
                infoText.css("margin-left", "10px");
                countDisplay.css("display", "none");
            }
            infoPanelContent.append(countDisplay);
            
            if (options.renderInfoPin !== false)
            {
                infoPinLinkImg = sprites.getSprite("icons", options.autoHideInfo ? "pin" : "unpin");
                infoPinLink = $("<a>", {
                    className: "yoxviewInfoLink",
                    href: "#",
                    title: options.autoHideInfo ? currentLanguage.PinInfo : currentLanguage.UnpinInfo,
                    css: { display: 'inline' },
                    click: function(e){
                        e.preventDefault();
                        options.autoHideInfo = !options.autoHideInfo;
                        infoPinLinkImg.css("background-position", sprites.getBackgroundPosition("icons", options.autoHideInfo ? "pin" : "unpin"));
                        this.title = options.autoHideInfo ? currentLanguage.PinInfo : currentLanguage.UnpinInfo;
                    }
                });
                infoPinLink.append(infoPinLinkImg).appendTo(infoPanelContent);
                
            }   
            if (options.linkToOriginalContext !== false)
            {
                infoPanelLink = $("<a>", {
                    className: "yoxviewInfoLink",
                    target: "_blank",
                    title: currentLanguage.OriginalContext
                });
                infoPanelLink.append(sprites.getSprite("icons", "link")).appendTo(infoPanelContent);
            }
            
			if (options.infoButtons)
			{
				yoxviewApi.infoButtons = options.infoButtons;
				for (infoButton in options.infoButtons)
				{
					options.infoButtons[infoButton].attr("className", "yoxviewInfoLink").css("display", "inline").appendTo(infoPanelContent);
				}
			}
			
            infoPanelContent.append(infoText);

            infoPanel.append(infoPanelContent).appendTo(infoPanelWrap);
            popup.append(infoPanelWrap);
        }        
        // set the background:
        popupBackground = $("<div>", {
            css : {
                "position" : "fixed",
                "height" : "100%",
                "width" : "100%",
                "top" : "0",
                "left" : "0",
                "background" : options.backgroundColor,
                "z-index" : "1",
                "opacity" : options.backgroundOpacity
            },
            click : function(){  
                return yoxviewApi.clickBtn(yoxviewApi.close, true);
            }  
        }).appendTo(popupWrap);

        if (options.buttonsFadeTime != 0)
        {
            ctlButtons.hover(
                function(){
                    if (yoxviewApi.isOpen)
                        $(this).stop().animate({ opacity : 0.6 }, options.buttonsFadeTime);
                },
                function(){
                    $(this).stop().animate({ opacity : 0 }, options.buttonsFadeTime);
                }
            );
        }
    }
    
    $(cacheImg).load(function()
    {
        $.extend(images[currentCacheImg].media, {
            width: this.width,
            height: this.height,
            loaded: true
        });
        advanceCache();
    })
    .error(function(){
        advanceCache();
	});
	
	function advanceCache()
	{
	    cachedImagesCount++;
        if (cachedImagesCount == imagesCount)
            cacheComplete = true;

        if (!cacheComplete)
            getCacheBuffer();
	}
    this.startCache = function()
    {
        loadViewImages(this.firstViewWithImages);
        calculateCacheBuffer();
        cacheImages(0);
    }
    function getCacheBuffer()
    {
        if (!options.cacheBuffer || currentCacheImg != cacheBufferLastIndex)
            cacheImages(currentCacheImg + (cacheDirectionForward ? 1 : -1));
    }
    function calculateCacheBuffer()
    {
        if (options.cacheBuffer)
        {
            cacheBufferLastIndex = cacheDirectionForward ? currentItemIndex + options.cacheBuffer : currentItemIndex - options.cacheBuffer;
            if (cacheBufferLastIndex < 0)
                cacheBufferLastIndex += imagesCount;
            else if (cacheBufferLastIndex >= imagesCount)
                cacheBufferLastIndex -= imagesCount;
        }
    }
    function cacheImages(imageIndexToCache)
    {
        if (cacheComplete)
            return;
            
        if (imageIndexToCache == imagesCount)
            imageIndexToCache = 0;
            
        else if (imageIndexToCache < 0)
            imageIndexToCache += imagesCount;
            
        var image = images[imageIndexToCache].media;
        currentCacheImg = imageIndexToCache;
        if (image && !image.loaded)
        {
            if (!image.type || image.type === "image")
                cacheImg.src = image.src;
            else
                loadMedia(image, function(){
                    advanceCache();
                });
        }
        else
            getCacheBuffer();
    }
    
    function showLoaderIcon()
    {
        loading = true;
        ajaxLoader.stop().stopTime()
        .oneTime(options.buttonsFadeTime, function()
        {
            $(this).stop().css("opacity", "0.6").fadeIn(options.buttonsFadeTime);
        });
    }

    function hideLoaderIcon()
    {
        loading = false;
        ajaxLoader.stop().stopTime().fadeOut(options.buttonsFadeTime);
    }

    function setImage(itemIndex)
    {
        if (!isPlaying)
        {
            showLoaderIcon();
        }
        loadAndDisplayMedia(yoxviewApi.currentImage.media);
    }
    
    function resizePopup(_width, _height, _top, _left, callBack)
    {
        popup.stop().animate({
            width: _width,
            height: _height,
            top: _top,
            left: _left
        }, options.popupResizeTime, callBack);
    }
    function startPlay()
    {
        if (imagesCount == 1)
            return;

        isPlaying = true;
        if (currentItemIndex < imagesCount - 1)
        {
            popup.oneTime(options.playDelay, "play", function(){
                yoxviewApi.next();
            });
        }
        else
        {
            if (options.loopPlay)
                popup.oneTime(options.playDelay, "play", function(){
                    yoxviewApi.select(0, null);
                });
            else
                stopPlay();
        }
    }
    function stopPlay()
    {
        popup.stopTime("play");
        isPlaying = false;
    }

    function blink(_element)
    {
        _element.animate({ opacity : 0.8 }, 1000, function()
        {
            $(this).animate({opacity: 0.2}, 1000, blink($(this)));
        });
    }
    
    var newPanel = panel1;
    var oldPanel = panel2;
    
    function getWindowDimensions()
    {
        var widthVal = $(parent.window).width();
        var heightVal = $(parent.window).height();
        var returnValue = {
            height : heightVal,
            width : widthVal,
            usableHeight : heightVal - options.popupMargin * 2,
            usableWidth : widthVal - options.popupMargin * 2
        };
        return returnValue;
    }
    windowDimensions = getWindowDimensions();
    
    this.resize = function()
    {
        if (isPlaying)
        {
            resumePlay = true;
            stopPlay();
        }

        var imageMaxSize = newPanel.data("maxSize");

        if (!imageMaxSize || !imageMaxSize)
            return;
            
        var newImageDimensions = Yox.fitImageSize(
            imageMaxSize,
            { width: windowDimensions.usableWidth, height: windowDimensions.usableHeight});

        newPanel.css({"width" : "100%", "height" : "100%"});
        
        var marginTop = Math.round((windowDimensions.height - newImageDimensions.height) / 2);
        var marginLeft = Math.round((windowDimensions.width - newImageDimensions.width) / 2);
        
        isResizing = true;
        if (newPanel.isMedia)
            ctlButtons.animate({top: newImageDimensions.height / 2 - mediaButtonsSize.height / 2}, options.popupResizeTime);

        resizePopup(newImageDimensions.width,
            newImageDimensions.height,
            marginTop,
            marginLeft,
            function(){
                var newImageWidth = popup.width();
                var newImageHeight = popup.height();

                newPanel.css({ "width" : newImageWidth + "px", "height" : newImageHeight + "px" });
                isResizing = false;

                if (infoPanel)
                    setInfoPanelHeight();
                
                if (resumePlay)
                {
                    startPlay();
                    resumePlay = false;
                }
            }
        );
    }

    function setInfoPanelHeight(callback)
    {
        clearTimeout(hideInfoTimeout);
        var titleHeight = infoText.outerHeight();

        if (titleHeight < infoPanelMinHeight)
            titleHeight = infoPanelMinHeight;
        
        infoPanel.stop().animate({height : titleHeight}, 500, function(){ 
            if (callback)
                callback();
        });
    }
    function hideInfoPanel(callback)
    {
        clearTimeout(hideInfoTimeout);
        infoPanel.stop().animate({ height: 0 }, 500, function(){
            if (callback)
                callback();
        });
    }
    function hideMenuPanel(callback)
    {
        clearTimeout(hideMenuTimeout);
        menuPanel.stop().animate({ top: menuHidePosition }, 500, function(){
            if (callback)
                callback();
        });
    }
    function showMenuPanel(callback)
    {
        clearTimeout(hideMenuTimeout);
        menuPanel.stop().animate({ top: 0 }, 500, function(){
            if (callback)
                callback();
        });
    }
    
	function changeMedia(media)
	{
	    var currentImageElement;
	    var mediaIsImage = !media.type || media.type === "image";
	    
	    if (mediaIsImage && disableInfo && infoPanel)
	        infoPanelWrap.css("display", "block");
	        
	    clearTimeout(hideInfoTimeout);
	    
        if (panel1.css('z-index') == 1)
        {
            newPanel = panel1;
            currentImageElement = image1;
            oldPanel = panel2;
        }
        else
        {
            newPanel = panel2;
            currentImageElement = image2;
            oldPanel = panel1;
        }

        newPanel.data("maxSize", { width: media.width, height: media.height});           

        var newImageDimensions = Yox.fitImageSize(
            media,
            { width: windowDimensions.usableWidth, height: windowDimensions.usableHeight });

        if (infoPanel)
        {
            var infoTextValue = media.title || "";
            if (media.description)
                infoTextValue += infoTextValue != ""
                    ? "<div id='yoxview_infoTextDescription'>" + media.description + "</div>"
                    : media.description;

            infoText.html(infoTextValue);
            
            if (imagesCount > 1)
                countDisplay.html(currentItemIndex + 1 + "/" + imagesCount);
            
            if (infoPanelLink)
            {
                if (yoxviewApi.currentImage.link)
                    infoPanelLink.attr("href", yoxviewApi.currentImage.link).css("display", "inline");
                else
                    infoPanelLink.css("display", "none");
            }
        }
        var panelData = newPanel.data("yoxviewPanel");
		if (mediaIsImage)
		{
			currentImageElement.attr({
				src : media.src,
				title : media.title,
				alt: media.alt
			});
			
			ctlButtons.css({"height": "100%", "width": "50%", "top": "0"});
			if(newPanel.isMedia)
			{
			    panelData.media.remove();
			    panelData.media = undefined;
			    panelData.image.show();
			    newPanel.isMedia = false;
			}
			disableInfo = false;
		}
		else
		{
		    if (!panelData.media)
		    {
		        panelData.media = $("<div>", {
		            className: "yoxview_mediaPanel"
		        });
		        panelData.image.hide();
		        newPanel.append(panelData.media);
		    }
		    else
		        panelData.media.show();
		        
			panelData.media.html(media.html);
			ctlButtons.css({
			    "width": mediaButtonsSize.width,
			    "height": mediaButtonsSize.height,
			    "top": (newImageDimensions.height / 2) - (mediaButtonsSize.height / 2)
			});
			if (!newPanel.isMedia)
			{
			    panelData.image.hide();
			    newPanel.isMedia = true;
			}
			if (infoPanel)
			{
			    if (options.autoHideInfo !== false)
			        hideInfoPanel();
    			    
			    infoPanelWrap.css("display", "none");
			    disableInfo = true;
			}
		}
        if (firstImage)
            newPanel.animate({
                width: newImageDimensions.width,
                height: newImageDimensions.height
            }, options.popupResizeTime);
        else
            newPanel.css({
                "width" : newImageDimensions.width + "px",
                "height" : newImageDimensions.height + "px"
            });

        var marginTop = Math.round((windowDimensions.height - newImageDimensions.height) / 2);
        var marginLeft = Math.round((windowDimensions.width - newImageDimensions.width) / 2);
              
        if (loading)
            hideLoaderIcon();

        isResizing = true;
        resizePopup(newImageDimensions.width,
            newImageDimensions.height,
            marginTop,
            marginLeft,
            function()
            {
                if (firstImage)
                {
                    yoxviewApi.isOpen = true;

                    if (options.controlsInitialDisplayTime > 0)
                    {
                        if (options.showButtonsOnOpen)
                            ctlButtons.animate({opacity: 0.5}, options.controlsInitialFadeTime, function(){ 
                                if(options.buttonsFadeTime != 0)
                                    $(this).delay(options.controlsInitialDisplayTime).animate({opacity : 0}, options.controlsInitialFadeTime);
                            });
                        
                        if (options.showBarsOnOpen)
                        {
                            showMenuPanel(function(){
                                if (options.autoHideMenu !== false)
                                    hideMenuTimeout = setTimeout(function(){ 
                                            hideMenuPanel();
                                        }, 
                                        options.controlsInitialDisplayTime
                                    );
                            });
                            if (infoPanel)
                                setInfoPanelHeight(function(){
                                    if (options.autoHideInfo !== false)
                                        hideInfoTimeout = setTimeout(function(){ hideInfoPanel(); }, options.controlsInitialDisplayTime);
                                });
                        }
                    }

                    if (options.autoPlay)
                        yoxviewApi.play();

                    if (options.onOpen)
                        options.onOpen();
                        
                    if (onOpenCallback)
                    {
                        onOpenCallback();
                        onOpenCallback = undefined;
                    }
            
                    firstImage = false;
                }
                isResizing = false;
            }
        );

        newPanel.css({'z-index': '2', opacity: 1});
        oldPanel.css('z-index', '1');
        
        newPanel.fadeIn(options.popupResizeTime, function(){
            oldPanel.css('display', 'none');
            if (infoPanel)
                setInfoPanelHeight(function(){
                    if (options.autoHideInfo !== false)
                        hideInfoTimeout = setTimeout(function(){ hideInfoPanel(); }, options.titleDisplayDuration);
                });
 
            if (imagesCount > 1)
            {
                if (currentItemIndex < imagesCount - 1 && options.cacheImagesInBackground)
                        cacheImages(currentItemIndex + 1);

                if (isPlaying)
                    startPlay();
            }
        });
	}
    $(tempImg).load(function()
    {
		if (this.width == 0)
		{
		    displayError("Image error");
            return;
        }
        changeMedia($.extend({}, yoxviewApi.currentImage.media, {
            width: this.width,
            height: this.height
        }));
    })
    .error(function(){
        displayError("Image not found:<br /><span class='errorUrl'>" + this.src + "</span>");
    });

    function loadMediaFromProvider(provider, url, availableSize, onLoad, onError)
    {
        jQuery.jsonp({
            url: (mediaProviderUrls[provider] || "http://oohembed.com/oohembed/"),
            data: jQuery.extend({
                "url" : url,
                "format": "json"
            }, availableSize),
            dataType: 'jsonp',
            callbackParameter: "callback",
            success: function(data)
            {
                var media = {
                    title: data.title,
                    width: data.width,
                    height: data.height,
                    type: data.type
                };
                
                if (data.type === "video")
                {
                    media.html = data.html
                        .replace(/<embed /, "<embed wmode=\"transparent\" ")
                        .replace(/<param/, "<param name=\"wmode\" value=\"transparent\"><param")
                        .replace(/width=\"[\d]+\"/ig, "width=\"100%\"")
                        .replace(/height=\"[\d]+\"/ig, "height=\"100%\"");
                }
                else if (data.type === "photo")
                {
                    jQuery.extend(media, {
                        src: data.url,
                        alt: data.title,
                        type: "image"
                    });                     
                }
                onLoad(media);
            },
            error: function(errorSender, errorMsg){
                if (onError)
                    onError(errorSender, errorMsg);
            }
        });
    };

    function loadAndDisplayMedia(media)
    {
        try
        {
            if (!media)
                throw("Error: Media is unavailable.");

            if (!media.type || media.type === "image")
            {
                // Resets the src attribute for the image - avoids a rendering problem in Chrome.
                // $.opacity is tested so this isn't applied in IE (up to IE8), 
                // since it creates a problem with the image's fading:
                if ($.support.opacity)
                    tempImg.src = "";

                tempImg.src = media.src;
            }
            else
            {
                if (!media.loaded)
                {
                    loadMedia(
                        media, 
                        function(loadedMedia){
                            changeMedia(loadedMedia);
                        },
                        function(errorSender)
                        {
                            displayError("Error getting data from:<br /><span class='errorUrl'>" + errorSender.data.url + "</span>");
                        }
                    );
		        }
		        else
		            changeMedia(yoxviewApi.currentImage.media);
		    }
		}
		catch(error)
		{
		    displayError(error);
		}
    }
    function loadMedia(media, onLoad, onError)
    {
        if (!media.type || media.type !== "image")
        {
	        loadMediaFromProvider(
	            media.provider,
	            media.url,
	            options.videoSize,
	            function(mediaData){
	                $.extend(media, mediaData, {loaded: true});
	                if (onLoad)
	                    onLoad(media);
	            },
	            onError
            );
        }
    }
    function displayError(errorMsg)
    {
        changeMedia({
            html: "<span class='yoxview_error'>" + errorMsg + "</span>",
            width: 500,
            height: 300,
            type: "error",
            title: ""
        });
    }
    this.unload = function(){
        jQuery.each(views, function(i, view){
            var $view = $(view);
            $view.undelegate("a", "click.yoxview")
            .removeData("yoxview")
            .yoxthumbs("unload", "yoxview");
        });
        
        $(window).unbind(".yoxview");
        
        if (popup){
            popupWrap.remove();
            popup = undefined;
        }
    };
}