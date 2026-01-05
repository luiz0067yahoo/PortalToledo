<div class="container">
    <div class="row">
		<div class="col-sm-4 col-md-4"  style="background-repeat:no-repeat;background-image:url('/assets/img/cms/LINK LINK.png');height:850px;">
		</div>
		<div class="col-sm-4 col-md-4">
			<form class="form-codigo" method="POST">
				<%= hidden_field_tag :authenticity_token, form_authenticity_token -%>
				<input type="hidden" id="email" name="email" value="<%=params[:email]%>">
				<h2 class="form-codigo-heading">Código de Verificação</h2>
				<div class="form-group">
					<div class="input-group">
						<label for="codigo_verificacao" class="sr-only">codigo_verificacao</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
						<input type="text" id="_odigo_verificacao" name="codigo_verificacao" class="form-control" placeholder="Código Verificação" required autofocus>
					</div>
				</div>			
				<input name="acao" class="btn btn-lg btn-primary btn-block" type="submit" value="Enviar">							
				<% if @usuario.tem_erro(:email)%>
			    <label class="alert alert-danger"><%=@usuario.mensagem_erro(:email)%></label>
				<% end %>
				<% if flash[:success].present? %>
				<label class="alert alert-success"><%= flash[:success] %></label>
				<% end %>
			</form>
		</div> 
	</div> 
</div>