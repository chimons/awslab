var api_url = "http://amazon-api-gateway-url.com/update-me!";
	
	
function getAjax(url, success) {
var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
xhr.open('GET', url);
xhr.onreadystatechange = function() {
if (xhr.readyState>3 && xhr.status==200) success(JSON.parse(xhr.responseText));
};
xhr.send();
return xhr;
}

getAjax(api_url, function(data){ 

for (var i = 0;i<data.length;i++){
var div = document.createElement('div');
var img = document.createElement('img');
var p = document.createElement('p');
p.innerHTML = "<b>Upload date: </b>"+data[i].eventTime+ 
"<br><b>filename :</b> "+data[i].filename+ 
"<br><b>URL :</b> <a href='"+data[i].url+"'>"+data[i].url+"</a>";
img.src = data[i].url;
img.title = data[i].filename;
div.appendChild(img);
div.appendChild(p);
document.body.appendChild(div);
}
});
document.getElementById("apigwurl").innerHTML = api_url;
