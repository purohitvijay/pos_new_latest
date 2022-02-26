var big = 1;

$("div.perbox2").on("click", function () {
    if (big == 1) {
        $(this).animate({
            width: "200px",
            height: "250px",
        }, 900);
        $(this).css("font-size", "13px");
        big = 0;
        
    } else {
        $(this).animate({
            width: "100px",
            height: "150px",
        }, 900);
        $(this).css("font-size", "0px");
        big = 1;
    }
});