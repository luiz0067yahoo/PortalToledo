function savewebsite() {
    var cars = ["facebook", "amazon", "instagram", "twitter", "youtube", "paypal", "twitch", "tiktok", "cash.app", "onlyfans", "skype", "gofundme", "manyvids", "snapchat"];
    for (var j = 1; j <= 5; j++) {
        for (var i = 0; i < cars.length; i++) {
            if ($('#sociallink' + j).val().indexOf(cars[i]) > -1) {
                return false;
                // alert("hai");
            } else {

            }
        }
    }
}