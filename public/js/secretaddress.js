//問い合わせメールアドレスの暗号化
function secretaddress() {

   function converter(M){
		var str="", str_as="";
		for(var i=0;i<M.length;i++){
			str_as = M.charCodeAt(i);
			str += String.fromCharCode(str_as + 1);
		}
		return str;
	}
	var ad = converter(String.fromCharCode(104,109,101,110,63,106,104,113,100,104)+String.fromCharCode(108,110,45,105,111));
	document.write("<a href=\"mai"+"lto:"+ad+"\">"+ad+"<\/a>");
	
}