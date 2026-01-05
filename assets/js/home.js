var loadDimension;
$(document).ready(function() {
	 loadDimension=function(){
		$(".height-square").each(function(){
				$(this).height($(this).width());
		});
		$(".dimenssion-parent").each(function(){
			height=($(this).parent().height());
			width=($(this).parent().width());
			$(this).height(height);
			$(this).width(width);
		});
		$(".max-height-parent-first-child").each(function(){
			height=($(this).parent().children(":first").height());
			$(this).css("max-height",height+"px");
		});
		$(".min-height-first-child").each(function(){
			height=($(this).first().height());
			if($(this).css("max-height").replace('px', '')>height)
				$(this).css("min-height",$(this).css("max-height"));
			else
				$(this).css("min-height",height+"px");
		});
		$(".h-80").each(function(){
			$(this).height($(this).parent().height()*0.8);
		});

	}
	setTimeout(loadDimension,250);


    $(".news .img").hover(function(e) {
        $(this).find(".shadow").height($(this).parent().height());
    }, function(e) {
        //$(this).find(".shadow").css("height","100%");
    });


    var countSizeInc = 0;
    var elementBody = $('*');
    var elementBtnIncreaseFont = $('#increase-font');
    var elementBtnDecreaseFont = $('#decrease-font');
    elementBtnIncreaseFont.click(function(event) {
        if (countSizeInc < 4) {
            elementBody.each(function() {
                var fontSize = $(this).css("fontSize");
                fontSize = fontSize.substr(0, fontSize.length - 2);
                fontSize = (fontSize * 1) + 2;
                $(this).css("fontSize", fontSize + "px");
            })
            countSizeInc++;
            $(".accessibility-container").width(500 + (countSizeInc * 50));
            $("#espacoBarraTopo").height($("#barraTopo").height());
        }
    });
    elementBtnDecreaseFont.click(function(event) {
        if (countSizeInc > 0) {
            elementBody.each(function() {
                var fontSize = $(this).css("fontSize");
                fontSize = fontSize.substr(0, fontSize.length - 2);
                fontSize = (fontSize * 1) - 2;
                $(this).css("fontSize", fontSize + "px");
            })
            countSizeInc--;
            $(".accessibility-container").width(500 + (countSizeInc * 50));
            $("#espacoBarraTopo").height($("#barraTopo").height());
        }
    });



    const html = document.querySelector("html")
    const getStyle = (element, style) => window.getComputedStyle(element).getPropertyValue(style)
    const initialColors = {
        "cor-0": getStyle(html, "--cor-0"),
        "cor-1": getStyle(html, "--cor-1"),
        "cor-2": getStyle(html, "--cor-2"),
        "cor-3": getStyle(html, "--cor-3"),
        "cor-4": getStyle(html, "--cor-4"),
        "cor-5": getStyle(html, "--cor-5"),
        "cor-6": getStyle(html, "--cor-6"),
        "cor-7": getStyle(html, "--cor-7"),
        "cor-8": getStyle(html, "--cor-8"),
        "cor-9": getStyle(html, "--cor-9"),
        "cor-10": getStyle(html, "--cor-10"),
        "cor-11": getStyle(html, "--cor-11"),
        "cor-12": getStyle(html, "--cor-12"),
        "cor-13": getStyle(html, "--cor-13"),
        "cor-14": getStyle(html, "--cor-14"),
        "cor-15": getStyle(html, "--cor-15")
    }
    const darkMode = {
        "cor-0": "#000000",
        /* #ffffff */
        "cor-1": "#eeeeee",
        /* #333333 */
        "cor-2": "#e2e1e0",
        /* #d0d0D0; = Cinza */
        "cor-3": "#333333",
        /* #eeeeee; = Cinza claro */
        "cor-4": "#fdea14",
        /* #01913a; = Verde */
        "cor-5": "#ff8566",
        /* #e00b1c = Vermelho */
        "cor-6": "#01913a",
        /* #fdea14 = amarelo */
        "cor-7": "#fdea14",
        /* #3399cc = azul ti */
        "cor-8": "fdea14",
        /* #005C90 = Azul para substituir o verde */
        "cor-9": "#ffffff",
        /* #000000 = preto*/
        "cor-10": "#0000FF",
        /* #AED6F1 = azul focus input */
        "cor-11": "#FEFEFE",
        /* #212529 = azul botão wordpress */
        "cor-12": "#8888FF",
        /* #0d6efd = hover azul botão wordpress */

    }
    const transformKey = key => "--" + key.replace(/([A-Z])/, "-$1").toLowerCase()
    const changeColors = (colors) => {
        Object.keys(colors).map(key =>
            html.style.setProperty(transformKey(key), colors[key])
        )
    }

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }


    if (getCookie("darkMode") == "darkMode") {
        $("body").addClass("darkMode");
        changeColors(darkMode);
    }

    $("#contrast").click(function() {
        if ($("body").hasClass("darkMode")) {
            changeColors(initialColors);
            setCookie("darkMode", "default", 1);
        } else {
            changeColors(darkMode);
            setCookie("darkMode", "darkMode", 1);
        }
        $("body").toggleClass("darkMode");
    });



    $("#mandar").click(function() {
        Swal.fire({
            html: "<div style='font-size: 17px;'> <h4> Acessibilidade </h4> <p> Pensando na acessibilidade da informação a todos os cidadãos, algumas ferramentas foram desenvolvidas no Portal da Transparencia,sendo elas: </p> <h4> Modo Alto Contraste: </h4> <i class = 'fas fa-adjust'> </i> <p> Para facilitar a leitura basta clicar neste ícone, localizado no menu superior da tela, que a combinação das cores(branco e preto) irão oferecer um maior contraste. </p> <h4> Tamanho: </h4>  <p> Esta opção possibilita o redimensionamento das informações do portal. </p> <p> A + Para aumentar a visibilidade, clicar nesta opção. <br> A- Para diminuir a visibilidade, clicar nesta opção. </p> <h4> Suite VLibras: </h4> <p> Para a tradução das informações disponíveis no portal em Língua Brasileira de Sinais - LIBRAS, sugerimos a utilização da ferramenta de código aberto e sem custo: Suite VLibras, a qual assegura o acesso ao portal para as pessoas que possuem redução ou ausência da capacidade auditiva. <br><br> Mais informações sobre a ferramenta: <a target='_blank' href = 'https://softwarepublico.gov.br/social/suite-vlibras'> Suite VLibras </a> </p> <div>"
        });
    })


    showHideTableContent = function(event) {
        var table = jQuery(event.target).closest("table");
        table.find("tbody").toggle();
        table.find("caption").find("i").toggleClass("fa-chevron-down fa-chevron-up");
        table.find("thead").toggle();
        if (table.find("caption").find("i").hasClass("fa-chevron-down")) {
            var tables = jQuery(".table-link-file-upload-box");
            tables.each(function(index) {
                var position = tables.index(table);
                if (position != index) {
                    jQuery(this).find("tbody").hide();
                    jQuery(this).find("thead").hide();
                    jQuery(this).find("caption").find("i").removeClass("fa-chevron-down").addClass("fa-chevron-up");
                }
            });
            tables = jQuery(".table-link-box");
            tables.each(function(index) {
                var position = tables.index(table);
                if (position != index) {
                    jQuery(this).find("tbody").hide();
                    jQuery(this).find("thead").hide();
                    jQuery(this).find("caption").find("i").removeClass("fa-chevron-down").addClass("fa-chevron-up");
                }
            });
        }
    }


    $(".table-link-file-upload-box").each(function(index) {
        $(this).find("caption").click(function(event) {
            showHideTableContent(event)
        })
    });
    $(".table-link-box").each(function(index) {
        $(this).find("caption").click(function(event) {
            showHideTableContent(event)
        })
    });










    /*agenda*/
	
    var day_mili = 1 * 24 * 60 * 60 * 1000;
    dayWeek = ["DOM", "SEG", "TER", "QUA", "QUI", "SEX", "SAB"];

    function dayWeekGenerator(date_, index) {
        $(".week").eq(index).html(dayWeek[date_.getDay()]);
        //$(".days").parent().scrollLeft(17 + 130 * 2);
        jQuery(".days").parent().scrollLeft(Math.round((jQuery(".days").width() - jQuery(".days").parent().width()) / 2)); //320 250
    }
    $(".year").html((new Date).getFullYear());
    $(".year_left").on("click", function() {
        $(".year").html(parseInt($(".year").html()) - 1);
        moveMounth();
        changeEventDate();
    });
    $(".year_right").on("click", function() {
        $(".year").html(parseInt($(".year").html()) + 1);
        moveMounth();
        changeEventDate();
    });
    //##############################   mounth  ###########################
    var mounth_list = $(".mounths").find(".mounth");
    mounth_list.each(
        function(index) {
            mounth_active = (new Date()).getMonth();
            $(".mounth").eq(mounth_active).addClass("mounth_active");
            moveMounth();
        }
    );
    $(".mounth").on("click", function() {
        $(".mounth_active").removeClass("mounth_active");
        $(this).addClass("mounth_active");
        moveMounth();
        changeEventDate();
    });


    function moveMounth() {
        year = $(".year").html();
        var mounth_active = $(".mounths").find(".mounth").index($(".mounth_active"));
        var mounthLastDay = new Date(year, mounth_active + 1, 0);
        var day_active = $(".day_active").html();

        if (parseInt(day_active) > mounthLastDay.getDate()) {
            day_active = String(mounthLastDay.getDate()).padStart(2, '0');
            if (day_active < 10)
                day_active = "0" + mounth_active;
            var current_date_str = year + "-" + mounth_active + "-" + day_active;
            var current_date = new Date(current_date_str);
            var startDate = new Date();
            startDate.setTime(current_date.getTime() - 2 * day_mili);
            var generator_date = new Date();
            day_list.each(
                function(index) {
                    generator_date.setTime(startDate.getTime() + index * day_mili);
                    $(this).html(String(generator_date.getDate()).padStart(2, '0'));
                    dayWeekGenerator(generator_date, index);
                }
            );
        }
        if (mounth_active == 0)
            $(".mounths").parent().scrollLeft(10 + 100 * mounth_active)
        else
            $(".mounths").parent().scrollLeft(-90 + 100 * mounth_active)

    }

    $(".left_mounth").on("click", function() {
        var mounth_list = $(this).parent().find(".mounths").find(".mounth");
        var position_mounth = -1;
        mounth_list.each(
            function(index) {
                if ($(this).hasClass("mounth_active")) {
                    position_mounth = index;
                    $(this).removeClass("mounth_active")
                }
            }
        );

        if (position_mounth < 0)
            position_mounth = mounth_list.length;
        mounth_list.eq(position_mounth - 1).addClass("mounth_active");
        moveMounth();
        if (position_mounth == 0) {
            $(".year").html(parseInt($(".year").html()) - 1);
        }
        changeEventDate();
    });

    $(".right_mounth").on("click", function() {
        var position_mounth = -1;
        var mounth_list = $(this).parent().find(".mounths").find(".mounth");
        mounth_list.each(
            function(index) {
                if ($(this).hasClass("mounth_active")) {
                    position_mounth = index;
                    $(this).removeClass("mounth_active")
                }
            }
        );
        if (position_mounth >= mounth_list.length - 1)
            position_mounth = -1
        mounth_list.eq(position_mounth + 1).addClass("mounth_active");
        moveMounth();
        if (position_mounth == -1) {
            $(".year").html(parseInt($(".year").html()) + 1);
        }
        changeEventDate();
    });


    //#############################dias#########################################################################



    var day_list = $(".days").find(".day");
    day_list.each(
        function(index) {
            var current_date_str = $("#eventDate").val();
            var current_date = new Date(current_date_str);
            current_date.setDate(current_date.getDate() - 1 + index);
            $(this).html(String(current_date.getDate()).padStart(2, '0'));
            dayWeekGenerator(current_date, index);
        }
    );
    jQuery(".days").parent().scrollLeft(Math.round((jQuery(".days").width() - jQuery(".days").parent().width()) / 2)); //320 250


    $(".day").on("click", function() {
        acc_day_active = $(".day_active").html();
        day_active = $(this).html()
        year = $(".year").html();
        mounth_active = $(".mounths").find(".mounth").index($(".mounth_active")) + 1;
        if (mounth_active < 10)
            mounth_active = "0" + mounth_active;
        var current_date_str = year + "-" + mounth_active + "-" + day_active;
        var current_date = new Date(current_date_str);
        if ((acc_day_active - 5) > (day_active * 1))
            $(".right_mounth").click();
        if ((acc_day_active + 5) < (day_active * 1))
            $(".left_mounth").click();
        var startDate = new Date();
        startDate.setTime(current_date.getTime() - 1 * day_mili);
        var generator_date = new Date();
        day_list.each(
            function(index) {
                generator_date.setTime(startDate.getTime() + index * day_mili);
                $(this).html(String(generator_date.getDate()).padStart(2, '0'));
                dayWeekGenerator(generator_date, index);
            }
        );
        changeEventDate();
    });

    $(".left_day").on("click", function() {
        day_active = $(".day_active").html()
        year = $(".year").html();
        mounth_active = $(".mounths").find(".mounth").index($(".mounth_active")) + 1;
        var mounthLastDay = new Date(year, mounth_active, 0);
        if (mounth_active < 10)
            mounth_active = "0" + mounth_active;
        if (day_active == 1) {
            $(".left_mounth").click();
        }
        var current_date_str = year + "-" + mounth_active + "-" + day_active;
        var current_date = new Date(current_date_str);
        var startDate = new Date();
        startDate.setTime(current_date.getTime() - 2 * day_mili);
        var generator_date = new Date();
        day_list.each(
            function(index) {
                generator_date.setTime(startDate.getTime() + index * day_mili);
                $(this).html(String(generator_date.getDate()).padStart(2, '0'));
                dayWeekGenerator(generator_date, index);
            }
        );
        changeEventDate();
    });

    $(".right_day").on("click", function() {

        day_active = $(".day_active").html()
        year = $(".year").html();
        mounth_active = $(".mounths").find(".mounth").index($(".mounth_active")) + 1;
        var mounthLastDay = new Date(year, mounth_active, 0);
        if (mounth_active < 10)
            mounth_active = "0" + mounth_active;
        if (day_active == String(mounthLastDay.getDate()).padStart(2, '0')) {
            $(".right_mounth").click();
        }
        var current_date_str = year + "-" + mounth_active + "-" + day_active;
        var current_date = new Date(current_date_str);
        var startDate = new Date();
        startDate.setTime(current_date.getTime());
        var generator_date = new Date();
        day_list.each(
            function(index) {
                generator_date.setTime(startDate.getTime() + index * day_mili);
                $(this).html(String(generator_date.getDate()).padStart(2, '0'));
                dayWeekGenerator(generator_date, index);
            }
        );
        changeEventDate();
    });



    function changeEventDate() {
        day_active = $(".day_active").html();
        year = $(".year").html();
        mounth_active = $(".mounths").find(".mounth").index($(".mounth_active")) + 1;
        if (mounth_active < 10)
            mounth_active = "0" + mounth_active;
        var current_date_str = year + "-" + mounth_active + "-" + day_active;
        $("#eventDate").val(current_date_str);
        var url = $("#agendaFrom").attr("action") + "&eventDate=" + $("#eventDate").val();
        if ($("#startDate").val() == "true")
            url += "&startDate=" + $("#startDate").val();
        $("#loadResultDataEvent").load(url,loadDimension);
    }


    $("#searchButton").click(function() {
        var url = $("#agendaFrom").attr("action") + "&search=" + $("#search").val();
        if ($("#startDate").val() == "true")
            url += "&startDate=" + $("#startDate").val();
        $("#loadResultDataEvent").load(url,loadDimension);
    });

    $("#searchButtonAll").click(function() {
        $("#startDate").val("true");
        var url = $("#agendaFrom").attr("action") + "&search=" + $("#search").val();
        url += "&eventDate=" + $("#eventDate").val();
        if ($("#startDate").val() == "true")
            url += "&startDate=" + $("#startDate").val();
        $("#loadResultDataEvent").load(url,loadDimension);
        $("#startDate").val("");
    });
    $("#searchButtonAll").click();


$(".bigContainerScheduleEvent-next").on("click", function(event){
		var index =$(".big-event").index($(event.target).closest(".big-event"));
		$(".big-event").eq(index).addClass("d-none");
		index++;
		$(".big-event").eq(index).removeClass("d-none");
		loadDimension();
		setTimeout(loadDimension,100);
		setTimeout(loadDimension,200);
	});
	$(".bigContainerScheduleEvent-back").on("click", function(event){
		var index =$(".big-event").index($(event.target).closest(".big-event"));
		$(".big-event").eq(index).addClass("d-none");
		index--;
		$(".big-event").eq(index).removeClass("d-none");
		loadDimension();
		setTimeout(loadDimension,100);
		setTimeout(loadDimension,200);
	});
	$(".bigContainerScheduleEvent-close").on("click", function(event){
		console.log($(event.target).closest(".containerScheduleEvent-container"));
		if($(event.target).closest(".containerScheduleEvent-container").length==0){
			$(event.target).closest(".big-event").addClass("d-none");
			loadDimension();
			setTimeout(loadDimension,100);
			setTimeout(loadDimension,200);
		}
	});
	$( "*" ).keydown(function( event ) {
		$(".bigContainerScheduleEvent-full").each(function(){
			if(!$(this).hasClass("d-none")){
					if (event.which == 37) {//left
						var index =$(".big-event").index($(this).find(".big-event"));
						$(".big-event").eq(index).addClass("d-none");
						index--;
						$(".big-event").eq(index).removeClass("d-none");
						loadDimension();
						setTimeout(loadDimension,100);
						setTimeout(loadDimension,200);
					}
					else if(event.which == 38){//up
						
					}
					else if(event.which == 39){//right 
						var index =$(".big-event").index($(this).find(".big-event"));
						$(".big-event").eq(index).addClass("d-none");
						index++;
						$(".big-event").eq(index).removeClass("d-none");
						loadDimension();
						setTimeout(loadDimension,100);
						setTimeout(loadDimension,200);
					}
					else if(event.which == 40){//down 
						
					}
					else if(event.which == 27){ // esc
						$(this).addClass("d-none");
					}
					event.preventDefault();
			}
		});
	});
	
	
	



    var lightbox = new SimpleLightbox({
        $items: $('.galleryItem')
    });


    //slider noticia

    const slider = $('.slider-inner');
    const progressBar = $('.prog-bar-inner');
    var movementX = 0;
    var strart_scrooll_slide = slider.parent().scrollLeft();
    let sliderGrabbed = false;

    slider.parent().on('scroll', (event) => {
        progressBar.css("width", getScrollPercentage());

    })

    slider.on('mousedown', (event) => {
        sliderGrabbed = true;
        slider.css("cursor", 'grabbing');
        movementX = event.pageX;
    })

    $('body').on('mouseup', (event) => {
        sliderGrabbed = false;
        slider.css("cursor", 'grab');
        movementX = 0;
        strart_scrooll_slide = slider.parent().scrollLeft();
    })

    slider.on('mouseleave', (event) => {
        sliderGrabbed = false;
        movementX = 0;
    })

    slider.on('mousemove', (event) => {
        if (sliderGrabbed) {
            slider.parent().scrollLeft(strart_scrooll_slide - (event.pageX - movementX));

        }
    })

    slider.on('wheel', (e) => {
        event.preventDefault();
        var delta = null;
        if (e.originalEvent) {
            if (e.originalEvent.wheelDelta) delta = e.originalEvent.wheelDelta / -40;
            if (e.originalEvent.deltaY) delta = e.originalEvent.deltaY;
            if (e.originalEvent.detail) delta = e.originalEvent.detail;
        }

        slider.parent().scrollLeft(slider.parent().scrollLeft() + delta);
        strart_scrooll_slide = slider.parent().scrollLeft();
    })

    function getScrollPercentage() {
        return ((slider.parent().scrollLeft() / (slider.parent().get(0).scrollWidth - slider.parent().innerWidth())) * 100) + "%";
    }
    $("html").css("margin-top", "0px");
    $("body").css("margin-top", "0px");



    function createTumbmailPDF(canvas, url) {
        pdfjsLib.getDocument(url).promise.then(function(doc) {
            var pages = [];
            if (pages.length < doc.numPages) pages.push(pages.length + 1);
            return Promise.all(pages.map(function(num) {
                return doc.getPage(num).then(
                    function(page) {
                        var scale = 0.4;
                        var viewport = page.getViewport({ scale: scale });
                        var scale = Math.min(canvas.width / viewport.width, canvas.height / viewport.height);
                        var outputScale = window.devicePixelRatio || 1;
                        var context = canvas.getContext('2d');
                        canvas.width = Math.floor(viewport.width * outputScale);
                        canvas.height = Math.floor(viewport.height * outputScale);
                        canvas.style.width = Math.floor(viewport.width) + "px";
                        canvas.style.height = Math.floor(viewport.height) + "px";
                        var transform = outputScale !== 1 ? [outputScale, 0, 0, outputScale, 0, 0] :
                            null;
                        var renderContext = {
                            canvasContext: context,
                            transform: transform,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    }
                )
            }));
        }).catch(console.error);
        return canvas;
    }
    jQuery(".canvas-pdf-image").each(function(index) {
        canvas = $(this).get(0);
        url = $(this).attr("url");
        canvas = createTumbmailPDF(canvas, url);
    });
    jQuery(".canvas-grafic-pie").each(function(index) {
        const mycanvas = $(this);
        const ctx = mycanvas.get(0).getContext('2d');
        var title_ = $(this).attr("title");
        var subTitle_ = $(this).attr("subTitle");
        var legends = JSON.parse($(this).attr("legend"));
        var datasValues = JSON.parse($(this).attr("dataValue"));
        var colorsItens = JSON.parse($(this).attr("colorItem"));
        mycanvas.removeClass("grafics-demo");
        var buildGraficsChartData = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: legends,
                datasets: [{
                    label: legends,
                    data: datasValues,
                    backgroundColor: colorsItens
                }]
            },
            options: {

                plugins: {
                    legend: {
                        display: false,
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: title_
                    },
                    subtitle: {
                        display: false,
                        text: subTitle_
                    }
                }
            }
        });
    });


    jQuery(".all-grafics-element").each(function() {
        var containerslegends = [];
        var maxContainerLegendsHeight = 0;
        jQuery(this).find(".container-legends").each(function(index) {
            containerslegends[index % 3] = jQuery(this);
            maxContainerLegendsHeight = (maxContainerLegendsHeight < jQuery(this).height()) ? jQuery(this).height() : maxContainerLegendsHeight;
            if (index % 3 == 2) {
                for (var i = 0; i < 3; i++) {
                    containerslegends[i].height(maxContainerLegendsHeight);
                }
                maxContainerLegendsHeight = 0;
            }
        });
    });


    jQuery(".carousel").each(function() {
        var maxHeightCarouselCaption = 0;
        jQuery(this).find(".carousel-caption").each(function() {
            var accHeight = Math.abs(jQuery(this).height());
            if (maxHeightCarouselCaption < accHeight)
                maxHeightCarouselCaption = accHeight;
        });
        jQuery(this).css("margin-bottom", maxHeightCarouselCaption + "px");
    })

});