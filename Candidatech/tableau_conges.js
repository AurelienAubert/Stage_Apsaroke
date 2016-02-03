$(document).ready(function() {
    $('.clickable').click(afficher_bouton);

    $('.valider').click(function () {
        var td = $(this).parent().prev().prev().find('td[class$=" clickable"]');
        var data = {
            mois: mois,
            annee: annee,
            action: 'valider',
            col_id: $(this).val()
        };

        $(td).each(function () {
            data[$(this).children().html()] = $(this).attr("class").split(' ')[0];
        });
        ajax(data);
    });

    $('.refuser').click(function() {
        if (confirm("Etes-vous sur de vouloir supprimer cette demande ?")) {
            var data = {
                mois: mois,
                annee: annee,
                action: 'refuser',
                col_id: $(this).val()
            };
            ajax(data);
        }
    });
});