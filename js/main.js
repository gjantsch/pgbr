/**
 * Created by Gustavo Jantsch <jantsch@gmail.com> on 05/09/17.
 */

document.getElementById("remover").addEventListener("click", function() {

    var id = document.getElementById("id_transacao"),
        status = document.getElementById("status2"),
        erro = false,
        msg = [];

    if (id.value == "") {
        id.borderColor = "#f00";
        id.focus();
        msg.push("O id é obrigatório.");
        erro = true;

    } else {
        id.borderColor = "#fff";
    }

    if (erro) {
        status.style.color = "#f00";
        status.innerHTML = "<ul><li>" + msg.join("</li><li>")  + "</li></ul>";
        return false;
    } else {
        status.innerHTML = "";
    }


    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            var xmlDoc = xhttp.responseXML;
            var status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            var mensagem = xmlDoc.getElementsByTagName("mensagem")[0].childNodes[0].nodeValue;

            document.getElementById("status2").innerHTML = mensagem;
        }
    };

    var data = [
        "token=d598ff54a88c1a356a48a6b0936b2dc3",
        "id=" + encodeURIComponent(id.value)
    ];

    xhttp.open("POST", "/api/remover", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(data.join("&"));


});

document.getElementById("inserir").addEventListener("click", function(){

    var nome = document.getElementById("nome"),
        email = document.getElementById("email"),
        valor = document.getElementById("valor"),
        status = document.getElementById("status1"),
        erro = false,
        msg = [];

    if (valor.value == "") {
        valor.borderColor = "#f00";
        valor.focus();
        msg.push("O valor é obrigatório.");
        erro = true;

    } else {
        valor.borderColor = "#fff";
    }

    if (email.value == "") {
        email.style.borderColor = "#f00";
        email.focus();
        msg.push("O email é obrigatório.");
        erro = true;

    } else {
        email.style.borderColor = "#fff";
    }

    if (nome.value == "") {
        nome.style.borderColor = "#f00";
        nome.focus();
        msg.push("O nome é obrigatório.");
        erro = true;

    } else {
        nome.borderColor = "#fff";
    }

    if (erro) {
        status.style.color = "#f00";
        status.innerHTML = "<ul><li>" + msg.join("</li><li>")  + "</li></ul>";
        return false;
    } else {
        status.innerHTML = "";
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            var xmlDoc = xhttp.responseXML;
            var status = xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
            var mensagem = xmlDoc.getElementsByTagName("mensagem")[0].childNodes[0].nodeValue;

            document.getElementById("status1").innerHTML = mensagem;
        }
    };

    var data = [
        "token=d598ff54a88c1a356a48a6b0936b2dc3",
        "nome=" + encodeURIComponent(nome.value),
        "email=" + encodeURIComponent(email.value),
        "valor=" + encodeURIComponent(valor.value)
    ];

    xhttp.open("POST", "/api/adicionar", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(data.join("&"));

});