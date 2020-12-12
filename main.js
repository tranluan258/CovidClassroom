$(document).ready(()=>{
    $("#myBtn").click(() =>{
        $("#myModal").modal();
    });
});

$(document).ready(()=>{
    $("#myBtnaddnewclass").click(() =>{
        $("#myModaladdnewclass").modal();
    });
});

function suggest(str) {
    if (str.length == 0) {
        $(".list-subject").show();
        $(".list-search-subject").hide();
    } else {
        $(".list-subject").hide();
        $(".parent-search-list").empty();
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let array = this.response;
                for(let i=0;i<array.length;i++){
                    $(".parent-search-list").append(
                        "<div class=\"col-md-3 col-sm-6 col-xs-12 row-container list-search-subject\">\n" +
                        "                <form action=\"subject/detail\" method=\"post\">\n" +
                        "                    <div class=\"subject_homepage\">\n" +
                        "                        <div class=\"textsubject_homepage\">\n" +
                        "                            <input class=\"currentcode\" name=\"currentcode\" id=\"currentcode\" value=\""+array[i]['code']+"\">\n" +
                        "                            <p>Tên lớp:"+array[i]['classname']+"</p>\n" +
                        "                            <p>Môn học:"+array[i]['subjectname']+" </p>\n" +
                        "                            <p>Giáo viên:"+array[i]['teachername']+" </p>\n" +
                        "                            <p>Phòng học:"+array[i]['room']+"</p>\n" +
                        "                        </div>\n" +
                        "                        <p> </p><br>\n" +
                        "                        <p> </p><br>\n" +
                        "                        <p> </p><br>\n" +
                        "                        <div class=\"btn_join_into_class\">\n" +
                        "                            <button type=\"submit\" class=\"btn btn-primary\" name=\"joinintoclass\" id=\"joinintoclass\">\n" +
                        "                                <i class=\"fa fa-arrow-circle-up\" aria-hidden=\"true\"></i>  Join Class</button>\n" +
                        "                        </div>\n" +
                        "\n" +
                        "                    </div>\n" +
                        "                </form>\n" +
                        "            </div>"
                    )
                }
            }
        };
        xmlhttp.responseType = "json"
        xmlhttp.open("POST", "home/search?q=" + str, true);
        xmlhttp.send();
    }
}

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight) + "px";
}


function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight) + "px";
}



function selectAll() {
    var items = document.getElementsByName('liststudent[]');
    for (var i = 0; i < items.length; i++) {
        if (items[i].type == 'checkbox')
            items[i].checked = true;
    }
}

function UnSelectAll() {
    var items = document.getElementsByName('liststudent[]');
    for (var i = 0; i < items.length; i++) {
        if (items[i].type == 'checkbox')
            items[i].checked = false;
    }
}