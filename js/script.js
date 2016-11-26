$(function () {
    document.getElementsByClassName('select_history')[0].ondblclick = function () {
        if (this.selectedIndex === -1) {
            return null;
        }

        var query = this.options[this.selectedIndex].text;
        var format = "";

        $.each(query.split("/#"), function (index, value) {
            if (value !== '') {
                if (value.substring(0,1) === " ") {
                    value = value.substring(1);
                }
                
                format += value + "\n";
            }
        });

        document.getElementById("sql").value = format;
    };
});