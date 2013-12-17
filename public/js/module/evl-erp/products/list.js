$(document).ready(function () {
    $.extend( $.fn.dataTableExt.oStdClasses, {
        "sWrapper": "dataTables_wrapper product-list"
    } );

    $('table.products').dataTable({
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        "bInfo": false,
        "oLanguage": {
            "sSearch": "",
            "sLengthMenu": sLengthMenu,
            "sProcessing": sProcessing,
            "oPaginate" : {
                "sFirst": sFirst,
                "sLast": sLast,
                "sNext": sNext,
                "sPrevious": sPrevious
            },
            "sInfoEmpty": sInfoEmpty,
            "sZeroRecords": sZeroRecords
        },
        // enable jQuery UI's ThemeRoller
//        "bJQueryUI": true,
        // column defnition (disabling sorting for cerain columns)
        "aoColumnDefs": [{
            "bSortable": false, "aTargets": [ 0 ]
        }, {
            "bSortable": false, "aTargets": [ 1 ]
        }, {
            "bSortable": true, "aTargets": [ 2 ]
        }, {
            "bSortable": true, "aTargets": [ 3 ]
        }, {
            "bSortable": false, "aTargets": [ 4 ]
        }, {
            "bSortable": true, "aTargets": [ 5 ]
        }, {
            "bSortable": true, "aTargets": [ 6 ]
        }, {
            "bSortable": false, "aTargets": [ 7 ]
        }, {
            "bSortable": false, "aTargets": [ 8 ]
        }],
        // extra server params
//        "fnServerParams": function ( aoData ) {
//            aoData.push( { "locale": locale } );
//        },
        // prevent initial sorting
        "aaSorting": [ ],
        // pagination
        "aLengthMenu": [[5, 10, 25, 50, 100, 200], [5, 10, 25, 50, 100, 200]],
        // server side processing
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": sProductsListAjaxSource
    });
    $('.dataTables_filter input').attr('placeholder', sSearchFor);
});
