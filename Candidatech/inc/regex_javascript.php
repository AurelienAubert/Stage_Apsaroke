<?php
    include_once "inc/regex.php";
?>

<script type="text/javascript">
    function surligne(champ, erreur) {
            if (erreur)
                champ.style.backgroundColor = "#fba";
            else
                champ.style.backgroundColor = "";
        }

    $(document).ready(function () {
        var regex_array = {
        <?php 
            foreach ($GLOBALS['regex_array'] as $nom=>$regex) {
                echo $nom . ": " . str_replace('#', '/', $regex) . ",\n";
            }
        ?>
        };
        var champs = {
        <?php 
            foreach ($GLOBALS['regex_champs'] as $nom=>$regex) {
                echo $nom . ": '" . $regex . "',\n";
            }
        ?>
        };
        var regex = /^\/(.+)\/$/;

        $.each(champs, function( index, value ) {
            $("input[name='" + index + "']").attr('pattern', regex_array[value].toString().replace(regex, "$1"))
                                            .blur(function() {
                                                if ($(this).attr('required')=='required' && this.value.length==0) {
                                                    surligne(this, true);
                                                }
                                                else if ($(this).attr('required')!='required' && this.value.length==0) {
                                                    surligne(this, false);
                                                }
                                                else {
                                                    surligne(this, !regex_array[value].test(this.value));
                                                }
                                            })
                                            .keypress(function() {
                                                surligne(this, false);
                                            });
        });
    });
</script>