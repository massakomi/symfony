$(document).ready(function(){
	var interval = setInterval(new_slide, 3000);
	//функции слайдера
	$('.slider_left_buttom').click(function(){
		var slide = $(this);
		change_slide(slide,-1);
		clearInterval(interval);
	})
	$('.slider_right_buttom').click(function(){
		var slide = $(this);
		change_slide(slide,1);
		clearInterval(interval);
	})
	$('.slider_indicators li').click(function(){
		var slide = $(this);
		var changes = $(this).index() - $(this).siblings(".active").index();
		if (!slide.hasClass("active")){
			change_slide(slide,changes);
			clearInterval(interval);
			interval = setInterval(new_slide, 3000);
		}
	})

	//плавающее меню
	var start_pos=$('#head_helper_bottom').offset().top;
	var menu_height =$('#head_helper_bottom').height();
	$(window).scroll(function(){
		if ($(window).scrollTop()>start_pos) {
			$('#head_helper_bottom').addClass('to_top');
		}
		else{
			$('#head_helper_bottom').removeClass('to_top');
		}
	});
	
	var i=0;
	
	//скрытая менюшка
	$('#extra_menu_button').click(function(){
		$('#extra_menu').css({"top":$('#head_helper_bottom').height()});
		$('#extra_menu').slideDown('fast');
		$('#menu_close').click(function(){
			$('#extra_menu').slideUp('fast');
		})
	})
});

//смена текущего слайда на значение change_value
function change_slide(slide,change_value){
	var parent_name = slide.parent().attr("class");
	var slider_name = slide.parent().parent().attr("id");
	var width = $("#"+slider_name+">.carousel_inner").css("width");
	var slides = $("#"+slider_name+">.carousel_inner").children(".item");
	var indicators = $("#"+slider_name+">.slider_indicators").children("li");
	var texts = $("#"+slider_name+">.slider_texts_area").children(".slider_text");
	var total_slide = slides.length;
	if (total_slide<2){
		return;
	}
	var cur_slide = $("#"+slider_name+">.carousel_inner>.active");
	var cur_number = cur_slide.index("#"+slider_name+">.carousel_inner>.item");
	
	
	var prev_slide = (cur_number+total_slide+change_value)%total_slide;
	//плавно крутим влево
	if (change_value>0){
		slides.eq(prev_slide).css({'left':width});
		cur_slide.animate({"left": "-="+width}, "slow");
		slides.eq(prev_slide).animate({"left": "-="+width}, "slow");
	//плавно крутим вправо
	}else if(change_value<0){
		slides.eq(prev_slide).css({"left":"-"+width});
		cur_slide.animate({"left": "+="+width}, "slow");
		slides.eq(prev_slide).animate({"left": "+="+width}, "slow");			
	}
	//обновляем тексты и интдикаторы
	cur_slide.removeClass("active");
	slides.eq(prev_slide).addClass("active");
	indicators.eq(cur_number).removeClass("active");
	indicators.eq(prev_slide).addClass("active");
	texts.eq(cur_number).removeClass("active");
	texts.eq(prev_slide).addClass("active");
}

function new_slide (){
	var slide = $('#slider_top .carousel_inner>.active');
	change_slide(slide,1);
};