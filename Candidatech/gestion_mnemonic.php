<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="mnemo-dialog" title="Mnémonique">
   <input type="text" name="MNEMONIC" id="mnemo"></input>
   <div style="display:none;">Mnémonique incorrecte</div>
</div>
<script>
    function test_mnemo()
    {
       
        if($('[name="PRENOM"]').val().search(' ')>0 || $('[name="NOM"]').val().search(' ')>0)
        {
            var prenom = $('[name="PRENOM"]').val().split(' ');  
            var nom = $('[name="NOM"]').val().split(' ');
        }
        else
        {
            var prenom = $('[name="PRENOM"]').val().split('-');  
            var nom = $('[name="NOM"]').val().split('-');
        }
       
        
        if(prenom.length == '2')
        {
            var plp = prenom[0].substring(0,1);
            var pln = prenom[1].substring(0,1);
            var dln = $('[name="NOM"]').val().substring(0,1);
        }
        else
        {
            if(nom.length == '2')
            {
                var plp = $('[name="PRENOM"]').val().substring(0,1);
                var pln = nom[0].substring(0,1);
                var dln = nom[1].substring(0,1);
            }
            else
            {
                var plp = $('[name="PRENOM"]').val().substring(0,1);
                var pln = $('[name="NOM"]').val().substring(0,1);
                var dln = $('[name="NOM"]').val().substring($('[name="NOM"]').val().length-1,$('[name="NOM"]').val().length);
            }
        }
        
        if(prenom.length == '2' && nom.length == '2')
        {
            var plp = prenom[0].substring(0,1);
            var pln = prenom[1].substring(0,1);
            var dln = nom[0].substring(0,1);
        }
        var mnemonic =  plp + pln + dln;
        
        
       // var plp = $('[name="PRENOM"]').val().substring(0,1);
       // var pln = $('[name="NOM"]').val().substring(0,1);
       // var dln = $('[name="NOM"]').val().substring($('[name="NOM"]').val().length-1,$('[name="NOM"]').val().length);
       // var mnemonic =  plp + pln + dln; 
       //alert($('.mnemo').val());
         $('#mnemo').val(mnemonic); 
         $('#mnemo-dialog').dialog({
                        autoOpen: true,
                        top: 0,
                        left: 200,
                        height: 200,
                        width: 250,
                        modal: true,
                buttons: {
                    "Sélectionner": function() {
                        $(this).children('div').hide();
                        if (/^[A-Z]{3}$/.test($('#mnemo').val())) {
                            $('[name="MNEMONIC"]').val($('#mnemo').val());
                            $(this).dialog('close');
                        }
                        else {
                          $(this).children('div').css({
                                color: '#610000',
                                background: '#F0C8C8',
                                border: '2px solid #610000',
                                'text-align':'center'
                            }).slideDown();
                        }
                    },
                    "Annuler": function() {
                        $(this).children('div').hide();
                        $(this).children().val('');
                        $(this).dialog('close');
                    }
                     }
                    });
    }
    

</script>
