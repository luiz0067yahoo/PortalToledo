function loadDimension(){
    $(".proportion-3x4").each(function(){
        $(this).height($(this).width()/4*3);
    });
    $(".proportion-16x9").each(function(){
        $(this).height($(this).width()/16*9);
    });
    $(".proportion-21x9").each(function(){
        $(this).height($(this).width()/21*9);
    });
    $(".proportion-3x1").each(function(){
        $(this).height($(this).width()/3*1);
    });
    $(".proportion-4x1").each(function(){
        $(this).height($(this).width()/4*1);
    });
    $(".proportion-5x1").each(function(){
        $(this).height($(this).width()/5*1);
    });
    $(".proportion-10x1").each(function(){
        $(this).height($(this).width()/10*1);
    });
    $(".square").each(function(){
        $(this).height($(this).width());
    });
    $(".height-parent").each(function(){
        $(this).height($(this).parent().height());
    });
    setTimeout(loadDimension,1000);
}

setTimeout(loadDimension);
    if ($( window ).width()<=600){
    	$("#navbarNavDropdown").hide();	
    	$("#anuncio_eventos").hide();	

    	$(".mobile-none").removeClass("d-none").addClass("d-none");	
    	$(".mobile-show").removeClass("d-none");	
    	$(".desktop-show").removeClass("d-none").addClass("d-none");	
    	$(".desktop-none").removeClass("d-none");	
    }
    else{
        $("#navbarNavDropdown").find(".nav-item").hover(
        function(){
            $(this).find(".dropdown-menu").removeClass("show").addClass("show");
        },
        function(){
            $(this).find(".dropdown-menu").removeClass("show");
        }
        );
    	$(".mobile-none").removeClass("d-none");
    	$(".mobile-show").removeClass("d-none").addClass("d-none");		
    	$(".desktop-show").removeClass("d-none");
    	$(".desktop-none").removeClass("d-none").addClass("d-none");		
    }
    
    //abrir_menus
    
    $("#abrir_menus").click(function(){
    	$("#navbarNavDropdown").toggle();	
    });

    $("#fecharsearchMobile").click(function(){
    	$("#searchMobile").removeClass("d-flex").addClass("d-none");	
    });

    $("#abrirsearchMobile").click(function(){
    	$("#searchMobile").removeClass("d-none").addClass("d-flex");	
    });
    
    $(document).ready(function() {
    // Seleciona todos os carrosséis (.carousel) que NÃO tenham IDs começando com 'banner-carousel'
    $('.carousel:not([id^="banner-carousel"])').each(function (){
        // Pega o intervalo do atributo data-interval do HTML, se existir. 
        // Caso contrário, usa 3000ms (3 segundos)
        var intervalSpeed = $(this).data('interval') || 3000;

        $(this).carousel({
            interval: intervalSpeed, 
            cycle: true
        }); 
    });
});
    var lightbox = new SimpleLightbox({
        $items: $('.galleryItem')
    });
	




jQuery(function ($) {
    $.fn.hScroll = function (amount) {
        amount = amount || 120;
        $(this).bind("DOMMouseScroll mousewheel", function (event) {
            var oEvent = event.originalEvent, 
                direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta, 
                position = $(this).scrollLeft();
            position += direction > 0 ? -amount : amount;
			if(direction > 0){
				$(this).attr("direct","right");
			}
			else
				$(this).attr("direct","left");
            $(this).scrollLeft(position);
            event.preventDefault();
        })
    };
});



$(document).ready(function() {
    $(".box_scroll").each(function(){
        $(this).hScroll(60); // You can pass (optionally) scrolling amount
    });
});
function moveScrollBox(){
    var time=10; 
	$(".box_scroll").each(function(){
        var box_scroll=$(this);
        //if((box_scroll.find(".item").length*250)>box_scroll.width())
        {

            var box_scroll=$(this);
            var position = box_scroll.scrollLeft();
            
            
            
            if(box_scroll.attr("direct")=="left"){
                var time=(position/20);
                if(position >= 250){
                    position = 5;
                    box_scroll.scrollLeft(position); 
                    time=2000;
                    var first=box_scroll.find(".item").first().clone();
                    box_scroll.append( first);
                    box_scroll.find(".item").first().remove();
                }
                box_scroll.scrollLeft(position+(1));
            }
            else {
                var time=((250-position)/20);
                if(position == 0){
                    position = 245;
                    box_scroll.scrollLeft(position); 
                    time=2000;
                    var last=box_scroll.find(".item").last().clone();
                    box_scroll.prepend( last);
                    box_scroll.find(".item").last().remove();
                }
                box_scroll.scrollLeft(position-(1));
            }
    	}
    	
	    });
	    setTimeout(moveScrollBox,time );
    }
moveScrollBox();

