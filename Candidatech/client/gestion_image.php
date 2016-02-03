    <script>
        $(document).on("click", "button[name='supprime']", function () {
            $("input[name='LOGO']").val('');
            $("input[name='logo_client']").val('');
            $("#divlogo").html('Ce client n\'a pas de logo');
        });
    </script>
