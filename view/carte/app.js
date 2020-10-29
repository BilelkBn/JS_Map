var addr,cp,lat,lon, resp, map, marker = [], circle


function maisonAutreJoueur(){
	$.get('index.php?controller=utilisateur&action=RecupToutImmobilierAutreJoueur',(getLogement) =>{
		var logements = JSON.parse(getLogement)
		
		var monIconeMaison = L.icon({
		iconUrl: './view/carte/assets/image/houseSold.png', /* Seule option exigée */
		  iconSize: [48, 48],
		  iconAnchor: [30, 20],
		  popupAnchor: [0, -24]
		});
		for (let index = 0; index < logements.length; index++) {
		  marker.forEach((item) => {
			  if (item['_latlng'].lat == logements[index].lat && item['_latlng'].lng == logements[index].lon) {
				map.removeLayer(item)
			}
		  })
		  L.marker([logements[index].lat,logements[index].lon], {icon: monIconeMaison}).bindPopup('<h1>Maison de '+logements[index].proprio+'</h1><p>'+logements[index].prix+' €</p>').addTo(map)          
	  }
		
	  })
}

function apelAPIGouv(lat,lng){
	if (circle != undefined) {
		map.removeLayer(circle) 
		 marker.forEach((item) => {
			 map.removeLayer(item)
		 })
		}
var IconeMaisonAVendre = L.icon({
		iconUrl: './view/carte/assets/image/ForSale.png', /* Seule option exigée */
		  iconSize: [48, 48],
		  iconAnchor: [30, 20],
		  popupAnchor: [0, -24]
		});
circle = L.circle([lat,lng],500,{color: 'green', weidth: 5, fillColor: 'grey'}).addTo(map)
 $.get('https://api.cquest.org/dvf?lat=' +lat+'&lon=' +lng+'&dist=500&type_local=Maison', function (data) {
	  resp = data
	  for (let index = 0; index < resp.features.length; index++) {
		if (resp.features[index].properties.surface_relle_bati > 0) {
		  marker.push(L.marker([resp.features[index].properties.lat,resp.features[index].properties.lon],{icon: IconeMaisonAVendre}).bindPopup('<h1>'+resp.features[index].properties.numero_voie+' '+resp.features[index].properties.type_voie+' '+ resp.features[index].properties.voie+'</h1><p>'+resp.features[index].properties.valeur_fonciere+' €</p><form action="index.php?controller=utilisateur&action=AjouterImmobilier" method="post"><input type="hidden" name="lat" value="'+resp.features[index].properties.lat+'"/><input type="hidden" name="lon" value="'+resp.features[index].properties.lon+'"/><input type="hidden" name="prix" value="'+resp.features[index].properties.valeur_fonciere+'"/><button type="submit" class="btn btn-success">Acheter</button></form>').addTo(map))           
		}            
	  }
	})
}

function maisonDuJoueur(){
	$.get('index.php?controller=utilisateur&action=RecupImmobilier', function (data) {
	  resp = JSON.parse(data)
	  
	  var monIconeMaison = L.icon({
		iconUrl: './view/carte/assets/image/iconMaison.png', /* Seule option exigée */
		  iconSize: [48, 48],
		  iconAnchor: [30, 20],
		  popupAnchor: [0, -24]
		});
	  for (let index = 0; index < resp.length; index++) {
		  marker.forEach((item) => {
			  if (item['_latlng'].lat == resp[index].lat && item['_latlng'].lng == resp[index].lon) {
				map.removeLayer(item)
			}
		  })
		  L.marker([resp[index].lat,resp[index].lon], {icon: monIconeMaison}).bindPopup('<h1>Votre Maison</h1><p>'+resp[index].prix+' €</p>').addTo(map)          
	  }
	})
}

function updateClassement(){
	$.get('index.php?controller=utilisateur&action=RecupClassementJoueur',(getClassement) =>{
		getClassement = JSON.parse(getClassement)
		$('.classement').text(getClassement[0])
		$('.players').text(getClassement[1])
	})
}

$(document).ready(function () {
	$('.game').hide()
	
	if ($('#lat').val() != undefined && $('#lng').val() != undefined) {
		
		$('.game').show()
		lat = $('#lat').val()
		lon = $('#lng').val()
		maps(lat, lon)
	} else{
		
		$("#go").click( (e) => {
			e.preventDefault()

			$('.game').show()
			addr = $("input")[0].value.trim()
			cp = $("input")[1].value.trim()
	
			let rex = / /gi
			addrModif = addr.replace(rex, '+')
		  
			$.get('https://api-adresse.data.gouv.fr/search/?q='+addrModif+'&postcode='+cp+'&autocomplete=0', (data) => {
                maps(data.features[0].geometry.coordinates[1], data.features[0].geometry.coordinates[0])
                lat = data.features[0].geometry.coordinates[1]
                lon = data.features[0].geometry.coordinates[0]
                $.get('index.php?controller=utilisateur&action=saveAddress&profil='+$('#pseudo h1').text()+'&address='+lat+'&cp='+lon, (data) => {}) 

            }) 
		})
	}


    
    
function maps(lat, lng){
	updateClassement()
	$("#verif").remove()
	
	map = L.map('Maps').setView([lat, lng], 18)

	L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
	maxZoom: 18,
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	
	var arcgisOnline = L.esri.Geocoding.arcgisOnlineProvider();

	var searchControl = L.esri.Geocoding.geosearch().addTo(map);
	
	var results = L.layerGroup().addTo(map);
	
	searchControl.on('results', function(data){
		results.clearLayers();
		
		for (var i = data.results.length - 1; i >= 0; i--) {
			
		results.addLayer(L.marker(data.results[i].latlng));
			apelAPIGouv(data.results[i].latlng.lat,data.results[i].latlng.lng)
			$.get('index.php?controller=utilisateur&action=saveAddress&profil='+$('#pseudo h1').text()+'&address='+data.results[i].latlng.lat+'&cp='+data.results[i].latlng.lng, (data) => {}) 

		}
	
	});		
		
	var markerD = new L.marker([lat,lng], {
		draggable: 'true'
	});

	markerD.on('dragend', function(event) {
	var position = markerD.getLatLng();
	markerD.setLatLng(position,{
		draggable: 'true'
	}).bindPopup(position).update();
	apelAPIGouv(position.lat, position.lng)
	maisonAutreJoueur(position.lat, position.lng)
	maisonDuJoueur(position.lat, position.lng)
	$.get('index.php?controller=utilisateur&action=saveAddress&profil='+$('#pseudo h1').text()+'&address='+position.lat+'&cp='+position.lng, (data) => {}) 

	}).addTo(map);
	

	apelAPIGouv(lat,lng)

	maisonDuJoueur();
	
	maisonAutreJoueur();		
	var intervalAchatMaisonParAutreJoueurID = window.setInterval(maisonAutreJoueur, 5000);
	var intervalRentreArgentID = window.setInterval( () => {
		
		$.get('index.php?controller=utilisateur&action=majArgent',(getNouveauSolde) =>{
			var player = $('#player_audio');
			player[0].play();
			$('#solde').text(getNouveauSolde);
		})		
	}, 60000 );
	var intervalClassementJoueurID = window.setInterval( () => {
		updateClassement()	
	}, 5000 );
}
    
})
