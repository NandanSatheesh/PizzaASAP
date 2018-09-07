$(document).ready(function() {
    var timer = setInterval(searchForNotifications, 5000);
});

function searchForNotifications() {
    var email = $("#mail-field").val();
    $.ajax({
        type: "POST",
        url: "../php_utils/notifications_admin.php",
        data: "email=" +email,
        success: function(data) {
            handleNotifications(data);
        }
    });
}

//formato risposta:
//tipo,testo,data;tipo,testo,data;tipo,testo,data;...
function handleNotifications(data) {
    if(data){
        data = data.slice(0, -1);
        var not_list = data.split(";");
        //not_list[0] = tipo,testo
        //not_list[1] = tipo,testo
        //...
        bellWiggle(not_list.length);
        var url = $(location).attr("href");
        if(url.includes("notifiche.php")) {
            var newline = '';
            var notification_line = new Array();
            for (var i = 0; i < not_list.length; i++) {
                notification_line = not_list[i].split(",");
                newline = '<div class="row bg-lightblue notification-row" style="display:none"><div class="col-md-1 align-self-center icon-col"><span class="fas fa-exclamation"></span></div><div class="col-md-9 align-self-center"><span class="notification-text">'+notification_line[1]+'</span></div><div class="col-md-2 align-self-center"><span class="notification-date">'+notification_line[2]+'</span></div></div>';
                $('.notifications-container').prepend(newline);//.show('fast');
                $(".notification-row").show(400);
            }
        }
    }
}


function bellWiggle(qty) {
    document.getElementById("bell-icon").classList.add("wiggle");
    setTimeout(function() {
        document.getElementById("bell-icon").classList.remove("wiggle");
    }, 300);
    var count = document.getElementById("bell-count").innerHTML;
    count = parseInt(count) + qty;
    document.getElementById("bell-count").innerHTML = count;
    if(document.getElementById("bell-count").classList.contains("hidden")) {
        document.getElementById("bell-count").classList.remove("hidden");
    }
}
