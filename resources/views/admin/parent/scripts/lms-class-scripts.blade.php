<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<script src="{{JS}}select2.js"></script>
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
<script>
    var app = angular.module('academia',  ['ngMessages']);
  app.controller('angTopicsController', function($scope, $http) {
});
</script>
<script>
    $('#lmsseries_type').on('change', function () {
        const selectedLmsType = $('#lmsseries_type').val();
        const lmsseriesOptions = @json($lms_options);
        const selectedLmsOptions = lmsseriesOptions?.[selectedLmsType] ?? [];

        $('#lmsseries_list').empty();
        $(selectedLmsOptions).each(function (item) {
            const option = selectedLmsOptions[item];

            $('#lmsseries_list').append(`<option value="${option.id}">${option.title}</option>`);
        });
    });

    function openExportModal () {
        $('#exportExcel').modal('show');
    }
</script>