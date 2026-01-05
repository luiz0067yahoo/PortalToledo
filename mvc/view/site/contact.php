<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');


$GLOBALS["og_title"]="Tooeste";
$GLOBALS["og_description"]="Informação ao seu Alcance";
$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/menu/320x240/".$GLOBALS["logo_site"];
$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

top();
if(isset($_POST["enviar"])&&$_POST["enviar"]=="Enviar"){
  $nome=getParameter("nome");
  $e_mail=getParameter("e-mail");
  $telefone=getParameter("telefone");
  $mensagem=getParameter("mensagem");
  $titulo_adm="Entre em contato com ${nome}";
  $mensagem_adm="Oi Equipe Tooeste!<br>\n ";
  $mensagem_adm.="     ${nome} entrou em contato, deixou seu email <a href='mailto:${e_mail}'>${e_mail}</a><br>\n" ;
  $mensagem_adm.="    e telefone:  para ligar <a href='tel:${telefone}'>${telefone}</a> <br>";
  $mensagem_adm.="    ou mandar whats <a href='https://wa.me/${telefone}'>${telefone}</a>.<br>\n" ;
  $mensagem_adm.="    Veja o que ${nome}  escreveu:<br><br>\n" ;
  $mensagem_adm.="    ${mensagem} <br>\n" ;

  echo $GLOBALS["mensagem_contato"];
  sendEmailMessage( 
      "smtp.hostinger.com",
      "contato@tooeste.com.br",
      "Contato  Tooeste",
      "contatoJa!4",
      "Oi! Obrigado pelo seu contato em breve a nossa equipe da Tooeste vai retornar.",
      $e_mail,
      $nome,
      $GLOBALS["mensagem_contato"]
  );
  sendEmailMessage(
      "smtp.hostinger.com",
      "contato@tooeste.com.br",
      "Contato  Tooeste",
      "contatoJa!4",
      $titulo_adm,
      "contato@tooeste.com.br",
      "Contato  Tooeste",
      $mensagem_adm
  );
  $GLOBALS["mensagem_contato"];
} 
?>
<form method="post" >
<div class="row mt-3 ">
	<div class="col-sm-12 shadow-lg p-3 mb-5  rounded border border-secundary border-1" 
        style="box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.5);
      -webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.5);
      -moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.5);
      background-color:#fefefe" >
        <div class="row">
          <h1 class="w-100 text-center">CONTATO</h1>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h4>Telefones:</h4>
            <br>
            <p><a href="tel:4532525250" target="blank" style="text-decoration:none" class="text-dark">(45)3252-5250</a></p>
            <p><a href="https://wa.me/5545999413583" style="text-decoration:none" class="text-dark"target="blank">(45) 99941-3583</a></p>
            <br>
            <h4>Email:</h4>
            <br>
            <p><a href="mailto:contato@tooeste.com.br" target="blank" style="text-decoration:none" class="text-dark">contato@tooeste.com.br</a></p>
            <br>
            <h4>Endereço:</h4>
            <br>
            <p><a href="https://goo.gl/maps/dQN2vs8AVbTN6dtn6" target="blank" style="text-decoration:none" class="text-dark">Rua São Francisco, 552 - Jardim Porto Alegre - Toledo-PR</a></p>
            <br>
            <h4>CNPJ</h4>
            <br>
            <p><a  style="text-decoration:none" class="text-dark">15.094.668/001-12</a></p>

            <div class="row mt-4">
              <div class="col-md-4 p-2 d-flex">
                  <a href="/missao_visao_e_valores"  class="d-flex text-break text-white w-100 text-wrap btn btn-primary square rounded justify-content-center align-items-center " style="text-decoration:none">
                    MISSÃO,
                    <br> VISÃO E
                    <br> VALORES
                  </a >
              </div>
              <div class="col-md-4 p-2 d-flex">
                  <a href="/politica_de_privacidade"  class="d-flex text-break text-white w-100 text-wrap btn btn-primary square rounded justify-content-center align-items-center " style="text-decoration:none">
                    POLÍTICA
                    <br> DE PRIVACIDADE
                  </a >
              </div>
              <div class="col-md-4 p-2 d-flex">
                  <a href="/termo_de_uso_e_responsabilidade" class="d-flex text-break text-white w-100 text-wrap btn btn-primary square rounded justify-content-center align-items-center " style="text-decoration:none">
                    TERMO DE
                    <br> RESPONSABILIDADE
                  </a >
              </div>
            </div>


          </div>
          <div class="col-md-6">
            <br>
            <h3>Entre em contato conosco</h3>
            <br>
            <p>Nome</p>
            <p><input type="text" class="w-100 form-control" name="nome"></p>
            <br>
            <p>E-mail</p>
            <p><input type="e-mail" class="w-100  form-control"  name="e-mail"></p>
            <br>
            <p>Telefone</p>
            <p><input type="fone"  class="w-100 form-control" name="telefone"></p>
            <br>
            <p>Mensagem</p>
            <p><textarea  class="w-100 form-control" name="mensagem"></textarea></p>
              <br>
            <p></p>
            <p><input class="btn  btn-primary" type="submit" name="enviar" value="Enviar"></p>

          </div>
	      </div>
        
	  </div>
  </div>
</form>





<div class="row mt-3">
	<div class="col-sm-12 justify-content-center">
		<div class="button-social-links justify-content-center">
    		    <a class="fcb rounded m-1" target="blank" href="https://www.facebook.com/Tooeste">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" fill="currentColor" height="40px"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg></a>
            
            <a class="twi rounded m-1" target="blank" href="https://twitter.com/tooeste_of"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg></a>


            <a class="you rounded m-1" target="blank" href="https://www.youtube.com/channel/UCczOOXxDGIRF2XNzn8cWwzA">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg></a>

            <a class="inst rounded m-1" target="blank" href="https://www.instagram.com/sitetooeste/">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></a>

            <a class="whats rounded m-1" target="blank" href="https://wa.me/+5545998472907">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg></a>

            <a class="andress rounded m-1" target="blank" href="https://www.google.com.br/maps/place/rua sao francisco 552 TOLEDO PR">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" height="40px" fill="currentColor"><path d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0z"></path></svg></a>

            <a class="phone rounded m-1" href="tel:+5545998472907">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="40px" fill="currentColor"><path d="M511.2 387l-23.25 100.8c-3.266 14.25-15.79 24.22-30.46 24.22C205.2 512 0 306.8 0 54.5c0-14.66 9.969-27.2 24.22-30.45l100.8-23.25C139.7-2.602 154.7 5.018 160.8 18.92l46.52 108.5c5.438 12.78 1.77 27.67-8.98 36.45L144.5 207.1c33.98 69.22 90.26 125.5 159.5 159.5l44.08-53.8c8.688-10.78 23.69-14.51 36.47-8.975l108.5 46.51C506.1 357.2 514.6 372.4 511.2 387z"></path></svg></a>


            <a class="e-mail rounded m-1" target="blank" href="mailto:contato@tooeste.com.br">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M464 64C490.5 64 512 85.49 512 112C512 127.1 504.9 141.3 492.8 150.4L275.2 313.6C263.8 322.1 248.2 322.1 236.8 313.6L19.2 150.4C7.113 141.3 0 127.1 0 112C0 85.49 21.49 64 48 64H464zM217.6 339.2C240.4 356.3 271.6 356.3 294.4 339.2L512 176V384C512 419.3 483.3 448 448 448H64C28.65 448 0 419.3 0 384V176L217.6 339.2z"></path></svg></a>


                      
        </a>
	    </div>
	</div>
</div>

<?php 

     foot();  
   
?>