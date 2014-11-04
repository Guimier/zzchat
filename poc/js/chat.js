/* JavaScript Document*/

$(
	function()
	{
		/*Tableau des messages :*/
		messages = createTableMessages() ;
		
		/*Génération du choix des langues :*/
		$( '#menu' ).html( createMenuLang( messages ) ) ;
		/*Valeur par défaut de la langue choisie :*/
		langChoosen = 1 ;
		/*Génération : on remplace ce qui est désigné par l'ID dans le html par ce qui suit
		Appel de createForm pour batir le formulaire de login.*/
		$( '#login' ).html( createForm() ) ;
		$( '#footer' ).html( messages[langChoosen][4] ) ;
	}
);

function createForm()
{
	/*On batit le formulaire pour la saisie du pseudonyme : method permet de spécifier la méthode utilisée pour passer les paramètres (post ou get), enctype donne le type du paramètre qui sera transmis, onsubmit réaliser une fonction à la soumission (içi vérifier la validité du pseudo).*/
	myString ="<form id=\"myform\" method=\"post\" enctype=\"multipart/form-data\" onsubmit=\"return testLogin(document.getElementById('pseudo'))\">" ;
	/*Label sert à mettre un label devant le champ à compléter.*/
	myString = myString + "<label id=\"loginLabel\" for=\"pseudo\">" + messages[langChoosen][2] + "</label><br/>" ;
	/*On crée le champ à compléter :*/
	myString = myString + "<input type=\"text\" id=\"pseudo\" name=\"pseudo\" size=\"16\"></input>" ;
	/*On crée un super bouton :*/
	myString = myString + "<input type=\"button\" id=\"btn\" value=" + messages[langChoosen][3] + "onclick=\"checkLogin()\"></input>" ;
	/*On ferme le formulaire. */
	myString = myString + "</form>" ;
	return myString ;
}
		
function createTableMessages()
{
			
	var col1 = new Array() ;
	var col2 = new Array() ;
	col1[1] = "Français" ;
	col1[2] = "Participant à l'agora, annoncez-vous :" ;
	col1[3] = "Entrer dans l'agora" ;
	col1[4] = "Agora ZZ © 2014, All Rights Reserved.<br/>Créé par Lucien Guimier, Damien Teyssier et Guilhem Drogue" ;
	col2[1] = "Anglais" ;
	col2[2] = "Say who you are" ;
	col2[3] = "Enter agora" ;
	col2[4] = "Agora ZZ © 2014, All Rights Reserved.<br/>Create by Lucien Guimier, Damien Teyssier and Guilhem Drogue" ;
			
	messages = new Array() ;
	messages[1] = col1 ;
	messages[2] = col2 ;
			
	return messages ;
}

function createMenuLang(messages)
{
	menu = "<form id=\"listLang\" name=\"listLang\"><select id=\"menuint\" name=\"menuint\" onchange=\"refreshLang()\")\">" ;
	for ( var i = 1 ; i <= 2 ; i++ )
	{
		menu = menu + "<option value=\"" + i + "\">" + messages[i][1] + "</option>" ;
	}
	menu = menu + "</select></form>" ;
	return menu ;
}

function refreshLang()
{
	var indChoosen = document.forms["listLang"].elements["menuint"].selectedIndex ;
	var newLangChoosen = document.forms["listLang"].elements["menuint"].options[indChoosen].value ;
	if ( newLangChoosen != langChoosen )
	{
		langChoosen = newLangChoosen ;
		$( '#loginLabel' ).text( messages[langChoosen][2] ) ;
		$( '#btn' ).val( messages[langChoosen][3] ) ;
		$( '#footer' ).html( messages[langChoosen][4] ) ;
	}
}
