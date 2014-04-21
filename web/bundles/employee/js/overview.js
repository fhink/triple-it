$(document).ready(function () {
    "use strict";

    $(".ti-js-ajax").on("click", function (e) {
        e.preventDefault();
        $.get(this.href, function (data) {
            var employee = JSON.parse(data);

            if (employee.photoUrl === null) {
                employee.photoUrl = "/bundles/employee/images/employee-thumbnail.jpg";
            } else {
                employee.photoUrl = "//www.triple-it.nl/resources/assessment/" + employee.photoUrl;
            }

            $("#detailed-view").html(
                ich.detailedView(employee)
            );
        });
    })

    $("#myTab").find("a").click(function (e) {
        e.preventDefault();
        $(this).tab('show')
    })
});