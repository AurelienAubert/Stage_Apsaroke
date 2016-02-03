function somme_colonne(numero) {
    var valeur = 0;
    var name = 'select[name$="-' + numero + '"]';
    $(name).each(function () {
        valeur += eval($(this).val());
    });
    return valeur;
}

function verif_erreur(numero) {
    name = 'select[name$="-' + numero + '"]';
    
    var valeur = somme_colonne(numero);

    if (valeur > 1) {
        $(name).parent().parent().addClass('erreur');
        $(name).parent().parent().css('background-color', 'red');
    }
    else {
        $(name).parent().parent().removeClass('erreur');
        $(name).parent().parent().css('background-color', '');
    }
}

$(document).ready(function() {
    var nbJours = eval($('#nbJours').html());
    
    for(var i=1 ; i<=nbJours ; i++) {
        verif_erreur(i);
    }

    $('select').change(function () {
        var objet = this;
        var name = $(this).attr('name');
        var numero = name.split('-')[1];

        name = 'select[name$="-' + numero + '"]';
        
        if ($(this).val()=='1') {
            $(name).each(function () {
                if (this!=objet && $(this).attr('name') != ('c-' + numero)) {
                    $(this).children(":selected").prop('selected', false);
                    $(this).children("[name$='0']").select();
                }
            });
        }
        verif_erreur(numero);
    });
    
    /*
     * interdit le submit dans le cas où le RAM est mal rempli
     * 
     */
    $('form').submit(function () {
        var mauvais = '';
        
        $('tr:eq(2)').children('td').each(function () {
            if ($(this).hasClass('erreur')) {
                mauvais += $(this).children().children().attr('name').split('-')[1] + ', ';
            }
        });
        
        var nbJours = eval($('#nbJours').html());
        nb_travail = 0;
        nb_spe = 0;
        travail_ferie = false;
        $('th.dimanche, th.samedi, th.feries').each(function() {
            nb_spe++;
            $('select[name$="-' + $(this).html() + '"]').not('[name^="conges"]').each(function() {
                if (eval($(this).val()) != 0) {
                    travail_ferie = true;
                }
            });
        });
        
        for(numero=1 ; numero<=nbJours ; numero++) {
            nb_travail+=somme_colonne(numero);
        }

        if (mauvais.length>0) {
            alert("il y a des erreurs dans le tableau, colonnes : " + mauvais.substring(0, mauvais.length-2));
            return false;
        }
        
        
        if (travail_ferie) {
            if (!confirm('Avez-vous travaillé ou posé un congé le week-end ou un jour férié ?')) {
                return false;
            }
        }
        return true;
    });
});

