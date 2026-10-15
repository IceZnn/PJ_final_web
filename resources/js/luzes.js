$(document).ready(function() {

    const IP = "http://IP_DO_ESP";

    // VERMELHA
    $("#ligar_vermelha").click(function() {
        $.ajax({
            url: IP + "/led/ligarvermelho",
            type: "POST",
            success: function(response) {
                alert("Luz Vermelha ligada!");
            }
        });
    });

    $("#desligar_vermelha").click(function() {
        $.ajax({
            url: IP + "/led/desligarvermelho",
            type: "POST",
            success: function(response) {
                alert("Luz Vermelha desligada!");
            }
        });
    });

    // VERDE
    $("#ligar_verde").click(function() {
        $.ajax({
            url: IP + "/led/ligarverde",
            type: "POST",
            success: function(response) {
                alert("Luz Verde ligada!");
            }
        });
    });

    $("#desligar_verde").click(function() {
        $.ajax({
            url: IP + "/led/desligarverde",
            type: "POST",
            success: function(response) {
                alert("Luz Verde desligada!");
            }
        });
    });

    // AMARELA
    $("#ligar_amarela").click(function() {
        $.ajax({
            url: IP + "/led/ligaramarelo",
            type: "POST",
            success: function(response) {
                alert("Luz Amarela ligada!");
            }
        });
    });

    $("#desligar_amarela").click(function() {
        $.ajax({
            url: IP + "/led/desligaramarelo",
            type: "POST",
            success: function(response) {
                alert("Luz Amarela desligada!");
            }
        });
    });

    $("#ligar_todas").click(function() {
        $.ajax({
            url: IP + "/led/ligartodas",
            type: "POST",
            success: function(response) {
                alert("Todas as luzes ligadas!");
            }
        });
    });

    $("#desligar_todas").click(function() {
        $.ajax({
            url: IP + "/led/desligartodas",
            type: "POST",
            success: function(response) {
                alert("Todas as luzes desligadas!");
            }
        });
    });

});