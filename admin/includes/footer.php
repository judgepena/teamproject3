
<footer class="col-md-12 text-center" id="footer">&copy; Copyright 2017 Bronter Market </footer>

<script>
    function get_child_options(selected){
        if(typeof selected === 'undefined'){
            var selected = '';
        }
        var parentID = jQuery('#parent').val();
        jQuery.ajax({
            url: '../admin/parsers/child_category.php',
            type: 'POST',
            data: {parentID : parentID, selected: selected},
            success: function(data){
                jQuery('#child').html(data);
            },
            error: function(){alert("Something went wrong with the child options.")},
        });
    }
    jQuery('select[name="parent"]').change(function(){
        get_child_options();
    });
</script>

</body>
</html>