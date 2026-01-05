$('#loader').hide();

$(".sql").click(
	function( event ) {
		var textarea=$(this);		
		var form_=event.target.form;
		$.ajax(
			{
				url: $(form_).attr("action"),
    			dataType: 'html',
    			processData: false,
    			contentType: false,			
                method: $(form_).attr("method"),			
    			data:$(form_).attr("method")=="POST"?(new FormData(form_)):($(form_).serialize()),
				beforeSend: function() {
					//load start
				},
				complete: function() {
					//load end
				},
				success: function(data, textStatus) {
					try{	
                    		if (data!==undefined){
                    			data=JSON.parse(data);
                    			//$(".result").html(JSON.stringify(data.title));
                    			var line=$('<tr>');
                    			var thead_=$('<thead>');
                    			data.title.forEach(
                    			    function myFunction(value) {
                                       line.append($('<th>').html(value));
                                    }
                    			);
                    			thead_.append(line);
                    			$(".result").append(thead_);
                    			var tbody_=$('<tbody>');
                    			data.data.forEach(
                    			    function myFunction(lineData) {
                				       line=$('<tr>');
                    			       lineData.forEach(
                            			    function myFunction(value) {
                                                line.append($('<td>').html(value));
                                            }
                            			);
                    			        tbody_.append(line);
                                    }
                    			);
                    			$(".result").append(tbody_);

                    			/*for(position in data.data){
                    				record=data.data[position];
                    				var id='';			
                    				for(var position_value in record){
                    					var	_value=data.registros[position][position_value];
                    						line.append($('<td>').append(_value));
                    				}
                    				$(".result").find('tbody').append(line);
                    				comeco++;
                    			}*/
                    		}
                    	}
                    	catch(erro){
                    		//$(".mensagem_erro").html(data);
                    		//$(".mensagem_erro").html(erro.message);
                    		//$(".mensagem_erro").removeClass("d-none");
                    	}	
				},
				error: function(xhr,er) {
					//erro
				}
			}
		);
	}
);


$(".selectAjax").each(
	function( index ) {
		var _select=$(this);		
		var form_atual=_select.closest("form");
		$.ajax(
			{
				url: form_atual.attr("action"),
				dataType: 'html',
				method: form_atual.attr("method"),			
				data:{
					chave:_select.attr("chave"),
					valor:_select.attr("valor"),
					modelo:_select.attr("modelo"),
					acao:"selectAjax"
				}
				,
				beforeSend: function() {
					//load start
				},
				complete: function() {
					//load end
				},
				success: function(data, textStatus) {
					if (data!==undefined){
						data=JSON.parse(data);
						_select.html("");
						for(posicao_registro in data.registros){
							registro=data.registros[posicao_registro];
							_chave=data.registros[posicao_registro][0];
							_valor=data.registros[posicao_registro][1];
							_opcao=$("<option>").html(_valor);
							_opcao.attr("value",_chave);
							_select.append(_opcao);
						}
					}
				},
				error: function(xhr,er) {
					//erro
				}
			}
		);
	}
)


function relogio(){
	if($("#cronometro").length>0)
	try{
		$.ajax(
			{
				url: "contator_tempo.php",
				dataType: 'html',
				beforeSend: function() {
				},
				complete: function() {
				},
				success: function(data, textStatus) {
					if(data=="00:00")
						top.location.href="/printer/";
					$("#cronometro").html(data);
				},
				error: function(xhr,er) {
					//erro
				}
			}
		);
		setTimeout("relogio()",500);
	}
	catch(e){}
}
relogio();

function processa_resposta_servidor(data){
	$(".resultado_busca").find('tbody').html("");
	$(".mensagem_erro").addClass("d-none");
	$(".mensagem_informacao").addClass("d-none");
	$(".mensagem_sucesso").addClass("d-none");
	try{	
		if (data!==undefined){
			data=JSON.parse(data);
			if (data.mensagem_sucesso!=undefined){
				$(".mensagem_sucesso").html(data.mensagem_sucesso);
				$(".mensagem_sucesso").removeClass("d-none");
			}
			else if((data.mensagem_erro!=undefined)&&(data.mensagem_erro!="")){
				$(".mensagem_erro").html(data.mensagem_erro);
				$(".mensagem_erro").removeClass("d-none");
			}
			else if((data.mensagem_informacao!=undefined)&&(data.mensagem_informacao!="")){
				$(".mensagem_informacao").html(data.mensagem_informacao);
				$(".mensagem_informacao").removeClass("d-none");
			}
			$(".resultado_busca").find('thead').html("");
			var linha=$('<tr>');
			var tipos = new Array();
			var nomes = new Array();
			var campos = new Array();
			var comeco=0;
			for(campo in data.campos){	
				tipos.push(data.campos[campo].tipo); 
				nomes.push(data.campos[campo].nome); 
				campos.push(campo); 
				linha.append(
					$('<th >').html(
						data.campos[campo].nome 
					)
				);
			}	
			linha.append(
					$('<th style="text-align:center">').html(
						"AÃ§Ã£o" 
					)
				);		
			$(".resultado_busca").find('thead').append(linha);
			
			
			for(posicao_registro in data.registros){
				registro=data.registros[posicao_registro];
				linha=$('<tr>');
				var id='';			
				for(var posicao_valor in registro){
					var	_valor=data.registros[posicao_registro][posicao_valor];
					var	_nome=campos[posicao_valor];
					var campo_formulario=$("[name="+_nome+"]");
					if (comeco==0){
						if($("[name="+_nome+"]").is("input:checkbox")){
							campo_formulario.prop( "checked", _valor==true );				
						}
						else if($("[name="+_nome+"]").is("input:file")){
							//campo_formulario.val(_valor)
						}
						else if($("[name="+_nome+"]").is("select")){
							campo_formulario.val(_valor)
						}
						else if($("[name="+_nome+"]").is("textarea")){
						    campo_formulario.val(_valor);
							try{
                        	    for (var i in CKEDITOR.instances) {
                                    var editor_html=CKEDITOR.instances[i]
                                    editor_html.setData($("[name="+editor_html.name+"]").val());
                                }
                        	}
                        	catch(eeeeeeeeee){}
						}
						else {
						    if(tipos[posicao_valor].indexOf("youtube")>=0)
						        _valor="https://www.youtube.com/watch?v="+_valor;
							campo_formulario.val(_valor);
						}
					}
					if(_nome=='id'){
						id=_valor;					
					}
					if(tipos[posicao_valor]=="boolean"){
						_checkbox=$('<input type="checkbox" disabled>');
						_checkbox.prop( "checked", _valor==true );
						linha.append($('<td>').append(_checkbox));
					}
					else if(tipos[posicao_valor].indexOf("integer")>=0){					
						linha.append($('<td style="text-align:right">').html(_valor));
					}	
					else if(tipos[posicao_valor].indexOf("image")>=0){
					    _path=$("[name="+_nome+"_path]").val();
					    _formats=$("[name="+_nome+"_formats]").val().split(",");
					    _formats_min=_formats[0];
					    _formats_max=_formats[_formats.length-1];
						_img=$('<img>');
						_img.attr('src','/uploads/'+_path+'/'+_formats_min+'/'+_valor);
						_link=$('<a>');
						_link.attr('href','/uploads/'+_path+'/'+_formats_max+'/'+_valor);
						_link.attr('target','_blank');
						_link.html(_img);						
						linha.append($('<td style="text-align:center">').html(_link));
					}	
					else if(tipos[posicao_valor].indexOf("youtube")>=0){			
						_iframe=$('<iframe>');
						_iframe.attr('type','text/html');
						_iframe.attr('width','640');
						_iframe.attr('height','360');
						_iframe.attr('frameborder','0');
						_iframe.attr('src','http://www.youtube.com/embed/'+_valor+'?autoplay=1&origin=http://casanovapizzaria.com.br');
						linha.append($('<td style="text-align:center">').html(_iframe));
					}
					else{					
						linha.append($('<td>').html(_valor));
					}					
				}
				linha.append($('<td style="text-align:center">').html(
					
						'<input type="hidden" name="id" value="'+id+'" >'+
						'<button name="editar" type="button" class="btn btn-primary minieditar  btn-sm"><i class="fa fa-edit " aria-hidden="true"></i>'+								
						'<button name="excluir" type="button" class="btn btn-danger miniexcluir  btn-sm"><i class="fa fa-times " aria-hidden="true"></i></button>'
			
				));
				$(".resultado_busca").find('tbody').append(linha);
				comeco++;
			}
			$(".resultado_busca").find("tbody").find("tr").mouseover(function(event) {
				$(this).addClass("table-primary");
			});
			$(".resultado_busca").find("tbody").find("tr").mouseout(function(event) {
				$(this).removeClass("table-primary");
			});		
			$(".minieditar").click(function(event) {
				var form_atual=$("form");
				form_atual.find('.buscarcampo').addClass("d-none");
				form_atual.find('.novo').addClass("d-none");   
				form_atual.find('.buscar').addClass("d-none");    
				form_atual.find('.salvar').addClass("d-none");	
				form_atual.find('.cancelar').addClass("d-none");	
				form_atual.find('.excluir').addClass("d-none");	
				form_atual.find('.editar').addClass("d-none");		
				form_atual.find('.novo').removeClass("d-none"); 
				form_atual.find('.salvar').removeClass("d-none");
				form_atual.find("input[name$='acao']").val("buscar");
				var campo_valor=$(event.target).closest(".input-group").find("select[name], textarea[name], input[name]");
				$.ajax(
					{
						url: form_atual.attr("action"),
						dataType: 'html',
						method: form_atual.attr("method"),			
						data:{
							acao:"buscarcampo",
							campo:"id",
							valor:$(this).parent().find("input[name=id]").val()
						}
						,
						beforeSend: function() {
							//load start
						},
						complete: function() {
							//load end
						},
						success: function(data, textStatus) {
							processa_resposta_servidor(data);
						},
						error: function(xhr,er) {
							//erro
						}
					}
				);
			});
			$(".miniexcluir").click(function(event) {
				var form_atual=$("form");
				form_atual.find('.buscarcampo').addClass("d-none");
				form_atual.find('.novo').addClass("d-none");   
				form_atual.find('.buscar').addClass("d-none");    
				form_atual.find('.salvar').addClass("d-none");	
				form_atual.find('.cancelar').addClass("d-none");	
				form_atual.find('.excluir').addClass("d-none");	
				form_atual.find('.editar').addClass("d-none");	
				
				form_atual.find('.novo').removeClass("d-none"); 
				form_atual.find('.salvar').removeClass("d-none");
		
				var campo_valor=$(event.target).closest(".input-group").find("select[name], textarea[name], input[name]");
				$.ajax(
					{
						url: form_atual.attr("action"),
						dataType: 'html',
						method: form_atual.attr("method"),			
						data:{
							acao:"excluir",
							id:$(this).parent().find("input[name=id]").val()
						}
						,
						beforeSend: function() {
							//load start
						},
						complete: function() {
							//load end
						},
						success: function(data, textStatus) {
							processa_resposta_servidor(data);
						},
						error: function(xhr,er) {
							//erro
						}
					}
				);
				form_atual.find(':input').not(':button, :submit, :reset, :checkbox, :radio, :hidden').val('');
				form_atual.find(':checkbox, :radio').prop('checked', false);
				form_atual.find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
				form_atual.find(':checkbox, :radio').prop( "disabled", false )	;
			});
		}
	}
	catch(erro){
		$(".mensagem_erro").html(data);
		//$(".mensagem_erro").html(erro.message);
		$(".mensagem_erro").removeClass("d-none");
	}
	
}
processa_resposta_servidor();

$(".novo").click(function(event) {
    try{
	    for (var i in CKEDITOR.instances) {
            var editor_html=CKEDITOR.instances[i]
            editor_html.setData("")
        }
	}
	catch(eeeee){}
	var form_atual=event.target.form;
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio, :hidden').val('');
    $(form_atual).find(':checkbox, :radio').prop('checked', false);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
    $(form_atual).find(':checkbox, :radio').prop( "disabled", false )	;
    $(form_atual).find('.buscarcampo').addClass("d-none");
    $(form_atual).find('.novo').addClass("d-none");   
    $(form_atual).find('.buscar').addClass("d-none");    
    $(form_atual).find('.salvar').addClass("d-none");	
    $(form_atual).find('.cancelar').addClass("d-none");	
    $(form_atual).find('.excluir').addClass("d-none");	
    $(form_atual).find('.editar').addClass("d-none");	
	
    $(form_atual).find('.buscarcampo').removeClass("d-none");
	$(form_atual).find('.buscar').removeClass("d-none");    
    $(form_atual).find('.salvar').removeClass("d-none");	
    $(form_atual).find('.cancelar').removeClass("d-none");
	processa_resposta_servidor();
});

$(".salvar").click(function(event) {
	try{
	    for (var i in CKEDITOR.instances) {
            var editor_html=CKEDITOR.instances[i]
            $("[name="+editor_html.name+"]").val(editor_html.getData());
        }
	}
	catch(eeeeeee){}
	var form_atual=event.target.form;
	$(form_atual).find("input[name$='id']").prop( "disabled", false );
    $(form_atual).find('.buscarcampo').addClass("d-none");
    $(form_atual).find('.novo').addClass("d-none");   
    $(form_atual).find('.buscar').addClass("d-none");    
    $(form_atual).find('.salvar').addClass("d-none");	
    $(form_atual).find('.cancelar').addClass("d-none");	
    $(form_atual).find('.excluir').addClass("d-none");	
    $(form_atual).find('.editar').addClass("d-none");	
	
    $(form_atual).find('.novo').removeClass("d-none");   
	$(form_atual).find('.editar').removeClass("d-none");		
    $(form_atual).find('.excluir').removeClass("d-none");
	$(form_atual).find("input[name='acao']").val("salvar");
	
	var files_item = [];

	$(form_atual).find("input[type='file']").each(
		function( index ) {
			var files = $(this)[0].files;
			var _start_bytes_item=0;
			for(var i = 0; i<files.length; i++){
				files_item.push({"name":files[i].name,"size":files[i].size,"start_bytes_item":_start_bytes_item});
				_start_bytes_item=_start_bytes_item+files[i].size;
			}
		}
	);
	$.ajax(
		{
			url: $(form_atual).attr("action"),
			dataType: 'html',
			processData: false,
			contentType: false,			
            method: $(form_atual).attr("method"),			
			data:$(form_atual).attr("method")=="POST"?(new FormData(form_atual)):($(form_atual).serialize()),
			beforeSend: function() {
                $('#loader').show();
            },
            complete: function() {
				$(".progress-item").find(".progress-bar").css("width","0%");
				$(".progress-item").find(".progress-bar").html("0%")
				$(".progress-item").removeClass("bg-success");				
				$(".progress-all").find(".progress-bar").css("width","0%");
				$(".progress-all").find(".progress-bar").html("0%")
				$(".progress-all").removeClass("bg-success");				
                $('#loader').hide();
            },
            success: function(data, textStatus) {
                processa_resposta_servidor(data);
            },
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						if(files_item.length>0){
    						var file=files_item[0];
    						percentItem=Math.round((evt.loaded - file.start_bytes_item)/file.size*100);
    						if(percentItem>=100){
    							files_item.shift()
    							$(".progress-item").find(".progress-bar").html("processando informaÃ§Ãµes");
    							$(".progress-item").find(".progress-bar").addClass("bg-success");
    						}
    						else{
    							$(".progress-item").find(".progress-bar").css("width",percentItem+"%");
    							$(".progress-item").find(".progress-bar").html(file.name+" "+percentItem+"%");
    							$(".progress-item").find(".progress-bar").removeClass("bg-success");
    						}
						}
						
						var percentComplete = Math.round((evt.loaded / evt.total) * 100);
						percentVal=percentComplete+"%";
						if(percentComplete>=100){
							$(".progress-all").find(".progress-bar").html("processando informaÃ§Ãµes");
							$(".progress-all").find(".progress-bar").addClass("bg-success");
						}
						else{
							$(".progress-all").find(".progress-bar").css("width",percentVal);
							$(".progress-all").find(".progress-bar").html(percentVal);
						}
					}
			   }, false);
			   return xhr;
			},
            error: function(xhr,er) {
				$(".progress-item").find(".progress-bar").css("width","0%");
				$(".progress-item").find(".progress-bar").html("0%")
				$(".progress-item").removeClass("bg-success");				
				$(".progress-all").find(".progress-bar").css("width","0%");
				$(".progress-all").find(".progress-bar").html("0%")
				$(".progress-all").removeClass("bg-success");				
                $('#loader').hide();
				$(".mensagem_erro").html("NÃ£o foi possivel Salvar houve um erro.");
				$(".mensagem_erro").removeClass("d-none");
            }
		}
	);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", true );
    $(form_atual).find(':checkbox, :radio').prop( "disabled", true )	;
});

$(".editar").click(function(event) {
    try{
	    for (var i in CKEDITOR.instances) {
            var editor_html=CKEDITOR.instances[i]
            editor_html.setData("")
        }
	}
	catch(eeeee){}
	var form_atual=event.target.form;
    $(form_atual).find('.buscarcampo').addClass("d-none");
    $(form_atual).find('.novo').addClass("d-none");   
    $(form_atual).find('.buscar').addClass("d-none");    
    $(form_atual).find('.salvar').addClass("d-none");	
    $(form_atual).find('.cancelar').addClass("d-none");	
    $(form_atual).find('.excluir').addClass("d-none");	
    $(form_atual).find('.editar').addClass("d-none");	
	
    $(form_atual).find('.salvar').removeClass("d-none");	
    $(form_atual).find('.cancelar').removeClass("d-none");	
	processa_resposta_servidor();
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
    $(form_atual).find(':checkbox, :radio').prop( "disabled", false )	;
	$(form_atual).find("input[name$='id']").prop( "disabled", true );
});


$(".cancelar").click(function(event) {
	var form_atual=event.target.form;
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio, :hidden').val('');
    $(form_atual).find(':checkbox, :radio').prop('checked', false);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
    $(form_atual).find(':checkbox, :radio').prop( "disabled", false )	;
    $(form_atual).find('.buscarcampo').addClass("d-none");
    $(form_atual).find('.novo').addClass("d-none");   
    $(form_atual).find('.buscar').addClass("d-none");    
    $(form_atual).find('.salvar').addClass("d-none");	
    $(form_atual).find('.cancelar').addClass("d-none");	
    $(form_atual).find('.excluir').addClass("d-none");	
    $(form_atual).find('.editar').addClass("d-none");	
	
    $(form_atual).find('.novo').removeClass("d-none"); 
    $(form_atual).find('.salvar').removeClass("d-none");
	processa_resposta_servidor();
});

$(".excluir").click(function(event) {
	var form_atual=event.target.form;
    $(form_atual).find('.buscarcampo').addClass("d-none");
    $(form_atual).find('.novo').addClass("d-none");   
    $(form_atual).find('.buscar').addClass("d-none");    
    $(form_atual).find('.salvar').addClass("d-none");	
    $(form_atual).find('.cancelar').addClass("d-none");	
    $(form_atual).find('.excluir').addClass("d-none");	
    $(form_atual).find('.editar').addClass("d-none");	
	
    $(form_atual).find('.novo').removeClass("d-none"); 
    $(form_atual).find('.salvar').removeClass("d-none");
	$.ajax(
		{
			url: $(form_atual).attr("action"),
			dataType: 'html',
            method: $(form_atual).attr("method"),			
			data:{
				acao:"excluir",
				id:$(form_atual).find("input[name$='id']").val()
			},
			beforeSend: function() {
                //load start
            },
            complete: function() {
                //load end
            },
            success: function(data, textStatus) {
                processa_resposta_servidor(data);
            },
            error: function(xhr,er) {
                //erro
            }
		}
	);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio, :hidden').val('');
    $(form_atual).find(':checkbox, :radio').prop('checked', false);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
    $(form_atual).find(':checkbox, :radio').prop( "disabled", false )	;
});

$(".buscar").click(function(event) {
    try{
	    for (var i in CKEDITOR.instances) {
            var editor_html=CKEDITOR.instances[i]
            $("[name="+editor_html.name+"]").val(editor_html.getData());
        }
	}
	catch(eeeeeee){}
	var form_atual=event.target.form;
    $(form_atual).find('.buscarcampo').addClass("d-none");
    $(form_atual).find('.novo').addClass("d-none");   
    $(form_atual).find('.buscar').addClass("d-none");    
    $(form_atual).find('.salvar').addClass("d-none");	
    $(form_atual).find('.cancelar').addClass("d-none");	
    $(form_atual).find('.excluir').addClass("d-none");	
    $(form_atual).find('.editar').addClass("d-none");		
    $(form_atual).find('.novo').removeClass("d-none"); 
    $(form_atual).find('.salvar').removeClass("d-none");
	$(form_atual).find("input[name$='acao']").val("buscar");
	$.ajax(
		{
			url: $(form_atual).attr("action"),
			dataType: 'html',
            method: $(form_atual).attr("method"),			
			data:$(form_atual).serialize(),
			beforeSend: function() {
                //load start
            },
            complete: function() {
                //load end
            },
            success: function(data, textStatus) {
                processa_resposta_servidor(data);
            },
            error: function(xhr,er) {
                //erro
            }
		}
	);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio, :hidden').val('');
    $(form_atual).find(':checkbox, :radio').prop('checked', false);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
    $(form_atual).find(':checkbox, :radio').prop( "disabled", false )	;
});

$(".buscarcampo").click(function(event) {
	var form_atual=$(event.target).closest("form");
    form_atual.find('.buscarcampo').addClass("d-none");
    form_atual.find('.novo').addClass("d-none");   
    form_atual.find('.buscar').addClass("d-none");    
    form_atual.find('.salvar').addClass("d-none");	
    form_atual.find('.cancelar').addClass("d-none");	
    form_atual.find('.excluir').addClass("d-none");	
    form_atual.find('.editar').addClass("d-none");		
    form_atual.find('.novo').removeClass("d-none"); 
    form_atual.find('.salvar').removeClass("d-none");
	form_atual.find("input[name$='acao']").val("buscar");
	var campo_valor=$(event.target).closest(".input-group").find("select[name], textarea[name], input[name]");
	$.ajax(
		{
			url: form_atual.attr("action"),
			dataType: 'html',
            method: form_atual.attr("method"),			
			data:{
				acao:"buscarcampo",
				campo:campo_valor.attr("name"),
				valor:campo_valor.val(),
			}
			//"acao=buscar"
			//+"&campo="+campo_valor.attr("name")
			//+"&valor="+campo_valor.val()
			,
			beforeSend: function() {
                //load start
            },
            complete: function() {
                //load end
            },
            success: function(data, textStatus) {
                processa_resposta_servidor(data);
            },
            error: function(xhr,er) {
                //erro
            }
		}
	);
	form_atual.find(':input').not(':button, :submit, :reset, :checkbox, :radio, :hidden').val('');
    form_atual.find(':checkbox, :radio').prop('checked', false);
	form_atual.find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
    form_atual.find(':checkbox, :radio').prop( "disabled", false );
});


$("#abre_buscar").click(function(event) {
	$("#buscar").css("display","block");
	$("#fechar_buscar").css("display","block");
});
$("#fechar_buscar").click(function(event) {
	$("#buscar").css("display","none");
	$("#fechar_buscar").css("display","none");
});
$("#abrir_menu").click(function(event) {
	
	$("#redes_sociais").css("display","block");
	$("#menu").css("display","block");
	$("#barra_menu").css("display","block");
	$("#buscar").css("display","block");
	$("#fechar_menu").css("display","block");
	$("#abrir_menu").css("display","none");
});

$("#fechar_menu").click(function(event) {

	$("#redes_sociais").css("display","none");
	$("#menu").css("display","none");
	$("#barra_menu").css("display","none");
	$("#buscar").css("display","none");
	$("#fechar_menu").css("display","none");
	$("#abrir_menu").css("display","block");
});

$('.owl-carousel').each(
	function( index ) {
		$( this ).owlCarousel({
			
			loop:true,
			nav:true,
			margin:10,
			dots: false,
			 navText:["<div class='esquerda_slide_home'><</div>","<div class='direita_slide_home'>></div>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},            
				960:{
					items:1.5
				},
				1200:{
					items:1.5
				}
			}
		});
		
		$( this ).on('mousewheel', '.owl-stage', function (e) {
				if (e.deltaY>0) {
					$( this ).trigger('next.owl');
				} else {
					$( this ).trigger('prev.owl');
				}
				e.preventDefault();
		});
		
	}	
);