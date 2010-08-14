Yox = {
    compare: function(obj1, obj2)
    {
        // deep-compare objects:
        function size(obj)
        {
            var size = 0;
            for (var keyName in obj)
            {
                if (keyName != null)
                    size++;
            }
            return size;
        }
        
        if (size(obj1) != size(obj2))
            return false;
            
        for(var keyName in obj1)
        {
            var value1 = obj1[keyName];
            var value2 = obj2[keyName];
            
            if (typeof value1 != typeof value2)
                return false;

            if (value1 && value1.length && (value1[0] !== undefined && value1[0].tagName))
            {
                if(!value2 || value2.length != value1.length || !value2[0].tagName || value2[0].tagName != value1[0].tagName)
                    return false;
            }
            else if (typeof value1 == 'function' || typeof value1 == 'object') {
                var equal = Yox.compare(value1, value2);
                if (!equal)
                    return equal;
            }
            else if (value1 != value2)
                return false;
        }
        return true;
    },
    dataRegex: {
        picasa: /http:\/\/(www.)?picasaweb\.google\..*/,
        flickr: /http:\/\/(www.)?flickr.com/,
        smugmug: /http:\/\/.*\.smugmug.com/
    },
    dataSources: [],
    fitImageSize: function(imageSize, targetSize, enlarge, isFill)
	{
		var resultSize = { width: imageSize.width, height: imageSize.height};
		if ((!enlarge && imageSize.width > targetSize.width) ||
		    (enlarge && imageSize.width != targetSize.width) )
		{
			resultSize.height = Math.round((targetSize.width / imageSize.width) * imageSize.height);
			resultSize.width = targetSize.width;
		}
		
		if (!isFill && resultSize.height > targetSize.height)
		{
			resultSize.width = Math.round((targetSize.height / resultSize.height) * resultSize.width);
			resultSize.height = targetSize.height;
		}
		else if (isFill && (resultSize.height < targetSize.height))
		{
			resultSize.height = targetSize.height;
			resultSize.width = Math.round((targetSize.height / imageSize.height) * imageSize.width);
		}
		
		return resultSize;
	},
    getDataSourceName: function(url)
    {
        for(dataSourceIndex in Yox.dataRegex)
        {
            if(url.match(Yox.dataRegex[dataSourceIndex]))
                return dataSourceIndex;
        }
        return null;
    },
    getPath: function(pathRegex)
    {
        var scripts = document.getElementsByTagName("script");
        for(var i=0; i<scripts.length; i++)
        {
            var currentScriptSrc = scripts[i].src;
            var matchPath = currentScriptSrc.match(pathRegex);
            if (matchPath)
                return matchPath[1];
        }
        
        return null;
    },
	imageRegex: /.*\.(?:jpg|jpeg|gif|png)/i,
    queryToJson: function(query)
    {
        var queryVars = query.split("&");
        var json = {};
        jQuery.each(queryVars, function(){
            var keyValue = this.split("=");
            json[keyValue[0]] = keyValue[1];
        });
        return json;
    },
    loadDataSource: function(options, callBack, target)
    {
        var self = this;
        var dataSourceName;
        if (options.dataUrl)
        {
            dataSourceName = Yox.getDataSourceName(options.dataUrl);
            if (dataSourceName)
                $.extend(options, { dataSource: dataSourceIndex });
        }
        if (options.dataSource && !Yox.dataSources[dataSourceName])
        {
            $.ajax({
                url : options.dataFolder + options.dataSource + ".js", 
                async : false,
                dataType : "script",
                success: function(data){
                    eval(data);
                    eval ("Yox.dataSources['" + options.dataSource + "'] = new yox_" + options.dataSource + "();");                      
                    callBack(target);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown)
                {
                    console.log(XMLHttpRequest, textStatus, errorThrown);
                }
            });
        }
        else
            callBack(target);
    },
    Sprites: function(sprites, spritesImage, srcImage)
    {
        var cacheImg = new Image();
        cacheImg.src = spritesImage;
        this.spritesImage = spritesImage;
        
        var currentTop = 0;
        jQuery.each(sprites, function(i, spriteGroup){
            spriteGroup.top = currentTop;
            currentTop += spriteGroup.height;
        });

        this.getSprite = function(spriteGroup, spriteName, title)
        {
            return jQuery("<img/>", {
                src: srcImage,
                alt: spriteName,
				title: title,
                css: {
                    width: sprites[spriteGroup].width,
                    height: sprites[spriteGroup].height,
                    "background-image": "url(" + spritesImage + ")",
                    "background-repeat": "no-repeat",
                    "background-position": this.getBackgroundPosition(spriteGroup, spriteName)
                }
            });
        }
        this.getBackgroundPosition = function(spriteGroup, spriteName)
        {
            var backgroundLeft = jQuery.inArray(spriteName, sprites[spriteGroup].sprites) * (sprites[spriteGroup].width || 0);
            return "-" + backgroundLeft + "px -" + sprites[spriteGroup].top + "px";
        }
    },
    videoRegex: {
        youtube: /.*youtube.com\/watch.*(?:v=[^&]+).*/i,
        vimeo: /vimeo.com\/\d+/i,
        hulu: /hulu.com\/watch\//i,
        viddler: /viddler.com\//i,
        flickr: /.*flickr.com\/.*/i,
        myspace: /.*vids.myspace.com\/.*/i,
        qik: /qik.com/i,
        revision3: /revision3.com/i,
        dailymotion: /dailymotion.com/i,
        "5min": /.*5min\.com\/Video/i
    }
}