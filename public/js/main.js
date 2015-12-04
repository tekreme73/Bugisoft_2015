$(document).ready(function(){
    
    /*$("#rules_lab").hover(function() {
        $(this).addClass("transition_rules");
    });*/
    $('#p1').hide();
    $("#rules_lab").hover(function() {
           $('#h2').hide();
           $('#p1').show();
           
           
    });
    
    $("#rules_lab").mouseleave(function() {
           $('#p1').hide();
           $('#h2').show();
           
    });
    
   

    
    
});