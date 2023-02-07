
function UCPostAccordion(objAccordion){
	
  var g_activeClass;
  var g_dataLinkNum, g_accItem, g_scrollOffset;
  
  /**
   * console log some string
   */
  function trace(str){
	  console.log(str);
  }
  
  //scroll func
  function scrollToTop(){
	
    var panel = jQuery(this).closest(".uc_ac_box");
    
    var panelTop = panel.offset().top;
    
    var scrollTop = panelTop + g_scrollOffset;
    
    jQuery('html, body').animate({
      scrollTop: scrollTop
    }, 400);
    
  }
  
  function onItemClick(){
    
    var objCaption = jQuery(this);
    var objContent = objCaption.next();
    var objItem = objCaption.parent();
    
    var isActive = objItem.hasClass(g_activeClass);
    
    if(isActive == true){              
      objItem.removeClass(g_activeClass);
      objContent.slideUp();        	            	
      return(true);
    }
    
    var objOpened = objAccordion.find("."+g_activeClass);
    objOpened.removeClass(g_activeClass);
    objOpened.find(".uc_content").slideUp();
    
    objItem.addClass(g_activeClass);
    
    var dataScroll = objAccordion.data('scroll');
    
	var animationDuration = 400;
	
	var isScroll = false;
		
	switch(dataScroll){
		case "desktop":
			if(window.matchMedia("(min-width: 1024px)").matches)
				isScroll = true;
		break;
		case "mobile":
			if(window.matchMedia("(max-width: 1024px)").matches)
				isScroll = true;
		break;
		case "desktop+mobile":
				isScroll = true;
		break;
	}
	
	if(isScroll == true)
		objContent.slideDown(animationDuration, scrollToTop);
	else
      objContent.slideDown();
	
	
    return false;
  }
  
  function linkToSlideScroll(){    
    jQuery('html, body').animate({
      scrollTop: g_accItem.offset().top + g_scrollOffset
    }, 400);
  }
  
  /*
  * check if element is in viewport
  */
  function isElementInViewport(objElement) {
    
    var elementTop = objElement.offset().top;
    var elementBottom = elementTop + objElement.outerHeight();
    
    var viewportTop = jQuery(window).scrollTop();
    var viewportBottom = viewportTop + jQuery(window).height();
    
    var isInViwport = elementBottom > viewportTop && elementTop < viewportBottom;
    
    return(isInViwport);
  } 
  
  /*
  * on trigger link click
  */
  function onLinkClick(e){
    
    var objLink = jQuery(this);
    
    var dataAccName = objAccordion.data('name');
    var dataLinkName = objLink.data('name');
    g_dataLinkNum = objLink.data('num');
    g_accItem = objAccordion.find('.uc_ac_box .uc_trigger').eq(g_dataLinkNum - 1);
    var accItemNumber = objAccordion.find('.uc_ac_box').length;
    
    if(dataLinkName != dataAccName)
    return(false);
    
    if(dataLinkName == undefined || g_dataLinkNum == '' || g_dataLinkNum > accItemNumber)
    return(false);
    
    var accItemContent = g_accItem.next();
    var accItemParent = g_accItem.parent();
    var isItemParentActive = accItemParent.hasClass("uc-item-active");
    
    e.preventDefault();
    
    if(isItemParentActive == true && isElementInViewport(accItemParent) == false){
      
      linkToSlideScroll();
      
    }else if(isItemParentActive == false && isElementInViewport(accItemParent) == false){
      
      onItemClick();	      
      accItemContent.slideDown(linkToSlideScroll);
      accItemParent.addClass("uc-item-active");
      
    }else if(isItemParentActive == false && isElementInViewport(accItemParent) == true){
      
      onItemClick();	      
      accItemContent.slideDown();
      accItemParent.addClass("uc-item-active");
    }
  }
  
  /**
  * init open links
  */
  function initLinks(){
	
    
	var objLinks = jQuery('.ue-link-goto-item').not(".uc-link-inited");
	
    if(objLinks.length == 0){
      return(false);
    }

	var elementName = objAccordion.data("name");

	jQuery.each(objLinks, function(index, linkElement){
		
		var objLink = jQuery(linkElement);
		
		var name = objLink.data("name");
		
		if(name != elementName)
			return(true);
		
		objLink.addClass("uc-link-inited");
		
		objLink.on("click", onLinkClick);
		
	});
	
	
  }
  
  
  function runPostAccordion(){
    //init globals
    	
    g_activeClass = "uc-item-active";
	
    g_scrollOffset = objAccordion.data('offset');
    if(g_scrollOffset)
    	g_scrollOffset = 0;
    
	initLinks();

    //init events
    
	objAccordion.on("click", ".uc_trigger", onItemClick);
    objAccordion.on("ucclick", ".uc_trigger", onItemClick);
  }

  runPostAccordion();
  
}