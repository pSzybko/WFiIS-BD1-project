var xmlHttp;
function getRequestObject(){
    if(window.ActiveXObject){
        return (newActiveXObject("Microsoft.XMLHTTP"));
    } else if(window.XMLHttpRequest){
        return (new XMLHttpRequest());
    } else{
        return(null);
    }
}
function showContent(evt, tabName){
    let MyDivElement = document.getElementsByClassName("MyDivElement");
    for (let i = 0; i < MyDivElement.length; i++) {
      MyDivElement[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
function showForm(flag){
    if(flag=='dodaj'){
        document.getElementById("formularzAdres").hidden=false;
        document.getElementById("formularzUsun").hidden=true;
    }else if(flag=='usun'){
        document.getElementById("formularzUsun").hidden=false;
        document.getElementById("formularzAdres").hidden=true;
    }else if(flag=='dodajRec'){
        document.getElementById("recenzjaForm").hidden=false;
        document.getElementById("recenzjaUsun").hidden=true;
    }else if(flag=='usunRec'){
        document.getElementById("recenzjaForm").hidden=true;
        document.getElementById("recenzjaUsun").hidden=false;
    }else if(flag=='miasto'){
        
        if(document.getElementById("selMiasto2").value=='Kraków'){
            document.getElementById("restKrakow").hidden=false;
            document.getElementById("restWarszawa").hidden=true;
        }else{
            document.getElementById("restKrakow").hidden=true;
            document.getElementById("restWarszawa").hidden=false;
        }
    }else if(flag=='dane'){
        document.getElementById("restDaneForm").hidden=false;
    }else if(flag=='kategoria'){
        document.getElementById("kategoriaForm").hidden=false;
        document.getElementById("produktForm").hidden=true;
        document.getElementById("produktUsun").hidden=true;
    }else if(flag=='dodajProdukt'){
        document.getElementById("kategoriaForm").hidden=true;
        document.getElementById("produktForm").hidden=false;
        document.getElementById("produktUsun").hidden=true;
    }else if(flag=='usunProdukt'){
        document.getElementById("kategoriaForm").hidden=true;
        document.getElementById("produktForm").hidden=true;
        document.getElementById("produktUsun").hidden=false;
    }
}
function showButtonForSelect(selId, divId){
    if(document.getElementById(selId).value!="wybierz")
        document.getElementById(divId).innerHTML="<input type='submit' value='Dalej'>";
    else
        document.getElementById(divId).innerHTML="";
}
function showButtonForSelect2(selId, buttonId){
    if(document.getElementById(selId).value!="wybierz")
        document.getElementById(buttonId).hidden=false;
    else
    document.getElementById(buttonId).hidden=true;
}
function getForm(formId){
    document.getElementById(formId).hidden=false;
}
function onlyOne(checkbox) {
    var checkboxes = document.getElementsByName('check');
    var b = document.getElementById('res_dalej');
    var flag=0;
    checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false
    });
    checkboxes.forEach((item) => {
        if (item.checked === true) {b.hidden = false; flag=1;}
    });
    if(flag==0)
        b.hidden = true;
}
function showButtonRecenzja() {
    if(document.getElementById("mojeRest").value!="wybierz" && document.getElementById("ocena").value!=""){
        document.getElementById("wyslijRecenzja").innerHTML="<input type='submit' value='Dalej'>";
    }
    else{
        document.getElementById("wyslijRecenzja").innerHTML="";
    }
}