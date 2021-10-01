<script>
    $(document).ready(function() {
        $("#REGION_ID").change(function() {
            filter_data(this.id);
        });

        $("#OFFICE_ID").change(function() {
            filter_data(this.id);
        });

        $("#BR_ID").change(function() {
            filter_data(this.id);
        });
    });

    if ($("input[id='CANCEL_DATE']").val() == 2) {
        $("div[id='CANCEL_DATE_BSF_AREA']").show();
    } else {
        $("div[id='CANCEL_DATE_BSF_AREA']").hide();
        $("input[id='CANCEL_DATE']").prop('required', false);
    }

    function filter_data(id) {
        reg_id = $("#REGION_ID").val();

        if (id == "REGION_ID") {
            if (reg_id == "" || reg_id == null) {
                select2_filter("OFFICE_ID", "", "reset_off");
                select2_filter("BR_ID", "", "reset_br");
            } else {
                select2_filter("OFFICE_ID", reg_id, 1);
                select2_filter("BR_ID", "", 2);
            }
        }

        if (id == "OFFICE_ID") {
            select2_filter("BR_ID", "", 2);
        }
    }

    function select2_filter(obj_name, id_data, proc) {
        region_id = $("#REGION_ID").val();
        txt = $("#" + obj_name).attr("placeholder");
        office_id = $("#OFFICE_ID").val();
        //   branch_id = $("#BR_ID").val();

        $("#" + obj_name).empty();
        if (proc == 2) {
            var setdata = {
                proc: proc,
                reg_id: region_id,
                off_id: office_id
            };
        } else {
            var setdata = {
                proc: proc,
                dep_id: id_data,
            };
        }

        $.ajax({
            url: "../webapp/load_select.php",
            type: "post",
            async: false,
            data: setdata,
            success: function(data) {
                $("#" + obj_name).append("<option value disabled selected>" + txt + "</option>"); //append first
                $.each(data, function(k, v) {
                    $("#" + obj_name).append(
                        "<option value=" + v.id + ">" + v.text + "</option>"
                    );
                });
            },
            error: function(data, thrown, error) {
                console.log(error);
            },
        });
    }
</script>