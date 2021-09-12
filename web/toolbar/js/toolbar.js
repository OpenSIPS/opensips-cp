/*===================================================
    File Name: toolbar.js
    Description: install toolbar OpenSIPS specific js
    -------------------------------------------------
    Author: Song H. Netlab
    Author URL: http://netlab.com
=====================================================*/
if (typeof activeModule === 'undefined' || activeModule === null) {
    console.log("undefined active module");
} else
    activePage = activeModule;

if (typeof extraColumn === 'undefined' || extraColumn === null) {
    console.log("undefined active module");
} else {
    extraLimit = extraColumn;
}

try {

    $(document).ready(function () {

        $('.pagingTable').parent().parent().remove();
        // Check jquery
        var selectHead = $('.ttable thead th[class]');
        var rowCount = $('.ttable tr').length;

        $('th').each(function () {
            if ($(this).text() === 'Edit' || $(this).text() === 'Delete') {
                if ($(this).text() === 'Edit')
                    var editSelector = true;
                else
                    var editSelector = false;
                $('td:nth-child(' + ($(this).index() + 1) + '), th:nth-child(' + ($(this).index() + 1) + ')').each(function () {
                    if (!$(this).is('th')) {
                        if (editSelector)
                            $(this).parent().append('<input type="hidden" value="' + ($(this).children('a').attr('href')) + '" class="edit-href">');
                        else
                            $(this).parent().append('<input type="hidden" value="' + ($(this).children('a').attr('href')) + '" class="trash-href">');
                    }
                    $(this).remove();
                });
            }
        });

        $('.ttable').show();
        $('.ttable').parent().removeClass('spinner');
        // Right global search
        $('head').append('<style type="text/css">.dataTables_wrapper .dataTables_length {\n' +
            '        float: right;\n' +
            '    }</style>');
        $('head').append('<style type="text/css">.dataTables_wrapper .dataTables_filter {\n' +
            '        float: left;\n' +
            '        text-align: left;\n' +
            '    }</style>');

        var extraColRowHtml = "<tr><td><input type='text' name='name[]' class=\"form-control\"></td>" +
            "<td style='width: 30%'>" +
            "   <select  class='form-control' name='attr[]' class=\"form-control\">" +
            "       <option value='int'>int</option>" +
            "       <option value='varchar'>varchar</option>" +
            "   </select>" +
            "</td>" +
            "<td ><input type='number' name='value[]' class=\"form-control\"></td>" +
            "</tr>";

        console.log(activePage)
        var extraColHtml = "<form action='toolbar.php' method='post' class='toolbar-form'>" +
            "<input name='active_module' hidden value='" + activePage + "'>" +
            "<div class='table-responsive extra-col'>" +
            "   <input type='submit' class='btn btn-success btn-sm toolbar-save' style='margin: 5px' value='Save'>" +
            "   <a class='btn btn-primary add-column'><i class='fa fa-plus'></i></a>" +
            "   <button style='margin: 5px' type='button' class='btn close btn-danger extra-close'\n" +
            "                data-dismiss='alert' aria-label='Close'>\n" +
            "                <span aria-hidden='true'>×</span>\n" +
            "   </button>" +
            "<table class='table table-bordered text-center table-striped extra-table' name='extra-table' style='border-radius: 1rem;margin-bottom: 0'>" +
            "   <thead><tr><th  class='text-center'>Column Name</th><th  class='text-center'>Column Type</th><th  class='text-center'>Column Length</th></tr></thead>" +
            "   <tbody>" +
            "       <tr>" +
            "           <td>" +
            "               <input type='text' name='name[]' class=\"form-control\" required>" +
            "           </td>" +
            "           <td style='width: 30%'>" +
            "               <select  class='form-control' name='attr[]' class=\"form-control\">" +
            "                   <option value='int'>int</option>" +
            "                   <option value='varchar'>varchar</option>" +
            "               </select>" +
            "           </td>" +
            "           <td >" +
            "               <input type='number' name='value[]' class=\"form-control\">" +
            "           </td>" +
            "       </tr>" +
            "   </tbody>" +
            "</table>" +
            "</input></form>";

        var nToolbarHtml = "  <div class='btn-group btn-column'>\n" +
            "   <a data-toggle='tooltip' title='Add Column' class='btn btn-success extra-column'>" +
            "       <i class='fa fa-plus'></i>" +
            "   </a>\n" +
            "   <a data-toggle='tooltip' title='Column Hide/Show' class='dropdown-toggle btn btn-success hide-show'>" +
            "        <i class='fa fa-eye-slash hide-show-i'></i>" +
            "   </a>\n" +
            "   <a data-toggle='tooltip' title='Column Sort' class='btn btn-success column-short'>" +
            "       <i class='fa fa-sort' aria-hidden='true'></i>" +
            "   </a>\n" +
            "   <a data-toggle='tooltip' title='Column Edit' class='dropdown-toggle btn btn-success column-edit'>" +
            "       <i class='fa fa-edit' aria-hidden='true'></i>" +
            "   </a>\n" +
            "   <a data-toggle='tooltip' title='Column Trash' class='dropdown-toggle btn btn-success column-trash'>" +
            "       <i class='fa fa-trash' aria-hidden='true'></i>" +
            "   </a>\n" +
            "  </div>";

        var successAlert = "<div class='alert alert-success alert-dismissible success-alert' role='alert' style='display: none' id='successAlert'>\n" +
            "          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n" +
            "            <span aria-hidden='true'>&times;</span>\n" +
            "          </button>\n" +
            "          <p class='success-msg'> You just have added.</p>" +
            "   </div>";

        var errorAlert = "<div class='alert alert-danger alert-dismissible error-alert' role='alert' style='display: none' id='errorAlert'>\n" +
            "          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n" +
            "            <span aria-hidden='true'>&times;</span>\n" +
            "          </button>\n" +
            "           <p class='error-msg'>You just have added.</p>" +
            "   </div>";


        //Add new row in extra column
        var nToolbar = $('<div class="ntl-toolbar text-left"></div>');

        nToolbar.append(nToolbarHtml);
        nToolbar.append(extraColHtml);


        try {
            var ntlTable = $('.ttable').DataTable({
                ordering: false,
                dom: "<'row'<'col-sm-7 ntl-m-1'f><'col-sm-4'l>>" +
                    "<'row'<'col-sm-12 custom-toolbar'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            });
        } catch (err) {
            alert(err + " Please check install guide. https://netlab.com/opensips/toolbar");
            //window.top.location.reload();
        }

        $(".custom-toolbar").append(nToolbar);
        gDataTable = ntlTable;


        /*********************************************
         * Add Extra Column on Module Table-  Toolbar *
         **********************************************/

        // Hide/Show extra column
        $('.extra-column').click(function () {
            $('.extra-col').show(1200);
        });
        $('.extra-close').click(function () {
            $('.extra-col').hide(400);
        });


        // Add new row on custom table
        $('.add-column').click(function () {
            if (extraLimit < $('.extra-table tr').length) {
                alert("Can't add anymore. Please contact us ")
            } else
                $(".extra-table > tbody").append(extraColRowHtml);
        });

        //Submit request to create new columns
        var request;
        $(".toolbar-form").submit(function (event) {

            $('.show-alert').each(function () {
                $(this).remove();
            });

            event.preventDefault();
            if (request) {
                request.abort();
            }
            var $form = $(this);
            var formVal = "";
            var $inputs = $form.find("input, select, button, textarea");
            var serializedData = $form.serializeArray();

            $('.extra-table').find('tr').each(function () {
                formVal += '&' + this.id + '=' + $(this).text();
            });

            $inputs.prop("disabled", true);

            request = $.ajax({
                url: "/toolbar/toolbar.php",
                type: "post",
                data: serializedData
            });

            request.done(function (response, textStatus, jqXHR) {
                var res = JSON.parse(response);
                for (let i = 0; i < res.length; i++) {
                    if ('error' in res[i]) {
                        nToolbar.prepend(errorAlert);
                        $('.error-msg').text(res[i]['message']);
                        $('.error-msg').removeClass('error-msg');
                        $(".error-alert").show();
                        $(".error-alert").addClass('show-alert');
                        $('.error-alert').removeClass('error-alert');
                    }
                    else if('success' in res[i]){
                        nToolbar.prepend(successAlert);
                        $('.success-msg').text(res[i]['message']);
                        $('.success-msg').removeClass('success-msg');
                        $(".success-alert").show();
                        $(".success-alert").addClass('show-alert');
                        $('.success-alert').removeClass('success-alert');
                    }
                }
                $(".load-alert").hide();
            });

            request.fail(function (jqXHR, textStatus, errorThrown) {
                // Log the error to the console
                $("#errorAlert").addClass('show');
                console.error(
                    "The following error occurred: " +
                    textStatus, errorThrown
                );
            });

            request.always(function () {
                // Reenable the inputs
                $inputs.prop("disabled", false);
            });

        });


        /*******************************************************************************
         * Custom (Hide/Show, Shorting, Edit/Delete) Action Of Module Table  -  Toolbar *
         *******************************************************************************/

            // Highlight needed action row
        var oldRowIndex = null;
        $(".ttable").delegate("tr", "click", function () {

            if ($(this).children('th').length) {
                vt = 0;
            } else {
                var curRow = $(this).index();
                if (oldRowIndex !== curRow) {

                    $('tr').removeClass('row-highlight');
                    $(this).addClass('row-highlight');
                    oldRowIndex = curRow;

                } else {

                    if ($(this).hasClass('row-highlight'))
                        $(this).removeClass('row-highlight');
                    else
                        $(this).addClass('row-highlight');
                }
            }
        });


        // Hide/show a column
        $('.hide-show').click(function () {

            var highCnt = 0;
            var inRow = 0;

            if ($(this).children('i').hasClass('fa-eye')) {
                location.reload();
            } else
                $('.row-highlight').each(function () {
                    highCnt++;
                    if (!$(this).hasClass('ntl-row-hide'))
                        $(this).addClass('ntl-row-hide');
                });

            if (highCnt === 0 && !$(this).children('i').hasClass('fa-eye'))
                alert("Please select a row if needed actions");

            $('.ntl-row-hide').each(function () {
                inRow++;
            });

            if (inRow === (rowCount - 1)) {
                $(this).children('i').removeClass('fa-eye-slash');
                $(this).children('i').addClass('fa-eye');
            }
        });


        //Enable/Disable shorting
        var shortDisabled = true;
        $('.column-short').click(function () {
            if (shortDisabled) {
                ntlTable.destroy();
                ntlTable = $('.ttable').DataTable({
                    dom: "<'row'<'col-sm-7 ntl-m-1'f><'col-sm-4'l>>" +
                        "<'row'<'col-sm-12 custom-toolbar'>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                });
                $(".custom-toolbar").append(nToolbar);
                shortDisabled = false;
                gDataTable = ntlTable;
            } else {
                location.reload();
            }
        });


        // Edit a row
        $('.column-edit').click(function () {
            var highCnt = 0;
            $('.row-highlight').each(function () {
                highCnt++;
                window.location.href = $(this).children('.edit-href').val();
            });
            if (highCnt === 0)
                alert("Please select a row if needed actions");
        });


        // Trash a row
        $('.column-trash').click(function () {
            var highCnt = 0;
            $('.row-highlight').each(function () {
                highCnt++;
                if (confirm("Are you sure this " + activePage + "?")) {
                    window.location.href = $(this).children('.trash-href').val();
                }
            });
            if (highCnt === 0)
                alert("Please select a row if needed actions");
        });


        /********************************************
         * Custom Filter Of Module Table  -  Toolbar *
         *******************************************/

        //install custom filter
        selectHead.each(function (idx) {
            if ($(this).text() === 'Edit' || $(this).text() === 'Delete') {
                $(this).find('i').remove();
            } else {
                $(this).append('<i class="fa fa-filter filter-ico"></i>');
            }
        });


        var oldIndex = null;
        $('.filter-ico').click(function () {

            var filterContent = "" +
                "<div class='column-filter'>" +
                "  <ul class=\"nav nav-tabs\">\n" +
                "        <li class=\"active\"><a data-toggle=\"tab\" href=\"#home\">Contains</a></li>\n" +
                "       <li><a data-toggle=\"tab\" href=\"#menu1\">Does Not Contains</a></li>\n" +
                "  </ul>\n" +
                "  <div class=\"tab-content\" style='margin-top: 1.1rem;'>\n" +
                "    <div id=\"home\" class=\"tab-pane fade in active\">\n" +
                "      <input type='text' class='form-control contained' col-index='" + $(this).parent().index() + "'>\n" +
                "      <div class='col-filter contained'></div>\n" +
                "    </div>\n" +
                "    <div id=\"menu1\" class=\"tab-pane fade\">\n" +
                "      <input type='text' class='form-control uncontained' col-index='" + $(this).parent().index() + "'>\n" +
                "      <div class='col-filter uncontained' ></div>\n" +
                "    </div>\n" +
                "  </div>" +
                "  <div style='text-align: center'><a class='btn btn-danger btn-block apply-btn' col-index='" + $(this).parent().index() + "'>Apply</a></div>" +
                "</div>";

            var rowCnt = 0;
            var colIndex = $(this).parent().index();
            var column = ntlTable.column(colIndex);
            if ($('div.column-filter').length) {

                $('div.column-filter').hide();
                $('div.column-filter').remove();

                if (oldIndex !== $(this).parent().index()) {

                    var otherColFilterContent = $(filterContent).appendTo(column.header());
                    var oContentField = $('<ul style="list-style-type: none;padding:0"></ul>');

                    column.data().unique().sort().each(function (d, j) {
                        if (d !== "" && d !== '&nbsp;') {
                            rowCnt++;
                            oContentField.append("<li class='filter-col' row-index='" + colIndex + "'><a href='#'>" + d + "</a></li>");
                        }
                    });
                    oContentField.appendTo($('.col-filter'));
                    otherColFilterContent.show();
                }

            } else {

                var colFilterContent = $(filterContent).appendTo(column.header());
                var contentField = $('<ul class="filter-col-u"></ul>');

                column.data().unique().sort().each(function (d, j) {
                    console.log(j);
                    if (d !== "" && d !== '&nbsp;') {
                        rowCnt++;
                        contentField.append("<li  class='filter-col' row-index='" + colIndex + "'><a href='#'>" + d + "</a></li>");
                    }
                });
                contentField.appendTo($('.col-filter'));
                colFilterContent.show();
            }

            if (rowCnt === 0)
                $('.apply-btn').attr('disabled', 'disabled');

            oldIndex = $(this).parent().index();
            $('.contained').keyup(function () {

                $('.uncontained').val('');
                $('div.uncontained').find('.filter-col').each(function () {
                    if ($(this).hasClass('ntl-filter-hide')) {
                        $(this).removeClass('ntl-filter-hide');
                    }
                });
                var containedKey = $(this).val().toLowerCase();
                if (containedKey === '' || containedKey === '&nbsp;') {
                    $('li.ntl-filter-hide').each(function () {
                        $(this).removeClass('ntl-filter-hide');
                    });
                } else {
                    $('div.contained').find('.filter-col').each(function () {
                        var eleVal = $(this).children('a').text().toLowerCase();
                        if (eleVal.indexOf(containedKey) === -1) {
                            $(this).addClass('ntl-filter-hide');
                        } else {
                            if ($(this).hasClass('ntl-filter-hide')) {
                                $(this).removeClass('ntl-filter-hide');
                            }
                        }
                    });
                }
            });

            $('.uncontained').keyup(function () {

                $('.contained').val('');
                $('div.contained').find('.filter-col').each(function () {
                    if ($(this).hasClass('ntl-filter-hide')) {
                        $(this).removeClass('ntl-filter-hide');
                    }
                });

                var uncontainedKey = $(this).val().toLowerCase();
                if (uncontainedKey === '' || uncontainedKey === '&nbsp;') {
                    $('li.ntl-filter-hide').each(function () {
                        $(this).removeClass('ntl-filter-hide');
                    });
                } else {
                    $('div.uncontained').find('.filter-col').each(function () {
                        var eleVal = $(this).children('a').text().toLowerCase();
                        if (eleVal.indexOf(uncontainedKey) !== -1) {
                            $(this).addClass('ntl-filter-hide');
                        } else {
                            if ($(this).hasClass('ntl-filter-hide')) {
                                $(this).removeClass('ntl-filter-hide');
                            }
                        }
                    });
                }
            });

            $('.contained .filter-col').click(function () {
                $('.filter-col-highlight').each(function () {
                    $(this).removeClass('filter-col-highlight');
                });
                $(this).addClass('filter-col-highlight');
            });

            $('.uncontained .filter-col').click(function () {
                $('.filter-col-highlight').each(function () {
                    $(this).removeClass('filter-col-highlight');
                });
                $(this).addClass('filter-col-highlight');
            });

            $('.apply-btn').click(function () {
                var highCol = 0;
                $('.filter-col-highlight').each(function () {
                    highCol++;
                    var column = ntlTable.column($(this).attr('row-index'));
                    column.search($(this).children('a').text()).draw();
                });

                if (highCol === 0) {
                    var column = ntlTable.column($(this).attr('col-index'));
                    column.search("").draw();
                }

                $('div.column-filter').hide();
                $('div.column-filter').remove();
            });
        });
    });

} catch (e) {

    alert(e + " Please check install guide. https://netlab.com/opensips/toolbar");
    // //window.top.location.reload();

}

$(document).mouseup(function (e) {

    var container = $('div.column-filter');

    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
        container.remove();
    }

    var editContainer = $('.hide-menu');

    if (!editContainer.is(e.target) && editContainer.has(e.target).length === 0) {
        editContainer.hide();
        editContainer.remove();
    }

    $('.row-highlight').each(function () {
        if (!$('.hide-show').is(e.target) && $('.hide-show').has(e.target).length === 0
            && !$('.hide-show-i').is(e.target) && $('.hide-show-i').has(e.target).length === 0
            && !$('.column-trash').is(e.target) && $('.column-trash').has(e.target).length === 0
            && !$('.column-edit').is(e.target) && $('.column-edit').has(e.target).length === 0
            && !$('.column-edit, .fa-edit').is(e.target) && $('.column-edit, .fa-edit').has(e.target).length === 0
            && !$('.column-trash, .fa-trash').is(e.target) && $('.column-trash, .fa-trash').has(e.target).length === 0)
            $(this).removeClass('row-highlight');
    });


});