// JavaScript Document
/****************************************************************************
*________________________________________________________________________ *
* About : Convertit jusqu'� 999 999 999 999 999 (Billion) *
* avec respect des accords *
*_________________________________________________________________________ *
* Auteur : GALA OUSSE Brice, Engineer programmer of management *
* Mail : bricegala@yahoo.fr, bricegala@gmail.com *
* T�l : +237 99 37 95 83/ +237 79 99 82 80 *
* Copyright : avril 2007 *
*_________________________________________________________________________ *
*****************************************************************************
*/
function Unite( nombre ){
    var unite;
    switch( nombre ){
        case 0:
            unite = "z�ro";
            break;
        case 1:
            unite = "Un";
            break;
        case 2:
            unite = "Deux";
            break;
        case 3:
            unite = "Trois";
            break;
        case 4:
            unite = "Quatre";
            break;
        case 5:
            unite = "Cinq";
            break;
        case 6:
            unite = "Six";
            break;
        case 7:
            unite = "Sept";
            break;
        case 8:
            unite = "Huit";
            break;
        case 9:
            unite = "Neuf";
            break;
    }//fin switch
    return unite;
}//-----------------------------------------------------------------------
function Dizaine( nombre ){
    switch( nombre ){
        case 10:
            dizaine = "Dix";
            break;
        case 11:
            dizaine = "Onze";
            break;
        case 12:
            dizaine = "Douze";
            break;
        case 13:
            dizaine = "Treize";
            break;
        case 14:
            dizaine = "Quatorze";
            break;
        case 15:
            dizaine = "Quinze";
            break;
        case 16:
            dizaine = "Seize";
            break;
        case 17:
            dizaine = "Dix-Sept";
            break;
        case 18:
            dizaine = "Dix-Huit";
            break;
        case 19:
            dizaine = "Dix-Neuf";
            break;
        case 20:
            dizaine = "Vingt";
            break;
        case 30:
            dizaine = "Trente";
            break;
        case 40:
            dizaine = "Quarante";
            break;
        case 50:
            dizaine = "Cinquante";
            break;
        case 60:
            dizaine = "Soixante";
            break;
        case 70:
            dizaine = "Soixante-Dix";
            break;
        case 80:
            dizaine = "Quatre-Vingt";
            break;
        case 90:
            dizaine = "Quatre-Vingt-Dix";
            break;
    }//fin switch
    return dizaine;
}//-----------------------------------------------------------------------
function NumberToLetter( nombre ){
    var i, j, n, quotient, reste, nb ;
    var ch
    var numberToLetter='';
    //__________________________________
    if( nombre.toString().replace( / /gi, "" ).length > 15 ) return "d�passement de capacit�";
    if( isNaN(nombre.toString().replace( / /gi, "" )) ) return "Nombre non valide";
    nb = parseFloat(nombre.toString().replace( / /gi, "" ));
    if( Math.ceil(nb) != nb ) return "Nombre avec virgule non g�r�.";
    n = nb.toString().length;
    switch( n ){
        case 1:
            numberToLetter = Unite(nb);
            break;
        case 2:
            if( nb > 19 ){
            quotient = Math.floor(nb / 10);
            reste = nb % 10;
            if( nb < 71 || (nb > 79 && nb < 91) ){
                if( reste == 0 ) numberToLetter = Dizaine(quotient * 10);
                if( reste == 1 ) numberToLetter = Dizaine(quotient * 10) + "-et-" + Unite(reste);
                if( reste > 1 ) numberToLetter = Dizaine(quotient * 10) + "-" + Unite(reste);
            }else numberToLetter = Dizaine((quotient - 1) * 10) + "-" + Dizaine(10 + reste);
        }else numberToLetter = Dizaine(nb);
            break;
        case 3:
            quotient = Math.floor(nb / 100);
            reste = nb % 100;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Cent";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Cent" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = Unite(quotient) + " Cents";
            if( quotient > 1 && reste != 0 ) numberToLetter = Unite(quotient) + " Cent " + NumberToLetter(reste);
            break;
        case 4 :
            quotient = Math.floor(nb / 1000);
            reste = nb - quotient * 1000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Mille";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Mille" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Mille";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Mille " + NumberToLetter(reste);
            break;
        case 5 :
            quotient = Math.floor(nb / 1000);
            reste = nb - quotient * 1000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Mille";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Mille" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Mille";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Mille " + NumberToLetter(reste);
            break;
        case 6 :
            quotient = Math.floor(nb / 1000);
            reste = nb - quotient * 1000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Mille";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Mille" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Mille";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Mille " + NumberToLetter(reste);
            break;
        case 7:
            quotient = Math.floor(nb / 1000000);
            reste = nb % 1000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Million";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Million" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Millions";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Millions " + NumberToLetter(reste);
            break;
        case 8:
            quotient = Math.floor(nb / 1000000);
            reste = nb % 1000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Million";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Million" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Millions";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Millions " + NumberToLetter(reste);
            break;
        case 9:
            quotient = Math.floor(nb / 1000000);
            reste = nb % 1000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Million";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Million" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Millions";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Millions " + NumberToLetter(reste);
            break;
        case 10:
            quotient = Math.floor(nb / 1000000000);
            reste = nb - quotient * 1000000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Milliard";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Milliard" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Milliards";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Milliards " + NumberToLetter(reste);
            break;
        case 11:
            quotient = Math.floor(nb / 1000000000);
            reste = nb - quotient * 1000000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Milliard";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Milliard" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Milliards";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Milliards " + NumberToLetter(reste);
            break;
        case 12:
            quotient = Math.floor(nb / 1000000000);
            reste = nb - quotient * 1000000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Milliard";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Milliard" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Milliards";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Milliards " + NumberToLetter(reste);
            break;
        case 13:
            quotient = Math.floor(nb / 1000000000000);
            reste = nb - quotient * 1000000000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Billion";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Billion" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Billions";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Billions " + NumberToLetter(reste);
            break;
        case 14:
            quotient = Math.floor(nb / 1000000000000);
            reste = nb - quotient * 1000000000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Billion";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Billion" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Billions";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Billions " + NumberToLetter(reste);
            break;
        case 15:
            quotient = Math.floor(nb / 1000000000000);
            reste = nb - quotient * 1000000000000;
            if( quotient == 1 && reste == 0 ) numberToLetter = "Un Billion";
            if( quotient == 1 && reste != 0 ) numberToLetter = "Un Billion" + " " + NumberToLetter(reste);
            if( quotient > 1 && reste == 0 ) numberToLetter = NumberToLetter(quotient) + " Billions";
            if( quotient > 1 && reste != 0 ) numberToLetter = NumberToLetter(quotient) + " Billions " + NumberToLetter(reste);
            break;
    }//fin switch
    /*respect de l'accord de quatre-vingt*/
    if( numberToLetter.substr(numberToLetter.length-"Quatre-Vingt".length,"Quatre-Vingt".length) == "Quatre-Vingt" ) numberToLetter = numberToLetter + "s";
    return numberToLetter;
}//----------------------------------------------------------------------- 
function NumberToLetterTunisien (number){
    number=number.toString();
    var tab = number.split(".");
    
    var dinars = tab[0];
    //var millimes = tab[1];
    if(tab.length==1){
        var millimes ="000";
    }else{
        var millimes = tab[1];
    }
    if(millimes.length==1){
        millimes=millimes+"00";
    }else if(millimes.length==2){
        millimes=millimes+"0";
    }
    var dinarText = ' Dinars';
    var millimeText = ' Millimes';

    var dinarsLetter = NumberToLetter(dinars);
    var millimesLetter = NumberToLetter(millimes);

    if(dinars == "0" && millimes == "000"){
        return "";
    }
    if(dinars == "0"){
        return millimesLetter + millimeText;
    }
    if(millimes == "000"){
        return dinarsLetter + dinarText;
    }
    return dinarsLetter + dinarText + ', ' + millimesLetter + millimeText;
}//----------------------------------------------------------------------- 