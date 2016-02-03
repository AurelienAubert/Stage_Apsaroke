var classe = $('select').val();

function click_correct(bouton) {
    var date = new Date();
//    passe la date au surlendemain pour bloquer 48h
//    date.setDate(date.getDate()+2);
    var jour = $(bouton).children().html();
    
    if (accreditation != 1 && classe != 'Congéssanssolde' && classe != 'Maladie') {
        if (annee>date.getFullYear()) {
            return true;
        }
        else if (annee==date.getFullYear()) {
            if (mois>(date.getMonth()+1)) {
                return true;
            }
            else if (mois==(date.getMonth()+1) && jour>date.getDate()) {
                return true;
            }
        }
    }
    else {
        return true;
    }
    return false;
}

function click_clickable() {
    if (click_correct(this)) {
        if ($(this).hasClass(classe)) {
            $(this).attr('class', classe + '-b');
        }
        else if ($(this).hasClass(classe + '-b')) {
            $(this).attr('class', '');
        }
        else {
            $(this).attr('class', classe);
        }
        $(this).addClass('clickable');
    }
}

$(document).ready(function () {
    
    var type = [];

    $('option').each(function() {
        type.push($(this).val());
        type.push($(this).val() + '-b');
    });

    $('select').change(function () {
        classe = $(this).val();
    });
    
    $('form').submit(function () {
        var nombre_type = type.length;
        for (var i=0 ; i<nombre_type ; i++) {
            $('.clickable.' + type[i]).each(function() {
                $('<input/>', {
                    type: 'hidden',
                    name: $(this).children().html(),
                    value: $(this).attr("class").split(' ')[0]
                }).appendTo($('form'));
            });
        }
    });
    
    $('.type').click(function () {
        var select = $(this).children().first().html().replace(/ /g, '');
        $('option:selected').prop('selected', false);
        $('option[value="' + select + '"]').attr('selected', 'selected');
        classe = select;
    });
    
    $('.clickable').click(click_clickable);
});