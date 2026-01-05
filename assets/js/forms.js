function generate_token(length){
    //edit the token allowed characters
    var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
    var b = [];  
    for (var i=0; i<length; i++) {
        var j = (Math.random() * (a.length-1)).toFixed(0);
        b[i] = a[j];
    }
    return b.join("");
}
function centeredText(doc,text, y) {
    var textWidth = doc.getStringUnitWidth(text) * doc.internal.getFontSize() / doc.internal.scaleFactor;
    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
    doc.text(textOffset, y, text);
}




$('#loader').hide();



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