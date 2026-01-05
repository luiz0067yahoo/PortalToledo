<% session[:usuario]= Usuario.new %>
<div class="container">
    <div class="row">
		<div class="col-sm-4 col-md-4" >
			<img src="../assets/img/LINK LINK.png" style="position:absolute;z-index:1000">
		</div>
		<div class="col-sm-4 col-md-4">
			<img src="../assets/img/prohibited-297091.svg">
			<form class="form-login" method="POST" action="\adm\">
				<%= hidden_field_tag :authenticity_token, form_authenticity_token -%>
				<input type="hidden" name="sistema" value="<%= request.url%>">
				<center><h2 class="form-login-heading" >Acesso negado</h2></center>
				<%if !@session_usuario.try(:nome).blank?%>
				<center><h2 class="form-login-heading">O perfil deste usuário <b><%=@session_usuario.nome%></b> não possui Política de aceso para ver este sistema.</h2></center>
				<center><h2 class="form-login-heading">Favor entrar com outro usuário</h2></center>
				<%else%>
				<center><h2 class="form-login-heading">Favor entrar com seu usuário</h2></center>
				<%end%>
				<div class="form-group">
					<div class="input-group">
						<label for="login" class="sr-only">Login</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
						<input type="text" id="login" name="login" class="form-control" placeholder="Login" required autofocus>
					</div>
				</div>			
				<div class="form-group">
					<div class="input-group">
						<label for="senha" class="sr-only">Senha</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
						<input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group col-sm-12  col-md-12 ">
						<input name="acao" class="btn btn-lg btn-primary btn-block" type="submit" value="Enviar">							
					</div>
				</div>
				<div class="form-group">
					<div class="input-group col-sm-5 col-md-5">
						<a href="\adm\painel" class="btn btn-link"><< voltar</a>	
					</div>
				</div>	
				<% if session[:usuario].tem_erro(:tentativas_falhadas)%>
			    <label class="alert alert-danger"><%=@usuario.mensagem_erro(:tentativas_falhadas)%></label>
				<% end %>			
				<% if flash[:success].present? %>
				<label class="alert alert-success"><%= flash[:success] %></label>
				<% end %>				
			</form>
		</div> 
	</div> 
	
</div>
