/* ================================================================ 
Copyright (c) 2011 Stu Nicholls - iStu.co.uk. All rights reserved.
This script and the associated html may be modified in any 
way to fit your requirements.
=================================================================== */
$(window).load(function(){
/* just one variable to set-up */

speed = 600;
tabColor = '#069';
tabCurrent = '#09c';

/* setting the initial state of the slideshow and first image */
var picVar = $('.iStu12 li.images div.slide div.slidePanel');
totPic = picVar.length;
curPicWidth = picVar.eq(0).width();
curPicHeight = picVar.eq(0).height();
totWidth = 0;

/* calculate the total width of the images and set the div.slide to match */
$.each((picVar), function() {
caption=$(this).attr('caption');
$('.iStu12 li.caption').append('<b>'+caption+'</b>');
totWidth = totWidth+$(this).width();
});
$('ul.iStu12 li.images div.slide').width(totWidth);

current=0;
var captionVar = $('.iStu12 li.caption b');
tabSet ()

/* resize the containing elements, left/right arrow positions and add the first image caption */
resize(curPicWidth,curPicHeight)


/* monitor 'next' clicks */
$('.iStu12 li.next').click (function() {
	picVar = $('.iStu12 li.images div.slide div.slidePanel');

	/* animate the line of images left one photo - then remove the first image from set, make it the last image then finally move the set to absolute position left:0 */
	curPicWidth = picVar.eq(0).width();
	curPicHeight = picVar.eq(0).height();
	$('ul.iStu12 li.images div.slide').animate({left:-curPicWidth}, speed, 
		function() {
		$('ul.iStu12 li.images div.slide').find('div.slidePanel:first').remove().appendTo('ul.iStu12 li.images div.slide');
		$('ul.iStu12 li.images div.slide').css('left','0px'); 
	});
	/* get the width, height and alt for the currently displayed image */
	curPicWidth = picVar.eq(1).width();
	curPicHeight = picVar.eq(1).height();
	/* animate the containing elements and left/right arrow positions to match the current image */
	resize(curPicWidth,curPicHeight)
	current++
	if(current==totPic) {current=0;}
	tabSet ()

});

/* monitor 'previous' clicks */
$('.iStu12 li.prev').click (function() {
	/*  get the last image from the set and make it the fist image, then set the left position of the set to minus the width of the new first image */
	$('ul.iStu12 li.images div.slide').find('div.slidePanel:last').remove().prependTo('ul.iStu12 li.images div.slide');
	picVar = $('.iStu12 li.images div.slide div.slidePanel');

	curPicWidth = picVar.eq(0).width();
	curPicHeight = picVar.eq(0).height();

	$('ul.iStu12 li.images div.slide').css('left',-curPicWidth); 
	/* animate the first image to left = 0 */
	$('ul.iStu12 li.images div.slide').animate({left:0}, speed);

	/* animate the containing elements, left/right arrow positions to match the current image and change the caption */
	resize(curPicWidth,curPicHeight)
	current--
	if(current==-1) {current=totPic-1;}
	tabSet ()

});

/* tab clicking routine */
$('.iStu12 li.caption b').click (function() {
	clicked = $(this).index()
	/* if to the right of the current tab then slide left */
	if (clicked>current) {
		rotate=clicked-current;
		picVar = $('.iStu12 li.images div.slide div.slidePanel');
		curPicWidth = 0;

		for (var i=0; i<rotate; i++) {
			curPicWidth = curPicWidth+picVar.eq(i).width();
		}
		$('ul.iStu12 li.images div.slide').animate({left:-curPicWidth},{queue:false, duration:speed,  
			complete: function() {
			for (var n=0; n<rotate; n++) {
			$('ul.iStu12 li.images div.slide').find('div.slidePanel:first').remove().appendTo('ul.iStu12 li.images div.slide');
			$('ul.iStu12 li.images div.slide').css('left','0px'); 
			}}
		});

		/* get the width, height and alt for the currently displayed image */
		curPicWidth = picVar.eq(rotate).width();
		curPicHeight = picVar.eq(rotate).height();
		/* animate the containing elements and left/right arrow positions to match the current image */
		resize(curPicWidth,curPicHeight)
		current=clicked;
		tabSet ()
	}
	/* if to the left of the current tab then slide right */
	if (clicked<current) {
		rotate=current-clicked;
		picVar = $('.iStu12 li.images div.slide div.slidePanel');
		curPicWidth = 0;

		for (var i=0; i<rotate; i++) {
			curPicWidth = curPicWidth+picVar.eq(totPic-1).width();
			$('ul.iStu12 li.images div.slide').find('div.slidePanel:last').remove().prependTo('ul.iStu12 li.images div.slide');
		}

		$('ul.iStu12 li.images div.slide').css({left:-curPicWidth, 
			complete: function() {
			/* animate the first image to left = 0 */
			$('ul.iStu12 li.images div.slide').animate({left:0}, speed);
			}
		});
		/* get the width, height and alt for the currently displayed image */
		picVar = $('.iStu12 li.images div.slide div.slidePanel');
		curPicWidth = picVar.eq(0).width();
		curPicHeight = picVar.eq(0).height();
		/* animate the containing elements and left/right arrow positions to match the current image */
		resize(curPicWidth,curPicHeight)
		current=clicked;
		tabSet ()
	}
});

$('.iStu12 li.caption b').mouseover (function() {
	if ($(this).index()!==current) {
		$(this).css('background',tabCurrent);
	}
}).mouseout(function(){
	if ($(this).index()!==current) {
		$(this).css('background',tabColor);
	}
});


function tabSet () {
	captionVar.css('background',tabColor);
	captionVar.eq(current).css('background',tabCurrent);
}

/* resize the containing elements, the left/right arrow positions and update the caption */
function resize (w,h) {
	$('.iStu12').animate({width:w, height:h},speed);
	$('.iStu12 li.images').animate({width:w, height:h},speed);
}

});