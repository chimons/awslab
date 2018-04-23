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
		var divCard = document.createElement('div');
		divCard.className = "card";
		divCard.style.cssText = "width: 18rem;";

		var cardTitle = document.createElement('h5');
		cardTitle.className = "card-title";
		cardTitle.innerHTML = "Filename : "+data[i].filename;

		var p = document.createElement('p');
		p.className = "card-text";
		p.innerHTML = 	"<b>Upload date: </b>"+data[i].eventTime+ 
						"<br><b>URL :</b> <a href='"+data[i].url+"'>"+data[i].url+"</a>";

		var img = document.createElement('img');
		img.src = data[i].url;
		img.alt = data[i].filename;
		img.className = "card-img-top";

		var divCardBody = document.createElement('div');
		divCardBody.className = "card-body";

		CardBody.appendChild(cardTitle);
		CardBody.appendChild(p);

		divCard.appendChild(img);
		divCard.appendChild(CardBody);

		document.body.appendChild(divCard);
	}
});
document.getElementById("apigwurl").innerHTML = api_url;