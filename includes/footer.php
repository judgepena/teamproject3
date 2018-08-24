<footer class="text-center" id="footer">&copy; Copyright 2017 Bronter Market </footer>

<script>
    function detailsmodal(id){
        var data = {"id" : id};
        jQuery.ajax({
            url : "../includes/modal.php",
            method : "post",
            data : data,
            success : function(data){
                jQuery('body').append(data);
                jQuery('#details-modal').modal('toggle');
            },
            error : function(){
                alert('Something went wrong');
            }
        });
    }
    
    function add_to_cart(){
        jQuery('#modal_errors').html("");
        var quantity = jQuery('#quantity').val();
        var available = jQuery('#available').val();
        var error = '';
        var data = jQuery('#add_product_form').serialize();
        if(quantity == '' || quantity == 0){
            error += '<p class="text-danger text-center">Please enter quantity.</p>';
            jQuery('#modal_errors').html(error);
            return;
        }//else{
            //jQuery.ajax({
               // url : '../admin/parsers/add_cart.php',
               // method : 'post',
              //  data : data,
             //   success : function(){},
            //    error : funtion()
          //      {alert("Something went wrong.");
          //  });
            
      //  }
    }
    
</script>


</body>
</html>