var count = 0;

function addToCart(id) {

    var email = $("#cart-field").val(); //salvataggio email
    var qty = parseInt($("#qty-"+id).val());
    //inserimento nel db
    $.ajax({
        type: "POST",
        url: "../php_utils/cartUpdate.php",
        data: "email=" +email+ "&id=" +id+ "&qty=" +qty+ "&count=" +count,
        success: function(data) {
            count++;
            cartWiggle(qty); //animazione carrello
        }
    });
}

function cartWiggle(qty) {
    document.getElementById("cart-icon").classList.add("wiggle");
    setTimeout(function() {
        document.getElementById("cart-icon").classList.remove("wiggle");
    }, 300);
    var count = document.getElementById("cart-count").innerHTML;
    count = parseInt(count) + qty;
    document.getElementById("cart-count").innerHTML = count;
    if(document.getElementById("cart-count").classList.contains("hidden")) {
        document.getElementById("cart-count").classList.remove("hidden");
    }
}
